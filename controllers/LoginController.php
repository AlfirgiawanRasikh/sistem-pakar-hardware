<?php

session_start();

include '../config/database.php';

$username = trim($_POST['username']);
$password = md5($_POST['password']);

$query = mysqli_query(
    $conn,
    "SELECT * FROM pengguna
     WHERE username='$username'
     AND password='$password'"
);

if(!$query){
    die("Query Error : " . mysqli_error($conn));
}

$data = mysqli_fetch_assoc($query);

if($data){

    $_SESSION['login'] = true;

    $_SESSION['id_pengguna']
        = $data['id_pengguna'];

    $_SESSION['nama']
        = $data['nama_lengkap'];

    $_SESSION['role']
        = $data['role'];

    header(
        "Location: ../index.php?page=dashboard"
    );

    exit;

}else{

    echo "
    <script>
        alert('Username atau Password salah!');
        window.location='../index.php';
    </script>
    ";

}