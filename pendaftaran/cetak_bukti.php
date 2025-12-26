<?php
require_once __DIR__ . '/../config/session_config.php';
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../helpers/auth_helper.php';
require_once __DIR__ . '/../helpers/security.php';
require_once __DIR__ . '/../helpers/tanggal_helper.php';

// Cek login siswa
require_login_siswa();

// Get data siswa
$siswa = get_siswa_data($conn);

// Get foto profil
$query_foto = "SELECT file_path FROM berkas WHERE siswa_id = ? AND jenis_berkas = 'foto'";
$stmt_foto = mysqli_prepare($conn, $query_foto);
mysqli_stmt_bind_param($stmt_foto, "i", $siswa['id']);
mysqli_stmt_execute($stmt_foto);
$result_foto = mysqli_stmt_get_result($stmt_foto);
$foto_profil = null;
if ($row_foto = mysqli_fetch_assoc($result_foto)) {
    $foto_profil = $row_foto['file_path'];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kartu Bukti Pendaftaran - <?php echo escape_output($siswa['nama_lengkap']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            background-color: #f8f9fa;
        }
        .cetak-container {
            width: 210mm; /* A4 width */
            min-height: 297mm; /* A4 height */
            margin: 20px auto;
            background: white;
            padding: 20px 40px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            position: relative;
        }
        .header {
            border-bottom: 3px double #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
        }
        .logo {
            width: 80px;
            height: auto;
            margin-right: 20px;
        }
        .school-info {
            text-align: center;
            flex-grow: 1;
        }
        .school-info h2 {
            margin: 0;
            font-weight: bold;
            font-size: 24px;
            text-transform: uppercase;
        }
        .school-info h4 {
            margin: 5px 0;
            font-size: 18px;
        }
        .school-info p {
            margin: 0;
            font-size: 14px;
        }
        .judul-kartu {
            text-align: center;
            margin: 30px 0;
            text-transform: uppercase;
            font-weight: bold;
            text-decoration: underline;
            font-size: 20px;
        }
        .content {
            margin-bottom: 40px;
        }
        .foto-box {
            width: 3cm;
            height: 4cm;
            border: 1px solid #000;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #eee;
            overflow: hidden;
            margin-bottom: 10px;
        }
        .foto-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .data-table td {
            padding: 8px 5px;
            vertical-align: top;
        }
        .footer-signature {
            margin-top: 50px;
            text-align: right;
        }
        .signature-box {
            display: inline-block;
            text-align: center;
            width: 200px;
        }
        .signature-space {
            height: 80px;
        }
        
        @media print {
            body {
                background: none;
                margin: 0;
            }
            .cetak-container {
                width: 100%;
                margin: 0;
                box-shadow: none;
                padding: 0;
            }
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>

    <div class="container text-center mt-3 no-print">
        <button class="btn btn-primary" onclick="window.print()">
            <i class="bi bi-printer"></i> Cetak Kartu
        </button>
        <a href="../siswa/dashboard_siswa.php" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="cetak-container">
        <!-- Header -->
        <div class="header">
            <img src="<?php echo BASE_URL; ?>public/images/logo_sekolah.png" alt="Logo" class="logo">
            <div class="school-info">
                <h2><?php echo NAMA_SEKOLAH; ?></h2>
                <h4>PANITIA PENERIMAAN PESERTA DIDIK BARU</h4>
                <p><?php echo ALAMAT_SEKOLAH; ?></p>
                <p>Telp: <?php echo TELP_SEKOLAH; ?> | Email: <?php echo EMAIL_SEKOLAH; ?></p>
            </div>
        </div>

        <h3 class="judul-kartu">KARTU BUKTI PENDAFTARAN</h3>

        <!-- Content -->
        <div class="row content">
            <div class="col-3 text-center">
                <div class="foto-box mx-auto">
                    <?php if ($foto_profil && file_exists(__DIR__ . '/../public/' . $foto_profil)): ?>
                        <img src="<?php echo BASE_URL . 'public/' . $foto_profil; ?>" alt="Foto Siswa">
                    <?php else: ?>
                        <div class="text-muted p-3">Pas Foto 3x4</div>
                    <?php endif; ?>
                </div>
                <small>Foto Peserta</small>
            </div>
            <div class="col-9">
                <table class="table table-borderless data-table">
                    <tr>
                        <td width="200"><strong>Nomor Pendaftaran</strong></td>
                        <td width="10">:</td>
                        <td><strong><?php echo escape_output($siswa['no_pendaftaran']); ?></strong></td>
                    </tr>
                    <tr>
                        <td><strong>Nama Lengkap</strong></td>
                        <td>:</td>
                        <td><?php echo escape_output($siswa['nama_lengkap']); ?></td>
                    </tr>
                    <tr>
                        <td><strong>NISN</strong></td>
                        <td>:</td>
                        <td><?php echo escape_output($siswa['nisn']); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Tempat, Tgl Lahir</strong></td>
                        <td>:</td>
                        <td><?php echo escape_output($siswa['tempat_lahir']) . ', ' . format_tanggal($siswa['tanggal_lahir']); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Jenis Kelamin</strong></td>
                        <td>:</td>
                        <td><?php echo ($siswa['jk'] == 'L' ? 'Laki-laki' : 'Perempuan'); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Alamat</strong></td>
                        <td>:</td>
                        <td><?php echo nl2br(escape_output($siswa['alamat'])); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Tanggal Daftar</strong></td>
                        <td>:</td>
                        <td><?php echo format_datetime($siswa['created_at']); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Status Seleksi</strong></td>
                        <td>:</td>
                        <td>
                            <?php 
                            if ($siswa['status'] == 'lulus') {
                                echo '<span class="fw-bold text-uppercase">LULUS</span>';
                            } elseif ($siswa['status'] == 'tidak_lulus') {
                                echo '<span class="fw-bold text-uppercase">TIDAK LULUS</span>';
                            } else {
                                echo 'MENUNGGU PENGUMUMAN';
                            }
                            ?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        
        <div class="alert alert-light border border-dark rounded-0 p-3 mb-4">
            <strong>Catatan Penting:</strong>
            <ul class="mb-0 small">
                <li>Kartu ini adalah bukti sah pendaftaran peserta didik baru.</li>
                <li>Simpan kartu ini dengan baik dan bawa saat verifikasi berkas fisik.</li>
                <li>Pantau terus informasi terbaru melalui website atau kontak sekolah.</li>
            </ul>
        </div>

        <!-- Signature -->
        <div class="footer-signature">
            <div class="signature-box">
                <p>Pamekasan, <?php echo format_tanggal(date('Y-m-d')); ?></p>
                <p>Panitia PPDB</p>
                <div class="signature-space"></div>
                <p><strong>(.......................................)</strong></p>
            </div>
        </div>
    </div>

    <script>
        // Auto print on load
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500);
        }
    </script>
</body>
</html>
