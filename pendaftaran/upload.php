<?php
require_once __DIR__ . '/../config/session_config.php';
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../helpers/auth_helper.php';
require_once __DIR__ . '/../helpers/security.php';
require_once __DIR__ . '/../helpers/upload_helper.php';

// Cek login
require_login_siswa();

// Get data siswa
$siswa = get_siswa_data($conn);

// Get berkas yang sudah diupload
$query_berkas = "SELECT * FROM berkas WHERE siswa_id = ? ORDER BY jenis_berkas";
$stmt = mysqli_prepare($conn, $query_berkas);
mysqli_stmt_bind_param($stmt, "i", $siswa['id']);
mysqli_stmt_execute($stmt);
$result_berkas = mysqli_stmt_get_result($stmt);

$berkas_list = [];
while ($row = mysqli_fetch_assoc($result_berkas)) {
    $berkas_list[$row['jenis_berkas']] = $row;
}

$page_title = 'Upload Berkas';
require_once __DIR__ . '/../templates/header.php';
require_once __DIR__ . '/../templates/navbar.php';
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="bi bi-upload"></i> Upload Berkas Pendaftaran</h4>
                </div>
                <div class="card-body p-4">
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i> 
                        <strong>Ketentuan Upload:</strong>
                        <ul class="mb-0 mt-2">
                            <li>Format file: PDF, JPG, atau PNG</li>
                            <li>Ukuran maksimal: 2 MB per file</li>
                            <li>Pastikan file yang diupload jelas dan terbaca</li>
                        </ul>
                    </div>
                    
                    <?php foreach (JENIS_BERKAS as $jenis => $label): ?>
                    <div class="card mb-3 no-hover">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-4">
                                    <h6 class="mb-0">
                                        <i class="bi bi-file-earmark"></i> <?php echo $label; ?>
                                    </h6>
                                </div>
                                <div class="col-md-4">
                                    <?php if (isset($berkas_list[$jenis])): ?>
                                        <?php
                                        $berkas = $berkas_list[$jenis];
                                        $status_badge = [
                                            'pending' => '<span class="badge bg-warning">Menunggu Verifikasi</span>',
                                            'valid' => '<span class="badge bg-success">Valid</span>',
                                            'invalid' => '<span class="badge bg-danger">Ditolak</span>'
                                        ];
                                        echo $status_badge[$berkas['status']];
                                        ?>
                                        <br>
                                        <small class="text-muted"><?php echo $berkas['nama_file']; ?></small>
                                        <?php if (!empty($berkas['catatan'])): ?>
                                        <br><small class="text-danger"><?php echo escape_output($berkas['catatan']); ?></small>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Belum Upload</span>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-4 text-end">
                                    <?php if (isset($berkas_list[$jenis])): ?>
                                        <a href="<?php echo BASE_URL . 'public/' . $berkas_list[$jenis]['file_path']; ?>" 
                                           class="btn btn-sm btn-info text-white" target="_blank">
                                            <i class="bi bi-eye"></i> Lihat
                                        </a>
                                    <?php endif; ?>
                                    <button type="button" class="btn btn-sm btn-primary" 
                                            data-bs-toggle="modal" data-bs-target="#modal<?php echo $jenis; ?>">
                                        <i class="bi bi-upload"></i> <?php echo isset($berkas_list[$jenis]) ? 'Ganti' : 'Upload'; ?>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Modal Upload -->
                    <div class="modal fade" id="modal<?php echo $jenis; ?>" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Upload <?php echo $label; ?></h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form method="POST" action="proses_upload.php" enctype="multipart/form-data">
                                    <div class="modal-body">
                                        <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                                        <input type="hidden" name="jenis_berkas" value="<?php echo $jenis; ?>">
                                        
                                        <div class="mb-3">
                                            <label class="form-label">Pilih File</label>
                                            <input type="file" class="form-control" name="file" required 
                                                   accept=".pdf,.jpg,.jpeg,.png"
                                                   onchange="previewFile(this, 'preview<?php echo $jenis; ?>')">
                                        </div>
                                        
                                        <div id="preview<?php echo $jenis; ?>" class="text-center mt-3"></div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary modal-btn" data-bs-dismiss="modal">
                                            <i class="bi bi-x-circle"></i> Batal
                                        </button>
                                        <button type="submit" class="btn btn-primary modal-btn">
                                            <i class="bi bi-upload"></i> Upload
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    
                    <hr class="my-4">
                    
                    <div class="d-flex justify-content-between">
                        <a href="<?php echo BASE_URL; ?>siswa/dashboard_siswa.php" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                        <?php if (count($berkas_list) >= 4): ?>
                        <button type="button" class="btn btn-success" onclick="updateStatusBerkas()">
                            <i class="bi bi-check-circle"></i> Tandai Berkas Lengkap
                        </button>
                        <?php endif; ?>
                    </div>
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

function updateStatusBerkas() {
    Swal.fire({
        title: 'Konfirmasi',
        text: 'Tandai berkas sebagai lengkap?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Ya',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'update_status_berkas.php';
        }
    });
}
</script>
";

require_once __DIR__ . '/../templates/footer.php';
?>
