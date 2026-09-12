<?php
require_once __DIR__ . '/../helpers/auth.php';
requireAdmin();
verifyCsrf();
require_once __DIR__ . '/../config/database.php';

if (isset($_POST['tambah']) || isset($_POST['edit'])) {
    $kode = inputString($_POST, 'kode_gejala');
    $nama = inputString($_POST, 'nama_gejala');
    $jenis = inputString($_POST, 'jenis');
    if (isset($_POST['edit'])) {
        $id = positiveId($_POST['id_gejala'] ?? null);
        $stmt = mysqli_prepare($conn, 'UPDATE gejala SET kode_gejala=?, nama_gejala=?, jenis=? WHERE id_gejala=?');
        mysqli_stmt_bind_param($stmt, 'sssi', $kode, $nama, $jenis, $id);
    } else {
        $stmt = mysqli_prepare($conn, 'INSERT INTO gejala (kode_gejala,nama_gejala,jenis) VALUES (?,?,?)');
        mysqli_stmt_bind_param($stmt, 'sss', $kode, $nama, $jenis);
    }
} elseif (isset($_POST['hapus'])) {
    $id = positiveId($_POST['hapus']);
    $stmt = mysqli_prepare($conn, 'DELETE FROM gejala WHERE id_gejala=?');
    mysqli_stmt_bind_param($stmt, 'i', $id);
} else {
    badRequest();
}
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);
header('Location: ../index.php?page=gejala');
exit;
