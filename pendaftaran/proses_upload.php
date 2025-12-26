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

// Cek CSRF token
if (!isset($_POST['csrf_token']) || !verify_csrf_token($_POST['csrf_token'])) {
    header('Location: upload.php?error=' . urlencode('Invalid request'));
    exit;
}

// Validasi input
if (!isset($_POST['jenis_berkas']) || !isset($_FILES['file'])) {
    header('Location: upload.php?error=' . urlencode('Data tidak lengkap'));
    exit;
}

$siswa_id = $_SESSION['siswa_id'];
$jenis_berkas = clean_input($_POST['jenis_berkas']);
$file = $_FILES['file'];

// Validasi jenis berkas
if (!array_key_exists($jenis_berkas, JENIS_BERKAS)) {
    header('Location: upload.php?error=' . urlencode('Jenis berkas tidak valid'));
    exit;
}

// Upload file
$upload_result = upload_berkas($file, $jenis_berkas, $siswa_id);

if (!$upload_result['success']) {
    header('Location: upload.php?error=' . urlencode($upload_result['message']));
    exit;
}

// Cek apakah berkas sudah ada
$check_query = "SELECT id FROM berkas WHERE siswa_id = ? AND jenis_berkas = ?";
$check_stmt = mysqli_prepare($conn, $check_query);
mysqli_stmt_bind_param($check_stmt, "is", $siswa_id, $jenis_berkas);
mysqli_stmt_execute($check_stmt);
$check_result = mysqli_stmt_get_result($check_stmt);

if (mysqli_num_rows($check_result) > 0) {
    // Update berkas existing
    $berkas = mysqli_fetch_assoc($check_result);
    
    // Hapus file lama
    delete_file($berkas['file_path']);
    
    // Update database
    $update_query = "UPDATE berkas SET 
                     nama_file = ?, file_path = ?, file_size = ?, status = 'pending', 
                     catatan = NULL, verified_by = NULL, verified_at = NULL 
                     WHERE id = ?";
    $update_stmt = mysqli_prepare($conn, $update_query);
    mysqli_stmt_bind_param($update_stmt, "ssii", 
        $file['name'], $upload_result['file_path'], $upload_result['file_size'], $berkas['id']);
    
    if (mysqli_stmt_execute($update_stmt)) {
        header('Location: upload.php?success=' . urlencode('Berkas berhasil diupdate'));
        exit;
    } else {
        header('Location: upload.php?error=' . urlencode('Gagal update berkas'));
        exit;
    }
} else {
    // Insert berkas baru
    $insert_query = "INSERT INTO berkas (siswa_id, jenis_berkas, nama_file, file_path, file_size) 
                     VALUES (?, ?, ?, ?, ?)";
    $insert_stmt = mysqli_prepare($conn, $insert_query);
    mysqli_stmt_bind_param($insert_stmt, "isssi", 
        $siswa_id, $jenis_berkas, $file['name'], $upload_result['file_path'], $upload_result['file_size']);
    
    if (mysqli_stmt_execute($insert_stmt)) {
        header('Location: upload.php?success=' . urlencode('Berkas berhasil diupload'));
        exit;
    } else {
        header('Location: upload.php?error=' . urlencode('Gagal menyimpan berkas'));
        exit;
    }
}
?>
