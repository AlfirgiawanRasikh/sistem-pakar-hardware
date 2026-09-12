<?php
require_once __DIR__ . '/../../helpers/auth.php';
requireLogin();

require_once __DIR__ . '/../../config/database.php';

if ($_SESSION['role'] === 'admin') {
    $stmt = mysqli_prepare($conn, 'SELECT * FROM riwayat_diagnosa ORDER BY id_riwayat DESC');
} else {
    $idPengguna = positiveId($_SESSION['id_pengguna']);
    $stmt = mysqli_prepare($conn, 'SELECT * FROM riwayat_diagnosa WHERE id_pengguna=? ORDER BY id_riwayat DESC');
    mysqli_stmt_bind_param($stmt, 'i', $idPengguna);
}
mysqli_stmt_execute($stmt);
$data = mysqli_stmt_get_result($stmt);
mysqli_stmt_close($stmt);
?>

<div class="content-wrapper">
    <section class="content-header"><div class="container-fluid"><div class="row mb-2"><div class="col-sm-6">
        <h1 class="m-0 text-dark"><i class="fas fa-history mr-2"></i> Riwayat Diagnosa</h1>
    </div></div></div></section>

    <section class="content"><div class="container-fluid">
        <div class="card card-primary card-outline shadow-sm">
            <div class="card-header"><h3 class="card-title mt-1"><i class="fas fa-list-alt mr-1"></i> Data Riwayat Diagnosa</h3></div>
            <div class="card-body"><div class="table-responsive">
                <table id="tabelRiwayat" class="table table-bordered table-striped table-hover">
                    <thead class="bg-light">
                        <tr><th class="text-center" width="50">No</th><th>Nama Pengguna</th><th>Hasil Diagnosa</th><th>Tanggal</th><th class="text-center" width="160">Aksi</th></tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; while($row = mysqli_fetch_assoc($data)): ?>
                        <tr>
                            <td class="text-center align-middle"><?= $no++ ?></td>
                            <td class="align-middle font-weight-bold"><?= e($row['nama_pengguna']) ?></td>
                            <td class="align-middle"><span class="badge badge-warning px-2 py-1" style="font-size: 13px;"><?= e($row['hasil_kerusakan']) ?></span></td>
                            <td class="align-middle text-muted"><i class="far fa-calendar-alt mr-1"></i> <?= date('d M Y, H:i', strtotime($row['tanggal'])) ?></td>
                            <td class="text-center align-middle">
                                <a href="index.php?page=detail_riwayat&id=<?= e($row['id_riwayat']) ?>" class="btn btn-info btn-sm shadow-sm"><i class="fas fa-eye"></i> Detail</a>
                                <form action="controllers/RiwayatController.php" method="POST" class="d-inline" onsubmit="return confirm('Yakin nih mau menghapus riwayat diagnosa ini?')">
        <?= csrfField() ?>
                            <input type="hidden" name="hapus" value="<?= e($row['id_riwayat']) ?>">
                            <button type="submit" class="btn btn-danger btn-sm shadow-sm"><i class="fas fa-trash"></i> Hapus</button>
                        </form>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div></div>
        </div>
    </div></section>
</div>

<script>
    $(function(){
        $('#tabelRiwayat').DataTable({ responsive: true, autoWidth: false, language: { search: "Cari Riwayat:", lengthMenu: "Tampilkan _MENU_ data", info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data", infoEmpty: "Tidak ada data yang tersedia", zeroRecords: "Data riwayat tidak ditemukan", paginate: { first: "Pertama", last: "Terakhir", next: "Selanjutnya", previous: "Sebelumnya" } } });
    });
</script>