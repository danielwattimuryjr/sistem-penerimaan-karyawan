<?php
require_once('./../../../functions/init-session.php');
require_once('./../../../functions/init-conn.php');
require_once('./../../../functions/page-protection.php');

$id_lowongan = $_GET['id_lowongan'] ?? null;
$id_user = $_SESSION['user']['id_user'];
if (!$id_lowongan) {
    header("Location: /sistem-penerimaan-karyawan/pages/departemen/beranda");
}

$checkPelamaranQueryStr = "
    SELECT COUNT(*) as total
    FROM pelamaran
    WHERE id_user = ? AND id_lowongan = ?
";
$checkPelamaranStmt = $conn->prepare($checkPelamaranQueryStr);
$checkPelamaranStmt->bind_param('ii', $id_user, $id_lowongan);
$checkPelamaranStmt->execute();
$checkPelamaranResult = $checkPelamaranStmt->get_result();
$checkPelamaran = $checkPelamaranResult->fetch_assoc();

$getLowonganQueryStr = "SELECT nama_lowongan, deskripsi, poster_lowongan FROM lowongan WHERE id_lowongan = ?";
$getLowonganStmt = $conn->prepare($getLowonganQueryStr);
$getLowonganStmt->bind_param('i', $id_lowongan);
$getLowonganStmt->execute();
$getLowonganResult = $getLowonganStmt->get_result();
$lowongan = $getLowonganResult->fetch_assoc();

$getPersyaratanStr = "SELECT pengalaman_kerja, umur, pendidikan FROM persyaratan WHERE id_lowongan = ? LIMIT 1";
$stmt = $conn->prepare($getPersyaratanStr);
$stmt->bind_param("i", $id_lowongan);
$stmt->execute();
$getPersyaratanResult = $stmt->get_result();
$persyaratan = $getPersyaratanResult->fetch_assoc();

$isApplied = ($checkPelamaran['total'] > 0);
$formPelamaranUrl = "/sistem-penerimaan-karyawan/pages/pelamar/form-pelamaran?id_lowongan=$id_lowongan";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $lowongan['nama_lowongan'] ?? 'Detail' ?></title>

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
                <h3>Detail Lowongan Pekerjaan</h3>
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
                                            <li><?= $persyaratan['pengalaman_kerja'] ?></li>
                                        </ul>

                                        <a href="<?= $formPelamaranUrl ?>"
                                            class="btn btn-primary <?= $isApplied ? 'disabled' : '' ?>">Ajukan
                                            Lamaran</a>

                                        <?= $isApplied ? '<p class="form-text ">Kamu sudah mendaftar</p>' : '' ?>
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