<?php
require_once __DIR__ . '/../helpers/auth.php';
requireAdmin();
verifyCsrf();
require_once __DIR__ . '/../config/database.php';

if (isset($_POST['tambah']) || isset($_POST['edit'])) {
    $nama = inputString($_POST, 'nama_kerusakan');
    $solusi = inputString($_POST, 'solusi');
    if (isset($_POST['edit'])) {
        $id = positiveId($_POST['id_kerusakan'] ?? null);
        $stmt = mysqli_prepare($conn, 'UPDATE kerusakan SET nama_kerusakan=?, solusi=? WHERE id_kerusakan=?');
        mysqli_stmt_bind_param($stmt, 'ssi', $nama, $solusi, $id);
    } else {
        $kode = inputString($_POST, 'kode_kerusakan');
        $stmt = mysqli_prepare($conn, 'INSERT INTO kerusakan (kode_kerusakan,nama_kerusakan,solusi) VALUES (?,?,?)');
        mysqli_stmt_bind_param($stmt, 'sss', $kode, $nama, $solusi);
    }
} elseif (isset($_POST['hapus'])) {
    $id = positiveId($_POST['hapus']);
    $stmt = mysqli_prepare($conn, 'DELETE FROM kerusakan WHERE id_kerusakan=?');
    mysqli_stmt_bind_param($stmt, 'i', $id);
} else {
    badRequest();
}
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);
header('Location: ../index.php?page=kerusakan');
exit;
