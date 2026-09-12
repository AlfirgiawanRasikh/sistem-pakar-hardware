<?php
require_once __DIR__ . '/../../helpers/auth.php';
requireAdmin();

require_once __DIR__ . '/../../config/database.php';

$kode = mysqli_fetch_assoc(mysqli_query($conn, "SELECT MAX(kode_gejala) as k FROM gejala"))['k'] ?? null;
$kodeBaru = "G" . str_pad($kode ? ((int)substr($kode, 1) + 1) : 1, 2, "0", STR_PAD_LEFT);
$data = mysqli_query($conn, "SELECT * FROM gejala ORDER BY kode_gejala ASC");
?>

<div class="content-wrapper">
    <section class="content-header"><div class="container-fluid"><div class="row"><div class="col-sm-6">
        <h1>Data Gejala</h1>
    </div><div class="col-sm-6 text-right">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalTambah"><i class="fas fa-plus"></i> Tambah Gejala</button>
    </div></div></div></section>

    <section class="content"><div class="container-fluid"><div class="card"><div class="card-body">
        <table id="tabelGejala" class="table table-bordered table-striped">
            <thead><tr><th>No</th><th>Kode</th><th>Nama Gejala</th><th>Jenis</th><th width="150">Aksi</th></tr></thead>
            <tbody>
                <?php $no = 1; while($row = mysqli_fetch_assoc($data)): ?>
                <tr>
                    <td><?= $no++ ?></td><td><?= e($row['kode_gejala']) ?></td><td><?= e($row['nama_gejala']) ?></td><td><?= e($row['jenis']) ?></td>
                    <td>
                        <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#modalEdit<?= e($row['id_gejala']) ?>">Edit</button>
                        <form action="controllers/GejalaController.php" method="POST" class="d-inline" onsubmit="return confirm('Hapus data?')">
        <?= csrfField() ?>
                            <input type="hidden" name="hapus" value="<?= e($row['id_gejala']) ?>">
                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                        </form>
                    </td>
                </tr>

                <div class="modal fade" id="modalEdit<?= e($row['id_gejala']) ?>"><div class="modal-dialog"><div class="modal-content">
                    <form action="controllers/GejalaController.php" method="POST">
        <?= csrfField() ?>
                        <div class="modal-header">
                            <h4 class="modal-title">Edit Gejala</h4>
                            <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="id_gejala" value="<?= e($row['id_gejala']) ?>">
                            <div class="form-group"><label>Kode Gejala</label><input type="text" name="kode_gejala" class="form-control" value="<?= e($row['kode_gejala']) ?>" readonly></div>
                            <div class="form-group"><label>Nama Gejala</label><input type="text" name="nama_gejala" class="form-control" value="<?= e($row['nama_gejala']) ?>" required></div>
                            <div class="form-group"><label>Jenis</label><input type="text" name="jenis" class="form-control" value="<?= e($row['jenis']) ?>" required></div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" name="edit" class="btn btn-success">Simpan Perubahan</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        </div>
                    </form>
                </div></div></div>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div></div></div></section>

    <div class="modal fade" id="modalTambah"><div class="modal-dialog"><div class="modal-content">
        <form action="controllers/GejalaController.php" method="POST">
        <?= csrfField() ?>
            <div class="modal-header">
                <h4 class="modal-title">Tambah Gejala</h4>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="form-group"><label>Kode Gejala</label><input type="text" name="kode_gejala" class="form-control" value="<?= e($kodeBaru) ?>" readonly></div>
                <div class="form-group"><label>Nama Gejala</label><input type="text" name="nama_gejala" class="form-control" required></div>
                <div class="form-group"><label>Jenis</label><input type="text" name="jenis" class="form-control" required></div>
            </div>
            <div class="modal-footer">
                <button type="submit" name="tambah" class="btn btn-primary">Simpan</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
            </div>
        </form>
    </div></div></div>
</div>