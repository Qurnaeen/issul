-- ================================================
-- Database Schema untuk Sistem PPDB
-- Tanpa Modul Jalur Pendaftaran
-- ================================================

CREATE DATABASE IF NOT EXISTS ppdb_db;
USE ppdb_db;

-- ================================================
-- Tabel: users_admin
-- Deskripsi: Menyimpan akun admin dan panitia
-- ================================================
CREATE TABLE users_admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin','panitia') DEFAULT 'panitia',
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_username (username),
    INDEX idx_role (role)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ================================================
-- Tabel: siswa
-- Deskripsi: Data lengkap calon siswa
-- ================================================
CREATE TABLE siswa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    no_pendaftaran VARCHAR(20) UNIQUE NOT NULL,
    nama_lengkap VARCHAR(100) NOT NULL,
    nisn VARCHAR(20),
    nik VARCHAR(20),
    jk ENUM('L','P') NOT NULL,
    tempat_lahir VARCHAR(50) NOT NULL,
    tanggal_lahir DATE NOT NULL,
    agama VARCHAR(20),
    alamat TEXT NOT NULL,
    rt VARCHAR(5),
    rw VARCHAR(5),
    kelurahan VARCHAR(50),
    kecamatan VARCHAR(50),
    kota VARCHAR(50),
    provinsi VARCHAR(50),
    kode_pos VARCHAR(10),
    no_telp VARCHAR(20),
    email VARCHAR(100),
    
    -- Data Orang Tua
    nama_ayah VARCHAR(100),
    pekerjaan_ayah VARCHAR(50),
    nama_ibu VARCHAR(100) NOT NULL,
    pekerjaan_ibu VARCHAR(50),
    no_telp_ortu VARCHAR(20),
    
    -- Status Pendaftaran
    status ENUM('pending','lulus','tidak_lulus') DEFAULT 'pending',
    status_berkas ENUM('belum_lengkap','lengkap','terverifikasi') DEFAULT 'belum_lengkap',
    catatan_admin TEXT,
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_no_pendaftaran (no_pendaftaran),
    INDEX idx_status (status),
    INDEX idx_nama (nama_lengkap),
    INDEX idx_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ================================================
-- Tabel: akun_siswa
-- Deskripsi: Kredensial login untuk siswa
-- ================================================
CREATE TABLE akun_siswa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    siswa_id INT UNIQUE,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    is_active TINYINT(1) DEFAULT 1,
    last_login TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (siswa_id) REFERENCES siswa(id) ON DELETE CASCADE,
    INDEX idx_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ================================================
-- Tabel: berkas
-- Deskripsi: File upload dokumen siswa
-- ================================================
CREATE TABLE berkas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    siswa_id INT NOT NULL,
    jenis_berkas ENUM('akta','kk','ijazah','foto') NOT NULL,
    nama_file VARCHAR(255) NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    file_size INT,
    status ENUM('pending','valid','invalid') DEFAULT 'pending',
    catatan VARCHAR(255),
    verified_by INT,
    verified_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (siswa_id) REFERENCES siswa(id) ON DELETE CASCADE,
    FOREIGN KEY (verified_by) REFERENCES users_admin(id) ON DELETE SET NULL,
    INDEX idx_siswa (siswa_id),
    INDEX idx_jenis (jenis_berkas),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ================================================
-- Tabel: log_aktivitas
-- Deskripsi: Audit trail untuk aktivitas admin
-- ================================================
CREATE TABLE log_aktivitas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    admin_id INT,
    aktivitas VARCHAR(255) NOT NULL,
    deskripsi TEXT,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (admin_id) REFERENCES users_admin(id) ON DELETE SET NULL,
    INDEX idx_admin (admin_id),
    INDEX idx_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ================================================
-- Data Awal: Admin Default
-- ================================================
-- Password: admin123 (hash yang benar)
INSERT INTO users_admin (nama, username, password, role) VALUES
('Administrator', 'admin', '$2y$10$e0MYzXyjpJS7Pd0RVvHwHe.Mji6gKhQqTfHVJvZBXiKzBqGLGKVHS', 'admin'),
('Panitia PPDB', 'panitia', '$2y$10$e0MYzXyjpJS7Pd0RVvHwHe.Mji6gKhQqTfHVJvZBXiKzBqGLGKVHS', 'panitia');

-- ================================================
-- Selesai
-- ================================================
