<?php

include '../config/database.php';

/*
|--------------------------------------------------------------------------
| TAMBAH ATURAN
|--------------------------------------------------------------------------
*/

if(isset($_POST['tambah'])){

    $id_kerusakan = $_POST['id_kerusakan'];
    $gejala       = $_POST['id_gejala'];

    $q = mysqli_query(
        $conn,
        "SELECT MAX(id_aturan) as max_id
         FROM aturan"
    );

    $d = mysqli_fetch_assoc($q);

    $next = $d['max_id'] + 1;

    $kode_aturan =
    'R' .
    str_pad(
        $next,
        2,
        '0',
        STR_PAD_LEFT
    );

    mysqli_query(
        $conn,
        "INSERT INTO aturan
        (
            kode_aturan,
            id_kerusakan
        )
        VALUES
        (
            '$kode_aturan',
            '$id_kerusakan'
        )"
    );

    $id_aturan =
    mysqli_insert_id($conn);

    foreach($gejala as $g){

        mysqli_query(
            $conn,
            "INSERT INTO detail_aturan
            (
                id_aturan,
                id_gejala
            )
            VALUES
            (
                '$id_aturan',
                '$g'
            )"
        );
    }

    header(
        "Location: ../index.php?page=aturan"
    );
    exit;
}

/*
|--------------------------------------------------------------------------
| EDIT ATURAN
|--------------------------------------------------------------------------
*/

if(isset($_POST['edit'])){

    $id_aturan    = $_POST['id_aturan'];
    $id_kerusakan = $_POST['id_kerusakan'];
    $gejala       = $_POST['id_gejala'];

    mysqli_query(
        $conn,
        "UPDATE aturan SET

        id_kerusakan='$id_kerusakan'

        WHERE id_aturan='$id_aturan'"
    );

    mysqli_query(
        $conn,
        "DELETE FROM detail_aturan
        WHERE id_aturan='$id_aturan'"
    );

    foreach($gejala as $g){

        mysqli_query(
            $conn,
            "INSERT INTO detail_aturan
            (
                id_aturan,
                id_gejala
            )
            VALUES
            (
                '$id_aturan',
                '$g'
            )"
        );
    }

    header(
        "Location: ../index.php?page=aturan"
    );
    exit;
}

/*
|--------------------------------------------------------------------------
| HAPUS ATURAN
|--------------------------------------------------------------------------
*/

if(isset($_GET['hapus'])){

    $id = $_GET['hapus'];

    mysqli_query(
        $conn,
        "DELETE FROM detail_aturan
        WHERE id_aturan='$id'"
    );

    mysqli_query(
        $conn,
        "DELETE FROM aturan
        WHERE id_aturan='$id'"
    );

    header(
        "Location: ../index.php?page=aturan"
    );
    exit;
}