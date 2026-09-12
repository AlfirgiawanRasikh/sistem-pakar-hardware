<?php
// Cek apakah ada session hasil, jika tidak ada kembalikan ke halaman diagnosa
if(!isset($_SESSION['hasil_kerusakan'])){
    echo "<script>window.location='index.php?page=diagnosa';</script>";
    exit;
}
?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark" style="font-weight: 600;">Kesimpulan Diagnosa</h1>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            
            <div class="row justify-content-center">
                <div class="col-md-10 col-lg-8">
                    
                    <div class="card shadow-lg border-0" style="border-radius: 15px; border-top: 5px solid #28a745 !important;">
                        <div class="card-body p-4 p-md-5">
                            
                            <div class="text-center mb-5">
                                <div class="d-inline-block p-4 rounded-circle bg-light shadow-sm mb-3">
                                    <i class="fas fa-laptop-medical text-success" style="font-size: 60px;"></i>
                                </div>
                                <h2 class="font-weight-bold" style="color: #2c3e50;">Diagnosa Selesai!</h2>
                                <p class="text-muted" style="font-size: 15px;">
                                    Sistem telah selesai menganalisis gejala yang dialami oleh perangkat keras komputer Anda.
                                </p>
                            </div>

                            <div class="alert shadow-sm mb-4" style="border-left: 5px solid #dc3545; background-color: #fffafb; color: #333;">
                                <h5 class="font-weight-bold text-danger mb-2">
                                    <i class="fas fa-exclamation-triangle mr-2"></i> Kerusakan Teridentifikasi:
                                </h5>
                                <h4 class="font-weight-bold mb-0 ml-4" style="color: #c82333;">
                                    <?= $_SESSION['hasil_kerusakan']; ?>
                                </h4>
                            </div>

                            <div class="alert shadow-sm" style="border-left: 5px solid #17a2b8; background-color: #f4fbfe; color: #333;">
                                <h5 class="font-weight-bold text-info mb-3">
                                    <i class="fas fa-tools mr-2"></i> Solusi Perbaikan:
                                </h5>
                                <div class="ml-4 text-justify" style="line-height: 1.6; font-size: 15px;">
                                    <?= nl2br($_SESSION['solusi']); ?>
                                </div>
                            </div>
                            
                            <div class="text-center mt-4">
                                <small class="text-muted">
                                    <i class="fas fa-info-circle mr-1"></i> Hasil ini ditarik berdasarkan basis aturan sistem pakar. Jika masalah berlanjut, disarankan untuk membawa perangkat ke teknisi profesional.
                                </small>
                            </div>

                        </div>
                        
                        <div class="card-footer bg-white text-center py-4" style="border-radius: 0 0 15px 15px; border-top: 1px dashed #e9ecef;">
                            <a href="index.php?page=diagnosa" class="btn btn-primary px-4 mr-2 shadow-sm" style="border-radius: 8px;">
                                <i class="fas fa-redo-alt mr-2"></i> Diagnosa Ulang
                            </a>
                            <a href="index.php?page=riwayat" class="btn btn-outline-secondary px-4 shadow-sm" style="border-radius: 8px;">
                                <i class="fas fa-history mr-2"></i> Lihat Riwayat
                            </a>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </section>
</div>

<?php
// Hapus session setelah ditampilkan agar data tidak menetap ketika di-refresh
unset($_SESSION['hasil_kerusakan']);
unset($_SESSION['solusi']);
?>