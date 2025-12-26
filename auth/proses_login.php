<?php
require_once __DIR__ . '/../config/session_config.php';
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../helpers/auth_helper.php';
require_once __DIR__ . '/../helpers/security.php';

// Cek CSRF token
if (!isset($_POST['csrf_token']) || !verify_csrf_token($_POST['csrf_token'])) {
    header('Location: login.php?error=' . urlencode('Invalid request'));
    exit;
}

// Validasi input
if (!isset($_POST['user_type']) || !isset($_POST['password'])) {
    header('Location: login.php?error=' . urlencode('Data tidak lengkap'));
    exit;
}

$user_type = clean_input($_POST['user_type']);
$password = $_POST['password'];

if ($user_type == 'admin') {
    // Login Admin
    if (!isset($_POST['username'])) {
        header('Location: login.php?error=' . urlencode('Username harus diisi'));
        exit;
    }
    
    $username = clean_input($_POST['username']);
    
    // Query admin
    $query = "SELECT * FROM users_admin WHERE username = ? AND is_active = 1";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($admin = mysqli_fetch_assoc($result)) {
        // Verify password
        if (verify_password($password, $admin['password'])) {
            // Set session
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_nama'] = $admin['nama'];
            $_SESSION['admin_role'] = $admin['role'];
            
            // Log aktivitas
            log_aktivitas($conn, $admin['id'], 'Login', 'Login berhasil');
            
            // Redirect ke dashboard
            header('Location: ' . BASE_URL . 'admin/dashboard.php');
            exit;
        } else {
            header('Location: login.php?error=' . urlencode('Password salah'));
            exit;
        }
    } else {
        header('Location: login.php?error=' . urlencode('Username tidak ditemukan'));
        exit;
    }
    
} elseif ($user_type == 'siswa') {
    // Login Siswa
    if (!isset($_POST['email'])) {
        header('Location: login.php?error=' . urlencode('Email harus diisi'));
        exit;
    }
    
    $email = clean_input($_POST['email']);
    
    // Validasi email
    if (!validate_email($email)) {
        header('Location: login.php?error=' . urlencode('Format email tidak valid'));
        exit;
    }
    
    // Query akun siswa
    $query = "SELECT a.*, s.id as siswa_id, s.nama_lengkap 
              FROM akun_siswa a 
              JOIN siswa s ON a.siswa_id = s.id 
              WHERE a.email = ? AND a.is_active = 1";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($akun = mysqli_fetch_assoc($result)) {
        // Verify password
        if (verify_password($password, $akun['password'])) {
            // Set session
            $_SESSION['siswa_id'] = $akun['siswa_id'];
            $_SESSION['akun_siswa_id'] = $akun['id'];
            $_SESSION['siswa_nama'] = $akun['nama_lengkap'];
            
            // Update last login
            $update_query = "UPDATE akun_siswa SET last_login = NOW() WHERE id = ?";
            $update_stmt = mysqli_prepare($conn, $update_query);
            mysqli_stmt_bind_param($update_stmt, "i", $akun['id']);
            mysqli_stmt_execute($update_stmt);
            
            // Redirect ke dashboard siswa
            header('Location: ' . BASE_URL . 'siswa/dashboard_siswa.php');
            exit;
        } else {
            header('Location: login.php?error=' . urlencode('Password salah'));
            exit;
        }
    } else {
        header('Location: login.php?error=' . urlencode('Email tidak terdaftar'));
        exit;
    }
    
} else {
    header('Location: login.php?error=' . urlencode('Tipe user tidak valid'));
    exit;
}
?>
