<?php

// Log details server-side; never expose queries, credentials, or stack traces.
ini_set('display_errors', '0');
ini_set('log_errors', '1');
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
set_exception_handler(function (Throwable $error): void {
    error_log('Application error: ' . get_class($error) . ' (code ' . $error->getCode() . ')');
    if (ob_get_level() > 0) {
        ob_clean();
    }
    http_response_code(500);
    exit('Terjadi kesalahan. Silakan coba lagi.');
});

// Values must be set by the host/PHP environment; .env is not loaded automatically.
$host = getenv('DB_HOST') !== false ? getenv('DB_HOST') : '127.0.0.1';
$user = getenv('DB_USER') !== false ? getenv('DB_USER') : 'root';
$pass = getenv('DB_PASS') !== false ? getenv('DB_PASS') : '';
$db = getenv('DB_NAME') !== false ? getenv('DB_NAME') : 'db_sistem_pakar_hardware';
$port = getenv('DB_PORT') !== false ? (int) getenv('DB_PORT') : (int) ini_get('mysqli.default_port');

$conn = mysqli_connect($host, $user, $pass, $db, $port);
mysqli_set_charset($conn, 'utf8mb4');
