<?php

include 'config/database.php';

$id = $_GET['id'];

$data = mysqli_query(
$conn,
"SELECT *
FROM riwayat_diagnosa
WHERE id_riwayat='$id'"
);

$row = mysqli_fetch_assoc($data);

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

<?= $row['nama_pengguna'] ?>

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

$g = mysqli_query(
$conn,
"SELECT
kode_gejala,
nama_gejala
FROM gejala
WHERE id_gejala='$idGejala'"
);

$dg =
mysqli_fetch_assoc($g);

if($dg){

echo
$dg['kode_gejala']
.
' - '
.
$dg['nama_gejala']
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

<?= $row['hasil_kerusakan'] ?>

</td>

</tr>

<tr>

<th>

Solusi

</th>

<td>

<?= nl2br(
$row['solusi']
) ?>

</td>

</tr>

<tr>

<th>

Tanggal Diagnosa

</th>

<td>

<?= $row['tanggal'] ?>

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