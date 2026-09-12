<?php
require_once __DIR__ . '/../helpers/auth.php';
requireAdmin();
verifyCsrf();
require_once __DIR__ . '/../config/database.php';

if (isset($_POST['tambah']) || isset($_POST['edit'])) {
    $idKerusakan = positiveId($_POST['id_kerusakan'] ?? null);
    $gejala = idList($_POST['id_gejala'] ?? null);
    $idAturan = isset($_POST['edit']) ? positiveId($_POST['id_aturan'] ?? null) : null;
    mysqli_begin_transaction($conn);
    if ($idAturan !== null) {
        $stmt = mysqli_prepare($conn, 'UPDATE aturan SET id_kerusakan=? WHERE id_aturan=?');
        mysqli_stmt_bind_param($stmt, 'ii', $idKerusakan, $idAturan);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        $stmt = mysqli_prepare($conn, 'DELETE FROM detail_aturan WHERE id_aturan=?');
        mysqli_stmt_bind_param($stmt, 'i', $idAturan);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    } else {
        $stmt = mysqli_prepare($conn, 'SELECT MAX(id_aturan) AS max_id FROM aturan');
        mysqli_stmt_execute($stmt);
        $next = (int) mysqli_fetch_assoc(mysqli_stmt_get_result($stmt))['max_id'] + 1;
        mysqli_stmt_close($stmt);
        $kode = 'R' . str_pad($next, 2, '0', STR_PAD_LEFT);
        $stmt = mysqli_prepare($conn, 'INSERT INTO aturan (kode_aturan,id_kerusakan) VALUES (?,?)');
        mysqli_stmt_bind_param($stmt, 'si', $kode, $idKerusakan);
        mysqli_stmt_execute($stmt);
        $idAturan = mysqli_insert_id($conn);
        mysqli_stmt_close($stmt);
    }
    $stmt = mysqli_prepare($conn, 'INSERT INTO detail_aturan (id_aturan,id_gejala) VALUES (?,?)');
    foreach ($gejala as $idGejala) {
        mysqli_stmt_bind_param($stmt, 'ii', $idAturan, $idGejala);
        mysqli_stmt_execute($stmt);
    }
    mysqli_stmt_close($stmt);
    mysqli_commit($conn);
} elseif (isset($_POST['hapus'])) {
    $id = positiveId($_POST['hapus']);
    mysqli_begin_transaction($conn);
    $stmt = mysqli_prepare($conn, 'DELETE FROM detail_aturan WHERE id_aturan=?');
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    $stmt = mysqli_prepare($conn, 'DELETE FROM aturan WHERE id_aturan=?');
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    mysqli_commit($conn);
} else {
    badRequest();
}
// On an exception the connection closes and MySQL rolls back the transaction.
header('Location: ../index.php?page=aturan');
exit;
