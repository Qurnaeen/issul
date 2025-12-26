<?php
/**
 * Helper Functions untuk Authentication
 * File: helpers/auth_helper.php
 */

/**
 * Cek apakah admin sudah login
 * @return bool
 */
function cek_login_admin() {
    if (!isset($_SESSION['admin_id']) || !isset($_SESSION['admin_role'])) {
        return false;
    }
    return true;
}

/**
 * Cek apakah siswa sudah login
 * @return bool
 */
function cek_login_siswa() {
    if (!isset($_SESSION['siswa_id']) || !isset($_SESSION['akun_siswa_id'])) {
        return false;
    }
    return true;
}

/**
 * Redirect jika belum login sebagai admin
 */
function require_login_admin() {
    if (!cek_login_admin()) {
        header('Location: ' . BASE_URL . 'auth/login.php?redirect=admin');
        exit;
    }
}

/**
 * Redirect jika belum login sebagai siswa
 */
function require_login_siswa() {
    if (!cek_login_siswa()) {
        header('Location: ' . BASE_URL . 'auth/login.php?redirect=siswa');
        exit;
    }
}

/**
 * Generate nomor pendaftaran unik
 * @param mysqli $conn
 * @return string
 */
function generate_no_pendaftaran($conn) {
    // Ambil nomor terakhir
    $query = "SELECT no_pendaftaran FROM siswa ORDER BY id DESC LIMIT 1";
    $result = mysqli_query($conn, $query);
    
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $last_no = $row['no_pendaftaran'];
        
        // Extract nomor urut
        $parts = explode('-', $last_no);
        $urutan = intval($parts[2]) + 1;
    } else {
        $urutan = 1;
    }
    
    // Format: PPDB-2025-00001
    return PREFIX_NO_PENDAFTARAN . str_pad($urutan, 5, '0', STR_PAD_LEFT);
}

/**
 * Hash password
 * @param string $password
 * @return string
 */
function hash_password($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

/**
 * Verify password
 * @param string $password
 * @param string $hash
 * @return bool
 */
function verify_password($password, $hash) {
    return password_verify($password, $hash);
}

/**
 * Get data admin yang sedang login
 * @param mysqli $conn
 * @return array|null
 */
function get_admin_data($conn) {
    if (!cek_login_admin()) {
        return null;
    }
    
    $admin_id = $_SESSION['admin_id'];
    $query = "SELECT * FROM users_admin WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $admin_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($row = mysqli_fetch_assoc($result)) {
        return $row;
    }
    
    return null;
}

/**
 * Get data siswa yang sedang login
 * @param mysqli $conn
 * @return array|null
 */
function get_siswa_data($conn) {
    if (!cek_login_siswa()) {
        return null;
    }
    
    $siswa_id = $_SESSION['siswa_id'];
    $query = "SELECT s.*, a.email FROM siswa s 
              LEFT JOIN akun_siswa a ON s.id = a.siswa_id 
              WHERE s.id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $siswa_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($row = mysqli_fetch_assoc($result)) {
        return $row;
    }
    
    return null;
}

/**
 * Logout admin
 */
function logout_admin() {
    unset($_SESSION['admin_id']);
    unset($_SESSION['admin_nama']);
    unset($_SESSION['admin_role']);
    session_destroy();
}

/**
 * Logout siswa
 */
function logout_siswa() {
    unset($_SESSION['siswa_id']);
    unset($_SESSION['akun_siswa_id']);
    unset($_SESSION['siswa_nama']);
    session_destroy();
}
?>
