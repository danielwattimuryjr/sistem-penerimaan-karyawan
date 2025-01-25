<?php
require_once('./../../../functions/init-session.php');
require_once('./../../../functions/init-conn.php');
require_once('./../../../functions/page-protection.php');

if (!isset($_GET['id_pelamaran']) || empty($_GET['id_pelamaran'])) {
    $type = 'error';
    $message = "ID pelamar tidak ditemukan";
    header('Location: /sistem-penerimaan-karyawan/pages/hrd/data-pelamar?type=error&message=' . urlencode($message));
    exit();
}
$id_pelamaran = $_GET['id_pelamaran'];

$queryStr = "SELECT
    u.nama_lengkap
FROM
    user u
JOIN
    profile p ON u.id_user = p.id_user
JOIN
    pelamaran pel ON u.id_user = pel.id_user
WHERE
    pel.id_pelamaran = ?";
$stmt = $conn->prepare($queryStr);
$stmt->bind_param("i", $id_pelamaran);
$stmt->execute();
$result = $stmt->get_result();
$dataPelamar = $result->fetch_assoc();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $dataPelamar['nama_lengkap'] . ' | Penilaian Pelamar' ?></title>

    <link rel="shortcut icon" href="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/compiled/svg/favicon.svg"
        type="image/x-icon">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/compiled/css/app.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/compiled/css/app-dark.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/compiled/css/iconly.css">
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/scss/pages/sweetalert2.scss">
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/extensions/sweetalert2/sweetalert2.min.css">
</head>

<>
    <script src="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/static/js/initTheme.js"></script>
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
                <h3>Penilaian Pelamar</h3>
            </div>
            <div class="page-content">
                <section class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <form method="POST" action="post-penilaian-request.php">
                                    <input type="hidden" name="id_pelamaran" value="<?= $id_pelamaran ?>">
                                    <div class="mb-3">
                                        <label class="form-label">Nama Lengkap</label>
                                        <input type="text" class="form-control"
                                            value="<?= $dataPelamar['nama_lengkap'] ?>" disabled>
                                    </div>

                                    <div class="mb-3">
                                        <label for="" class="form-label">Tes Tertulis</label>
                                        <input type="number" name="nilai_tes_tertulis" id="" class="form-control"
                                            min="1" max="100" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Tes Wawancara</label>
                                        <select class="form-select" name="nilai_tes_wawancara" required>
                                            <option selected disabled>-- PILIH PENILAIAN --</option>
                                            <option value="Sangat Kurang">Sangat Kurang</option>
                                            <option value="Kurang">Kurang</option>
                                            <option value="Cukup">Cukup</option>
                                            <option value="Baik">Baik</option>
                                            <option value="Sangat Baik">Sangat Baik</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Tes Praktek</label>
                                        <select class="form-select" name="nilai_tes_praktek" required>
                                            <option selected disabled>-- PILIH PENILAIAN --</option>
                                            <option value="Sangat Kurang">Sangat Kurang</option>
                                            <option value="Kurang">Kurang</option>
                                            <option value="Cukup">Cukup</option>
                                            <option value="Baik">Baik</option>
                                            <option value="Sangat Baik">Sangat Baik</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Tes Psikotes</label>
                                        <input type="number" name="nilai_tes_psikotes" id="" class="form-control"
                                            min="1" max="100" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Tes Kesehatan</label>
                                        <select class="form-select" name="nilai_tes_kesehatan" required>
                                            <option selected disabled>-- PILIH PENILAIAN --</option>
                                            <option value="Sangat Kurang">Sangat Kurang</option>
                                            <option value="Kurang">Kurang</option>
                                            <option value="Cukup">Cukup</option>
                                            <option value="Baik">Baik</option>
                                            <option value="Sangat Baik">Sangat Baik</option>
                                        </select>
                                    </div>

                                    <button type="submit" class="btn btn-primary">Simpan</button>
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
    <script src="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/static/js/components/dark.js"></script>
    <script
        src="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/compiled/js/app.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/extensions/tinymce/tinymce.min.js"></script>
    <script src="/sistem-penerimaan-karyawan/assets/js/tiny-mce.js"></script>
    <script
        src="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/extensions/sweetalert2/sweetalert2.min.js"></script>
    <script src="/sistem-penerimaan-karyawan/assets/js/sweet-alert.js"></script>
    </body>

</html>