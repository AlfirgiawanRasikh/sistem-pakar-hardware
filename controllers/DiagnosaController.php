<?php
require_once __DIR__ . '/../helpers/auth.php';
requireLogin();
verifyCsrf();
require_once __DIR__ . '/../config/database.php';

if(!isset($_POST['proses'])){

    header(
    "Location: ../index.php?page=diagnosa"
    );

    exit;
}

$gejalaDipilih = $_POST['gejala'] ?? [];
if (!is_array($gejalaDipilih)) {
    badRequest();
}

if(count($gejalaDipilih)==0){

    echo "
    <script>

    alert('Pilih minimal satu gejala');

    window.location='../index.php?page=diagnosa';

    </script>
    ";

    exit;
}

$gejalaDipilih = idList($gejalaDipilih);

/*
|--------------------------------------------------------------------------
| AMBIL SEMUA ATURAN
|--------------------------------------------------------------------------
*/

$stmt = mysqli_prepare($conn, 'SELECT * FROM aturan ORDER BY id_aturan ASC');
mysqli_stmt_execute($stmt);
$aturan = mysqli_stmt_get_result($stmt);
mysqli_stmt_close($stmt);

$hasilKerusakan = null;

while(
$r =
mysqli_fetch_assoc($aturan)
){

    $idAturan =
    $r['id_aturan'];

    $stmt = mysqli_prepare($conn, 'SELECT id_gejala FROM detail_aturan WHERE id_aturan=?');
    mysqli_stmt_bind_param($stmt, 'i', $idAturan);
    mysqli_stmt_execute($stmt);
    $detail = mysqli_stmt_get_result($stmt);
    mysqli_stmt_close($stmt);

    $syarat = [];

    while(
    $d =
    mysqli_fetch_assoc($detail)
    ){

        $syarat[] =
        $d['id_gejala'];

    }

    $match = true;

    foreach($syarat as $s){

        if(
        !in_array(
        $s,
        $gejalaDipilih
        )
        ){

            $match = false;
            break;

        }
    }

    if($match){

        $hasilKerusakan =
        $r['id_kerusakan'];

        break;
    }
}

/*
|--------------------------------------------------------------------------
| JIKA TIDAK DITEMUKAN
|--------------------------------------------------------------------------
*/

if(!$hasilKerusakan){

    echo "
    <script>

    alert('Kerusakan tidak terdeteksi');

    window.location='../index.php?page=diagnosa';

    </script>
    ";

    exit;
}

/*
|--------------------------------------------------------------------------
| AMBIL DATA KERUSAKAN
|--------------------------------------------------------------------------
*/

$stmt = mysqli_prepare($conn, 'SELECT * FROM kerusakan WHERE id_kerusakan=?');
mysqli_stmt_bind_param($stmt, 'i', $hasilKerusakan);
mysqli_stmt_execute($stmt);
$kerusakan = mysqli_stmt_get_result($stmt);
mysqli_stmt_close($stmt);

$dataKerusakan =
mysqli_fetch_assoc(
$kerusakan
);

$namaKerusakan =
$dataKerusakan['nama_kerusakan'];

$solusi =
$dataKerusakan['solusi'];

/*
|--------------------------------------------------------------------------
| SIMPAN RIWAYAT
|--------------------------------------------------------------------------
*/

$idPengguna =
$_SESSION['id_pengguna'];

$namaPengguna =
$_SESSION['nama'];

$daftarGejala =
implode(
', ',
$gejalaDipilih
);

$stmt = mysqli_prepare($conn, 'INSERT INTO riwayat_diagnosa
    (id_pengguna,nama_pengguna,gejala_dipilih,hasil_kerusakan,solusi) VALUES (?,?,?,?,?)');
mysqli_stmt_bind_param($stmt, 'issss', $idPengguna, $namaPengguna, $daftarGejala, $namaKerusakan, $solusi);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

$_SESSION['hasil_kerusakan']
= $namaKerusakan;

$_SESSION['solusi']
= $solusi;

header(
"Location: ../index.php?page=hasil_diagnosa"
);

exit;
