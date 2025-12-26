<?php
require_once __DIR__ . '/../config/session_config.php';
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../helpers/auth_helper.php';
require_once __DIR__ . '/../helpers/security.php';

// Cek CSRF token
if (!isset($_POST['csrf_token']) || !verify_csrf_token($_POST['csrf_token'])) {
    header('Location: registrasi.php?error=' . urlencode('Invalid request'));
    exit;
}

// Validasi input
$nama_lengkap = clean_input($_POST['nama_lengkap']);
$email = clean_input($_POST['email']);
$password = $_POST['password'];
$password_confirm = $_POST['password_confirm'];
$no_telp = isset($_POST['no_telp']) ? clean_input($_POST['no_telp']) : '';

// Validasi
$errors = [];

if (empty($nama_lengkap)) {
    $errors[] = 'Nama lengkap harus diisi';
}

if (empty($email)) {
    $errors[] = 'Email harus diisi';
} elseif (!validate_email($email)) {
    $errors[] = 'Format email tidak valid';
}

if (empty($password)) {
    $errors[] = 'Password harus diisi';
} elseif (strlen($password) < 6) {
    $errors[] = 'Password minimal 6 karakter';
}

if ($password !== $password_confirm) {
    $errors[] = 'Password dan konfirmasi password tidak cocok';
}

// Cek email sudah terdaftar
$check_query = "SELECT id FROM akun_siswa WHERE email = ?";
$check_stmt = mysqli_prepare($conn, $check_query);
mysqli_stmt_bind_param($check_stmt, "s", $email);
mysqli_stmt_execute($check_stmt);
$check_result = mysqli_stmt_get_result($check_stmt);

if (mysqli_num_rows($check_result) > 0) {
    $errors[] = 'Email sudah terdaftar';
}

// Jika ada error, redirect kembali
if (!empty($errors)) {
    $error_message = implode(', ', $errors);
    header('Location: registrasi.php?error=' . urlencode($error_message));
    exit;
}

// Mulai transaksi
mysqli_begin_transaction($conn);

try {
    // Generate nomor pendaftaran
    $no_pendaftaran = generate_no_pendaftaran($conn);
    
    // Insert data siswa
    $insert_siswa = "INSERT INTO siswa (no_pendaftaran, nama_lengkap, no_telp, jk, tempat_lahir, tanggal_lahir, alamat, nama_ibu) 
                     VALUES (?, ?, ?, 'L', '-', '2000-01-01', '-', '-')";
    $stmt_siswa = mysqli_prepare($conn, $insert_siswa);
    mysqli_stmt_bind_param($stmt_siswa, "sss", $no_pendaftaran, $nama_lengkap, $no_telp);
    
    if (!mysqli_stmt_execute($stmt_siswa)) {
        throw new Exception('Gagal menyimpan data siswa');
    }
    
    $siswa_id = mysqli_insert_id($conn);
    
    // Hash password
    $hashed_password = hash_password($password);
    
    // Insert akun siswa
    $insert_akun = "INSERT INTO akun_siswa (siswa_id, email, password) VALUES (?, ?, ?)";
    $stmt_akun = mysqli_prepare($conn, $insert_akun);
    mysqli_stmt_bind_param($stmt_akun, "iss", $siswa_id, $email, $hashed_password);
    
    if (!mysqli_stmt_execute($stmt_akun)) {
        throw new Exception('Gagal membuat akun');
    }
    
    // Commit transaksi
    mysqli_commit($conn);
    
    // Auto login
    $_SESSION['siswa_id'] = $siswa_id;
    $_SESSION['akun_siswa_id'] = mysqli_insert_id($conn);
    $_SESSION['siswa_nama'] = $nama_lengkap;
    
    // Redirect ke dashboard dengan pesan sukses
    header('Location: ' . BASE_URL . 'siswa/dashboard_siswa.php?success=' . urlencode('Registrasi berhasil! Silakan lengkapi data pendaftaran'));
    exit;
    
} catch (Exception $e) {
    // Rollback jika ada error
    mysqli_rollback($conn);
    header('Location: registrasi.php?error=' . urlencode($e->getMessage()));
    exit;
}
?>
