<?php
require_once __DIR__ . '/../../helpers/auth.php';
requireLogin();

require_once __DIR__ . '/../../config/database.php';
$gejala = mysqli_query($conn, "SELECT * FROM gejala ORDER BY kode_gejala ASC");
?>

<style>
    .gejala-box { border: 1px solid #e9ecef; border-radius: 8px; padding: 12px 15px; transition: .2s; background: #fff; height: 100%; }
    .gejala-box:hover { border-color: #007bff; background: #f8faff; box-shadow: 0 4px 10px rgba(0,123,255,.1); transform: translateY(-2px); }
    .custom-control-label { cursor: pointer; width: 100%; font-weight: normal !important; }
    .kode-badge { background: #e9ecef; color: #495057; padding: 3px 8px; border-radius: 4px; font-size: 12px; font-weight: bold; margin-right: 10px; }
</style>

<div class="content-wrapper">
    <section class="content-header"><div class="container-fluid"><div class="row mb-2"><div class="col-sm-6">
        <h1 class="m-0" style="color: #2c3e50; font-weight: 600;">Diagnosa Kerusakan Hardware</h1>
    </div></div></div></section>

    <section class="content"><div class="container-fluid">
        <div class="card card-primary card-outline shadow-sm" style="border-radius: 10px;">
            <div class="card-header bg-transparent border-bottom-0 pt-4 pb-2">
                <h3 class="card-title" style="color: #34495e; font-weight: 500;"><i class="fas fa-list-check mr-2 text-primary"></i> Pilih Gejala Yang Dialami</h3>
            </div>
            
            <form action="controllers/DiagnosaController.php" method="POST">
        <?= csrfField() ?>
                <div class="card-body bg-light" style="border-radius: 0 0 10px 10px;">
                    <div class="alert alert-default-info bg-white shadow-sm border-0 mb-4" style="border-left: 4px solid #17a2b8 !important;">
                        <i class="fas fa-info-circle text-info mr-2"></i> Tandai satu atau lebih gejala yang sedang terjadi pada perangkat keras komputer.
                    </div>
                    
                    <div class="row">
                        <?php while($row = mysqli_fetch_assoc($gejala)): ?>
                        <div class="col-md-6 col-lg-4 mb-3"><div class="gejala-box"><div class="custom-control custom-checkbox">
                            <input class="custom-control-input" type="checkbox" name="gejala[]" value="<?= e($row['id_gejala']) ?>" id="g<?= e($row['id_gejala']) ?>">
                            <label class="custom-control-label d-flex align-items-start" for="g<?= e($row['id_gejala']) ?>">
                                <span class="kode-badge"><?= e($row['kode_gejala']) ?></span>
                                <span class="text-sm mt-1" style="line-height: 1.4;"><?= e($row['nama_gejala']) ?></span>
                            </label>
                        </div></div></div>
                        <?php endwhile; ?>
                    </div>
                </div>
                
                <div class="card-footer bg-white border-top p-3 text-right" style="border-radius: 0 0 10px 10px;">
                    <a href="index.php?page=dashboard" class="btn btn-light border px-4 mr-2">Batal</a>
                    <button type="submit" name="proses" class="btn btn-primary px-4 shadow-sm"><i class="fas fa-search mr-1"></i> Proses Diagnosa</button>
                </div>
            </form>
        </div>
    </div></section>
</div>