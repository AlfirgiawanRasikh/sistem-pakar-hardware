<?php

require_once __DIR__ . '/../helpers/auth.php';

verifyCsrf();
require_once __DIR__ . '/../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../index.php');
    exit;
}

$username = trim(inputString($_POST, 'username', false));
$password = inputString($_POST, 'password', false);

if ($username === '' || $password === '') {
    echo "
    <script>
        alert('Username dan password wajib diisi.');
        window.location='../index.php';
    </script>
    ";
    exit;
}

$stmt = mysqli_prepare(
    $conn,
    "SELECT id_pengguna, nama_lengkap, username, password, role
     FROM pengguna
     WHERE username = ?
     LIMIT 1"
);

mysqli_stmt_bind_param($stmt, 's', $username);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($result);

$loginValid = false;
$legacyMd5 = false;

if ($data) {
    if (password_verify($password, $data['password'])) {
        $loginValid = true;
    } elseif (md5($password) === $data['password']) {
        // Dukungan sementara untuk password lama MD5.
        $loginValid = true;
        $legacyMd5 = true;
    }
}

if ($loginValid) {

    // Otomatis upgrade MD5 lama ke password_hash().
    if ($legacyMd5) {
        $newHash = password_hash($password, PASSWORD_DEFAULT);

        $update = mysqli_prepare(
            $conn,
            "UPDATE pengguna
             SET password = ?
             WHERE id_pengguna = ?"
        );

        mysqli_stmt_bind_param(
            $update,
            'si',
            $newHash,
            $data['id_pengguna']
        );

        mysqli_stmt_execute($update);
        mysqli_stmt_close($update);
    }

    session_regenerate_id(true);
    $_SESSION = []; // Discard any previous account's result/report and CSRF token.

    $_SESSION['login'] = true;
    $_SESSION['id_pengguna'] = $data['id_pengguna'];
    $_SESSION['nama'] = $data['nama_lengkap'];
    $_SESSION['role'] = $data['role'];

    mysqli_stmt_close($stmt);

    header('Location: ../index.php?page=dashboard');
    exit;
}

mysqli_stmt_close($stmt);

echo "
<script>
    alert('Username atau password salah!');
    window.location='../index.php';
</script>
";