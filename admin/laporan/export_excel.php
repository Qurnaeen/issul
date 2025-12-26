<?php
require_once __DIR__ . '/../../config/session_config.php';
session_start();
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../../helpers/auth_helper.php';

// Cek login admin
require_login_admin();

// Set headers untuk download Excel
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="Data_Pendaftar_PPDB_' . date('Y-m-d') . '.xls"');
header('Cache-Control: max-age=0');

// Query data
$query = "SELECT s.*, a.email FROM siswa s 
          LEFT JOIN akun_siswa a ON s.id = a.siswa_id 
          ORDER BY s.created_at DESC";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Data Pendaftar PPDB</title>
    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid black; padding: 5px; }
        th { background-color: #0d6efd; color: white; font-weight: bold; }
    </style>
</head>
<body>
    <h2>Data Pendaftar PPDB <?php echo NAMA_SEKOLAH; ?></h2>
    <p>Tahun Ajaran: <?php echo TAHUN_AJARAN; ?></p>
    <p>Tanggal Export: <?php echo date('d-m-Y H:i:s'); ?></p>
    
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>No. Pendaftaran</th>
                <th>Nama Lengkap</th>
                <th>NISN</th>
                <th>NIK</th>
                <th>Jenis Kelamin</th>
                <th>Tempat Lahir</th>
                <th>Tanggal Lahir</th>
                <th>Agama</th>
                <th>Alamat</th>
                <th>No. Telepon</th>
                <th>Email</th>
                <th>Nama Ayah</th>
                <th>Pekerjaan Ayah</th>
                <th>Nama Ibu</th>
                <th>Pekerjaan Ibu</th>
                <th>No. Telp Ortu</th>
                <th>Status Berkas</th>
                <th>Status Seleksi</th>
                <th>Tanggal Daftar</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1;
            while ($row = mysqli_fetch_assoc($result)): 
            ?>
            <tr>
                <td><?php echo $no++; ?></td>
                <td><?php echo $row['no_pendaftaran']; ?></td>
                <td><?php echo $row['nama_lengkap']; ?></td>
                <td><?php echo $row['nisn']; ?></td>
                <td><?php echo $row['nik']; ?></td>
                <td><?php echo $row['jk'] == 'L' ? 'Laki-laki' : 'Perempuan'; ?></td>
                <td><?php echo $row['tempat_lahir']; ?></td>
                <td><?php echo $row['tanggal_lahir']; ?></td>
                <td><?php echo $row['agama']; ?></td>
                <td><?php echo $row['alamat']; ?></td>
                <td><?php echo $row['no_telp']; ?></td>
                <td><?php echo $row['email']; ?></td>
                <td><?php echo $row['nama_ayah']; ?></td>
                <td><?php echo $row['pekerjaan_ayah']; ?></td>
                <td><?php echo $row['nama_ibu']; ?></td>
                <td><?php echo $row['pekerjaan_ibu']; ?></td>
                <td><?php echo $row['no_telp_ortu']; ?></td>
                <td><?php echo strtoupper(str_replace('_', ' ', $row['status_berkas'])); ?></td>
                <td><?php echo strtoupper(str_replace('_', ' ', $row['status'])); ?></td>
                <td><?php echo $row['created_at']; ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>
