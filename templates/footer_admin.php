    </div> <!-- End main-content container -->
</div> <!-- End main-content wrapper -->

<!-- Footer Admin -->
<footer class="bg-dark text-white py-4" style="margin-left: 250px;">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                <h5><?php echo NAMA_SEKOLAH; ?></h5>
                <p class="mb-1"><i class="bi bi-geo-alt"></i> <?php echo ALAMAT_SEKOLAH; ?></p>
                <p class="mb-1"><i class="bi bi-telephone"></i> <?php echo TELP_SEKOLAH; ?></p>
                <p><i class="bi bi-envelope"></i> <?php echo EMAIL_SEKOLAH; ?></p>
            </div>
            <div class="col-md-6 text-md-end">
                <p class="mb-0">&copy; <?php echo date('Y'); ?> PPDB <?php echo NAMA_SEKOLAH; ?></p>
                <p class="mb-0">Tahun Ajaran <?php echo TAHUN_AJARAN; ?></p>
            </div>
        </div>
    </div>
</footer>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- DataTables JS -->
<?php if (isset($use_datatables) && $use_datatables): ?>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<?php endif; ?>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Custom JS -->
<script src="<?php echo BASE_URL; ?>public/js/script.js"></script>

<?php if (isset($extra_js)): echo $extra_js; endif; ?>
</body>
</html>
