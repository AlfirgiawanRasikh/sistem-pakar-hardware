<?php
require_once __DIR__ . '/../../helpers/auth.php';
requireLogin();

require_once __DIR__ . '/../../config/database.php';

$role = $_SESSION['role'] ?? 'pengguna';
$nama = $_SESSION['nama']; 

if ($role === 'admin') {
    $counts = [];
    foreach (['gejala', 'kerusakan', 'aturan', 'pengguna', 'riwayat_diagnosa'] as $tabel) {
        $counts[$tabel] = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM $tabel"))['total'];
    }
    $hariIni = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM riwayat_diagnosa WHERE DATE(tanggal)=CURDATE()"))['total'];
    
    $cards = [
        ['bg-primary', $counts['gejala'], 'Total Gejala', 'fa-list', 'gejala'],
        ['bg-success', $counts['kerusakan'], 'Total Kerusakan', 'fa-tools', 'kerusakan'],
        ['bg-warning', $counts['aturan'], 'Total Aturan', 'fa-project-diagram', 'aturan'],
        ['bg-danger', $counts['pengguna'], 'Total Pengguna', 'fa-users', 'pengguna']
    ];
} else {
    $idPengguna = positiveId($_SESSION['id_pengguna']);
    $stmt = mysqli_prepare($conn, 'SELECT COUNT(*) AS total FROM riwayat_diagnosa WHERE id_pengguna=?');
    mysqli_stmt_bind_param($stmt, 'i', $idPengguna);
    mysqli_stmt_execute($stmt);
    $totalDiagnosaUser = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt))['total'];
    mysqli_stmt_close($stmt);
}
?>

