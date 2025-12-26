<?php
/**
 * Konfigurasi Aplikasi
 * File: config/app.php
 */

// Informasi Sekolah
define('NAMA_SEKOLAH', 'SMKS AL-MUJTAMA\'');
define('ALAMAT_SEKOLAH', 'Jl. Raya Pegantenan KM 09 Pamekasan');
define('TELP_SEKOLAH', '0819-3939-5440');
define('EMAIL_SEKOLAH', 'smkamt20@gmail.com');

// Tahun Ajaran
define('TAHUN_AJARAN', '2025/2026');

// Timezone
date_default_timezone_set('Asia/Jakarta');

// Upload Configuration
define('UPLOAD_PATH', __DIR__ . '/../public/uploads/');
define('UPLOAD_MAX_SIZE', 2097152); // 2MB dalam bytes
define('ALLOWED_EXTENSIONS', ['pdf', 'jpg', 'jpeg', 'png']);
define('ALLOWED_MIME_TYPES', [
    'application/pdf',
    'image/jpeg',
    'image/jpg',
    'image/png'
]);

// URL Base
define('BASE_URL', 'http://localhost/projek-PPDB/');

// Pagination
define('ITEMS_PER_PAGE', 20);

// Format Nomor Pendaftaran
// Format: PPDB-TAHUN-URUTAN (contoh: PPDB-2025-00001)
define('PREFIX_NO_PENDAFTARAN', 'PPDB-2025-');

// Status Pendaftaran
define('STATUS_PENDING', 'pending');
define('STATUS_LULUS', 'lulus');
define('STATUS_TIDAK_LULUS', 'tidak_lulus');

// Status Berkas
define('BERKAS_BELUM_LENGKAP', 'belum_lengkap');
define('BERKAS_LENGKAP', 'lengkap');
define('BERKAS_TERVERIFIKASI', 'terverifikasi');

// Jenis Berkas
define('JENIS_BERKAS', [
    'akta' => 'Akta Kelahiran',
    'kk' => 'Kartu Keluarga',
    'ijazah' => 'Ijazah / Surat Keterangan Lulus',
    'foto' => 'Pas Foto'
]);
?>
