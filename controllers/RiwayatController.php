<?php

include '../config/database.php';

if(isset($_GET['hapus'])){

    $id = $_GET['hapus'];

    mysqli_query(
    $conn,
    "DELETE FROM riwayat_diagnosa
    WHERE id_riwayat='$id'"
    );

    header(
    "Location: ../index.php?page=riwayat"
    );

    exit;
}