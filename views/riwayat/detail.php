<?php
require_once __DIR__ . '/../../helpers/auth.php';
requireLogin();


require_once __DIR__ . '/../../config/database.php';

$id = positiveId($_GET['id'] ?? null);
if ($_SESSION['role'] === 'admin') {
    $stmt = mysqli_prepare($conn, 'SELECT * FROM riwayat_diagnosa WHERE id_riwayat=?');
    mysqli_stmt_bind_param($stmt, 'i', $id);
} else {
    $idPengguna = positiveId($_SESSION['id_pengguna']);
    $stmt = mysqli_prepare($conn, 'SELECT * FROM riwayat_diagnosa WHERE id_riwayat=? AND id_pengguna=?');
    mysqli_stmt_bind_param($stmt, 'ii', $id, $idPengguna);
}
mysqli_stmt_execute($stmt);
$row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
mysqli_stmt_close($stmt);
if (!$row) {
    http_response_code(404);
    exit('Data tidak ditemukan.');
}

?>

<div class="content-wrapper">

<section class="content-header">

<div class="container-fluid">

<h1>Detail Riwayat Diagnosa</h1>

</div>

</section>

<section class="content">

<div class="container-fluid">

<div class="card card-primary">

<div class="card-header">

<h3 class="card-title">

Detail Hasil Diagnosa

</h3>

</div>

<div class="card-body">

<table class="table table-bordered">

<tr>

<th width="250">

Nama Pengguna

</th>

<td>

<?= e($row['nama_pengguna']) ?>

</td>

</tr>

<tr>

<th>

Gejala Dipilih

</th>

<td>

<?php

$gejalaIds =
explode(
',',
$row['gejala_dipilih']
);

foreach($gejalaIds as $idGejala){

$idGejala =
trim($idGejala);

if (filter_var($idGejala, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]) === false) {
    continue;
}
$stmt = mysqli_prepare($conn, 'SELECT kode_gejala,nama_gejala FROM gejala WHERE id_gejala=?');
mysqli_stmt_bind_param($stmt, 'i', $idGejala);
mysqli_stmt_execute($stmt);
$g = mysqli_stmt_get_result($stmt);
mysqli_stmt_close($stmt);

$dg =
mysqli_fetch_assoc($g);

if($dg){

echo
e($dg['kode_gejala'])
.
' - '
.
e($dg['nama_gejala'])
.
'<br>';

}

}

?>

</td>

</tr>

<tr>

<th>

Kerusakan

</th>

<td>

<?= e($row['hasil_kerusakan']) ?>

</td>

</tr>

<tr>

<th>

Solusi

</th>

<td>

<?= nl2br(e($row['solusi'])) ?>

</td>

</tr>

<tr>

<th>

Tanggal Diagnosa

</th>

<td>

<?= e($row['tanggal']) ?>

</td>

</tr>

</table>

</div>

<div class="card-footer">

<a
href="index.php?page=riwayat"
class="btn btn-secondary">

Kembali

</a>

</div>

</div>

</div>

</section>

</div>