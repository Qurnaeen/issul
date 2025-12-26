<?php
require_once __DIR__ . '/../config/session_config.php';
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../helpers/auth_helper.php';
require_once __DIR__ . '/../helpers/security.php';

// Cek login
require_login_siswa();

// Cek CSRF token
if (!isset($_POST['csrf_token']) || !verify_csrf_token($_POST['csrf_token'])) {
    header('Location: form.php?error=' . urlencode('Invalid request'));
    exit;
}

// Get siswa ID
$siswa_id = $_SESSION['siswa_id'];

// Ambil dan sanitasi data
$nama_lengkap = clean_input($_POST['nama_lengkap']);
$nisn = clean_input($_POST['nisn']);
$nik = clean_input($_POST['nik']);
$jk = clean_input($_POST['jk']);
$tempat_lahir = clean_input($_POST['tempat_lahir']);
$tanggal_lahir = clean_input($_POST['tanggal_lahir']);
$agama = clean_input($_POST['agama']);
$no_telp = clean_input($_POST['no_telp']);

$alamat = clean_input($_POST['alamat']);
$rt = clean_input($_POST['rt']);
$rw = clean_input($_POST['rw']);
$kelurahan = clean_input($_POST['kelurahan']);
$kecamatan = clean_input($_POST['kecamatan']);
$kota = clean_input($_POST['kota']);
$provinsi = clean_input($_POST['provinsi']);
$kode_pos = clean_input($_POST['kode_pos']);

$nama_ayah = clean_input($_POST['nama_ayah']);
$pekerjaan_ayah = clean_input($_POST['pekerjaan_ayah']);
$nama_ibu = clean_input($_POST['nama_ibu']);
$pekerjaan_ibu = clean_input($_POST['pekerjaan_ibu']);
$no_telp_ortu = clean_input($_POST['no_telp_ortu']);

// Validasi
$errors = [];

if (empty($nama_lengkap)) $errors[] = 'Nama lengkap harus diisi';
if (empty($nik)) $errors[] = 'NIK harus diisi';
if (!empty($nik) && !validate_nik($nik)) $errors[] = 'NIK harus 16 digit';
if (!empty($nisn) && !validate_nisn($nisn)) $errors[] = 'NISN harus 10 digit';
if (empty($jk)) $errors[] = 'Jenis kelamin harus dipilih';
if (empty($tempat_lahir)) $errors[] = 'Tempat lahir harus diisi';
if (empty($tanggal_lahir)) $errors[] = 'Tanggal lahir harus diisi';
if (empty($agama)) $errors[] = 'Agama harus dipilih';
if (empty($alamat)) $errors[] = 'Alamat harus diisi';
if (empty($nama_ibu)) $errors[] = 'Nama ibu harus diisi';

if (!empty($errors)) {
    $error_message = implode(', ', $errors);
    header('Location: form.php?error=' . urlencode($error_message));
    exit;
}

// Update data siswa
$query = "UPDATE siswa SET 
          nama_lengkap = ?, nisn = ?, nik = ?, jk = ?, 
          tempat_lahir = ?, tanggal_lahir = ?, agama = ?, no_telp = ?,
          alamat = ?, rt = ?, rw = ?, kelurahan = ?, kecamatan = ?, kota = ?, provinsi = ?, kode_pos = ?,
          nama_ayah = ?, pekerjaan_ayah = ?, nama_ibu = ?, pekerjaan_ibu = ?, no_telp_ortu = ?
          WHERE id = ?";

$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "sssssssssssssssssssssi", 
    $nama_lengkap, $nisn, $nik, $jk,
    $tempat_lahir, $tanggal_lahir, $agama, $no_telp,
    $alamat, $rt, $rw, $kelurahan, $kecamatan, $kota, $provinsi, $kode_pos,
    $nama_ayah, $pekerjaan_ayah, $nama_ibu, $pekerjaan_ibu, $no_telp_ortu,
    $siswa_id
);

if (mysqli_stmt_execute($stmt)) {
    // Update session nama
    $_SESSION['siswa_nama'] = $nama_lengkap;
    
    header('Location: form.php?success=' . urlencode('Data berhasil disimpan'));
    exit;
} else {
    header('Location: form.php?error=' . urlencode('Gagal menyimpan data'));
    exit;
}
?>
