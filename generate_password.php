<?php
/**
 * Script untuk generate password hash
 * Jalankan sekali untuk mendapatkan hash password yang benar
 */

// Password yang ingin di-hash
$password = 'admin123';

// Generate hash
$hash = password_hash($password, PASSWORD_DEFAULT);

echo "Password: " . $password . "\n";
echo "Hash: " . $hash . "\n\n";

echo "Copy hash di atas dan update di database dengan query:\n";
echo "UPDATE users_admin SET password = '$hash' WHERE username = 'admin';\n";
echo "UPDATE users_admin SET password = '$hash' WHERE username = 'panitia';\n";
?>
