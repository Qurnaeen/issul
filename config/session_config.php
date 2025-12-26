<?php
/**
 * Session Configuration
 * File: config/session_config.php
 * 
 * PENTING: File ini HARUS di-include SEBELUM session_start()
 */

// Session Configuration - HARUS sebelum session_start()
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 0); // Set 1 jika menggunakan HTTPS
ini_set('session.cookie_lifetime', 0); // Session berakhir saat browser ditutup
ini_set('session.gc_maxlifetime', 3600); // 1 jam

// Timezone
date_default_timezone_set('Asia/Jakarta');
?>
