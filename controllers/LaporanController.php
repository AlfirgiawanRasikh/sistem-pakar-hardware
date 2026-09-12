<?php

session_start();

include '../config/database.php';

if(!isset($_POST['tampilkan'])){
    header("Location: ../index.php?page=laporan");
    exit;
}

$jenis = $_POST['jenis_laporan'];
$awal = $_POST['tanggal_awal'];
$akhir = $_POST['tanggal_akhir'];

switch($jenis){
    case 'riwayat':
        $query = mysqli_query($conn, "SELECT nama_pengguna, hasil_kerusakan, tanggal FROM riwayat_diagnosa WHERE DATE(tanggal) BETWEEN '$awal' AND '$akhir' ORDER BY tanggal DESC");
        $judul = "Laporan Riwayat Diagnosa";
        break;

    case 'pengguna':
        $query = mysqli_query($conn, "SELECT id_pengguna, nama_lengkap, username, role, created_at FROM pengguna ORDER BY id_pengguna ASC");
        $judul = "Laporan Data Pengguna";
        break;

    case 'gejala':
        $query = mysqli_query($conn, "SELECT kode_gejala, nama_gejala, jenis FROM gejala ORDER BY kode_gejala ASC");
        $judul = "Laporan Data Gejala";
        break;

    case 'kerusakan':
        $query = mysqli_query($conn, "SELECT kode_kerusakan, nama_kerusakan, solusi FROM kerusakan ORDER BY kode_kerusakan ASC");
        $judul = "Laporan Data Kerusakan";
        break;

    case 'aturan':
        $query = mysqli_query($conn, "SELECT a.kode_aturan, GROUP_CONCAT(g.kode_gejala ORDER BY g.kode_gejala SEPARATOR ', ') AS gejala, k.nama_kerusakan FROM aturan a JOIN detail_aturan d ON a.id_aturan = d.id_aturan JOIN gejala g ON d.id_gejala = g.id_gejala JOIN kerusakan k ON a.id_kerusakan = k.id_kerusakan GROUP BY a.id_aturan ORDER BY a.kode_aturan ASC");
        $judul = 'Laporan Basis Aturan';
        break;

    default:
        header("Location: ../index.php?page=laporan");
        exit;
}

if(mysqli_num_rows($query) == 0){
    $_SESSION['preview_laporan'] = '<div class="alert alert-warning">Data tidak ditemukan.</div>';
    header("Location: ../index.php?page=laporan");
    exit;
}

// Ambil baris pertama untuk mendapatkan struktur kolom
$rowAwal = mysqli_fetch_assoc($query);
$kolom = array_keys($rowAwal);

$header = [
    'id_pengguna'     => 'ID Pengguna',
    'nama_lengkap'    => 'Nama Lengkap',
    'username'        => 'Username',
    'role'            => 'Role',
    'created_at'      => 'Tanggal Dibuat',
    'kode_gejala'     => 'Kode Gejala',
    'nama_gejala'     => 'Nama Gejala',
    'jenis'           => 'Jenis',
    'kode_kerusakan'  => 'Kode Kerusakan',
    'nama_kerusakan'  => 'Nama Kerusakan',
    'solusi'          => 'Solusi',
    'nama_pengguna'   => 'Nama Pengguna',
    'hasil_kerusakan' => 'Hasil Diagnosa',
    'tanggal'         => 'Tanggal Diagnosa',
    'kode_aturan'     => 'Kode Aturan'
];

// --- 1. MEMBANGUN HEADER TABEL HTML & PDF BERSAMAAN ---
$html = '
<div class="card">
    <div class="card-header">
        <h3 class="card-title">'.$judul.'</h3>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>';

$tabelPdf = '
<table style="width:100%;border-collapse:collapse;">
    <tr>
        <th style="border:1px solid #000;padding:8px;background:#dbe5f1;">No</th>';