<div class="content-wrapper">
    <div class="content-header"><div class="container-fluid"><div class="row mb-2"><div class="col-sm-6"><h1 class="m-0 text-dark">Dashboard</h1></div></div></div></div>

    <section class="content"><div class="container-fluid">
        <?php if ($role === 'admin') : ?>
            
            <div class="row">
                <?php foreach($cards as $k): ?>
                <div class="col-lg-3 col-6"><div class="small-box <?= e($k[0]) ?> shadow-sm">
                    <div class="inner"><h3><?= e($k[1]) ?></h3><p><?= e($k[2]) ?></p></div>
                    <div class="icon"><i class="fas <?= e($k[3]) ?>"></i></div>
                    <a href="?page=<?= e($k[4]) ?>" class="small-box-footer">Lebih Lanjut <i class="fas fa-arrow-circle-right"></i></a>
                </div></div>
                <?php endforeach; ?>
            </div>

            <div class="row">
                <?php 
                $infos = [['bg-info', 'fa-stethoscope', 'Total Diagnosa Seluruhnya', $counts['riwayat_diagnosa']], ['bg-secondary', 'fa-calendar-day', 'Diagnosa Hari Ini', $hariIni]];
                foreach($infos as $i): ?>
                <div class="col-md-6 col-sm-6 col-12"><div class="info-box shadow-sm">
                    <span class="info-box-icon <?= e($i[0]) ?>"><i class="fas <?= e($i[1]) ?>"></i></span>
                    <div class="info-box-content"><span class="info-box-text"><?= e($i[2]) ?></span><span class="info-box-number"><?= e($i[3]) ?> <small>kali</small></span></div>
                </div></div>
                <?php endforeach; ?>
            </div>

            <div class="row mt-2">
                <div class="col-md-8"><div class="card card-primary card-outline shadow-sm">
                    <div class="card-header"><h3 class="card-title"><i class="fas fa-clock mr-1"></i> Riwayat Diagnosa Terbaru</h3></div>
                    <div class="card-body p-0"><table class="table table-striped table-hover">
                        <thead><tr><th style="width: 10px">No</th><th>Pengguna</th><th>Hasil Kerusakan</th><th>Tanggal</th></tr></thead>
                        <tbody>
                            <?php
                            $riwayat = mysqli_query($conn, "SELECT * FROM riwayat_diagnosa ORDER BY id_riwayat DESC LIMIT 5");
                            $no = 1; while($r = mysqli_fetch_assoc($riwayat)): ?>
                            <tr>
                                <td><?= $no++ ?></td><td><b><?= e($r['nama_pengguna']) ?></b></td>
                                <td><span class="badge badge-warning"><?= e($r['hasil_kerusakan']) ?></span></td>
                                <td><small class="text-muted"><i class="fas fa-calendar-alt mr-1"></i> <?= date('d M Y, H:i', strtotime($r['tanggal'])) ?></small></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table></div>
                </div></div>

                <div class="col-md-4"><div class="card card-danger card-outline shadow-sm">
                    <div class="card-header"><h3 class="card-title"><i class="fas fa-chart-pie mr-1"></i> Top Kerusakan</h3></div>
                    <div class="card-body p-0"><table class="table">
                        <thead><tr><th>Kerusakan</th><th>Trend</th><th style="width: 40px">Total</th></tr></thead>
                        <tbody>
                            <?php
                            $top = mysqli_query($conn, "SELECT hasil_kerusakan, COUNT(*) AS total FROM riwayat_diagnosa GROUP BY hasil_kerusakan ORDER BY total DESC LIMIT 5");
                            $totDiag = max($counts['riwayat_diagnosa'], 1);
                            while($t = mysqli_fetch_assoc($top)): $persen = round(($t['total'] / $totDiag) * 100); ?>
                            <tr>
                                <td><?= e($t['hasil_kerusakan']) ?></td>
                                <td class="align-middle"><div class="progress progress-xs"><div class="progress-bar bg-danger" style="width: <?= $persen ?>%"></div></div></td>
                                <td><span class="badge bg-danger"><?= e($t['total']) ?></span></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table></div>
                </div></div>
            </div>

        <?php else : ?>
            
            <div class="row">
                <div class="col-lg-8">
                    <div class="card card-primary card-outline shadow-sm"><div class="card-body">
                        <h3 class="text-primary"><i class="fas fa-smile mr-2"></i> Selamat Datang, <?= e($nama) ?>!</h3>
                        <p class="lead mt-3">Di Sistem Pakar Diagnosa Kerusakan Hardware Komputer.</p>
                        <p class="text-justify text-muted">Sistem ini dirancang untuk membantu Anda mengidentifikasi dan mendiagnosa kerusakan pada perangkat keras komputer. Cukup dengan memilih gejala-gejala yang sedang dialami oleh komputer Anda, sistem akan menggunakan basis pengetahuan (aturan) yang ada untuk menarik kesimpulan dan memberikan solusi perbaikan yang tepat.</p>
                        <hr>
                        <a href="?page=diagnosa" class="btn btn-primary btn-lg mt-2 mr-2"><i class="fas fa-laptop-medical mr-2"></i> Mulai Diagnosa</a>
                        <a href="?page=riwayat" class="btn btn-outline-secondary btn-lg mt-2"><i class="fas fa-history mr-2"></i> Lihat Riwayat Diagnosa</a>
                    </div></div>

                    <div class="card shadow-sm mt-3"><div class="card-header bg-info"><h3 class="card-title"><i class="fas fa-book-open mr-2"></i> Panduan Penggunaan</h3></div>
                    <div class="card-body"><ul class="list-group list-group-unbordered">
                        <?php 
                        $panduan = [
                            "Klik tombol <strong class='text-primary'>Mulai Diagnosa</strong> pada menu atau tombol di atas.",
                            "Baca dan centang gejala-gejala kerusakan yang dirasakan atau terjadi pada komputer Anda.",
                            "Setelah selesai memilih, klik tombol <strong>Proses Diagnosa</strong>.",
                            "Sistem akan menampilkan hasil berupa nama kerusakan, persentase kepastian (jika ada), dan solusi perbaikannya.",
                            "Anda dapat mencetak hasil diagnosa atau melihatnya kembali di halaman riwayat."
                        ];
                        foreach($panduan as $i => $step): ?>
                            <li class="list-group-item border-0"><b><?= $i+1 ?>.</b> <?= $step ?></li>
                        <?php endforeach; ?>
                    </ul></div></div>
                </div>

                <div class="col-lg-4"><div class="small-box bg-success shadow-sm">
                    <div class="inner"><h3><?= $totalDiagnosaUser ?></h3><p>Diagnosa yang Anda Lakukan</p></div>
                    <div class="icon"><i class="fas fa-clipboard-check"></i></div>
                    <a href="?page=riwayat" class="small-box-footer">Detail Riwayat <i class="fas fa-arrow-circle-right"></i></a>
                </div></div>
            </div>

        <?php endif; ?>
    </div></section>
</div>