<?php
require_once __DIR__ . '/../config/session_config.php';
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../helpers/auth_helper.php';
require_once __DIR__ . '/../helpers/security.php';
require_once __DIR__ . '/../helpers/tanggal_helper.php';

// Cek login
require_login_siswa();

// Get data siswa
$siswa = get_siswa_data($conn);

// Get statistik berkas
$query_berkas = "SELECT jenis_berkas, status, file_path FROM berkas WHERE siswa_id = ?";
$stmt_berkas = mysqli_prepare($conn, $query_berkas);
mysqli_stmt_bind_param($stmt_berkas, "i", $siswa['id']);
mysqli_stmt_execute($stmt_berkas);
$result_berkas = mysqli_stmt_get_result($stmt_berkas);

$berkas_uploaded = [];
$foto_profil = null;

while ($row = mysqli_fetch_assoc($result_berkas)) {
    $berkas_uploaded[$row['jenis_berkas']] = $row['status'];
    if ($row['jenis_berkas'] == 'foto' && !empty($row['file_path'])) {
        $foto_profil = $row['file_path'];
    }
}

$page_title = 'Dashboard Siswa';
require_once __DIR__ . '/../templates/header.php';
require_once __DIR__ . '/../templates/navbar.php';
?>

<div class="container my-5">
    <!-- Welcome Section -->
    <div class="row mb-4">
        <div class="col-12">
            <!-- Announcement Alert -->
            <?php if ($siswa['status'] == 'lulus'): ?>
            <div class="alert alert-success border-success shadow-sm mb-4" role="alert">
                <div class="d-flex align-items-center">
                    <i class="bi bi-trophy-fill fs-1 me-3"></i>
                    <div>
                        <h4 class="alert-heading fw-bold mb-1">SELAMAT! ANDA DITERIMA</h4>
                        <p class="mb-0">Selamat, Anda dinyatakan <strong>LULUS</strong> seleksi PPDB <?php echo NAMA_SEKOLAH; ?>.</p>
                        <hr>
                        <p class="mb-0 small">Silakan cetak bukti pendaftaran dan lakukan daftar ulang sesuai jadwal yang ditentukan.</p>
                    </div>
                </div>
            </div>
            <?php elseif ($siswa['status'] == 'tidak_lulus'): ?>
            <div class="alert alert-danger border-danger shadow-sm mb-4" role="alert">
                <div class="d-flex align-items-center">
                    <i class="bi bi-x-circle-fill fs-1 me-3"></i>
                    <div>
                        <h4 class="alert-heading fw-bold mb-1">MOHON MAAF</h4>
                        <p class="mb-0">Mohon maaf, Anda dinyatakan <strong>TIDAK LULUS</strong> seleksi PPDB tahun ini.</p>
                        <p class="mb-0 small">Tetap semangat dan jangan putus asa!</p>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <div class="card bg-primary text-white">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="me-3">
                        <?php if ($foto_profil && file_exists(__DIR__ . '/../public/' . $foto_profil)): ?>
                            <img src="<?php echo BASE_URL . 'public/' . $foto_profil; ?>" alt="Foto Profil" class="rounded-circle" style="width: 80px; height: 80px; object-fit: cover; border: 3px solid white;">
                        <?php else: ?>
                            <i class="bi bi-person-circle" style="font-size: 4rem;"></i>
                        <?php endif; ?>
                    </div>
                    <div>
                        <h3>Selamat Datang, <?php echo escape_output($siswa['nama_lengkap']); ?>!</h3>
                        <p class="mb-0">Nomor Pendaftaran: <strong><?php echo escape_output($siswa['no_pendaftaran']); ?></strong></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Status Cards -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card h-100">
                <div class="card-body text-center">
                    <i class="bi bi-file-text text-primary" style="font-size: 3rem;"></i>
                    <h5 class="mt-3">Status Pendaftaran</h5>
                    <?php
                    $status_badge = [
                        'pending' => '<span class="badge bg-warning">Pending</span>',
                        'lulus' => '<span class="badge bg-success">Lulus</span>',
                        'tidak_lulus' => '<span class="badge bg-danger">Tidak Lulus</span>'
                    ];
                    echo $status_badge[$siswa['status']];
                    ?>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-3">
            <div class="card h-100">
                <div class="card-body text-center">
                    <i class="bi bi-folder text-info" style="font-size: 3rem;"></i>
                    <h5 class="mt-3">Status Berkas</h5>
                    <?php
                    $berkas_badge = [
                        'belum_lengkap' => '<span class="badge bg-secondary">Belum Lengkap</span>',
                        'lengkap' => '<span class="badge bg-info">Lengkap</span>',
                        'terverifikasi' => '<span class="badge bg-success">Terverifikasi</span>'
                    ];
                    echo $berkas_badge[$siswa['status_berkas']];
                    ?>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-3">
            <div class="card h-100">
                <div class="card-body text-center">
                    <i class="bi bi-upload text-success" style="font-size: 3rem;"></i>
                    <h5 class="mt-3">Berkas Terupload</h5>
                    <h3><?php echo count($berkas_uploaded); ?> / 4</h3>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Progress Checklist -->
    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-list-check"></i> Progress Pendaftaran</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <!-- Step 1: Registrasi -->
                        <div class="list-group-item d-flex align-items-center">
                            <i class="bi bi-check-circle-fill text-success fs-4 me-3"></i>
                            <div class="flex-grow-1">
                                <h6 class="mb-0">Registrasi Akun</h6>
                                <small class="text-muted">Akun berhasil dibuat</small>
                            </div>
                        </div>
                        
                        <!-- Step 2: Isi Formulir -->
                        <div class="list-group-item d-flex align-items-center">
                            <?php if ($siswa['tempat_lahir'] != '-'): ?>
                                <i class="bi bi-check-circle-fill text-success fs-4 me-3"></i>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0">Isi Formulir Pendaftaran</h6>
                                    <small class="text-muted">Data sudah dilengkapi</small>
                                </div>
                            <?php else: ?>
                                <i class="bi bi-circle text-secondary fs-4 me-3"></i>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0">Isi Formulir Pendaftaran</h6>
                                    <small class="text-muted">Belum dilengkapi</small>
                                </div>
                                <a href="<?php echo BASE_URL; ?>pendaftaran/form.php" class="btn btn-sm btn-primary">
                                    Isi Sekarang
                                </a>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Step 3: Upload Berkas -->
                        <div class="list-group-item d-flex align-items-center">
                            <?php if (count($berkas_uploaded) >= 4): ?>
                                <i class="bi bi-check-circle-fill text-success fs-4 me-3"></i>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0">Upload Berkas</h6>
                                    <small class="text-muted">Semua berkas sudah diupload</small>
                                </div>
                            <?php else: ?>
                                <i class="bi bi-circle text-secondary fs-4 me-3"></i>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0">Upload Berkas</h6>
                                    <small class="text-muted"><?php echo count($berkas_uploaded); ?> dari 4 berkas</small>
                                </div>
                                <a href="<?php echo BASE_URL; ?>pendaftaran/upload.php" class="btn btn-sm btn-primary">
                                    Upload
                                </a>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Step 4: Verifikasi -->
                        <div class="list-group-item d-flex align-items-center">
                            <?php if ($siswa['status_berkas'] == 'terverifikasi'): ?>
                                <i class="bi bi-check-circle-fill text-success fs-4 me-3"></i>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0">Verifikasi Berkas</h6>
                                    <small class="text-muted">Berkas sudah terverifikasi</small>
                                </div>
                            <?php else: ?>
                                <i class="bi bi-circle text-secondary fs-4 me-3"></i>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0">Verifikasi Berkas</h6>
                                    <small class="text-muted">Menunggu verifikasi admin</small>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Step 5: Pengumuman -->
                        <div class="list-group-item d-flex align-items-center">
                            <?php if ($siswa['status'] != 'pending'): ?>
                                <i class="bi bi-check-circle-fill text-success fs-4 me-3"></i>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0">Pengumuman Hasil</h6>
                                    <small class="text-muted">Hasil sudah diumumkan</small>
                                </div>
                            <?php else: ?>
                                <i class="bi bi-circle text-secondary fs-4 me-3"></i>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0">Pengumuman Hasil</h6>
                                    <small class="text-muted">Menunggu pengumuman</small>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-lightning-fill"></i> Menu Cepat</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="<?php echo BASE_URL; ?>pendaftaran/form.php" class="btn btn-primary">
                            <i class="bi bi-file-text"></i> Formulir Pendaftaran
                        </a>
                        <a href="<?php echo BASE_URL; ?>pendaftaran/upload.php" class="btn btn-info text-white">
                            <i class="bi bi-upload"></i> Upload Berkas
                        </a>
                        <?php if ($siswa['tempat_lahir'] != '-'): ?>
                        <a href="<?php echo BASE_URL; ?>pendaftaran/cetak_bukti.php" class="btn btn-success" target="_blank">
                            <i class="bi bi-printer"></i> Cetak Bukti
                        </a>
                        <?php endif; ?>
                        <a href="<?php echo BASE_URL; ?>siswa/cek_status.php" class="btn btn-warning">
                            <i class="bi bi-search"></i> Cek Status
                        </a>
                    </div>
                </div>
            </div>
            
            <?php if (!empty($siswa['catatan_admin'])): ?>
            <div class="card mt-3">
                <div class="card-header bg-warning">
                    <h6 class="mb-0"><i class="bi bi-exclamation-triangle"></i> Catatan Admin</h6>
                </div>
                <div class="card-body">
                    <p class="mb-0"><?php echo nl2br(escape_output($siswa['catatan_admin'])); ?></p>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
$extra_js = "
<script>
// Show success message if any
const urlParams = new URLSearchParams(window.location.search);
const success = urlParams.get('success');

if (success) {
    Swal.fire({
        icon: 'success',
        title: 'Berhasil',
        text: decodeURIComponent(success)
    });
}
</script>
";

require_once __DIR__ . '/../templates/footer.php';
?>
