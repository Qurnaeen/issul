# Sistem PPDB (Penerimaan Peserta Didik Baru)

Sistem Penerimaan Peserta Didik Baru berbasis web menggunakan PHP Procedural dengan MySQL.

## 🚀 Fitur Utama

### Untuk Calon Siswa
- ✅ Registrasi akun online
- ✅ Login dengan email dan password
- ✅ Pengisian formulir pendaftaran lengkap
- ✅ Upload berkas (Akta, KK, Ijazah, Foto)
- ✅ Cetak bukti pendaftaran (PDF)
- ✅ Cek status seleksi real-time
- ✅ Dashboard progress pendaftaran

### Untuk Admin/Panitia
- ✅ Dashboard statistik lengkap
- ✅ Manajemen data pendaftar
- ✅ Verifikasi berkas upload
- ✅ Penentuan kelulusan
- ✅ Export data ke Excel dan PDF
- ✅ Log aktivitas admin
- ✅ Filter dan pencarian data

## 📋 Teknologi yang Digunakan

- **Backend**: PHP 7.4+ (Procedural)
- **Database**: MySQL 5.7+
- **Frontend**: Bootstrap 5.3
- **JavaScript**: jQuery 3.7
- **Libraries**:
  - DataTables (tabel interaktif)
  - SweetAlert2 (notifikasi)
  - Chart.js (visualisasi data)
  - TCPDF (generate PDF)

## 🛠️ Instalasi

### Persyaratan
- XAMPP / WAMP / LAMP
- PHP 7.4 atau lebih tinggi
- MySQL 5.7 atau lebih tinggi
- Web browser modern

### Langkah Instalasi

1. **Clone atau Download Project**
   ```bash
   # Letakkan di folder htdocs (XAMPP) atau www (WAMP)
   cd C:\xampp\htdocs\
   ```

2. **Buat Database**
   - Buka phpMyAdmin (http://localhost/phpmyadmin)
   - Buat database baru dengan nama `ppdb_db`
   - Import file SQL:
     ```
     database/database_schema.sql
     ```

3. **Konfigurasi Database** (Opsional)
   - Buka file `config/database.php`
   - Sesuaikan kredensial database jika perlu:
     ```php
     define('DB_HOST', 'localhost');
     define('DB_USER', 'root');
     define('DB_PASS', '');
     define('DB_NAME', 'ppdb_db');
     ```

4. **Konfigurasi Aplikasi** (Opsional)
   - Buka file `config/app.php`
   - Sesuaikan informasi sekolah dan pengaturan lainnya

5. **Set Permission Folder Upload**
   ```bash
   # Pastikan folder uploads bisa ditulis
   chmod 755 public/uploads/
   ```

6. **Akses Aplikasi**
   - Buka browser dan akses: `http://localhost/projek-PPDB/`

## 👤 Akun Default

### Admin
- **Username**: `admin`
- **Password**: `admin123`

### Panitia
- **Username**: `panitia`
- **Password**: `admin123`

> ⚠️ **PENTING**: Segera ganti password default setelah login pertama kali!

## 📁 Struktur Folder

```
projek-PPDB/
├── admin/                  # Modul admin
│   ├── dashboard.php
│   ├── siswa/             # Manajemen data siswa
│   └── laporan/           # Laporan dan export
├── auth/                   # Autentikasi
│   ├── login.php
│   ├── registrasi.php
│   └── logout.php
├── config/                 # Konfigurasi
│   ├── database.php
│   └── app.php
├── database/              # SQL schema
│   └── database_schema.sql
├── helpers/               # Helper functions
│   ├── auth_helper.php
│   ├── security.php
│   ├── upload_helper.php
│   └── tanggal_helper.php
├── pendaftaran/           # Modul pendaftaran siswa
│   ├── form.php
│   ├── upload.php
│   └── cetak_bukti.php
├── public/                # Assets
│   ├── css/
│   ├── js/
│   └── uploads/          # Folder upload berkas
├── siswa/                 # Modul siswa
│   ├── dashboard_siswa.php
│   └── cek_status.php
├── templates/             # Template files
│   ├── header.php
│   ├── footer.php
│   ├── navbar.php
│   └── navbar_admin.php
├── .htaccess             # Security & routing
└── index.php             # Landing page
```

## 🔒 Fitur Keamanan

- ✅ Password hashing dengan `password_hash()`
- ✅ Prepared statements untuk mencegah SQL Injection
- ✅ CSRF token protection
- ✅ XSS prevention dengan `htmlspecialchars()`
- ✅ File upload validation (type, size, MIME)
- ✅ .htaccess protection untuk folder uploads
- ✅ Session management yang aman
- ✅ Input sanitization
- ✅ Activity logging untuk admin

## 📊 Database Schema

### Tabel Utama
1. **users_admin** - Akun admin dan panitia
2. **siswa** - Data calon siswa
3. **akun_siswa** - Kredensial login siswa
4. **berkas** - File upload dokumen
5. **log_aktivitas** - Audit trail admin

## 🎯 Alur Penggunaan

### Untuk Calon Siswa
1. Registrasi akun dengan email
2. Login ke sistem
3. Lengkapi formulir pendaftaran
4. Upload berkas persyaratan
5. Cetak bukti pendaftaran
6. Tunggu verifikasi admin
7. Cek pengumuman hasil seleksi

### Untuk Admin
1. Login dengan akun admin
2. Lihat dashboard statistik
3. Verifikasi data dan berkas siswa
4. Tentukan status kelulusan
5. Export laporan
6. Monitor aktivitas sistem

## 🐛 Troubleshooting

### Error: "Connection failed"
- Pastikan MySQL sudah running
- Cek kredensial database di `config/database.php`

### Error: "Failed to upload file"
- Pastikan folder `public/uploads/` memiliki permission write
- Cek ukuran file (max 2MB)
- Pastikan format file sesuai (PDF, JPG, PNG)

### Error: "Session not working"
- Pastikan `session_start()` dipanggil di awal file
- Cek konfigurasi PHP session

## 📝 Catatan Pengembangan

- Sistem ini menggunakan PHP Procedural (bukan OOP)
- Separation of concerns tetap diterapkan melalui struktur folder
- Helper functions digunakan untuk reusability
- Prepared statements untuk semua query database
- Bootstrap 5 untuk responsive design

## 📞 Support

Jika ada pertanyaan atau kendala, silakan hubungi:
- Email: <?php echo EMAIL_SEKOLAH; ?>
- Telp: <?php echo TELP_SEKOLAH; ?>

## 📄 License

© 2025 <?php echo NAMA_SEKOLAH; ?>. All rights reserved.

---

**Dibuat dengan ❤️ menggunakan PHP Procedural**
