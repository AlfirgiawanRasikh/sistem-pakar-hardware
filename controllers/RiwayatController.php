<?php
require_once __DIR__ . '/../helpers/auth.php';
requireLogin();
verifyCsrf();
require_once __DIR__ . '/../config/database.php';

if (!isset($_POST['hapus'])) {
    badRequest();
}
$id = positiveId($_POST['hapus']);
if ($_SESSION['role'] === 'admin') {
    $stmt = mysqli_prepare($conn, 'DELETE FROM riwayat_diagnosa WHERE id_riwayat=?');
    mysqli_stmt_bind_param($stmt, 'i', $id);
} else {
    $idPengguna = positiveId($_SESSION['id_pengguna']);
    $stmt = mysqli_prepare($conn, 'DELETE FROM riwayat_diagnosa WHERE id_riwayat=? AND id_pengguna=?');
    mysqli_stmt_bind_param($stmt, 'ii', $id, $idPengguna);
}
mysqli_stmt_execute($stmt);
if (mysqli_stmt_affected_rows($stmt) === 0) {
    http_response_code(404);
    exit('Data tidak ditemukan.');
}
mysqli_stmt_close($stmt);
header('Location: ../index.php?page=riwayat');
exit;
