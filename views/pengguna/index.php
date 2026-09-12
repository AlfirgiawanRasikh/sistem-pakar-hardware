<?php
require_once __DIR__ . '/../../helpers/auth.php';
requireAdmin();

require_once __DIR__ . '/../../config/database.php';
$data = mysqli_query($conn, "SELECT * FROM pengguna ORDER BY id_pengguna ASC");
?>

<div class="content-wrapper">
    <section class="content-header"><div class="container-fluid"><div class="row"><div class="col-sm-6">
        <h1>Data Pengguna</h1>
    </div><div class="col-sm-6 text-right">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalTambah"><i class="fas fa-plus"></i> Tambah Pengguna</button>
    </div></div></div></section>

    <section class="content"><div class="container-fluid"><div class="card"><div class="card-body">
        <table id="tabelPengguna" class="table table-bordered table-striped">
            <thead><tr><th width="50">No</th><th>Nama Lengkap</th><th>Username</th><th>Role</th><th width="130">Aksi</th></tr></thead>
            <tbody>
                <?php $no = 1; while($row = mysqli_fetch_assoc($data)): ?>
                <tr>
                    <td><?= $no++ ?></td><td><?= e($row['nama_lengkap']) ?></td><td><?= e($row['username']) ?></td>
                    <td><span class="badge badge-<?= $row['role'] == 'admin' ? 'danger' : 'success' ?>"><?= e(ucfirst($row['role'])) ?></span></td>
                    <td style="white-space: nowrap;">
                        <button class="btn btn-warning btn-sm" style="margin-right:5px;" data-toggle="modal" data-target="#edit<?= e($row['id_pengguna']) ?>">Edit</button>
                        <form action="controllers/PenggunaController.php" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data?')">
        <?= csrfField() ?>
                            <input type="hidden" name="hapus" value="<?= e($row['id_pengguna']) ?>">
                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                        </form>
                    </td>
                </tr>

                <div class="modal fade" id="edit<?= e($row['id_pengguna']) ?>"><div class="modal-dialog"><div class="modal-content">
                    <form action="controllers/PenggunaController.php" method="POST">
        <?= csrfField() ?>
                        <div class="modal-header"><h4 class="modal-title">Edit Pengguna</h4><button type="button" class="close" data-dismiss="modal"><span>&times;</span></button></div>
                        <div class="modal-body">
                            <input type="hidden" name="id_pengguna" value="<?= e($row['id_pengguna']) ?>">
                            <div class="form-group"><label>Nama Lengkap</label><input type="text" name="nama_lengkap" class="form-control" value="<?= e($row['nama_lengkap']) ?>" required></div>
                            <div class="form-group"><label>Username</label><input type="text" name="username" class="form-control" value="<?= e($row['username']) ?>" required></div>
                            <div class="form-group"><label>Password Baru</label><input type="password" minlength="8" maxlength="72" name="password" class="form-control"><small class="text-muted">Kosongkan jika tidak ingin mengubah password</small></div>
                            <div class="form-group"><label>Role</label>
                                <select name="role" class="form-control">
                                    <option value="admin" <?= $row['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
                                    <option value="pengguna" <?= $row['role'] == 'pengguna' ? 'selected' : '' ?>>Pengguna</option>
                                </select>
                            </div>
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
    <form action="controllers/PenggunaController.php" method="POST">
        <?= csrfField() ?>
        <div class="modal-header"><h4 class="modal-title">Tambah Pengguna</h4><button type="button" class="close" data-dismiss="modal"><span>&times;</span></button></div>
        <div class="modal-body">
            <div class="form-group"><label>Nama Lengkap</label><input type="text" name="nama_lengkap" class="form-control" required></div>
            <div class="form-group"><label>Username</label><input type="text" name="username" class="form-control" required></div>
            <div class="form-group"><label>Password</label><input type="password" minlength="8" maxlength="72" name="password" class="form-control" required></div>
            <div class="form-group"><label>Role</label>
                <select name="role" class="form-control">
                    <option value="admin">Admin</option>
                    <option value="pengguna">Pengguna</option>
                </select>
            </div>
        </div>
        <div class="modal-footer"><button type="submit" name="tambah" class="btn btn-primary">Simpan</button><button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button></div>
    </form>
</div></div></div>

<script>$(function(){ $('#tabelPengguna').DataTable(); });</script>