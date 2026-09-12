<div class="content-wrapper">
    <section class="content-header"><div class="container-fluid"><h1>Laporan</h1></div></section>
    <section class="content"><div class="container-fluid">
        <div class="card card-primary">
            <div class="card-header"><h3 class="card-title">Filter Laporan</h3></div>
            <div class="card-body">
                <form action="controllers/LaporanController.php" method="POST">
                    <div class="row">
                        <div class="col-md-4"><label>Jenis Laporan</label>
                            <select name="jenis_laporan" class="form-control" required>
                                <option value="">-- Pilih Jenis Laporan --</option>
                                <option value="riwayat">Laporan Riwayat Diagnosa</option>
                                <option value="pengguna">Laporan Pengguna</option>
                                <option value="gejala">Laporan Gejala</option>
                                <option value="kerusakan">Laporan Kerusakan</option>
                                <option value="aturan">Laporan Aturan</option>
                            </select>
                        </div>
                        <div class="col-md-3"><label>Tanggal Awal</label><input type="date" name="tanggal_awal" class="form-control" required></div>
                        <div class="col-md-3"><label>Tanggal Akhir</label><input type="date" name="tanggal_akhir" class="form-control" required></div>
                        <div class="col-md-2"><label>&nbsp;</label><button type="submit" name="tampilkan" class="btn btn-primary btn-block">Tampilkan</button></div>
                    </div>
                </form>
            </div>
        </div>
        
        <?php 
        if (isset($_SESSION['preview_laporan'])) { 
            echo $_SESSION['preview_laporan']; 
            unset($_SESSION['preview_laporan']); 
        } 
        ?>
    </div></section>
</div>