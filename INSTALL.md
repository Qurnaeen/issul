# Panduan Instalasi Sistem PPDB

## Langkah 1: Persiapan Environment

### Pastikan Sudah Terinstall:
- XAMPP (atau WAMP/LAMP)
- PHP 7.4 atau lebih tinggi
- MySQL 5.7 atau lebih tinggi
- Web browser modern (Chrome, Firefox, Edge)

## Langkah 2: Setup Database

1. **Jalankan XAMPP**
   - Start Apache
   - Start MySQL

2. **Buka phpMyAdmin**
   - Akses: `http://localhost/phpmyadmin`

3. **Buat Database**
   - Klik "New" di sidebar kiri
   - Nama database: `ppdb_db`
   - Collation: `utf8mb4_unicode_ci`
   - Klik "Create"

4. **Import Database Schema**
   - Pilih database `ppdb_db`
   - Klik tab "Import"
   - Klik "Choose File"
   - Pilih file: `projek-PPDB/database/database_schema.sql`
   - Klik "Go"
   - Tunggu hingga selesai (akan muncul pesan sukses)

## Langkah 3: Konfigurasi Aplikasi (Opsional)

Jika menggunakan kredensial database yang berbeda:

1. **Edit File Database Config**
   - Buka: `projek-PPDB/config/database.php`
   - Sesuaikan:
     ```php
     define('DB_HOST', 'localhost');
     define('DB_USER', 'root');
     define('DB_PASS', '');  // Isi jika ada password
     define('DB_NAME', 'ppdb_db');
     ```

2. **Edit Informasi Sekolah**
   - Buka: `projek-PPDB/config/app.php`
   - Sesuaikan nama sekolah, alamat, dll.

## Langkah 4: Set Permission Folder Upload

### Windows (XAMPP):
Biasanya sudah otomatis bisa write. Jika ada error:
- Klik kanan folder `public/uploads`
- Properties → Security
- Pastikan "Users" memiliki permission "Write"

### Linux/Mac:
```bash
chmod 755 public/uploads/
```

## Langkah 5: Akses Aplikasi

1. **Buka Browser**
   - Chrome, Firefox, atau Edge

2. **Akses URL**
   ```
   http://localhost/projek-PPDB/
   ```

3. **Anda akan melihat Landing Page**
   - Jika berhasil, halaman akan tampil dengan baik
   - Jika error, cek langkah sebelumnya

## Langkah 6: Login Pertama Kali

### Login sebagai Admin:
1. Klik "Login" di navbar
2. Pilih tab "Admin"
3. Masukkan:
   - Username: `admin`
   - Password: `admin123`
4. Klik "Login Admin"

### Login sebagai Panitia:
- Username: `panitia`
- Password: `admin123`

> ⚠️ **PENTING**: Segera ganti password default setelah login pertama!

## Langkah 7: Test Sistem

### Test Alur Siswa:
1. Logout dari admin
2. Klik "Daftar Sekarang"
3. Isi form registrasi
4. Login dengan email yang didaftarkan
5. Lengkapi formulir pendaftaran
6. Upload berkas (Akta, KK, Ijazah, Foto)
7. Cetak bukti pendaftaran
8. Cek status

### Test Alur Admin:
1. Login sebagai admin
2. Lihat dashboard (statistik harus muncul)
3. Buka "Data Pendaftar"
4. Klik "Detail" pada salah satu siswa
5. Verifikasi berkas
6. Ubah status seleksi
7. Export data ke Excel

## Troubleshooting

### Error: "Connection failed"
**Solusi:**
- Pastikan MySQL di XAMPP sudah running
- Cek kredensial di `config/database.php`
- Pastikan database `ppdb_db` sudah dibuat

### Error: "Failed to upload file"
**Solusi:**
- Pastikan folder `public/uploads/` ada
- Cek permission folder (harus bisa write)
- Pastikan ukuran file < 2MB
- Format file harus PDF/JPG/PNG

### Error: "Page not found" atau "404"
**Solusi:**
- Pastikan URL benar: `http://localhost/projek-PPDB/`
- Pastikan folder ada di `C:\xampp\htdocs\projek-PPDB\`
- Restart Apache di XAMPP

### Error: "Session not working"
**Solusi:**
- Pastikan PHP session enabled
- Cek folder `C:\xampp\tmp` ada dan bisa write
- Restart Apache

### Halaman Blank / White Screen
**Solusi:**
- Aktifkan error display:
  - Buka `php.ini` di XAMPP
  - Set `display_errors = On`
  - Restart Apache
- Cek error di `C:\xampp\apache\logs\error.log`

### CSS/JS Tidak Load
**Solusi:**
- Clear browser cache (Ctrl + Shift + Del)
- Hard refresh (Ctrl + F5)
- Cek koneksi internet (untuk CDN Bootstrap, jQuery, dll)

## Checklist Instalasi

- [ ] XAMPP terinstall dan running
- [ ] Database `ppdb_db` sudah dibuat
- [ ] File SQL sudah diimport
- [ ] Folder `public/uploads/` bisa write
- [ ] Bisa akses `http://localhost/projek-PPDB/`
- [ ] Bisa login sebagai admin
- [ ] Bisa registrasi sebagai siswa
- [ ] Upload file berhasil

## Kontak Support

Jika masih ada kendala, silakan hubungi:
- Email: info@sman1jakarta.sch.id
- Telp: (021) 1234567

---

**Selamat! Sistem PPDB Anda sudah siap digunakan! 🎉**
