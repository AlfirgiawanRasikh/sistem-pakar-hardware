<?php
require_once __DIR__ . '/../helpers/auth.php';
requireAdmin();
verifyCsrf();
require_once __DIR__ . '/../config/database.php';

if (isset($_POST['tambah']) || isset($_POST['edit'])) {
    $nama = trim(inputString($_POST, 'nama_lengkap'));
    $username = trim(inputString($_POST, 'username'));
    $role = inputString($_POST, 'role');
    $password = inputString($_POST, 'password', isset($_POST['tambah']));
    if (!in_array($role, ['admin', 'pengguna'], true)) {
        badRequest();
    }
    if ($password !== '' && (strlen($password) < 8 || strlen($password) > 72 || strpos($password, "\0") !== false)) {
        http_response_code(400);
        exit('Password harus 8 sampai 72 byte.');
    }
    $id = isset($_POST['edit']) ? positiveId($_POST['id_pengguna'] ?? null) : 0;
    $stmt = mysqli_prepare($conn, 'SELECT id_pengguna FROM pengguna WHERE username=? AND id_pengguna<>? LIMIT 1');
    mysqli_stmt_bind_param($stmt, 'si', $username, $id);
    mysqli_stmt_execute($stmt);
    $duplicate = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    mysqli_stmt_close($stmt);
    if ($duplicate) {
        http_response_code(409);
        exit('Username sudah digunakan!');
    }
    if ($password !== '') {
        $hash = password_hash($password, PASSWORD_DEFAULT);
    }
    if ($id === 0) {
        $stmt = mysqli_prepare($conn, 'INSERT INTO pengguna (nama_lengkap,username,password,role) VALUES (?,?,?,?)');
        mysqli_stmt_bind_param($stmt, 'ssss', $nama, $username, $hash, $role);
    } elseif ($password !== '') {
        $stmt = mysqli_prepare($conn, 'UPDATE pengguna SET nama_lengkap=?, username=?, password=?, role=? WHERE id_pengguna=?');
        mysqli_stmt_bind_param($stmt, 'ssssi', $nama, $username, $hash, $role, $id);
    } else {
        $stmt = mysqli_prepare($conn, 'UPDATE pengguna SET nama_lengkap=?, username=?, role=? WHERE id_pengguna=?');
        mysqli_stmt_bind_param($stmt, 'sssi', $nama, $username, $role, $id);
    }
} elseif (isset($_POST['hapus'])) {
    $id = positiveId($_POST['hapus']);
    $stmt = mysqli_prepare($conn, 'DELETE FROM pengguna WHERE id_pengguna=?');
    mysqli_stmt_bind_param($stmt, 'i', $id);
} else {
    badRequest();
}
try {
    mysqli_stmt_execute($stmt);
} catch (mysqli_sql_exception $error) {
    if ($error->getCode() === 1062) {
        http_response_code(409);
        exit('Username sudah digunakan!');
    }
    throw $error;
}
mysqli_stmt_close($stmt);
header('Location: ../index.php?page=pengguna');
exit;
