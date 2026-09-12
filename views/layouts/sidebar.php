<?php
$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
$role = isset($_SESSION['role']) ? $_SESSION['role'] : '';
?>

<aside class="main-sidebar sidebar-dark-primary elevation-4">
    
    <a href="index.php" class="brand-link text-center border-bottom-0 mt-3 mb-2">
        <span class="brand-text font-weight-bold" style="letter-spacing: 1.5px;">SIMETRI INDONESIA</span>
    </a>

    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column nav-flat nav-compact" data-widget="treeview" role="menu">

                <li class="nav-item">
                    <a href="index.php?page=dashboard" class="nav-link <?= $page == 'dashboard' ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-th-large"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <?php if($role == 'admin'): ?>
                
                <li class="nav-item mt-3">
                    <span class="nav-header text-muted" style="font-size: 11px; letter-spacing: 1px;">DATABASE</span>
                </li>

                <li class="nav-item">
                    <a href="index.php?page=gejala" class="nav-link <?= $page == 'gejala' ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-cube"></i>
                        <p>Data Gejala</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="index.php?page=kerusakan" class="nav-link <?= $page == 'kerusakan' ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-cubes"></i>
                        <p>Data Kerusakan</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="index.php?page=aturan" class="nav-link <?= $page == 'aturan' ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-code-branch"></i>
                        <p>Basis Aturan</p>
                    </a>
                </li>

                <li class="nav-item mt-3">
                    <span class="nav-header text-muted" style="font-size: 11px; letter-spacing: 1px;">SISTEM</span>
                </li>

                <li class="nav-item">
                    <a href="index.php?page=pengguna" class="nav-link <?= $page == 'pengguna' ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-user-shield"></i>
                        <p>Pengguna</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="index.php?page=laporan" class="nav-link <?= $page == 'laporan' ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-print"></i>
                        <p>Laporan</p>
                    </a>
                </li>

                <?php endif; ?>

                <li class="nav-item mt-3">
                    <span class="nav-header text-muted" style="font-size: 11px; letter-spacing: 1px;">LAYANAN</span>
                </li>

                <li class="nav-item">
                    <a href="index.php?page=diagnosa" class="nav-link <?= $page == 'diagnosa' ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-stethoscope"></i>
                        <p>Diagnosa</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="index.php?page=riwayat" class="nav-link <?= $page == 'riwayat' ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-history"></i>
                        <p>Riwayat</p>
                    </a>
                </li>

                <li class="nav-item mt-4">
                    <a href="logout.php" class="nav-link" style="border-left: 3px solid #dc3545;">
                        <i class="nav-icon fas fa-power-off text-danger"></i>
                        <p class="text-danger">Logout</p>
                    </a>
                </li>

            </ul>
        </nav>
    </div>
</aside>