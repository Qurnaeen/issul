<?php
require_once __DIR__ . '/../config/session_config.php';
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../helpers/auth_helper.php';
require_once __DIR__ . '/../helpers/security.php';

// Redirect jika sudah login
if (cek_login_siswa()) {
    header('Location: ' . BASE_URL . 'siswa/dashboard_siswa.php');
    exit;
}

$page_title = 'Registrasi Akun';
require_once __DIR__ . '/../templates/header.php';
?>

<div class="min-vh-100 d-flex align-items-center bg-light py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow-lg border-0">
                    <div class="card-header bg-primary text-white text-center py-4">
                        <h4 class="mb-0"><i class="bi bi-person-plus-fill"></i> Registrasi Akun PPDB</h4>
                    </div>
                    <div class="card-body p-4">
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i> 
                            Buat akun terlebih dahulu untuk memulai pendaftaran PPDB
                        </div>
                        
                        <form method="POST" action="proses_registrasi.php" id="formRegistrasi">
                            <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                            
                            <div class="mb-3">
                                <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="nama_lengkap" required 
                                       placeholder="Nama lengkap sesuai akta kelahiran">
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" name="email" required 
                                       placeholder="email@example.com">
                                <small class="text-muted">Email akan digunakan untuk login</small>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Password <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control" name="password" id="password" 
                                           required minlength="6" placeholder="Minimal 6 karakter">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control" name="password_confirm" 
                                           id="password_confirm" required placeholder="Ulangi password">
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Nomor Telepon</label>
                                <input type="tel" class="form-control" name="no_telp" 
                                       placeholder="08xxxxxxxxxx">
                            </div>
                            
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="agree" required>
                                <label class="form-check-label" for="agree">
                                    Saya menyetujui syarat dan ketentuan pendaftaran PPDB
                                </label>
                            </div>
                            
                            <button type="submit" class="btn btn-primary w-100 py-2">
                                <i class="bi bi-check-circle"></i> Daftar Sekarang
                            </button>
                        </form>
                        
                        <hr class="my-4">
                        
                        <div class="text-center">
                            <p class="mb-0">Sudah punya akun? 
                                <a href="login.php" class="text-decoration-none">Login di sini</a>
                            </p>
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
// Validasi password match
document.getElementById('formRegistrasi').addEventListener('submit', function(e) {
    const password = document.getElementById('password').value;
    const confirm = document.getElementById('password_confirm').value;
    
    if (password !== confirm) {
        e.preventDefault();
        Swal.fire({
            icon: 'error',
            title: 'Password Tidak Cocok',
            text: 'Password dan konfirmasi password harus sama'
        });
    }
});

// Show alert if there's a message in URL
const urlParams = new URLSearchParams(window.location.search);
const error = urlParams.get('error');

if (error) {
    Swal.fire({
        icon: 'error',
        title: 'Registrasi Gagal',
        text: decodeURIComponent(error)
    });
}
</script>
";

require_once __DIR__ . '/../templates/footer.php';
?>
