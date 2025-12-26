<?php
require_once __DIR__ . '/../config/session_config.php';
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../helpers/auth_helper.php';
require_once __DIR__ . '/../helpers/security.php';

// Redirect jika sudah login
if (cek_login_admin()) {
    header('Location: ' . BASE_URL . 'admin/dashboard.php');
    exit;
}
if (cek_login_siswa()) {
    header('Location: ' . BASE_URL . 'siswa/dashboard_siswa.php');
    exit;
}

$page_title = 'Login';
require_once __DIR__ . '/../templates/header.php';
?>

<div class="min-vh-100 d-flex align-items-center bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10 col-lg-8">
                <div class="card shadow-lg border-0">
                    <div class="card-body p-0">
                        <div class="row g-0">
                            <!-- Left Side - Branding -->
                            <div class="col-md-5 bg-primary text-white p-5 d-flex flex-column justify-content-center">
                                <div class="text-center">
                                    <img src="<?php echo BASE_URL; ?>public/images/logo_sekolah.png" alt="Logo Sekolah" style="width: 150px; height: auto;" class="mb-2">
                                    <h3 class="mt-3 fw-bold">PPDB Online</h3>
                                    <h5><?php echo NAMA_SEKOLAH; ?></h5>
                                    <p class="mt-3">Tahun Ajaran <?php echo TAHUN_AJARAN; ?></p>
                                </div>
                            </div>
                            
                            <!-- Right Side - Login Form -->
                            <div class="col-md-7 p-5">
                                <h4 class="mb-4 fw-bold">Selamat Datang</h4>
                                
                                <!-- Tab Navigation -->
                                <ul class="nav nav-pills mb-4" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="siswa-tab" data-bs-toggle="tab" 
                                                data-bs-target="#siswa" type="button">
                                            <i class="bi bi-person"></i> Siswa
                                        </button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="admin-tab" data-bs-toggle="tab" 
                                                data-bs-target="#admin" type="button">
                                            <i class="bi bi-shield-lock"></i> Admin
                                        </button>
                                    </li>
                                </ul>
                                
                                <!-- Tab Content -->
                                <div class="tab-content">
                                    <!-- Login Siswa -->
                                    <div class="tab-pane fade show active" id="siswa">
                                        <form method="POST" action="proses_login.php" id="formLoginSiswa">
                                            <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                                            <input type="hidden" name="user_type" value="siswa">
                                            
                                            <div class="mb-3">
                                                <label class="form-label">Email</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                                    <input type="email" class="form-control" name="email" required 
                                                           placeholder="email@example.com">
                                                </div>
                                            </div>
                                            
                                            <div class="mb-3">
                                                <label class="form-label">Password</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                                    <input type="password" class="form-control" id="siswaPassword" name="password" required 
                                                           placeholder="Masukkan password">
                                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('siswaPassword', this)">
                                                        <i class="bi bi-eye"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            
                                            <button type="submit" class="btn btn-primary w-100 mb-3">
                                                <i class="bi bi-box-arrow-in-right"></i> Login
                                            </button>
                                            
                                            <div class="text-center">
                                                <p class="mb-0">Belum punya akun? 
                                                    <a href="registrasi.php" class="text-decoration-none">Daftar Sekarang</a>
                                                </p>
                                            </div>
                                        </form>
                                    </div>
                                    
                                    <!-- Login Admin -->
                                    <div class="tab-pane fade" id="admin">
                                        <form method="POST" action="proses_login.php" id="formLoginAdmin">
                                            <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                                            <input type="hidden" name="user_type" value="admin">
                                            
                                            <div class="mb-3">
                                                <label class="form-label">Username</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                                                    <input type="text" class="form-control" name="username" required 
                                                           placeholder="Username admin">
                                                </div>
                                            </div>
                                            
                                            <div class="mb-3">
                                                <label class="form-label">Password</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                                    <input type="password" class="form-control" id="adminPassword" name="password" required 
                                                           placeholder="Password admin">
                                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('adminPassword', this)">
                                                        <i class="bi bi-eye"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            
                                            <button type="submit" class="btn btn-primary w-100">
                                                <i class="bi bi-shield-check"></i> Login Admin
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$extra_js = "
<script>
// Toggle password visibility
function togglePassword(inputId, button) {
    const input = document.getElementById(inputId);
    const icon = button.querySelector('i');
    
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('bi-eye');
        icon.classList.add('bi-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('bi-eye-slash');
        icon.classList.add('bi-eye');
    }
}

// Show alert if there's a message in URL
const urlParams = new URLSearchParams(window.location.search);
const error = urlParams.get('error');
const success = urlParams.get('success');

if (error) {
    Swal.fire({
        icon: 'error',
        title: 'Login Gagal',
        text: decodeURIComponent(error)
    });
}

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
