<?php

session_start();

include '../config/database.php';

if(isset($_POST['register'])){

$nama =
mysqli_real_escape_string(
$conn,
$_POST['nama_lengkap']
);

$username =
mysqli_real_escape_string(
$conn,
$_POST['username']
);

$password =
$_POST['password'];

$konfirmasi =
$_POST['konfirmasi'];

if($password != $konfirmasi){

echo "

<script>

alert('Konfirmasi password tidak sesuai');

window.location='../register.php';

</script>

";

exit;

}

$cek =
mysqli_query(
$conn,
"SELECT *
FROM pengguna
WHERE username='$username'"
);

if(mysqli_num_rows($cek)>0){

echo "

<script>

alert('Username sudah digunakan');

window.location='../register.php';

</script>

";

exit;

}

$passwordHash =
md5($password);

mysqli_query(
$conn,
"INSERT INTO pengguna
(
nama_lengkap,
username,
password,
role
)
VALUES
(
'$nama',
'$username',
'$passwordHash',
'pengguna'
)"
);

echo "

<script>

alert('Registrasi berhasil');

window.location='../index.php';

</script>

";

}