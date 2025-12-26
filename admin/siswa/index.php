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

// Filter
$filter_status = isset($_GET['status']) ? clean_input($_GET['status']) : '';
$filter_berkas = isset($_GET['berkas']) ? clean_input($_GET['berkas']) : '';
$search = isset($_GET['search']) ? clean_input($_GET['search']) : '';

// Build query
$query = "SELECT s.*, a.email FROM siswa s 
          LEFT JOIN akun_siswa a ON s.id = a.siswa_id 
          WHERE 1=1";

if (!empty($filter_status)) {
    $query .= " AND s.status = '" . mysqli_real_escape_string($conn, $filter_status) . "'";
}

if (!empty($filter_berkas)) {
    $query .= " AND s.status_berkas = '" . mysqli_real_escape_string($conn, $filter_berkas) . "'";
}

if (!empty($search)) {
    $search_term = mysqli_real_escape_string($conn, $search);
    $query .= " AND (s.nama_lengkap LIKE '%$search_term%' OR s.no_pendaftaran LIKE '%$search_term%' OR s.nisn LIKE '%$search_term%')";
}

$query .= " ORDER BY s.created_at DESC";

$result = mysqli_query($conn, $query);

$page_title = 'Data Pendaftar';
$use_datatables = true;
require_once __DIR__ . '/../../templates/header.php';
require_once __DIR__ . '/../../templates/navbar_admin.php';
?>

<h2 class="mb-4">Data Pendaftar</h2>

<!-- Filter -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Status Seleksi</label>
                <select class="form-select" name="status">
                    <option value="">Semua</option>
                    <option value="pending" <?php echo ($filter_status == 'pending') ? 'selected' : ''; ?>>Pending</option>
                    <option value="lulus" <?php echo ($filter_status == 'lulus') ? 'selected' : ''; ?>>Lulus</option>
                    <option value="tidak_lulus" <?php echo ($filter_status == 'tidak_lulus') ? 'selected' : ''; ?>>Tidak Lulus</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Status Berkas</label>
                <select class="form-select" name="berkas">
                    <option value="">Semua</option>
                    <option value="belum_lengkap" <?php echo ($filter_berkas == 'belum_lengkap') ? 'selected' : ''; ?>>Belum Lengkap</option>
                    <option value="lengkap" <?php echo ($filter_berkas == 'lengkap') ? 'selected' : ''; ?>>Lengkap</option>
                    <option value="terverifikasi" <?php echo ($filter_berkas == 'terverifikasi') ? 'selected' : ''; ?>>Terverifikasi</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Cari</label>
                <input type="text" class="form-control" name="search" placeholder="Nama / No. Pendaftaran / NISN" value="<?php echo escape_output($search); ?>">
            </div>
            <div class="col-md-2">
                <label class="form-label">&nbsp;</label>
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-search"></i> Filter
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Table -->
<div class="card">
    <div class="card-header bg-light d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Daftar Pendaftar</h5>
        <a href="<?php echo BASE_URL; ?>admin/laporan/export_excel.php" class="btn btn-success btn-sm">
            <i class="bi bi-file-excel"></i> Export Excel
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover data-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>No. Pendaftaran</th>
                        <th>Nama Lengkap</th>
                        <th>NISN</th>
                        <th>Jenis Kelamin</th>
                        <th>Status Berkas</th>
                        <th>Status Seleksi</th>
                        <th>Tanggal Daftar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    while ($siswa = mysqli_fetch_assoc($result)): 
                    ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo escape_output($siswa['no_pendaftaran']); ?></td>
                        <td><?php echo escape_output($siswa['nama_lengkap']); ?></td>
                        <td><?php echo escape_output($siswa['nisn']); ?></td>
                        <td><?php echo $siswa['jk'] == 'L' ? 'Laki-laki' : 'Perempuan'; ?></td>
                        <td>
                            <?php
                            $berkas_badge = [
                                'belum_lengkap' => '<span class="badge bg-secondary">Belum Lengkap</span>',
                                'lengkap' => '<span class="badge bg-info">Lengkap</span>',
                                'terverifikasi' => '<span class="badge bg-success">Terverifikasi</span>'
                            ];
                            $berkas_key = !empty($siswa['status_berkas']) ? $siswa['status_berkas'] : 'belum_lengkap';
                            echo isset($berkas_badge[$berkas_key]) ? $berkas_badge[$berkas_key] : '<span class="badge bg-secondary">' . escape_output($berkas_key) . '</span>';
                            ?>
                        </td>
                        <td>
                            <?php
                            $status_badge = [
                                'pending' => '<span class="badge bg-warning">Pending</span>',
                                'lulus' => '<span class="badge bg-success">Lulus</span>',
                                'tidak_lulus' => '<span class="badge bg-danger">Tidak Lulus</span>'
                            ];
                            $status_key = !empty($siswa['status']) ? $siswa['status'] : 'pending';
                            echo isset($status_badge[$status_key]) ? $status_badge[$status_key] : '<span class="badge bg-secondary">' . escape_output($status_key) . '</span>';
                            ?>
                        </td>
                        <td><?php echo format_tanggal($siswa['created_at']); ?></td>
                        <td>
                            <a href="detail.php?id=<?php echo $siswa['id']; ?>" class="btn btn-sm btn-info text-white">
                                <i class="bi bi-eye"></i> Detail
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
require_once __DIR__ . '/../../templates/footer_admin.php';
?>
