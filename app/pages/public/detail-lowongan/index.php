<?php
require_once('./../../../functions/init-conn.php');
require_once('./../../../functions/init-session.php');
require_once('./../../../functions/string-helpers.php');

$id_lowongan = $_GET['id_lowongan'] ?? null;
if (!$id_lowongan) {
    header("Location: /pages/public/landing-page");
}

$getLowonganQueryStr = "SELECT nama_lowongan, poster_lowongan, tanggal_mulai, u.name, p.jenis_kelamin FROM lowongan l JOIN permintaan p ON l.id_permintaan = p.id_permintaan JOIN user u ON p.id_user = u.id_user WHERE id_lowongan = ?";
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

$formPelamaranUrl = "/pages/public/form-pelamaran?id_lowongan=$id_lowongan";
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
    <link id="theme-style" rel="stylesheet" href="/assets/css/public.styles.css">
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
                    <div class="d-flex align-items-center justify-content-center" style="width: 100%">
                        <a href="/pages/public/landing-page">
                            <img src="/assets/images/app-logo.png" alt="logo" style="width: 150px;">
                        </a>
                    </div><!--//site-logo-->
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
                                    <img src="<?= '/assets/uploads/poster/' . $lowongan['poster_lowongan'] ?>" alt=""
                                        style="width: 300px">
                                </div>
                                <div class="col-12 col-lg-8">
                                    <h4 class="heading-level-4">Persyaratan :</h4>
                                    <ul class="article-list">
                                        <li><?= str_replace(',', ' atau ', $lowongan['jenis_kelamin']) ?>
                                            usia minimal <?= $persyaratan['umur'] ?> tahun</li>
                                        <li>Pendidikan minimal <?= $persyaratan['pendidikan'] ?></li>
                                        <li>Memiliki pengalaman kerja selama <?= $persyaratan['pengalaman_kerja'] ?>
                                            tahun</li>
                                    </ul>

                                    <a href="<?= $formPelamaranUrl ?>" class="btn btn-primary">Ajukan Lamaran</a>
                                </div>
                            </div>

                        </article><!--//help-article-->

                    </section><!--//main-section-->
                </div><!--//col-->
            </div><!--//row-->

        </div><!--//container-->
    </div><!--//help-content-wrapper-->

    <!-- Javascript -->
    <script src="/assets/plugins/popper.min.js"></script>
    <script src="/assets/plugins/bootstrap/js/bootstrap.min.js"></script>
    <script
        src="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/extensions/sweetalert2/sweetalert2.min.js"></script>
    <script src="/assets/js/sweet-alert.js"></script>
</body>

</html>
