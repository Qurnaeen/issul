<?php
require_once __DIR__ . '/config/session_config.php';
session_start();
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/helpers/auth_helper.php';
require_once __DIR__ . '/helpers/security.php';

$page_title = 'Beranda';
require_once __DIR__ . '/templates/header.php';
require_once __DIR__ . '/templates/navbar.php';
?>

<!-- Hero Section -->
<div class="bg-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-3">Penerimaan Peserta Didik Baru</h1>
                <h3 class="mb-4"><?php echo NAMA_SEKOLAH; ?></h3>
                <p class="lead mb-4">Tahun Ajaran <?php echo TAHUN_AJARAN; ?></p>
                <div class="d-flex gap-3">
                    <?php if (!cek_login_siswa()): ?>
                    <a href="auth/registrasi.php" class="btn btn-light btn-lg">
                        <i class="bi bi-person-plus"></i> Daftar Sekarang
                    </a>
                    <a href="auth/login.php" class="btn btn-outline-light btn-lg">
                        <i class="bi bi-box-arrow-in-right"></i> Login
                    </a>
                    <?php else: ?>
                    <a href="siswa/dashboard_siswa.php" class="btn btn-light btn-lg">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-lg-6 text-center">
                <i class="bi bi-mortarboard-fill" style="font-size: 15rem; opacity: 0.2;"></i>
            </div>
        </div>
    </div>
</div>

<!-- Info Section -->
<div class="container my-5">
    <div class="row text-center mb-5">
        <div class="col-12">
            <h2 class="fw-bold mb-3">Mengapa Memilih Kami?</h2>
            <p class="text-muted">Bergabunglah dengan sekolah terbaik untuk masa depan cerah</p>
        </div>
    </div>
    
    <div class="row g-4">
        <div class="col-md-4">
            <div class="card h-100 text-center border-0 shadow">
                <div class="card-body p-4">
                    <div class="mb-3">
                        <i class="bi bi-award text-primary" style="font-size: 4rem;"></i>
                    </div>
                    <h5 class="fw-bold">Akreditasi A</h5>
                    <p class="text-muted">Sekolah dengan standar pendidikan terbaik dan terakreditasi A</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card h-100 text-center border-0 shadow">
                <div class="card-body p-4">
                    <div class="mb-3">
                        <i class="bi bi-people text-success" style="font-size: 4rem;"></i>
                    </div>
                    <h5 class="fw-bold">Tenaga Pengajar Profesional</h5>
                    <p class="text-muted">Guru-guru berpengalaman dan berkompeten di bidangnya</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card h-100 text-center border-0 shadow">
                <div class="card-body p-4">
                    <div class="mb-3">
                        <i class="bi bi-building text-info" style="font-size: 4rem;"></i>
                    </div>
                    <h5 class="fw-bold">Fasilitas Lengkap</h5>
                    <p class="text-muted">Ruang kelas modern, laboratorium, dan fasilitas olahraga</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Timeline Section -->
<div class="bg-light py-5">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="fw-bold mb-3">Alur Pendaftaran</h2>
                <p class="text-muted">Ikuti langkah-langkah berikut untuk mendaftar</p>
            </div>
        </div>
        
        <div class="row g-4">
            <div class="col-md-3">
                <div class="card h-100 border-primary">
                    <div class="card-body text-center">
                        <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                             style="width: 60px; height: 60px;">
                            <h3 class="mb-0">1</h3>
                        </div>
                        <h5 class="fw-bold">Registrasi</h5>
                        <p class="text-muted small">Buat akun dengan email aktif</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="card h-100 border-primary">
                    <div class="card-body text-center">
                        <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                             style="width: 60px; height: 60px;">
                            <h3 class="mb-0">2</h3>
                        </div>
                        <h5 class="fw-bold">Isi Formulir</h5>
                        <p class="text-muted small">Lengkapi data pribadi dan orang tua</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="card h-100 border-primary">
                    <div class="card-body text-center">
                        <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                             style="width: 60px; height: 60px;">
                            <h3 class="mb-0">3</h3>
                        </div>
                        <h5 class="fw-bold">Upload Berkas</h5>
                        <p class="text-muted small">Upload dokumen persyaratan</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="card h-100 border-primary">
                    <div class="card-body text-center">
                        <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                             style="width: 60px; height: 60px;">
                            <h3 class="mb-0">4</h3>
                        </div>
                        <h5 class="fw-bold">Pengumuman</h5>
                        <p class="text-muted small">Cek hasil seleksi</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Requirements Section -->
<div class="container my-5">
    <div class="row">
        <div class="col-lg-6 mb-4">
            <h3 class="fw-bold mb-4">Persyaratan Pendaftaran</h3>
            <ul class="list-group">
                <li class="list-group-item"><i class="bi bi-check-circle text-success me-2"></i> Akta Kelahiran</li>
                <li class="list-group-item"><i class="bi bi-check-circle text-success me-2"></i> Kartu Keluarga</li>
                <li class="list-group-item"><i class="bi bi-check-circle text-success me-2"></i> Ijazah / Surat Keterangan Lulus</li>
                <li class="list-group-item"><i class="bi bi-check-circle text-success me-2"></i> Pas Foto 3x4 (2 lembar)</li>
            </ul>
        </div>
        
        <div class="col-lg-6 mb-4">
            <h3 class="fw-bold mb-4">Kontak Kami</h3>
            <div class="card border-0 shadow">
                <div class="card-body">
                    <p class="mb-2"><i class="bi bi-geo-alt text-primary me-2"></i> <?php echo ALAMAT_SEKOLAH; ?></p>
                    <p class="mb-2"><i class="bi bi-telephone text-primary me-2"></i> <?php echo TELP_SEKOLAH; ?></p>
                    <p class="mb-0"><i class="bi bi-envelope text-primary me-2"></i> <?php echo EMAIL_SEKOLAH; ?></p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CTA Section -->
<div class="bg-primary text-white py-5">
    <div class="container text-center">
        <h2 class="fw-bold mb-3">Siap Bergabung?</h2>
        <p class="lead mb-4">Daftarkan diri Anda sekarang dan raih masa depan cerah bersama kami!</p>
        <?php if (!cek_login_siswa()): ?>
        <a href="auth/registrasi.php" class="btn btn-light btn-lg">
            <i class="bi bi-person-plus"></i> Daftar Sekarang
        </a>
        <?php else: ?>
        <a href="siswa/dashboard_siswa.php" class="btn btn-light btn-lg">
            <i class="bi bi-speedometer2"></i> Ke Dashboard
        </a>
        <?php endif; ?>
    </div>
</div>

<?php
require_once __DIR__ . '/templates/footer.php';
?>
