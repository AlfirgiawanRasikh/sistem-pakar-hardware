<?php

require 'vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

session_start();

if(!isset($_SESSION['pdf_html'])){

    die('Data laporan tidak ditemukan');

}

$options = new Options();

$options->set(
    'isRemoteEnabled',
    true
);

$dompdf = new Dompdf($options);

$dompdf->loadHtml(
    $_SESSION['pdf_html']
);

$dompdf->setPaper(
    'A4',
    'portrait'
);

$dompdf->render();

header("Content-Type: application/pdf");

echo $dompdf->output();

exit;