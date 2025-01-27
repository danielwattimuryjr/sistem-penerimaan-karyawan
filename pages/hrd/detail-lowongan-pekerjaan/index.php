<?php
require_once('./../../../functions/init-session.php');
require_once('./../../../functions/init-conn.php');
require_once('./../../../functions/page-protection.php');

$id_lowongan = $_GET['id_lowongan'] ?? null;
if (!$id_lowongan) {
    header("Location: /sistem-penerimaan-karyawan/pages/hrd/beranda");
}

$getLowonganQueryStr = "SELECT id_lowongan, nama_lowongan, deskripsi, poster_lowongan FROM lowongan LIMIT 1";
$getLowonganResult = $conn->query($getLowonganQueryStr);
$lowongan = $getLowonganResult->fetch_assoc();

$getPersyaratanStr = "SELECT pengalaman_kerja, umur, pendidikan FROM persyaratan WHERE id_lowongan = ? LIMIT 1";
$stmt = $conn->prepare($getPersyaratanStr);
$stmt->bind_param("i", $id_lowongan);
$stmt->execute();
$getPersyaratanResult = $stmt->get_result();
$persyaratan = $getPersyaratanResult->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $lowongan['nama_lowongan'] ?? 'Detail Lowongan' ?></title>


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

<body>
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
                <h3>Beranda</h3>
            </div>
            <div class="page-content">
                <section class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div
                                    class="d-flex flex-column flex-lg-row gap-3 align-items-center align-items-lg-start">
                                    <img src="<?= '/sistem-penerimaan-karyawan/assets/uploads/poster/' . $lowongan['poster_lowongan'] ?>"
                                        alt="" style="width: 300px">

                                    <div class="d-flex flex-column text-start">
                                        <h2><?= $lowongan['nama_lowongan'] ?></h2>
                                        <h5>Deskripsi Pekerjaan :</h5>
                                        <p><?= $lowongan['deskripsi'] ?></p>

                                        <h5>Persyaratan :</h5>
                                        <ul>
                                            <li>Pria/Wanita usia maksimal <?= $persyaratan['umur'] ?> tahun</li>
                                            <li>Pendidikan minimal <?= $persyaratan['pendidikan'] ?></li>
                                            <li>Memiliki pengalaman kerja selama <?= $persyaratan['pengalaman_kerja'] ?>
                                                tahun</li>
                                        </ul>

                                        <a href="/sistem-penerimaan-karyawan/pages/hrd/form-edit-lowongan?id_lowongan=<?= $lowongan['id_lowongan'] ?>"
                                            class="btn btn-warning">Update Lamaran</a>
                                    </div>
                                </div>
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
    <script
        src="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/extensions/sweetalert2/sweetalert2.min.js"></script>
    <script src="/sistem-penerimaan-karyawan/assets/js/sweet-alert.js"></script>
</body>

</html>