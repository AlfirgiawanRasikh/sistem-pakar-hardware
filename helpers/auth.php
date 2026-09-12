<?php

if (session_status() !== PHP_SESSION_ACTIVE) {
    ini_set('session.use_strict_mode', '1');
    ini_set('session.use_only_cookies', '1');
    $https = !empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off';
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'secure' => $https,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

function requireLogin(): void
{
    if (($_SESSION['login'] ?? false) !== true || empty($_SESSION['id_pengguna'])) {
        http_response_code(401);
        exit('Unauthorized');
    }
    header('Cache-Control: no-store');

    // Refresh once per request so role changes and deleted accounts take effect
    // even when the browser still has an authenticated session cookie.
    static $verified = false;
    if ($verified) {
        return;
    }
    global $conn;
    require_once __DIR__ . '/../config/database.php';
    $id = positiveId($_SESSION['id_pengguna']);
    $stmt = mysqli_prepare($conn, 'SELECT nama_lengkap, role FROM pengguna WHERE id_pengguna=? LIMIT 1');
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $account = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    mysqli_stmt_close($stmt);
    if (!$account) {
        $_SESSION = [];
        session_destroy();
        http_response_code(401);
        exit('Unauthorized');
    }
    $_SESSION['nama'] = $account['nama_lengkap'];
    $_SESSION['role'] = $account['role'];
    $verified = true;
}

function requireAdmin(): void
{
    requireLogin();
    if (($_SESSION['role'] ?? '') !== 'admin') {
        http_response_code(403);
        exit('Forbidden');
    }
}

require_once __DIR__ . '/fungsi.php';
require_once __DIR__ . '/csrf.php';
