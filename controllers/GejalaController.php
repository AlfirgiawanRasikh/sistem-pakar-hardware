<?php
include '../config/database.php';

if (isset($_POST['tambah'])) {
    mysqli_query($conn, "INSERT INTO gejala (kode_gejala, nama_gejala, jenis) VALUES ('{$_POST['kode_gejala']}', '{$_POST['nama_gejala']}', '{$_POST['jenis']}')");
    header("Location: ../index.php?page=gejala");
} elseif (isset($_POST['edit'])) {
    mysqli_query($conn, "UPDATE gejala SET kode_gejala='{$_POST['kode_gejala']}', nama_gejala='{$_POST['nama_gejala']}', jenis='{$_POST['jenis']}' WHERE id_gejala='{$_POST['id_gejala']}'");
    header("Location: ../index.php?page=gejala");
} elseif (isset($_GET['hapus'])) {
    mysqli_query($conn, "DELETE FROM gejala WHERE id_gejala='{$_GET['hapus']}'");
    header("Location: ../index.php?page=gejala");
}