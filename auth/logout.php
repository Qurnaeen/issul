<?php
require_once __DIR__ . '/../config/session_config.php';
session_start();
require_once __DIR__ . '/../config/app.php';

// Destroy session
session_destroy();

// Redirect ke halaman login
header('Location: ' . BASE_URL . 'auth/login.php?success=' . urlencode('Logout berhasil'));
exit;
?>
