<?php
require_once __DIR__ . '/../../helpers/auth.php';
requireLogin();
?>
<nav class="main-header navbar navbar-expand navbar-white navbar-light">

<ul class="navbar-nav">

<li class="nav-item">

<a
class="nav-link"
data-widget="pushmenu"
href="#">

<i class="fas fa-bars"></i>

</a>

</li>

</ul>

<ul class="navbar-nav ml-auto">

<li class="nav-item">

<span class="nav-link">

<?= e($_SESSION['nama']) ?>

</span>

</li>

</ul>

</nav>