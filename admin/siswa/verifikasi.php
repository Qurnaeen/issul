<?php
require_once __DIR__ . '/../../config/session_config.php';
session_start();
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../../helpers/auth_helper.php';
require_once __DIR__ . '/../../helpers/security.php';

// Cek login admin
require_login_admin();

// Validasi input
if (!isset($_GET['id']) || !isset($_GET['status'])) {
    header('Location: index.php?error=' . urlencode('Parameter tidak lengkap'));
    exit;
}

$berkas_id = intval($_GET['id']);
$status = clean_input($_GET['status']);
$catatan = isset($_GET['catatan']) ? clean_input($_GET['catatan']) : '';

// Validasi status
if (!in_array($status, ['pending', 'valid', 'invalid'])) {
    header('Location: index.php?error=' . urlencode('Status tidak valid'));
    exit;
}

// Get siswa_id dari berkas
$query_siswa = "SELECT siswa_id FROM berkas WHERE id = ?";
$stmt_siswa = mysqli_prepare($conn, $query_siswa);
mysqli_stmt_bind_param($stmt_siswa, "i", $berkas_id);
mysqli_stmt_execute($stmt_siswa);
$result_siswa = mysqli_stmt_get_result($stmt_siswa);
$berkas_data = mysqli_fetch_assoc($result_siswa);

if (!$berkas_data) {
    header('Location: index.php?error=' . urlencode('Berkas tidak ditemukan'));
    exit;
}

$siswa_id = $berkas_data['siswa_id'];

// Update status berkas
$admin_id = $_SESSION['admin_id'];
$query = "UPDATE berkas SET status = ?, catatan = ?, verified_by = ?, verified_at = NOW() WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "ssii", $status, $catatan, $admin_id, $berkas_id);

if (mysqli_stmt_execute($stmt)) {
    // Cek apakah semua berkas sudah valid
    $check_query = "SELECT COUNT(*) as total, 
                    SUM(CASE WHEN status = 'valid' THEN 1 ELSE 0 END) as valid_count 
                    FROM berkas WHERE siswa_id = ?";
    $check_stmt = mysqli_prepare($conn, $check_query);
    mysqli_stmt_bind_param($check_stmt, "i", $siswa_id);
    mysqli_stmt_execute($check_stmt);
    $check_result = mysqli_stmt_get_result($check_stmt);
    $check_data = mysqli_fetch_assoc($check_result);
    
    // Jika semua berkas valid, update status_berkas siswa
    if ($check_data['total'] >= 4 && $check_data['valid_count'] >= 4) {
        $update_siswa = "UPDATE siswa SET status_berkas = 'terverifikasi' WHERE id = ?";
        $update_stmt = mysqli_prepare($conn, $update_siswa);
        mysqli_stmt_bind_param($update_stmt, "i", $siswa_id);
        mysqli_stmt_execute($update_stmt);
    }
    
    // Log aktivitas
    log_aktivitas($conn, $admin_id, 'Verifikasi Berkas', "Verifikasi berkas ID $berkas_id dengan status $status");
    
    header('Location: detail.php?id=' . $siswa_id . '&success=' . urlencode('Berkas berhasil diverifikasi'));
    exit;
} else {
    header('Location: detail.php?id=' . $siswa_id . '&error=' . urlencode('Gagal verifikasi berkas'));
    exit;
}
?>
