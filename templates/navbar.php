<nav class="navbar navbar-expand-lg navbar-dark bg-primary sticky-top shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold d-flex align-items-center" href="<?php echo BASE_URL; ?>">
            <img src="<?php echo BASE_URL; ?>public/images/logo.png" alt="Logo" height="40" class="me-2">
            PPDB <?php echo TAHUN_AJARAN; ?>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo BASE_URL; ?>">
                        <i class="bi bi-house"></i> Beranda
                    </a>
                </li>
                
                <?php if (cek_login_siswa()): ?>
                    <!-- Menu untuk siswa yang sudah login -->
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>siswa/dashboard_siswa.php">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>pendaftaran/form.php">
                            <i class="bi bi-file-text"></i> Formulir
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>siswa/cek_status.php">
                            <i class="bi bi-check-circle"></i> Status
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <?php 
                        $nav_foto = '';
                        if (isset($conn) && isset($_SESSION['siswa_id'])) {
                            $stmt_nav = mysqli_prepare($conn, "SELECT file_path FROM berkas WHERE siswa_id = ? AND jenis_berkas = 'foto'");
                            if ($stmt_nav) {
                                mysqli_stmt_bind_param($stmt_nav, "i", $_SESSION['siswa_id']);
                                mysqli_stmt_execute($stmt_nav);
                                $res_nav = mysqli_stmt_get_result($stmt_nav);
                                if ($row_nav = mysqli_fetch_assoc($res_nav)) {
                                    $nav_foto = $row_nav['file_path'];
                                }
                                mysqli_stmt_close($stmt_nav);
                            }
                        }
                        ?>
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" 
                           data-bs-toggle="tooltip" data-bs-placement="bottom" title="<?php echo $_SESSION['siswa_nama']; ?>">
                            <?php if (!empty($nav_foto) && file_exists(__DIR__ . '/../public/' . $nav_foto)): ?>
                                <img src="<?php echo BASE_URL . 'public/' . $nav_foto; ?>" alt="Profil" class="rounded-circle" style="width: 30px; height: 30px; object-fit: cover; border: 2px solid white;">
                            <?php else: ?>
                                <i class="bi bi-person-circle" style="font-size: 1.5rem;"></i>
                            <?php endif; ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>auth/logout.php">
                                <i class="bi bi-box-arrow-right"></i> Logout
                            </a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <!-- Menu untuk pengunjung -->
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>auth/login.php">
                            <i class="bi bi-box-arrow-in-right"></i> Login
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-light text-primary ms-2" href="<?php echo BASE_URL; ?>auth/registrasi.php">
                            <i class="bi bi-person-plus"></i> Daftar
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
