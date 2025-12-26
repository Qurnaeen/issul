<?php
require_once __DIR__ . '/../../config/session_config.php';
session_start();
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../../helpers/auth_helper.php';
require_once __DIR__ . '/../../helpers/security.php';
require_once __DIR__ . '/../../helpers/tanggal_helper.php';

// Cek login admin
require_login_admin();

// Get siswa ID
if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$siswa_id = intval($_GET['id']);

// Get data siswa
$query = "SELECT s.*, a.email FROM siswa s 
          LEFT JOIN akun_siswa a ON s.id = a.siswa_id 
          WHERE s.id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $siswa_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$siswa = mysqli_fetch_assoc($result);

if (!$siswa) {
    header('Location: index.php?error=' . urlencode('Data tidak ditemukan'));
    exit;
}

// Get berkas
$query_berkas = "SELECT * FROM berkas WHERE siswa_id = ? ORDER BY jenis_berkas";
$stmt_berkas = mysqli_prepare($conn, $query_berkas);
mysqli_stmt_bind_param($stmt_berkas, "i", $siswa_id);
mysqli_stmt_execute($stmt_berkas);
$result_berkas = mysqli_stmt_get_result($stmt_berkas);

$page_title = 'Detail Pendaftar';
require_once __DIR__ . '/../../templates/header.php';
require_once __DIR__ . '/../../templates/navbar_admin.php';
?>

<h2 class="mb-4">Detail Pendaftar</h2>

