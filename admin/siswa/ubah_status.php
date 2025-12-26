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

$siswa_id = intval($_GET['id']);
$status = strtolower(clean_input($_GET['status']));
$catatan = isset($_GET['catatan']) ? clean_input($_GET['catatan']) : '';

// Validasi status
if (!in_array($status, ['pending', 'lulus', 'tidak_lulus'])) {
    header('Location: detail.php?id=' . $siswa_id . '&error=' . urlencode('Status tidak valid'));
    exit;
}

// Update status
$query = "UPDATE siswa SET status = ?, catatan_admin = ? WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "ssi", $status, $catatan, $siswa_id);

// DEBUG LOGGING
$log_msg = date('Y-m-d H:i:s') . " - ID: $siswa_id, Status: $status, Catatan: $catatan\n";
file_put_contents(__DIR__ . '/debug_log.txt', $log_msg, FILE_APPEND);

if (mysqli_stmt_execute($stmt)) {
    // Log aktivitas
    log_aktivitas($conn, $_SESSION['admin_id'], 'Ubah Status Siswa', "Mengubah status siswa ID $siswa_id menjadi $status");
    
    // DEBUG SUCCESS
    file_put_contents(__DIR__ . '/debug_log.txt', "SUCCESS UPDATING\n", FILE_APPEND);

    header('Location: detail.php?id=' . $siswa_id . '&success=' . urlencode('Status berhasil diubah'));
    exit;
} else {
    // DEBUG ERROR
    file_put_contents(__DIR__ . '/debug_log.txt', "ERROR: " . mysqli_error($conn) . "\n", FILE_APPEND);

    header('Location: detail.php?id=' . $siswa_id . '&error=' . urlencode('Gagal mengubah status'));
    exit;
}
?>