foreach($kolom as $k){
    $judulKolom = isset($header[$k]) ? $header[$k] : ucfirst($k);
    
    $html .= '<th>'.$judulKolom.'</th>';
    $tabelPdf .= '<th style="border:1px solid #000;padding:8px;background:#dbe5f1;">'.$judulKolom.'</th>';
}

$html .= '</tr></thead><tbody>';
$tabelPdf .= '</tr>';

// --- 2. MENGISI DATA BARIS HTML & PDF DALAM 1 LOOP (MENGHAPUS KODE SIA-SIA) ---
$no = 1;
do {
    $html .= '<tr>';
    $html .= '<td>'.$no.'</td>';
    
    $tabelPdf .= '<tr>';
    $tabelPdf .= '<td style="border:1px solid #000;padding:6px;text-align:center;">'.$no.'</td>';

    foreach($rowAwal as $isi){
        $html .= '<td>'.$isi.'</td>';
        $tabelPdf .= '<td style="border:1px solid #000;padding:6px;">'.$isi.'</td>';
    }

    $html .= '</tr>';
    $tabelPdf .= '</tr>';
    
    $no++;
} while($rowAwal = mysqli_fetch_assoc($query));

// Menutup tabel HTML
$html .= '
            </tbody>
        </table>
        <br>
        <a href="laporan_pdf.php" target="_blank" class="btn btn-danger">
            <i class="fas fa-file-pdf"></i> Cetak PDF
        </a>
    </div>
</div>';

// Menutup tabel PDF
$tabelPdf .= '</table>';


// --- 3. MERANGKAI PDF HTML ---
$bulan = [
    1=>'Januari', 2=>'Februari', 3=>'Maret', 4=>'April', 5=>'Mei', 6=>'Juni',
    7=>'Juli', 8=>'Agustus', 9=>'September', 10=>'Oktober', 11=>'November', 12=>'Desember'
];

$tanggalIndonesia = date('d').' '.$bulan[(int)date('m')].' '.date('Y');

$_SESSION['pdf_html'] = '
<style>
@page{ margin:40px; }
body{ font-family:Arial, sans-serif; font-size:11px; }
.header{ width:100%; margin-bottom:15px; }
.header-table{ width:100%; border:none; }
.header-table td{ border:none; vertical-align:top; }
.judul{ text-align:center; margin-top:10px; margin-bottom:20px; }
.judul h2{ margin:0; }
.judul p{ margin:5px 0; }
.info{ margin-bottom:15px; }
table{ width:100%; border-collapse:collapse; }
th{ background:#dbe5f1; font-weight:bold; text-align:center; }
th,td{ border:1px solid #000; padding:6px; }
.ttd{ margin-top:40px; width:100%; }
.ttd table{ border:none; }
.ttd td{ border:none; }
</style>

<div class="header">
    <table class="header-table">
        <tr>
            <td width="75%">
                <h2 style="margin:0;">PT SIMETRI INDONESIA</h2>
                <div>Jl. Pengadegan Selatan 1 No.30</div>
                <div>Jakarta 12770, Indonesia</div>
                <div>www.simetri-indonesia.com</div>
            </td>
            <td width="25%" align="right">
                <img src="http://localhost/sistem-pakar-hardware/assets/img/logo.png" width="120">
            </td>
        </tr>
    </table>
</div>

<hr>

<div class="judul">
    <h2>'.$judul.'</h2>
</div>

<div class="info">
    <b>Tanggal Cetak :</b> '.$tanggalIndonesia.'
</div>

'.$tabelPdf.'

<div class="ttd">
    <table>
        <tr>
            <td width="60%"></td>
            <td width="40%" align="center">
                Jakarta, '.$tanggalIndonesia.'<br><br><br><br>
                Admin<br><br>
                (__________________)
            </td>
        </tr>
    </table>
</div>
';

$_SESSION['preview_laporan'] = $html;

header("Location: ../index.php?page=laporan");
exit;