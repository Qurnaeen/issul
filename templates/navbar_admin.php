<div class="sidebar bg-dark text-white vh-100 position-fixed" style="width: 250px;">
    <div class="p-3">
        <div class="text-center mb-3">
            <img src="<?php echo BASE_URL; ?>public/images/logo.png" alt="Logo" height="60" class="mb-2">
            <h5 class="mb-0">Admin Panel</h5>
        </div>
        <hr>
        
        <div class="mb-3 text-center">
            <i class="bi bi-person-circle fs-1"></i>
            <p class="mb-0 mt-2 small"><?php echo $_SESSION['admin_nama']; ?></p>
            <span class="badge bg-info"><?php echo ucfirst($_SESSION['admin_role']); ?></span>
        </div>
        
        <hr>
        
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link text-white <?php echo (basename($_SERVER['PHP_SELF']) == 'dashboard.php') ? 'active bg-primary' : ''; ?>" 
                   href="<?php echo BASE_URL; ?>admin/dashboard.php">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
            </li>
            
            <li class="nav-item mt-3">
                <small class="text-muted ps-3">DATA SISWA</small>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white <?php echo (strpos($_SERVER['PHP_SELF'], 'siswa') !== false) ? 'active bg-primary' : ''; ?>" 
                   href="<?php echo BASE_URL; ?>admin/siswa/index.php">
                    <i class="bi bi-people"></i> Data Pendaftar
                </a>
            </li>
            
            <li class="nav-item mt-3">
                <small class="text-muted ps-3">LAPORAN</small>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white <?php echo (strpos($_SERVER['PHP_SELF'], 'laporan') !== false) ? 'active bg-primary' : ''; ?>" 
                   href="<?php echo BASE_URL; ?>admin/laporan/index.php">
                    <i class="bi bi-file-earmark-text"></i> Laporan
                </a>
            </li>
            
            <li class="nav-item mt-3">
                <small class="text-muted ps-3">PENGATURAN</small>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="<?php echo BASE_URL; ?>">
                    <i class="bi bi-globe"></i> Lihat Website
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="<?php echo BASE_URL; ?>auth/logout.php">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </a>
            </li>
        </ul>
    </div>
</div>

<!-- Main Content Wrapper -->
<div class="main-content" style="margin-left: 250px;">
    <nav class="navbar navbar-light bg-light border-bottom">
        <div class="container-fluid">
            <span class="navbar-brand mb-0 h1"><?php echo isset($page_title) ? $page_title : 'Admin Panel'; ?></span>
            <span class="text-muted small">
                <i class="bi bi-calendar"></i> <?php echo format_tanggal(time(), true); ?>
            </span>
        </div>
    </nav>
    
    <div class="container-fluid p-4">
