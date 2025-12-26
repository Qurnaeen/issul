<?php
require_once __DIR__ . '/../../config/session_config.php';
session_start();
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../../helpers/auth_helper.php';
require_once __DIR__ . '/../../helpers/tanggal_helper.php';

// Cek login admin
require_login_admin();

// Query data
$query = "SELECT s.*, a.email FROM siswa s 
          LEFT JOIN akun_siswa a ON s.id = a.siswa_id 
          ORDER BY s.created_at DESC";
$result = mysqli_query($conn, $query);

$jml_pendaftar = mysqli_num_rows($result);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Data Pendaftar PPDB - <?php echo NAMA_SEKOLAH; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 3px double #000;
            padding-bottom: 10px;
        }
        .logo {
            width: 80px;
            position: absolute;
            left: 30px;
            top: 20px;
        }
        .header h2 {
            margin: 0;
            text-transform: uppercase;
            font-weight: bold;
            font-size: 18pt;
        }
        .header h3 {
            margin: 5px 0;
            font-size: 14pt;
        }
        .header p {
            margin: 0;
            font-size: 11pt;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px; /* Increased padding */
            font-size: 10pt; /* Adjusted font size */
            vertical-align: middle;
        }
        th {
            background-color: #f2f2f2;
            text-align: center;
            font-weight: bold;
        }
        .text-center { text-align: center; }
        .text-nowrap { white-space: nowrap; }
        
        .footer-signature {
            margin-top: 40px;
            float: right;
            text-align: center;
            width: 200px;
            page-break-inside: avoid;
        }
        .signature-space {
            height: 70px;
        }
        
        @media print {
            @page {
                size: landscape;
                margin: 1cm;
            }
            body {
                margin: 0;
                padding: 0;
            }
            .no-print {
                display: none !important;
            }
            table {
                page-break-inside: auto;
            }
            tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }
        }
    </style>
</head>
<body>

    <div class="container-fluid no-print mb-3 mt-3">
        <button onclick="window.print()" class="btn btn-primary">
            <i class="bi bi-printer"></i> Cetak / Simpan PDF
        </button>
        <a href="index.php" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <!-- Header Laporan -->
    <div class="header position-relative">
        <img src="<?php echo BASE_URL; ?>public/images/logo_sekolah.png" alt="Logo" class="logo">
        <div style="margin-left: 0;"> <!-- Center text regardless of logo -->
            <h2>LAPORAN DATA PENDAFTAR</h2>
            <h3>PPDB <?php echo NAMA_SEKOLAH; ?></h3>
            <p><?php echo ALAMAT_SEKOLAH; ?></p>
            <p>Tahun Ajaran <?php echo TAHUN_AJARAN; ?></p>
        </div>
    </div>

    <div class="mb-3">
        <strong>Total Pendaftar:</strong> <?php echo $jml_pendaftar; ?> Siswa
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="12%">No. Daftar</th>
                <th>Nama Lengkap</th>
                <th width="12%">NISN</th>
                <th width="8%">JK</th>
                <th width="10%">Status</th>
                <th width="12%">Tgl Daftar</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1;
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)): 
            ?>
            <tr>
                <td class="text-center"><?php echo $no++; ?></td>
                <td class="text-nowrap"><?php echo htmlspecialchars($row['no_pendaftaran']); ?></td>
                <td><?php echo htmlspecialchars($row['nama_lengkap']); ?></td>
                <td class="text-center"><?php echo htmlspecialchars($row['nisn']); ?></td>
                <td class="text-center"><?php echo $row['jk'] == 'L' ? 'Lk' : 'Pr'; ?></td>
                <!-- Removed Asal Sekolah column -->
                <td class="text-center">
                    <?php 
                    $status_map = [
                        'pending' => 'Menunggu',
                        'lulus' => 'Lulus',
                        'tidak_lulus' => 'Tidak Lulus'
                    ];
                    echo isset($status_map[$row['status']]) ? $status_map[$row['status']] : ucfirst($row['status']); 
                    ?>
                </td>
                <td class="text-center"><?php echo date('d/m/Y', strtotime($row['created_at'])); ?></td>
            </tr>
            <?php 
                endwhile;
            } else {
                echo '<tr><td colspan="7" class="text-center">Belum ada data pendaftar.</td></tr>';
            }
            ?>
        </tbody>
    </table>

    <div class="footer-signature">
        <p>Pamekasan, <?php echo format_tanggal(date('Y-m-d')); ?></p>
        <p>Ketua Panitia PPDB</p>
        <div class="signature-space"></div>
        <p><strong>(_______________________)</strong></p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto print
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500);
        }
    </script>
</body>
</html>
