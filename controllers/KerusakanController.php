<?php

include '../config/database.php';

/*
|--------------------------------------------------------------------------
| TAMBAH
|--------------------------------------------------------------------------
*/

if(isset($_POST['tambah'])){

    $kode =
    $_POST['kode_kerusakan'];

    $nama =
    $_POST['nama_kerusakan'];

    $solusi =
    $_POST['solusi'];

    mysqli_query(
    $conn,
    "INSERT INTO kerusakan
    (
        kode_kerusakan,
        nama_kerusakan,
        solusi
    )
    VALUES
    (
        '$kode',
        '$nama',
        '$solusi'
    )"
    );

    header(
    "Location: ../index.php?page=kerusakan"
    );

}

/*
|--------------------------------------------------------------------------
| EDIT
|--------------------------------------------------------------------------
*/

if(isset($_POST['edit'])){

    $id =
    $_POST['id_kerusakan'];

    $nama =
    $_POST['nama_kerusakan'];

    $solusi =
    $_POST['solusi'];

    mysqli_query(
    $conn,
    "UPDATE kerusakan SET

    nama_kerusakan='$nama',
    solusi='$solusi'

    WHERE id_kerusakan='$id'"
    );

    header(
    "Location: ../index.php?page=kerusakan"
    );

}

/*
|--------------------------------------------------------------------------
| HAPUS
|--------------------------------------------------------------------------
*/

if(isset($_GET['hapus'])){

    $id =
    $_GET['hapus'];

    mysqli_query(
    $conn,
    "DELETE FROM kerusakan
    WHERE id_kerusakan='$id'"
    );

    header(
    "Location: ../index.php?page=kerusakan"
    );

}