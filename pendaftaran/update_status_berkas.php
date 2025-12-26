<?php
require_once __DIR__ . '/../config/session_config.php';
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../helpers/auth_helper.php';

// Cek login
require_login_siswa();

$siswa_id = $_SESSION['siswa_id'];

// Hitung jumlah berkas yang sudah diupload
$count_query = "SELECT COUNT(*) as total FROM berkas WHERE siswa_id = ?";
$count_stmt = mysqli_prepare($conn, $count_query);
mysqli_stmt_bind_param($count_stmt, "i", $siswa_id);
mysqli_stmt_execute($count_stmt);
$count_result = mysqli_stmt_get_result($count_stmt);
$count_row = mysqli_fetch_assoc($count_result);

if ($count_row['total'] >= 4) {
    // Update status berkas menjadi lengkap
    $update_query = "UPDATE siswa SET status_berkas = 'lengkap' WHERE id = ?";
    $update_stmt = mysqli_prepare($conn, $update_query);
    mysqli_stmt_bind_param($update_stmt, "i", $siswa_id);
    
    if (mysqli_stmt_execute($update_stmt)) {
        header('Location: upload.php?success=' . urlencode('Status berkas berhasil diupdate menjadi lengkap'));
        exit;
    }
}

header('Location: upload.php?error=' . urlencode('Berkas belum lengkap'));
exit;
?>
