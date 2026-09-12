<?php

session_start();

include '../config/database.php';

if(!isset($_POST['proses'])){

    header(
    "Location: ../index.php?page=diagnosa"
    );

    exit;
}

$gejalaDipilih =
$_POST['gejala'] ?? [];

if(count($gejalaDipilih)==0){

    echo "
    <script>

    alert('Pilih minimal satu gejala');

    window.location='../index.php?page=diagnosa';

    </script>
    ";

    exit;
}

/*
|--------------------------------------------------------------------------
| AMBIL SEMUA ATURAN
|--------------------------------------------------------------------------
*/

$aturan = mysqli_query(
$conn,
"SELECT *
FROM aturan
ORDER BY id_aturan ASC"
);

$hasilKerusakan = null;

while(
$r =
mysqli_fetch_assoc($aturan)
){

    $idAturan =
    $r['id_aturan'];

    $detail = mysqli_query(
    $conn,
    "SELECT id_gejala
    FROM detail_aturan
    WHERE id_aturan='$idAturan'"
    );

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

$kerusakan = mysqli_query(
$conn,
"SELECT *
FROM kerusakan
WHERE id_kerusakan='$hasilKerusakan'"
);

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

mysqli_query(
$conn,
"INSERT INTO riwayat_diagnosa
(
nama_pengguna,
gejala_dipilih,
hasil_kerusakan,
solusi
)
VALUES
(
'$namaPengguna',
'$daftarGejala',
'$namaKerusakan',
'$solusi'
)"
);

$_SESSION['hasil_kerusakan']
= $namaKerusakan;

$_SESSION['solusi']
= $solusi;

header(
"Location: ../index.php?page=hasil_diagnosa"
);

exit;