<div class="row">
    <div class="col-lg-8 mb-4">
        <!-- Data Pribadi -->
        <div class="card mb-3">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-person"></i> Data Pribadi</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td width="200"><strong>No. Pendaftaran</strong></td>
                        <td><?php echo escape_output($siswa['no_pendaftaran']); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Nama Lengkap</strong></td>
                        <td><?php echo escape_output($siswa['nama_lengkap']); ?></td>
                    </tr>
                    <tr>
                        <td><strong>NISN</strong></td>
                        <td><?php echo escape_output($siswa['nisn']); ?></td>
                    </tr>
                    <tr>
                        <td><strong>NIK</strong></td>
                        <td><?php echo escape_output($siswa['nik']); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Jenis Kelamin</strong></td>
                        <td><?php echo $siswa['jk'] == 'L' ? 'Laki-laki' : 'Perempuan'; ?></td>
                    </tr>
                    <tr>
                        <td><strong>Tempat, Tanggal Lahir</strong></td>
                        <td><?php echo escape_output($siswa['tempat_lahir']) . ', ' . format_tanggal($siswa['tanggal_lahir']); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Umur</strong></td>
                        <td><?php echo hitung_umur($siswa['tanggal_lahir']); ?> tahun</td>
                    </tr>
                    <tr>
                        <td><strong>Agama</strong></td>
                        <td><?php echo escape_output($siswa['agama']); ?></td>
                    </tr>
                    <tr>
                        <td><strong>No. Telepon</strong></td>
                        <td><?php echo escape_output($siswa['no_telp']); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Email</strong></td>
                        <td><?php echo escape_output($siswa['email']); ?></td>
                    </tr>
                </table>
            </div>
        </div>
        
        <!-- Alamat -->
        <div class="card mb-3">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="bi bi-geo-alt"></i> Alamat</h5>
            </div>
            <div class="card-body">
                <p><?php echo nl2br(escape_output($siswa['alamat'])); ?></p>
                <p class="mb-0">
                    RT/RW: <?php echo escape_output($siswa['rt']) . '/' . escape_output($siswa['rw']); ?><br>
                    Kelurahan: <?php echo escape_output($siswa['kelurahan']); ?><br>
                    Kecamatan: <?php echo escape_output($siswa['kecamatan']); ?><br>
                    Kota: <?php echo escape_output($siswa['kota']); ?><br>
                    Provinsi: <?php echo escape_output($siswa['provinsi']); ?><br>
                    Kode Pos: <?php echo escape_output($siswa['kode_pos']); ?>
                </p>
            </div>
        </div>
        
        <!-- Data Orang Tua -->
        <div class="card mb-3">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="bi bi-people"></i> Data Orang Tua</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td width="200"><strong>Nama Ayah</strong></td>
                        <td><?php echo escape_output($siswa['nama_ayah']); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Pekerjaan Ayah</strong></td>
                        <td><?php echo escape_output($siswa['pekerjaan_ayah']); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Nama Ibu</strong></td>
                        <td><?php echo escape_output($siswa['nama_ibu']); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Pekerjaan Ibu</strong></td>
                        <td><?php echo escape_output($siswa['pekerjaan_ibu']); ?></td>
                    </tr>
                    <tr>
                        <td><strong>No. Telepon Orang Tua</strong></td>
                        <td><?php echo escape_output($siswa['no_telp_ortu']); ?></td>
                    </tr>
                </table>
            </div>
        </div>
        
        <!-- Berkas -->
        <div class="card">
            <div class="card-header bg-warning">
                <h5 class="mb-0"><i class="bi bi-folder"></i> Berkas Upload</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Jenis Berkas</th>
                                <th>Status</th>
                                <th>Catatan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($berkas = mysqli_fetch_assoc($result_berkas)): ?>
                            <tr>
                                <td><?php echo JENIS_BERKAS[$berkas['jenis_berkas']]; ?></td>
                                <td>
                                    <?php
                                    $badge = [
                                        'pending' => '<span class="badge bg-warning">Pending</span>',
                                        'valid' => '<span class="badge bg-success">Valid</span>',
                                        'invalid' => '<span class="badge bg-danger">Invalid</span>'
                                    ];
                                    echo $badge[$berkas['status']];
                                    ?>
                                </td>
                                <td><?php echo escape_output($berkas['catatan']); ?></td>
                                <td>
                                    <a href="<?php echo BASE_URL . 'public/' . $berkas['file_path']; ?>" 
                                       class="btn btn-sm btn-info text-white" target="_blank">
                                        <i class="bi bi-eye"></i> Lihat
                                    </a>
                                    <button class="btn btn-sm btn-success" 
                                            onclick="verifikasiBerkas(<?php echo $berkas['id']; ?>, 'valid')">
                                        <i class="bi bi-check"></i> Valid
                                    </button>
                                    <button class="btn btn-sm btn-danger" 
                                            onclick="verifikasiBerkas(<?php echo $berkas['id']; ?>, 'invalid')">
                                        <i class="bi bi-x"></i> Invalid
                                    </button>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Sidebar Actions -->
    <div class="col-lg-4">
        <div class="card mb-3">
            <div class="card-header bg-light">
                <h5 class="mb-0">Status</h5>
            </div>
            <div class="card-body">
                <p><strong>Status Berkas:</strong><br>
                <?php
                $berkas_badge = [
                    'belum_lengkap' => '<span class="badge bg-secondary">Belum Lengkap</span>',
                    'lengkap' => '<span class="badge bg-info">Lengkap</span>',
                    'terverifikasi' => '<span class="badge bg-success">Terverifikasi</span>'
                ];
                $berkas_key = !empty($siswa['status_berkas']) ? $siswa['status_berkas'] : 'belum_lengkap';
                echo isset($berkas_badge[$berkas_key]) ? $berkas_badge[$berkas_key] : '<span class="badge bg-secondary">' . escape_output($berkas_key) . '</span>';
                ?>
                </p>
                
                <p><strong>Status Seleksi:</strong><br>
                <?php
                $status_badge = [
                    'pending' => '<span class="badge bg-warning">Pending</span>',
                    'lulus' => '<span class="badge bg-success">Lulus</span>',
                    'tidak_lulus' => '<span class="badge bg-danger">Tidak Lulus</span>'
                ];
                $status_key = !empty($siswa['status']) ? $siswa['status'] : 'pending';
                echo isset($status_badge[$status_key]) ? $status_badge[$status_key] : '<span class="badge bg-secondary">' . escape_output($status_key) . '</span>';
                ?>
                </p>
                
                <p><strong>Tanggal Daftar:</strong><br>
                <?php echo format_datetime($siswa['created_at']); ?>
                </p>
            </div>
        </div>
        
        <div class="card mb-3">
            <div class="card-header bg-light">
                <h5 class="mb-0">Aksi</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <button class="btn btn-success" onclick="ubahStatus('lulus')">
                        <i class="bi bi-check-circle"></i> Tandai Lulus
                    </button>
                    <button class="btn btn-danger" onclick="ubahStatus('tidak_lulus')">
                        <i class="bi bi-x-circle"></i> Tandai Tidak Lulus
                    </button>
                    <button class="btn btn-warning" onclick="ubahStatus('pending')">
                        <i class="bi bi-clock"></i> Set Pending
                    </button>
                    <hr>
                    <a href="index.php" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
        
        <?php if (!empty($siswa['catatan_admin'])): ?>
        <div class="card">
            <div class="card-header bg-warning">
                <h5 class="mb-0">Catatan</h5>
            </div>
            <div class="card-body">
                <p><?php echo nl2br(escape_output($siswa['catatan_admin'])); ?></p>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php
$extra_js = "
<script>
function ubahStatus(status) {
    Swal.fire({
        title: 'Ubah Status',
        text: 'Ubah status menjadi ' + status.toUpperCase() + '?',
        icon: 'question',
        input: 'textarea',
        inputLabel: 'Catatan (opsional)',
        showCancelButton: true,
        confirmButtonText: 'Ya, Ubah',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'ubah_status.php?id=' + $siswa_id + '&status=' + status + '&catatan=' + encodeURIComponent(result.value || '');
        }
    });
}

function verifikasiBerkas(berkasId, status) {
    let catatan = '';
    if (status === 'invalid') {
        Swal.fire({
            title: 'Berkas Invalid',
            input: 'textarea',
            inputLabel: 'Alasan penolakan',
            inputPlaceholder: 'Masukkan alasan...',
            showCancelButton: true
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'verifikasi.php?id=' + berkasId + '&status=' + status + '&catatan=' + encodeURIComponent(result.value);
            }
        });
    } else {
        window.location.href = 'verifikasi.php?id=' + berkasId + '&status=' + status;
    }
}
</script>
";

require_once __DIR__ . '/../../templates/footer_admin.php';
?>
