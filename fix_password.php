<?php
/**
 * Script untuk memperbaiki password admin
 * Akses: http://localhost/projek-PPDB/fix_password.php
 */

require_once __DIR__ . '/config/database.php';

// Password yang benar
$password = 'admin123';
$hash = password_hash($password, PASSWORD_DEFAULT);

echo "<h2>Fix Password Admin</h2>";
echo "<p>Password baru: <strong>admin123</strong></p>";
echo "<p>Hash: <code>$hash</code></p>";

// Update password untuk admin
$query1 = "UPDATE users_admin SET password = ? WHERE username = 'admin'";
$stmt1 = mysqli_prepare($conn, $query1);
mysqli_stmt_bind_param($stmt1, "s", $hash);
$result1 = mysqli_stmt_execute($stmt1);

// Update password untuk panitia
$query2 = "UPDATE users_admin SET password = ? WHERE username = 'panitia'";
$stmt2 = mysqli_prepare($conn, $query2);
mysqli_stmt_bind_param($stmt2, "s", $hash);
$result2 = mysqli_stmt_execute($stmt2);

if ($result1 && $result2) {
    echo "<p style='color: green;'><strong>✓ Password berhasil diupdate!</strong></p>";
    echo "<p>Sekarang Anda bisa login dengan:</p>";
    echo "<ul>";
    echo "<li>Username: <strong>admin</strong> | Password: <strong>admin123</strong></li>";
    echo "<li>Username: <strong>panitia</strong> | Password: <strong>admin123</strong></li>";
    echo "</ul>";
    echo "<p><a href='auth/login.php'>Klik di sini untuk login</a></p>";
} else {
    echo "<p style='color: red;'><strong>✗ Gagal update password!</strong></p>";
    echo "<p>Error: " . mysqli_error($conn) . "</p>";
}

// Verifikasi
echo "<hr>";
echo "<h3>Verifikasi Data Admin:</h3>";
$check = mysqli_query($conn, "SELECT username, password FROM users_admin");
while ($row = mysqli_fetch_assoc($check)) {
    echo "<p>Username: <strong>{$row['username']}</strong><br>";
    echo "Hash: <code>{$row['password']}</code></p>";
    
    // Test password
    if (password_verify('admin123', $row['password'])) {
        echo "<p style='color: green;'>✓ Password 'admin123' COCOK untuk {$row['username']}</p>";
    } else {
        echo "<p style='color: red;'>✗ Password 'admin123' TIDAK COCOK untuk {$row['username']}</p>";
    }
}

mysqli_close($conn);
?>
