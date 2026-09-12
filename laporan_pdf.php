<?php

require_once __DIR__ . '/helpers/auth.php';
requireAdmin();
require __DIR__ . '/vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;



if(!isset($_SESSION['pdf_html'])){

    http_response_code(404);
    exit('Data laporan tidak ditemukan');

}

$options = new Options();

$options->set(
    'isRemoteEnabled',
    false
);

$options->set('isPhpEnabled', false);
$options->set('isJavascriptEnabled', false);
$options->setChroot(__DIR__ . '/assets');
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