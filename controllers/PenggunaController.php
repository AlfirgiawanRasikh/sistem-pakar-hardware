<?php

include '../config/database.php';

/*
|--------------------------------------------------------------------------
| TAMBAH PENGGUNA
|--------------------------------------------------------------------------
*/

if(isset($_POST['tambah'])){

    $nama       = mysqli_real_escape_string($conn, $_POST['nama_lengkap']);
    $username   = mysqli_real_escape_string($conn, $_POST['username']);
    $password   = md5($_POST['password']);
    $role       = mysqli_real_escape_string($conn, $_POST['role']);

    $cek = mysqli_query(
        $conn,
        "SELECT * FROM pengguna
         WHERE username='$username'"
    );

    if(mysqli_num_rows($cek) > 0){

        echo "
        <script>
            alert('Username sudah digunakan!');
            window.location='../index.php?page=pengguna';
        </script>
        ";
        exit;
    }

    mysqli_query(
        $conn,
        "INSERT INTO pengguna
        (
            nama_lengkap,
            username,
            password,
            role
        )
        VALUES
        (
            '$nama',
            '$username',
            '$password',
            '$role'
        )"
    );

    header(
        "Location: ../index.php?page=pengguna"
    );
    exit;
}

/*
|--------------------------------------------------------------------------
| EDIT PENGGUNA
|--------------------------------------------------------------------------
*/

if(isset($_POST['edit'])){

    $id         = $_POST['id_pengguna'];
    $nama       = mysqli_real_escape_string($conn, $_POST['nama_lengkap']);
    $username   = mysqli_real_escape_string($conn, $_POST['username']);
    $role       = mysqli_real_escape_string($conn, $_POST['role']);
    $password   = $_POST['password'];

    if(!empty($password)){

        $password = md5($password);

        mysqli_query(
            $conn,
            "UPDATE pengguna SET

            nama_lengkap='$nama',
            username='$username',
            password='$password',
            role='$role'

            WHERE id_pengguna='$id'"
        );

    }else{

        mysqli_query(
            $conn,
            "UPDATE pengguna SET

            nama_lengkap='$nama',
            username='$username',
            role='$role'

            WHERE id_pengguna='$id'"
        );

    }

    header(
        "Location: ../index.php?page=pengguna"
    );
    exit;
}

/*
|--------------------------------------------------------------------------
| HAPUS PENGGUNA
|--------------------------------------------------------------------------
*/

if(isset($_GET['hapus'])){

    $id = $_GET['hapus'];

    mysqli_query(
        $conn,
        "DELETE FROM pengguna
        WHERE id_pengguna='$id'"
    );

    header(
        "Location: ../index.php?page=pengguna"
    );
    exit;
}