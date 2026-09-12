<?php
include 'config/database.php';

$role = $_SESSION['role'] ?? '';
$nama_safe = mysqli_real_escape_string($conn, $_SESSION['nama'] ?? '');

$query = ($role === 'admin') 
    ? "SELECT * FROM riwayat_diagnosa ORDER BY id_riwayat DESC" 
    : "SELECT * FROM riwayat_diagnosa WHERE nama_pengguna='$nama_safe' ORDER BY id_riwayat DESC";

$data = mysqli_query($conn, $query);
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
                            <td class="align-middle font-weight-bold"><?= htmlspecialchars($row['nama_pengguna']) ?></td>
                            <td class="align-middle"><span class="badge badge-warning px-2 py-1" style="font-size: 13px;"><?= $row['hasil_kerusakan'] ?></span></td>
                            <td class="align-middle text-muted"><i class="far fa-calendar-alt mr-1"></i> <?= date('d M Y, H:i', strtotime($row['tanggal'])) ?></td>
                            <td class="text-center align-middle">
                                <a href="index.php?page=detail_riwayat&id=<?= $row['id_riwayat'] ?>" class="btn btn-info btn-sm shadow-sm"><i class="fas fa-eye"></i> Detail</a>
                                <a href="controllers/RiwayatController.php?hapus=<?= $row['id_riwayat'] ?>" class="btn btn-danger btn-sm shadow-sm" onclick="return confirm('Yakin nih mau menghapus riwayat diagnosa ini?')"><i class="fas fa-trash"></i> Hapus</a>
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