<?php

session_start();

if(!isset($_SESSION['login'])){

include 'views/auth/login.php';
exit;

}

include 'views/layouts/header.php';
include 'views/layouts/navbar.php';
include 'views/layouts/sidebar.php';

$page =
$_GET['page']
?? 'dashboard';

switch($page){

case 'dashboard':
include 'views/dashboard/index.php';
break;

case 'gejala':
include 'views/gejala/index.php';
break;

case 'kerusakan':
include 'views/kerusakan/index.php';
break;

case 'pengguna':
include 'views/pengguna/index.php';
break;

case 'aturan':
include 'views/aturan/index.php';
break;

case 'diagnosa':
include 'views/diagnosa/index.php';
break;

case 'riwayat':
include 'views/riwayat/index.php';
break;

case 'hasil_diagnosa':
include 'views/diagnosa/hasil.php';
break;

case 'detail_riwayat':
include 'views/riwayat/detail.php';
break;

case 'laporan':
include 'views/laporan/index.php';
break;

default:
include 'views/dashboard/index.php';
break;

}

include 'views/layouts/footer.php';