<?php
include 'config/database.php';

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
                    $detail = mysqli_query($conn, "SELECT g.id_gejala, g.kode_gejala FROM detail_aturan d JOIN gejala g ON d.id_gejala = g.id_gejala WHERE d.id_aturan='{$row['id_aturan']}' ORDER BY g.kode_gejala ASC");
                    $gejalaList = []; $selected = [];
                    while($g = mysqli_fetch_assoc($detail)){
                        $gejalaList[] = $g['kode_gejala'];
                        $selected[] = $g['id_gejala'];
                    }
                ?>
                <tr>
                    <td><?= $no++ ?></td><td><?= $row['kode_aturan'] ?></td><td><?= implode(', ', $gejalaList) ?></td>
                    <td><?= $row['kode_kerusakan'] ?> - <?= $row['nama_kerusakan'] ?></td>
                    <td style="white-space: nowrap;">
                        <button class="btn btn-warning btn-sm" style="margin-right:5px;" data-toggle="modal" data-target="#edit<?= $row['id_aturan'] ?>">Edit</button>
                        <a href="controllers/AturanController.php?hapus=<?= $row['id_aturan'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus data?')">Hapus</a>
                    </td>
                </tr>

                <div class="modal fade" id="edit<?= $row['id_aturan'] ?>"><div class="modal-dialog modal-lg"><div class="modal-content">
                    <form action="controllers/AturanController.php" method="POST">
                        <div class="modal-header"><h4 class="modal-title">Edit Aturan</h4><button type="button" class="close" data-dismiss="modal"><span>&times;</span></button></div>
                        <div class="modal-body">
                            <input type="hidden" name="id_aturan" value="<?= $row['id_aturan'] ?>">
                            <div class="form-group"><label>Kerusakan</label>
                                <select name="id_kerusakan" class="form-control" required>
                                    <?php foreach($list_ker as $k): ?>
                                        <option value="<?= $k['id_kerusakan'] ?>" <?= ($k['id_kerusakan'] == $row['id_kerusakan']) ? 'selected' : '' ?>><?= $k['kode_kerusakan'] ?> - <?= $k['nama_kerusakan'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div><hr><label>Gejala</label><div class="row">
                                <?php foreach($list_gej as $g): ?>
                                    <div class="col-md-6"><div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="id_gejala[]" value="<?= $g['id_gejala'] ?>" <?= in_array($g['id_gejala'], $selected) ? 'checked' : '' ?>>
                                        <label class="form-check-label"><?= $g['kode_gejala'] ?> - <?= $g['nama_gejala'] ?></label>
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
        <div class="modal-header"><h4 class="modal-title">Tambah Aturan</h4><button type="button" class="close" data-dismiss="modal"><span>&times;</span></button></div>
        <div class="modal-body">
            <div class="form-group"><label>Kerusakan</label>
                <select name="id_kerusakan" class="form-control" required>
                    <?php foreach($list_ker as $k): ?>
                        <option value="<?= $k['id_kerusakan'] ?>"><?= $k['kode_kerusakan'] ?> - <?= $k['nama_kerusakan'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div><hr><label>Gejala</label><div class="row">
                <?php foreach($list_gej as $g): ?>
                    <div class="col-md-6"><div class="form-check">
                        <input type="checkbox" class="form-check-input" name="id_gejala[]" value="<?= $g['id_gejala'] ?>">
                        <label class="form-check-label"><?= $g['kode_gejala'] ?> - <?= $g['nama_gejala'] ?></label>
                    </div></div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="modal-footer"><button type="submit" name="tambah" class="btn btn-primary">Simpan</button></div>
    </form>
</div></div></div>

<script>$(function(){ $('#tabelAturan').DataTable(); });</script>