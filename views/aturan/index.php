<?php
require_once __DIR__ . '/../../helpers/auth.php';
requireAdmin();

require_once __DIR__ . '/../../config/database.php';

// Tarik master data kerusakan & gejala sekali saja di awal untuk mencegah query berulang
$q_ker = mysqli_query($conn, "SELECT * FROM kerusakan ORDER BY kode_kerusakan ASC");
$list_ker = []; while($k = mysqli_fetch_assoc($q_ker)) $list_ker[] = $k;

$q_gej = mysqli_query($conn, "SELECT * FROM gejala ORDER BY kode_gejala ASC");
$list_gej = []; while($g = mysqli_fetch_assoc($q_gej)) $list_gej[] = $g;

// Tambahkan a.id_kerusakan agar bisa dicocokkan di Modal Edit
$data = mysqli_query($conn, "SELECT a.id_aturan, a.kode_aturan, a.id_kerusakan, k.kode_kerusakan, k.nama_kerusakan FROM aturan a JOIN kerusakan k ON a.id_kerusakan = k.id_kerusakan ORDER BY a.id_aturan ASC");
?>

<div class="content-wrapper">
    <section class="content-header"><div class="container-fluid"><div class="row"><div class="col-sm-6">
        <h1>Data Aturan</h1>
    </div><div class="col-sm-6 text-right">
        <button class="btn btn-primary" data-toggle="modal" data-target="#modalTambah"><i class="fas fa-plus"></i> Tambah Aturan</button>
    </div></div></div></section>

    <section class="content"><div class="container-fluid"><div class="card"><div class="card-body">
        <table id="tabelAturan" class="table table-bordered table-striped">
            <thead><tr><th width="50">No</th><th>Kode Aturan</th><th>Gejala</th><th>Kerusakan</th><th width="180">Aksi</th></tr></thead>
            <tbody>
                <?php $no = 1; while($row = mysqli_fetch_assoc($data)): 
                    // Menggabungkan query detail gejala & selected ke dalam 1 tarikan query
                    $stmt = mysqli_prepare($conn, 'SELECT g.id_gejala, g.kode_gejala FROM detail_aturan d JOIN gejala g ON d.id_gejala = g.id_gejala WHERE d.id_aturan=? ORDER BY g.kode_gejala ASC');
                    mysqli_stmt_bind_param($stmt, 'i', $row['id_aturan']);
                    mysqli_stmt_execute($stmt);
                    $detail = mysqli_stmt_get_result($stmt);
                    mysqli_stmt_close($stmt);
                    $gejalaList = []; $selected = [];
                    while($g = mysqli_fetch_assoc($detail)){
                        $gejalaList[] = $g['kode_gejala'];
                        $selected[] = $g['id_gejala'];
                    }
                ?>
                <tr>
                    <td><?= $no++ ?></td><td><?= e($row['kode_aturan']) ?></td><td><?= e(implode(', ', $gejalaList)) ?></td>
                    <td><?= e($row['kode_kerusakan']) ?> - <?= e($row['nama_kerusakan']) ?></td>
                    <td style="white-space: nowrap;">
                        <button class="btn btn-warning btn-sm" style="margin-right:5px;" data-toggle="modal" data-target="#edit<?= e($row['id_aturan']) ?>">Edit</button>
                        <form action="controllers/AturanController.php" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data?')">
        <?= csrfField() ?>
                            <input type="hidden" name="hapus" value="<?= e($row['id_aturan']) ?>">
                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                        </form>
                    </td>
                </tr>

                <div class="modal fade" id="edit<?= e($row['id_aturan']) ?>"><div class="modal-dialog modal-lg"><div class="modal-content">
                    <form action="controllers/AturanController.php" method="POST">
        <?= csrfField() ?>
                        <div class="modal-header"><h4 class="modal-title">Edit Aturan</h4><button type="button" class="close" data-dismiss="modal"><span>&times;</span></button></div>
                        <div class="modal-body">
                            <input type="hidden" name="id_aturan" value="<?= e($row['id_aturan']) ?>">
                            <div class="form-group"><label>Kerusakan</label>
                                <select name="id_kerusakan" class="form-control" required>
                                    <?php foreach($list_ker as $k): ?>
                                        <option value="<?= e($k['id_kerusakan']) ?>" <?= ($k['id_kerusakan'] == $row['id_kerusakan']) ? 'selected' : '' ?>><?= e($k['kode_kerusakan']) ?> - <?= e($k['nama_kerusakan']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div><hr><label>Gejala</label><div class="row">
                                <?php foreach($list_gej as $g): ?>
                                    <div class="col-md-6"><div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="id_gejala[]" value="<?= e($g['id_gejala']) ?>" <?= in_array($g['id_gejala'], $selected) ? 'checked' : '' ?>>
                                        <label class="form-check-label"><?= e($g['kode_gejala']) ?> - <?= e($g['nama_gejala']) ?></label>
                                    </div></div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div class="modal-footer"><button type="submit" name="edit" class="btn btn-warning">Update</button></div>
                    </form>
                </div></div></div>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div></div></div></section>
</div>

<div class="modal fade" id="modalTambah"><div class="modal-dialog modal-lg"><div class="modal-content">
    <form action="controllers/AturanController.php" method="POST">
        <?= csrfField() ?>
        <div class="modal-header"><h4 class="modal-title">Tambah Aturan</h4><button type="button" class="close" data-dismiss="modal"><span>&times;</span></button></div>
        <div class="modal-body">
            <div class="form-group"><label>Kerusakan</label>
                <select name="id_kerusakan" class="form-control" required>
                    <?php foreach($list_ker as $k): ?>
                        <option value="<?= e($k['id_kerusakan']) ?>"><?= e($k['kode_kerusakan']) ?> - <?= e($k['nama_kerusakan']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div><hr><label>Gejala</label><div class="row">
                <?php foreach($list_gej as $g): ?>
                    <div class="col-md-6"><div class="form-check">
                        <input type="checkbox" class="form-check-input" name="id_gejala[]" value="<?= e($g['id_gejala']) ?>">
                        <label class="form-check-label"><?= e($g['kode_gejala']) ?> - <?= e($g['nama_gejala']) ?></label>
                    </div></div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="modal-footer"><button type="submit" name="tambah" class="btn btn-primary">Simpan</button></div>
    </form>
</div></div></div>

<script>$(function(){ $('#tabelAturan').DataTable(); });</script>