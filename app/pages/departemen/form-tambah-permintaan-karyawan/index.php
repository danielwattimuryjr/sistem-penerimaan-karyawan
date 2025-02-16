<?php
require_once('./../../../functions/init-session.php');
require_once('./../../../functions/init-conn.php');
if (!$_SESSION['user']) {
    header("Location: /pages/auth/sign-in");
}

$queryStr = "SELECT id_user, name FROM user WHERE role = 'Departement'";

$stmt = $conn->prepare($queryStr);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Permintaan Karyawan</title>

    <link rel="shortcut icon" href="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/compiled/svg/favicon.svg"
        type="image/x-icon">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/compiled/css/app.css">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/compiled/css/iconly.css">
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/scss/pages/sweetalert2.scss">
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/extensions/sweetalert2/sweetalert2.min.css">
</head>

<body>
    
    <!-- Start content here -->

    <div id="app">
        <div id="sidebar">
            <?php require_once('./../_components/sidebar.php'); ?>
        </div>
        <div id="main">
            <header class="mb-3">
                <a href="#" class="burger-btn d-block d-xl-none">
                    <i class="bi bi-justify fs-3"></i>
                </a>
            </header>
            <!-- Content -->
            <div class="page-heading">
                <h5 class="card-title">Formulir Permintaan Karyawan</h5>
            </div>
            <div class="page-content">
                <section class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <form action="store-permintaan-request.php" method="POST">
                                    <div class="mb-3">
                                        <label class="form-label">Tanggal Permintaan</label>
                                        <input type="date" class="form-control" name="tanggal_permintaan" required
                                            readonly value="<?php echo date('Y-m-d'); ?>">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Department</label>
                                        <input type="hidden" name="id_divisi"
                                            value="<?= $_SESSION['user']['id_user'] ?>">
                                        <input type="text" class="form-control" readonly
                                            value="<?= $_SESSION['user']['name'] ?>">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Posisi</label>
                                        <input type="text" class="form-control" name="posisi" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Jumlah Permintaan</label>
                                        <input type="number" class="form-control" min="0" name="jumlah_permintaan"
                                            required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Jenis Kelamin</label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="Laki-laki"
                                                name="jenis_kelamin[]" id="laki-laki">
                                            <label class="form-check-label" for="laki-laki">
                                                Laki-laki
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="Perempuan"
                                                name="jenis_kelamin[]" id="perempuan">
                                            <label class="form-check-label" for="perempuan">
                                                Perempuan
                                            </label>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="" class="form-label">Status Kerja</label>
                                        <select name="status_kerja" id="" class="form-control" required>
                                            <option selected disabled>-- PILIH STATUS KERJA --</option>
                                            <option value="daily-worker">Daily Worker</option>
                                            <option value="karyawan-kontrak">Karyawan Kontrak</option>
                                        </select>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-12 col-lg-6">
                                            <label for="" class="form-label">Tanggal Mulai</label>
                                            <input type="date" name="tanggal_mulai" id="" class="form-control"
                                                min="<?php echo date('Y-m-d'); ?>" required>
                                        </div>
                                        <div class="col-12 col-lg-6">
                                            <label for="" class="form-label">Tanggal Selesai</label>
                                            <input type="date" name="tanggal_selesai" id="" class="form-control"
                                                min="<?php echo date('Y-m-d'); ?>">
                                            <div class="form-text">Opsional</div>
                                        </div>
                                    </div>
                                    
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
            <!-- End Content -->
        </div>
    </div>

    <!-- End content -->
    
    <script
        src="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/compiled/js/app.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/extensions/tinymce/tinymce.min.js"></script>
    <script src="/assets/js/tiny-mce.js"></script>
    <script
        src="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/extensions/sweetalert2/sweetalert2.min.js"></script>
    <script src="/assets/js/sweet-alert.js"></script>
</body>

</html>