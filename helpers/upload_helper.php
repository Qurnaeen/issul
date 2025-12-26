<?php
/**
 * Helper Functions untuk Upload File
 * File: helpers/upload_helper.php
 */

/**
 * Upload berkas dengan validasi
 * @param array $file - $_FILES['input_name']
 * @param string $jenis_berkas - akta, kk, ijazah, foto
 * @param int $siswa_id
 * @return array ['success' => bool, 'message' => string, 'file_path' => string]
 */
function upload_berkas($file, $jenis_berkas, $siswa_id) {
    // Validasi file
    $validation = validate_file($file);
    if (!$validation['valid']) {
        return [
            'success' => false,
            'message' => $validation['message'],
            'file_path' => ''
        ];
    }
    
    // Generate nama file unik
    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $new_filename = generate_filename($siswa_id, $jenis_berkas, $extension);
    
    // Path tujuan
    $upload_dir = UPLOAD_PATH . $siswa_id . '/';
    
    // Buat folder jika belum ada
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }
    
    $destination = $upload_dir . $new_filename;
    
    // Upload file
    if (move_uploaded_file($file['tmp_name'], $destination)) {
        // Relative path untuk disimpan di database
        $relative_path = 'uploads/' . $siswa_id . '/' . $new_filename;
        
        return [
            'success' => true,
            'message' => 'File berhasil diupload',
            'file_path' => $relative_path,
            'file_size' => $file['size']
        ];
    } else {
        return [
            'success' => false,
            'message' => 'Gagal mengupload file',
            'file_path' => ''
        ];
    }
}

/**
 * Validasi file upload
 * @param array $file
 * @return array ['valid' => bool, 'message' => string]
 */
function validate_file($file) {
    // Cek error upload
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return [
            'valid' => false,
            'message' => 'Error saat upload file: ' . $file['error']
        ];
    }
    
    // Cek ukuran file
    if ($file['size'] > UPLOAD_MAX_SIZE) {
        $max_mb = UPLOAD_MAX_SIZE / 1048576;
        return [
            'valid' => false,
            'message' => 'Ukuran file terlalu besar. Maksimal ' . $max_mb . ' MB'
        ];
    }
    
    // Cek ekstensi file
    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($extension, ALLOWED_EXTENSIONS)) {
        return [
            'valid' => false,
            'message' => 'Ekstensi file tidak diizinkan. Hanya: ' . implode(', ', ALLOWED_EXTENSIONS)
        ];
    }
    
    // Cek MIME type
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime_type = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    
    if (!in_array($mime_type, ALLOWED_MIME_TYPES)) {
        return [
            'valid' => false,
            'message' => 'Tipe file tidak valid'
        ];
    }
    
    return [
        'valid' => true,
        'message' => 'File valid'
    ];
}

/**
 * Generate nama file unik
 * @param int $siswa_id
 * @param string $jenis_berkas
 * @param string $extension
 * @return string
 */
function generate_filename($siswa_id, $jenis_berkas, $extension) {
    $timestamp = time();
    $random = bin2hex(random_bytes(4));
    return $siswa_id . '_' . $jenis_berkas . '_' . $timestamp . '_' . $random . '.' . $extension;
}

/**
 * Hapus file dari server
 * @param string $file_path - relative path
 * @return bool
 */
function delete_file($file_path) {
    $full_path = __DIR__ . '/../public/' . $file_path;
    
    if (file_exists($full_path)) {
        return unlink($full_path);
    }
    
    return false;
}

/**
 * Get file size dalam format readable
 * @param int $bytes
 * @return string
 */
function format_file_size($bytes) {
    if ($bytes >= 1073741824) {
        return number_format($bytes / 1073741824, 2) . ' GB';
    } elseif ($bytes >= 1048576) {
        return number_format($bytes / 1048576, 2) . ' MB';
    } elseif ($bytes >= 1024) {
        return number_format($bytes / 1024, 2) . ' KB';
    } else {
        return $bytes . ' bytes';
    }
}

/**
 * Cek apakah file adalah gambar
 * @param string $file_path
 * @return bool
 */
function is_image($file_path) {
    $extension = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));
    return in_array($extension, ['jpg', 'jpeg', 'png']);
}

/**
 * Get icon untuk jenis file
 * @param string $file_path
 * @return string - class icon
 */
function get_file_icon($file_path) {
    $extension = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));
    
    switch ($extension) {
        case 'pdf':
            return 'bi-file-pdf text-danger';
        case 'jpg':
        case 'jpeg':
        case 'png':
            return 'bi-file-image text-primary';
        default:
            return 'bi-file-earmark text-secondary';
    }
}
?>
