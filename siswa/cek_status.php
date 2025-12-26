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

$page_title = 'Cek Status Pendaftaran';
require_once __DIR__ . '/../templates/header.php';
require_once __DIR__ . '/../templates/navbar.php';
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Header Card -->
            <div class="card shadow-lg border-0 mb-4">
                <div class="card-body p-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <div class="row align-items-center text-white">
                        <div class="col-md-8">
                            <h3 class="mb-2"><i class="bi bi-person-badge"></i> Status Pendaftaran PPDB</h3>
                            <p class="mb-1 opacity-75">Nomor Pendaftaran</p>
                            <h4 class="mb-0 fw-bold"><?php echo escape_output($siswa['no_pendaftaran']); ?></h4>
                        </div>
                        <div class="col-md-4 text-md-end mt-3 mt-md-0">
                            <div class="bg-white bg-opacity-25 rounded p-3">
                                <p class="mb-1 small opacity-75">Nama Lengkap</p>
                                <h5 class="mb-0"><?php echo escape_output($siswa['nama_lengkap']); ?></h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Progress Overview -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body p-4">
                    <h5 class="mb-4"><i class="bi bi-graph-up"></i> Progress Pendaftaran</h5>
                    
                    <?php
                    $progress = 0;
                    if ($siswa['created_at']) $progress += 25;
                    if ($siswa['tempat_lahir'] != '-') $progress += 25;
                    if ($siswa['status_berkas'] != 'belum_lengkap') $progress += 25;
                    if ($siswa['status'] != 'pending') $progress += 25;
                    
                    $progress_color = $progress < 50 ? 'bg-danger' : ($progress < 75 ? 'bg-warning' : ($progress < 100 ? 'bg-info' : 'bg-success'));
                    ?>
                    
                    <div class="progress" style="height: 30px;">
                        <div class="progress-bar <?php echo $progress_color; ?> progress-bar-striped progress-bar-animated" 
                             role="progressbar" style="width: <?php echo $progress; ?>%">
                            <strong><?php echo $progress; ?>% Selesai</strong>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Timeline -->
            <div class="row g-4">
                <!-- Step 1: Registrasi -->
                <div class="col-md-6">
                    <div class="card h-100 border-0 shadow-sm status-card completed">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-start">
                                <div class="status-icon bg-success text-white me-3">
                                    <i class="bi bi-check-circle-fill"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h5 class="mb-0">Registrasi Akun</h5>
                                        <span class="badge bg-success">Selesai</span>
                                    </div>
                                    <p class="text-muted small mb-2">
                                        <i class="bi bi-calendar3"></i> <?php echo format_datetime($siswa['created_at']); ?>
                                    </p>
                                    <p class="mb-0 text-success small">
                                        <i class="bi bi-check2"></i> Akun berhasil dibuat
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 2: Formulir -->
                <div class="col-md-6">
                    <div class="card h-100 border-0 shadow-sm status-card <?php echo ($siswa['tempat_lahir'] != '-') ? 'completed' : 'pending'; ?>">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-start">
                                <div class="status-icon <?php echo ($siswa['tempat_lahir'] != '-') ? 'bg-success' : 'bg-secondary'; ?> text-white me-3">
                                    <i class="bi bi-<?php echo ($siswa['tempat_lahir'] != '-') ? 'check-circle-fill' : 'circle'; ?>"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h5 class="mb-0">Pengisian Formulir</h5>
                                        <?php if ($siswa['tempat_lahir'] != '-'): ?>
                                            <span class="badge bg-success">Selesai</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Belum</span>
                                        <?php endif; ?>
                                    </div>
                                    <?php if ($siswa['tempat_lahir'] != '-'): ?>
                                        <p class="mb-0 text-success small">
                                            <i class="bi bi-check2"></i> Data pribadi sudah dilengkapi
                                        </p>
                                    <?php else: ?>
                                        <p class="mb-2 text-muted small">Lengkapi data pribadi Anda</p>
                                        <a href="<?php echo BASE_URL; ?>pendaftaran/form.php" class="btn btn-sm btn-primary">
                                            <i class="bi bi-pencil"></i> Isi Formulir
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 3: Upload Berkas -->
                <div class="col-md-6">
                    <div class="card h-100 border-0 shadow-sm status-card <?php echo ($siswa['status_berkas'] != 'belum_lengkap') ? 'completed' : 'pending'; ?>">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-start">
                                <?php
                                $berkas_icon_class = 'bg-secondary';
                                if ($siswa['status_berkas'] == 'lengkap') $berkas_icon_class = 'bg-info';
                                if ($siswa['status_berkas'] == 'terverifikasi') $berkas_icon_class = 'bg-success';
                                ?>
                                <div class="status-icon <?php echo $berkas_icon_class; ?> text-white me-3">
                                    <i class="bi bi-<?php echo ($siswa['status_berkas'] != 'belum_lengkap') ? 'check-circle-fill' : 'circle'; ?>"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h5 class="mb-0">Upload Berkas</h5>
                                        <?php
                                        $berkas_badge = [
                                            'belum_lengkap' => '<span class="badge bg-secondary">Belum Lengkap</span>',
                                            'lengkap' => '<span class="badge bg-info">Lengkap</span>',
                                            'terverifikasi' => '<span class="badge bg-success">Terverifikasi</span>'
                                        ];
                                        echo $berkas_badge[$siswa['status_berkas']];
                                        ?>
                                    </div>
                                    <?php if ($siswa['status_berkas'] == 'terverifikasi'): ?>
                                        <p class="mb-0 text-success small">
                                            <i class="bi bi-shield-check"></i> Semua berkas sudah diverifikasi
                                        </p>
                                    <?php elseif ($siswa['status_berkas'] == 'lengkap'): ?>
                                        <p class="mb-0 text-info small">
                                            <i class="bi bi-hourglass-split"></i> Menunggu verifikasi admin
                                        </p>
                                    <?php else: ?>
                                        <p class="mb-2 text-muted small">Upload dokumen persyaratan</p>
                                        <a href="<?php echo BASE_URL; ?>pendaftaran/upload.php" class="btn btn-sm btn-primary">
                                            <i class="bi bi-upload"></i> Upload Berkas
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 4: Hasil Seleksi -->
                <div class="col-md-6">
                    <div class="card h-100 border-0 shadow-sm status-card <?php echo ($siswa['status'] != 'pending') ? 'completed' : 'pending'; ?>">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-start">
                                <?php
                                $status_icon_class = 'bg-secondary';
                                if ($siswa['status'] == 'lulus') $status_icon_class = 'bg-success';
                                if ($siswa['status'] == 'tidak_lulus') $status_icon_class = 'bg-danger';
                                ?>
                                <div class="status-icon <?php echo $status_icon_class; ?> text-white me-3">
                                    <i class="bi bi-<?php echo ($siswa['status'] != 'pending') ? 'check-circle-fill' : 'hourglass-split'; ?>"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h5 class="mb-0">Hasil Seleksi</h5>
                                        <?php
                                        if ($siswa['status'] == 'lulus') {
                                            echo '<span class="badge bg-success">LULUS</span>';
                                        } elseif ($siswa['status'] == 'tidak_lulus') {
                                            echo '<span class="badge bg-danger">TIDAK LULUS</span>';
                                        } else {
                                            echo '<span class="badge bg-warning">Menunggu</span>';
                                        }
                                        ?>
                                    </div>
                                    <?php if ($siswa['status'] == 'lulus'): ?>
                                        <div class="alert alert-success mb-0 py-2">
                                            <i class="bi bi-trophy-fill"></i> <strong>Selamat!</strong> Anda dinyatakan LULUS seleksi PPDB.
                                        </div>
                                    <?php elseif ($siswa['status'] == 'tidak_lulus'): ?>
                                        <div class="alert alert-danger mb-0 py-2">
                                            <i class="bi bi-x-circle-fill"></i> Mohon maaf, Anda belum berhasil dalam seleksi kali ini.
                                        </div>
                                    <?php else: ?>
                                        <p class="mb-0 text-muted small">
                                            <i class="bi bi-clock-history"></i> Hasil akan diumumkan setelah verifikasi selesai
                                        </p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Catatan Admin -->
            <?php if (!empty($siswa['catatan_admin'])): ?>
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-body p-4">
                    <div class="alert alert-warning mb-0">
                        <h6 class="alert-heading">
                            <i class="bi bi-exclamation-triangle-fill"></i> Catatan dari Admin
                        </h6>
                        <hr>
                        <p class="mb-0"><?php echo nl2br(escape_output($siswa['catatan_admin'])); ?></p>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Action Buttons -->
            <div class="text-center mt-4">
                <a href="<?php echo BASE_URL; ?>siswa/dashboard_siswa.php" class="btn btn-lg btn-primary px-5">
                    <i class="bi bi-arrow-left-circle"></i> Kembali ke Dashboard
                </a>
            </div>
        </div>
    </div>
</div>

<?php
$extra_css = "
<style>
/* Status Card Styling */
.status-card {
    transition: all 0.3s ease;
    border-left: 4px solid #dee2e6 !important;
}

.status-card.completed {
    border-left-color: #198754 !important;
    background: linear-gradient(to right, #f0fdf4 0%, #ffffff 100%);
}

.status-card.pending {
    border-left-color: #6c757d !important;
}

.status-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1) !important;
}

/* Status Icon */
.status-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    flex-shrink: 0;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
}

/* Progress Bar Animation */
.progress {
    box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1);
}

/* Card Hover Effect */
.card {
    transition: all 0.3s ease;
}

/* Alert in Card */
.status-card .alert {
    border: none;
    font-size: 0.875rem;
}

/* Badge Styling */
.badge {
    font-size: 0.75rem;
    padding: 0.4em 0.8em;
}

/* Responsive */
@media (max-width: 768px) {
    .status-icon {
        width: 40px;
        height: 40px;
        font-size: 1.2rem;
    }
    
    .status-card h5 {
        font-size: 1rem;
    }
}
</style>
";

require_once __DIR__ . '/../templates/footer.php';
?>
