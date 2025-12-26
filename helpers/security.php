<?php
/**
 * Helper Functions untuk Security
 * File: helpers/security.php
 */

/**
 * Sanitasi input dari user
 * @param string $data
 * @return string
 */
function clean_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

/**
 * Escape output untuk mencegah XSS
 * @param string $data
 * @return string
 */
function escape_output($data) {
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

/**
 * Generate CSRF Token
 * @return string
 */
function generate_csrf_token() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Verify CSRF Token
 * @param string $token
 * @return bool
 */
function verify_csrf_token($token) {
    if (!isset($_SESSION['csrf_token'])) {
        return false;
    }
    return hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Validasi email
 * @param string $email
 * @return bool
 */
function validate_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Validasi nomor telepon Indonesia
 * @param string $phone
 * @return bool
 */
function validate_phone($phone) {
    // Format: 08xxxxxxxxxx atau +628xxxxxxxxxx
    $pattern = '/^(\+62|62|0)[0-9]{9,12}$/';
    return preg_match($pattern, $phone);
}

/**
 * Validasi NISN (10 digit)
 * @param string $nisn
 * @return bool
 */
function validate_nisn($nisn) {
    return preg_match('/^[0-9]{10}$/', $nisn);
}

/**
 * Validasi NIK (16 digit)
 * @param string $nik
 * @return bool
 */
function validate_nik($nik) {
    return preg_match('/^[0-9]{16}$/', $nik);
}

/**
 * Sanitasi nama file
 * @param string $filename
 * @return string
 */
function sanitize_filename($filename) {
    // Hapus karakter berbahaya
    $filename = preg_replace('/[^a-zA-Z0-9._-]/', '', $filename);
    return $filename;
}

/**
 * Get client IP address
 * @return string
 */
function get_client_ip() {
    $ip = '';
    
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    
    return $ip;
}

/**
 * Get user agent
 * @return string
 */
function get_user_agent() {
    return isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
}

/**
 * Log aktivitas admin
 * @param mysqli $conn
 * @param int $admin_id
 * @param string $aktivitas
 * @param string $deskripsi
 */
function log_aktivitas($conn, $admin_id, $aktivitas, $deskripsi = '') {
    $ip = get_client_ip();
    $user_agent = get_user_agent();
    
    $query = "INSERT INTO log_aktivitas (admin_id, aktivitas, deskripsi, ip_address, user_agent) 
              VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "issss", $admin_id, $aktivitas, $deskripsi, $ip, $user_agent);
    mysqli_stmt_execute($stmt);
}

/**
 * Prevent SQL Injection dengan prepared statement helper
 * @param mysqli $conn
 * @param string $query
 * @param string $types
 * @param array $params
 * @return mysqli_result|bool
 */
function execute_query($conn, $query, $types = '', $params = []) {
    if (empty($types)) {
        return mysqli_query($conn, $query);
    }
    
    $stmt = mysqli_prepare($conn, $query);
    if (!$stmt) {
        return false;
    }
    
    if (!empty($params)) {
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }
    
    mysqli_stmt_execute($stmt);
    return mysqli_stmt_get_result($stmt);
}
?>
