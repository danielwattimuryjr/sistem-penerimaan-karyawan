<?php
require_once('./../../../functions/init-conn.php');
require_once('./../../../functions/init-session.php');
require_once('./../../../functions/string-helpers.php');

$id_lowongan = $_GET['id_lowongan'] ?? null;
$id_user = $_SESSION['user']['id_user'] ?? null;
if (!$id_lowongan) {
    header("Location: /sistem-penerimaan-karyawan/pages/public/landing-page");
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

$getLowonganQueryStr = "SELECT nama_lowongan, deskripsi, poster_lowongan, tanggal_mulai, u.name, p.jenis_kelamin FROM lowongan l JOIN permintaan p ON l.id_permintaan = p.id_permintaan JOIN user u ON p.id_user = u.id_user WHERE id_lowongan = ?";
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
    <title>Grand Pasundan Careers</title>

    <!-- Meta -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Bootstrap Knowledge Base and Help Centre Template">
    <meta name="author" content="Xiaoying Riley at 3rd Wave Media">
    <link rel="shortcut icon" href="favicon.ico">

    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500&display=swap"
        rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <!-- Plugin CSS -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/atom-one-dark-reasonable.min.css">

    <!-- Theme CSS -->
    <link id="theme-style" rel="stylesheet" href="/sistem-penerimaan-karyawan/assets/css/public.styles.css">
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/scss/pages/sweetalert2.scss">
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/extensions/sweetalert2/sweetalert2.min.css">
</head>

<body>
    <div class="page-header-wrapper">
        <div class="page-header-bg-pattern-holder">
            <div class="bg-pattern-top"></div>
            <div class="bg-pattern-bottom"></div>
        </div><!--//page-header-bg-pattern-holder-->

        <header class="header">
            <div class="container">
                <nav class="navbar navbar-expand-lg">
                    <div class="site-logo me-3">
                        <a class="navbar-brand" href="/sistem-penerimaan-karyawan">
                            <img src="/sistem-penerimaan-karyawan/assets/images/app-logo.png" alt="logo"
                                style="width: 100px;">
                        </a>
                    </div><!--//site-logo-->


                    <button class="navbar-toggler collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navigation" aria-controls="navigation" aria-expanded="false"
                        aria-label="Toggle navigation">
                        <span> </span>
                        <span> </span>
                        <span> </span>
                    </button>

                    <div class="collapse navbar-collapse ms-auto" id="navigation">
                        <ul class="navbar-nav ms-auto align-items-lg-center">
                            <?php if (isset($_SESSION['user'])): ?>
                                <li class="nav-item dropdown me-lg-4">
                                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                                        aria-expanded="false"><?= $_SESSION['user']['name'] ?? 'name' ?></a>
                                    <ul class="dropdown-menu dropdown-menu-lg-end rounded shadow">
                                        <li><a class="dropdown-item"
                                                href="/sistem-penerimaan-karyawan/pages/pelamar/profile">Profile</a></li>
                                        <li><a class="dropdown-item text-danger"
                                                href="/sistem-penerimaan-karyawan/pages/public/landing-page/logout.php">Logout</a>
                                        </li>
                                    </ul>
                                </li>
                            <?php else: ?>
                                <li class="nav-item me-lg-4">
                                    <a class="nav-link" href="/sistem-penerimaan-karyawan/pages/public/sign-in">Sign In</a>
                                </li>
                                <li class="nav-item pt-3 pt-lg-0">
                                    <a class="nav-link"
                                        href="/sistem-penerimaan-karyawan/pages/public/register">Register</a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </nav>
            </div><!--//container-->

        </header><!--//header-->
    </div><!--//page-header-wrapper-->

    <div class="help-content-wrapper theme-section pt-4">
        <div class="container">
            <div class="row">
                <div class="col">
                    <section class="main-section order-lg-last">
                        <article class="help-article mb-5">
                            <header class="article-header mb-5">
                                <h1 class="heading-level-1 text-center mb-2">
                                    <?= toTitleCase($lowongan['nama_lowongan']) ?>
                                </h1>
                                <div class="article-meta mx-auto d-flex justify-content-center align-items-center">
                                    <div class="meta-info-wrapper text-center">
                                        <div class="meta-author"><?= toTitleCase($lowongan['name']) ?></div>
                                        <div class="meta-time">Tanggal Mulai:
                                            <?= date('j F Y', strtotime($lowongan['tanggal_mulai'])) ?>
                                        </div>
                                    </div>
                                </div><!--//article-meta-->
                            </header>
                            <div class="row">
                                <div class="col-12 col-lg-4">
                                    <img src="<?= '/sistem-penerimaan-karyawan/assets/uploads/poster/' . $lowongan['poster_lowongan'] ?>"
                                        alt="" style="width: 300px">
                                </div>
                                <div class="col-12 col-lg-8">
                                    <?= $lowongan['deskripsi'] ?>

                                    <h4 class="heading-level-4">Persyaratan :</h4>
                                    <ul class="article-list">
                                        <li><?= str_replace(',', ' atau ', $lowongan['jenis_kelamin']) ?>
                                            usia maksimal <?= $persyaratan['umur'] ?> tahun</li>
                                        <li>Pendidikan minimal <?= $persyaratan['pendidikan'] ?></li>
                                        <li>Memiliki pengalaman kerja selama <?= $persyaratan['pengalaman_kerja'] ?>
                                            tahun</li>
                                    </ul>

                                    <?php if (isset($_SESSION['user'])): ?>
                                        <a href="<?= $formPelamaranUrl ?>"
                                            class="btn btn-primary <?= $isApplied ? 'disabled' : '' ?>"><?= $isApplied ? 'Sudah Mengajukan' : 'Ajukan Lamaran' ?></a>
                                    <?php endif; ?>
                                </div>
                            </div>

                        </article><!--//help-article-->

                    </section><!--//main-section-->
                </div><!--//col-->
            </div><!--//row-->

        </div><!--//container-->
    </div><!--//help-content-wrapper-->

    <!-- Javascript -->
    <script src="/sistem-penerimaan-karyawan/assets/plugins/popper.min.js"></script>
    <script src="/sistem-penerimaan-karyawan/assets/plugins/bootstrap/js/bootstrap.min.js"></script>
    <script
        src="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/extensions/sweetalert2/sweetalert2.min.js"></script>
    <script src="/sistem-penerimaan-karyawan/assets/js/sweet-alert.js"></script>
</body>

</html>