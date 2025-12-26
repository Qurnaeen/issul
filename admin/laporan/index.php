<?php
require_once __DIR__ . '/../../config/session_config.php';
session_start();
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../../helpers/auth_helper.php';
require_once __DIR__ . '/../../helpers/security.php';
require_once __DIR__ . '/../../helpers/tanggal_helper.php';

// Cek login admin
require_login_admin();

$page_title = 'Laporan PPDB';
require_once __DIR__ . '/../../templates/header.php';
require_once __DIR__ . '/../../templates/navbar_admin.php';
?>

<h2 class="mb-4">Laporan PPDB</h2>

<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="bi bi-file-text"></i> Export Laporan</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="card border-success">
                            <div class="card-body text-center">
                                <i class="bi bi-file-excel text-success" style="font-size: 4rem;"></i>
                                <h5 class="mt-3">Export ke Excel</h5>
                                <p class="text-muted">Download data pendaftar dalam format Excel (.xlsx)</p>
                                <a href="export_excel.php" class="btn btn-success">
                                    <i class="bi bi-download"></i> Download Excel
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <div class="card border-danger">
                            <div class="card-body text-center">
                                <i class="bi bi-file-pdf text-danger" style="font-size: 4rem;"></i>
                                <h5 class="mt-3">Export ke PDF</h5>
                                <p class="text-muted">Download laporan dalam format PDF</p>
                                <a href="export_pdf.php" class="btn btn-danger">
                                    <i class="bi bi-download"></i> Download PDF
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
require_once __DIR__ . '/../../templates/footer_admin.php';
?>
