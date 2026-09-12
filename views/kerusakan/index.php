<?php
include 'config/database.php';

$kode = mysqli_fetch_assoc(mysqli_query($conn, "SELECT MAX(kode_kerusakan) as k FROM kerusakan"))['k'] ?? null;
$kodeBaru = "K" . str_pad($kode ? ((int)substr($kode, 1) + 1) : 1, 2, "0", STR_PAD_LEFT);
$data = mysqli_query($conn, "SELECT * FROM kerusakan ORDER BY kode_kerusakan ASC");
?>

<div class="content-wrapper">
    <section class="content-header"><div class="container-fluid"><div class="row"><div class="col-sm-6">
        <h1>Data Kerusakan</h1>
    </div><div class="col-sm-6 text-right">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalTambah"><i class="fas fa-plus"></i> Tambah Kerusakan</button>
    </div></div></div></section>

    <section class="content"><div class="container-fluid"><div class="card"><div class="card-body">
        <table id="tabelKerusakan" class="table table-bordered table-striped">
            <thead><tr><th width="50">No</th><th width="100">Kode</th><th>Nama Kerusakan</th><th>Solusi</th><th width="140">Aksi</th></tr></thead>
            <tbody>
                <?php $no = 1; while($row = mysqli_fetch_assoc($data)): ?>
                <tr>
                    <td><?= $no++ ?></td><td><?= $row['kode_kerusakan'] ?></td><td><?= $row['nama_kerusakan'] ?></td><td><?= $row['solusi'] ?></td>
                    <td>
                        <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#edit<?= $row['id_kerusakan'] ?>">Edit</button>
                        <a href="controllers/KerusakanController.php?hapus=<?= $row['id_kerusakan'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus data?')">Hapus</a>
                    </td>
                </tr>

                <div class="modal fade" id="edit<?= $row['id_kerusakan'] ?>"><div class="modal-dialog"><div class="modal-content">
                    <form action="controllers/KerusakanController.php" method="POST">
                        <div class="modal-header"><h4 class="modal-title">Edit Kerusakan</h4><button type="button" class="close" data-dismiss="modal"><span>&times;</span></button></div>
                        <div class="modal-body">
                            <input type="hidden" name="id_kerusakan" value="<?= $row['id_kerusakan'] ?>">
                            <div class="form-group"><label>Kode Kerusakan</label><input type="text" name="kode_kerusakan" class="form-control" value="<?= $row['kode_kerusakan'] ?>" readonly></div>
                            <div class="form-group"><label>Nama Kerusakan</label><input type="text" name="nama_kerusakan" class="form-control" value="<?= $row['nama_kerusakan'] ?>" required></div>
                            <div class="form-group"><label>Solusi</label><textarea name="solusi" class="form-control" rows="5" required><?= $row['solusi'] ?></textarea></div>
                        </div>
                        <div class="modal-footer"><button type="submit" name="edit" class="btn btn-warning">Update</button><button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button></div>
                    </form>
                </div></div></div>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div></div></div></section>
</div>

<div class="modal fade" id="modalTambah"><div class="modal-dialog"><div class="modal-content">
    <form action="controllers/KerusakanController.php" method="POST">
        <div class="modal-header"><h4 class="modal-title">Tambah Kerusakan</h4><button type="button" class="close" data-dismiss="modal"><span>&times;</span></button></div>
        <div class="modal-body">
            <input type="hidden" name="kode_kerusakan" value="<?= $kodeBaru ?>">
            <div class="form-group"><label>Kode Kerusakan</label><input type="text" class="form-control" value="<?= $kodeBaru ?>" readonly></div>
            <div class="form-group"><label>Nama Kerusakan</label><input type="text" name="nama_kerusakan" class="form-control" required></div>
            <div class="form-group"><label>Solusi</label><textarea name="solusi" class="form-control" rows="5" required></textarea></div>
        </div>
        <div class="modal-footer"><button type="submit" name="tambah" class="btn btn-primary">Simpan</button><button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button></div>
    </form>
</div></div></div>

<script>$(function(){ $('#tabelKerusakan').DataTable(); });</script>