<?php
require_once __DIR__ . '/../config/session_config.php';
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../helpers/auth_helper.php';
require_once __DIR__ . '/../helpers/security.php';
require_once __DIR__ . '/../helpers/tanggal_helper.php';

// Cek login admin
require_login_admin();

// Get statistik
$stats = [];

// Total pendaftar
$query_total = "SELECT COUNT(*) as total FROM siswa";
$result_total = mysqli_query($conn, $query_total);
$stats['total'] = mysqli_fetch_assoc($result_total)['total'];

// Per status
$query_status = "SELECT status, COUNT(*) as jumlah FROM siswa GROUP BY status";
$result_status = mysqli_query($conn, $query_status);
while ($row = mysqli_fetch_assoc($result_status)) {
    $stats['status_' . $row['status']] = $row['jumlah'];
}

// Berkas terverifikasi
$query_verified = "SELECT COUNT(DISTINCT id) as total FROM siswa WHERE status_berkas = 'terverifikasi'";
$result_verified = mysqli_query($conn, $query_verified);
$stats['verified'] = mysqli_fetch_assoc($result_verified)['total'];

// Pendaftar hari ini
$query_today = "SELECT COUNT(*) as total FROM siswa WHERE DATE(created_at) = CURDATE()";
$result_today = mysqli_query($conn, $query_today);
$stats['today'] = mysqli_fetch_assoc($result_today)['total'];

// Recent activities
$query_recent = "SELECT s.nama_lengkap, s.no_pendaftaran, s.created_at 
                 FROM siswa s 
                 ORDER BY s.created_at DESC 
                 LIMIT 10";
$result_recent = mysqli_query($conn, $query_recent);

$page_title = 'Dashboard Admin';
require_once __DIR__ . '/../templates/header.php';
require_once __DIR__ . '/../templates/navbar_admin.php';
?>

<!-- Dashboard Content -->
<h2 class="mb-4">Dashboard</h2>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card bg-primary text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase mb-1">Total Pendaftar</h6>
                        <h2 class="mb-0"><?php echo $stats['total']; ?></h2>
                    </div>
                    <div>
                        <i class="bi bi-people" style="font-size: 3rem; opacity: 0.5;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card bg-success text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase mb-1">Lulus</h6>
                        <h2 class="mb-0"><?php echo isset($stats['status_lulus']) ? $stats['status_lulus'] : 0; ?></h2>
                    </div>
                    <div>
                        <i class="bi bi-check-circle" style="font-size: 3rem; opacity: 0.5;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card bg-warning text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase mb-1">Pending</h6>
                        <h2 class="mb-0"><?php echo isset($stats['status_pending']) ? $stats['status_pending'] : 0; ?></h2>
                    </div>
                    <div>
                        <i class="bi bi-clock" style="font-size: 3rem; opacity: 0.5;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card bg-info text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase mb-1">Terverifikasi</h6>
                        <h2 class="mb-0"><?php echo $stats['verified']; ?></h2>
                    </div>
                    <div>
                        <i class="bi bi-shield-check" style="font-size: 3rem; opacity: 0.5;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts & Recent Activity -->
<div class="row">
    <div class="col-md-8 mb-4">
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="bi bi-bar-chart"></i> Statistik Pendaftaran</h5>
            </div>
            <div class="card-body">
                <canvas id="chartStatus" height="100"></canvas>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="bi bi-clock-history"></i> Pendaftar Terbaru</h5>
            </div>
            <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                <div class="list-group list-group-flush">
                    <?php while ($recent = mysqli_fetch_assoc($result_recent)): ?>
                    <div class="list-group-item px-0">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="mb-0"><?php echo escape_output($recent['nama_lengkap']); ?></h6>
                                <small class="text-muted"><?php echo $recent['no_pendaftaran']; ?></small>
                            </div>
                            <small class="text-muted"><?php echo time_ago($recent['created_at']); ?></small>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="bi bi-lightning"></i> Aksi Cepat</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-2">
                        <a href="siswa/index.php" class="btn btn-primary w-100">
                            <i class="bi bi-people"></i> Lihat Semua Pendaftar
                        </a>
                    </div>
                    <div class="col-md-3 mb-2">
                        <a href="siswa/index.php?status=pending" class="btn btn-warning w-100">
                            <i class="bi bi-hourglass"></i> Pending Verifikasi
                        </a>
                    </div>
                    <div class="col-md-3 mb-2">
                        <a href="laporan/index.php" class="btn btn-info text-white w-100">
                            <i class="bi bi-file-text"></i> Laporan
                        </a>
                    </div>
                    <div class="col-md-3 mb-2">
                        <a href="laporan/export_excel.php" class="btn btn-success w-100">
                            <i class="bi bi-file-excel"></i> Export Excel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php
$extra_js = "
<script src='https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js'></script>
<script>
// Chart Status
const ctx = document.getElementById('chartStatus').getContext('2d');
const chartStatus = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Pending', 'Lulus', 'Tidak Lulus'],
        datasets: [{
            label: 'Jumlah Pendaftar',
            data: [
                " . (isset($stats['status_pending']) ? $stats['status_pending'] : 0) . ",
                " . (isset($stats['status_lulus']) ? $stats['status_lulus'] : 0) . ",
                " . (isset($stats['status_tidak_lulus']) ? $stats['status_tidak_lulus'] : 0) . "
            ],
            backgroundColor: [
                'rgba(255, 193, 7, 0.8)',
                'rgba(25, 135, 84, 0.8)',
                'rgba(220, 53, 69, 0.8)'
            ],
            borderColor: [
                'rgb(255, 193, 7)',
                'rgb(25, 135, 84)',
                'rgb(220, 53, 69)'
            ],
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
            }
        }
    }
});
</script>
";

require_once __DIR__ . '/../templates/footer_admin.php';
?>
