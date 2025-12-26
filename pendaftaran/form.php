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

$page_title = 'Formulir Pendaftaran';
require_once __DIR__ . '/../templates/header.php';
require_once __DIR__ . '/../templates/navbar.php';
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="bi bi-file-text"></i> Formulir Pendaftaran PPDB</h4>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="simpan.php" id="formPendaftaran">
                        <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                        
                        <!-- Data Pribadi -->
                        <h5 class="mb-3"><i class="bi bi-person"></i> Data Pribadi</h5>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="nama_lengkap" required 
                                       value="<?php echo escape_output($siswa['nama_lengkap']); ?>">
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">NISN</label>
                                <input type="text" class="form-control" name="nisn" maxlength="10" 
                                       value="<?php echo escape_output($siswa['nisn']); ?>" 
                                       placeholder="10 digit">
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">NIK <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="nik" required maxlength="16" 
                                       value="<?php echo escape_output($siswa['nik']); ?>" 
                                       placeholder="16 digit">
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                                <select class="form-select" name="jk" required>
                                    <option value="">Pilih...</option>
                                    <option value="L" <?php echo ($siswa['jk'] == 'L') ? 'selected' : ''; ?>>Laki-laki</option>
                                    <option value="P" <?php echo ($siswa['jk'] == 'P') ? 'selected' : ''; ?>>Perempuan</option>
                                </select>
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Tempat Lahir <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="tempat_lahir" required 
                                       value="<?php echo escape_output($siswa['tempat_lahir']); ?>">
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="tanggal_lahir" required 
                                       value="<?php echo $siswa['tanggal_lahir']; ?>">
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Agama <span class="text-danger">*</span></label>
                                <select class="form-select" name="agama" required>
                                    <option value="">Pilih...</option>
                                    <option value="Islam" <?php echo ($siswa['agama'] == 'Islam') ? 'selected' : ''; ?>>Islam</option>
                                    <option value="Kristen" <?php echo ($siswa['agama'] == 'Kristen') ? 'selected' : ''; ?>>Kristen</option>
                                    <option value="Katolik" <?php echo ($siswa['agama'] == 'Katolik') ? 'selected' : ''; ?>>Katolik</option>
                                    <option value="Hindu" <?php echo ($siswa['agama'] == 'Hindu') ? 'selected' : ''; ?>>Hindu</option>
                                    <option value="Buddha" <?php echo ($siswa['agama'] == 'Buddha') ? 'selected' : ''; ?>>Buddha</option>
                                    <option value="Konghucu" <?php echo ($siswa['agama'] == 'Konghucu') ? 'selected' : ''; ?>>Konghucu</option>
                                </select>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nomor Telepon</label>
                                <input type="tel" class="form-control" name="no_telp" 
                                       value="<?php echo escape_output($siswa['no_telp']); ?>" 
                                       placeholder="08xxxxxxxxxx">
                            </div>
                        </div>
                        
                        <hr class="my-4">
                        
                        <!-- Alamat -->
                        <h5 class="mb-3"><i class="bi bi-geo-alt"></i> Alamat</h5>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Alamat <span class="text-danger">*</span></label>
                                <textarea class="form-control" name="alamat" rows="3" required placeholder="Masukkan alamat lengkap"><?php echo escape_output($siswa['alamat']); ?></textarea>
                            </div>
                        </div>
                        
                        <hr class="my-4">
                        
                        <!-- Data Orang Tua -->
                        <h5 class="mb-3"><i class="bi bi-people"></i> Data Orang Tua</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nama Ayah</label>
                                <input type="text" class="form-control" name="nama_ayah" 
                                       value="<?php echo escape_output($siswa['nama_ayah']); ?>">
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Pekerjaan Ayah</label>
                                <input type="text" class="form-control" name="pekerjaan_ayah" 
                                       value="<?php echo escape_output($siswa['pekerjaan_ayah']); ?>">
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nama Ibu <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="nama_ibu" required 
                                       value="<?php echo escape_output($siswa['nama_ibu']); ?>">
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Pekerjaan Ibu</label>
                                <input type="text" class="form-control" name="pekerjaan_ibu" 
                                       value="<?php echo escape_output($siswa['pekerjaan_ibu']); ?>">
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">No. Telepon Orang Tua</label>
                                <input type="tel" class="form-control" name="no_telp_ortu" 
                                       value="<?php echo escape_output($siswa['no_telp_ortu']); ?>" 
                                       placeholder="08xxxxxxxxxx">
                            </div>
                        </div>
                        
                        <hr class="my-4">
                        
                        <div class="d-flex justify-content-between">
                            <a href="<?php echo BASE_URL; ?>siswa/dashboard_siswa.php" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Simpan Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$extra_js = "
<script>
// Show success/error message
const urlParams = new URLSearchParams(window.location.search);
const error = urlParams.get('error');
const success = urlParams.get('success');

if (error) {
    Swal.fire({
        icon: 'error',
        title: 'Gagal',
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

// Form validation
document.getElementById('formPendaftaran').addEventListener('submit', function(e) {
    const nik = document.querySelector('[name=\"nik\"]').value;
    const nisn = document.querySelector('[name=\"nisn\"]').value;
    
    if (nik && nik.length !== 16) {
        e.preventDefault();
        Swal.fire({
            icon: 'error',
            title: 'NIK Tidak Valid',
            text: 'NIK harus 16 digit'
        });
        return;
    }
    
    if (nisn && nisn.length !== 10) {
        e.preventDefault();
        Swal.fire({
            icon: 'error',
            title: 'NISN Tidak Valid',
            text: 'NISN harus 10 digit'
        });
        return;
    }
});
</script>
";

require_once __DIR__ . '/../templates/footer.php';
?>
