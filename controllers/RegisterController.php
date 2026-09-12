<?php

session_start();

require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../register.php');
    exit;
}

$nama = trim($_POST['nama_lengkap'] ?? '');
$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';
$konfirmasi = $_POST['konfirmasi'] ?? '';

if (
    $nama === '' ||
    $username === '' ||
    $password === '' ||
    $konfirmasi === ''
) {
    echo "
    <script>
        alert('Semua field wajib diisi.');
        window.location='../register.php';
    </script>
    ";
    exit;
}

if ($password !== $konfirmasi) {
    echo "
    <script>
        alert('Konfirmasi password tidak sesuai.');
        window.location='../register.php';
    </script>
    ";
    exit;
}

if (strlen($password) < 8) {
    echo "
    <script>
        alert('Password minimal 8 karakter.');
        window.location='../register.php';
    </script>
    ";
    exit;
}

$cek = mysqli_prepare(
    $conn,
    "SELECT id_pengguna
     FROM pengguna
     WHERE username = ?
     LIMIT 1"
);

mysqli_stmt_bind_param($cek, 's', $username);
mysqli_stmt_execute($cek);

$result = mysqli_stmt_get_result($cek);

if (mysqli_fetch_assoc($result)) {
    mysqli_stmt_close($cek);

    echo "
    <script>
        alert('Username sudah digunakan.');
        window.location='../register.php';
    </script>
    ";
    exit;
}

mysqli_stmt_close($cek);

$passwordHash = password_hash(
    $password,
    PASSWORD_DEFAULT
);

$stmt = mysqli_prepare(
    $conn,
    "INSERT INTO pengguna
        (nama_lengkap, username, password, role)
     VALUES
        (?, ?, ?, 'pengguna')"
);

mysqli_stmt_bind_param(
    $stmt,
    'sss',
    $nama,
    $username,
    $passwordHash
);

if (!mysqli_stmt_execute($stmt)) {
    die('Registrasi gagal.');
}

mysqli_stmt_close($stmt);

echo "
<script>
    alert('Registrasi berhasil. Silakan login.');
    window.location='../index.php';
</script>
";