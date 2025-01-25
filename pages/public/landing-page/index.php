<?php
require_once('./../../../functions/init-session.php');
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
                        <a class="navbar-brand" href="index.html">
                            <img class="logo-icon" src="/sistem-penerimaan-karyawan/assets/images/app-logo.png"
                                alt="logo">
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
                                        <li><a class="dropdown-item" href="help-category-alt.html">Profile</a></li>
                                        <li><a class="dropdown-item text-danger" href="logout.php">Logout</a></li>
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

        <div class="page-heading-holder">
            <div class="container text-center">
                <h1 class="page-heading mb-3">Jelajahi Peluang Bersama Grand Pasundan</h1>

                <div class="page-heading-sub single-col-max mx-auto">
                    <div class="help-search-intro">
                        Telusuri lowongan kerja dan raih peluang karier terbaik Anda.
                    </div>
                    <div class="help-search-main pt-3 d-block mx-auto">
                        <form class="search-form w-100">
                            <input type="text" placeholder="Cari Lowongan Pekerjaan" name="search"
                                class="form-control search-input">
                            <button type="submit" class="btn search-btn" value="Search">
                                <i class="bi bi-search"></i>
                            </button>
                        </form>
                    </div><!--//help-search-main-->
                </div>
            </div>

        </div><!--//page-heading-holder-->
    </div><!--//page-header-wrapper-->

    <section class="help-featured-section theme-section">
        <div class="container">

            <div class="section-header text-center mb-5">
                <h2 class="section-title mb-3">Lowongan Pekerjaan</h2>
            </div>
            <div class="row align-content-stretch">
                <div class="item col-12 col-md-6 col-lg-3 py-4 p-md-4">
                    <div class="item-inner shadow rounded-4 p-4">
                        <a class="item-link" href="help-article.html">
                            <h3 class="item-heading">
                                <div class="help-article-icon-holder mb-2"></div>Onboarding 101: Getting Started with
                                Lorem Ipsum
                            </h3>
                            <div class="item-desc">
                                <span class="rate-icon me-2"><i class="fa-solid fa-thumbs-up"></i></span> 56 people
                                found this article helpful
                            </div><!--//item-meta-->
                        </a>
                    </div><!--//item-inner-->
                </div><!--//item-->
                <div class="item col-12 col-md-6 col-lg-3 py-4 p-md-4">
                    <div class="item-inner shadow rounded-4 p-4">
                        <a class="item-link" href="help-article.html">
                            <h3 class="item-heading">
                                <div class="help-article-icon-holder mb-2"></div>Navigating the Dashboard: Key Features
                                Explained
                            </h3>
                            <div class="item-desc">
                                <span class="rate-icon me-2"><i class="fa-solid fa-thumbs-up"></i></span>25 people found
                                this article helpful
                            </div><!--//item-meta-->
                        </a>
                    </div><!--//item-inner-->
                </div><!--//item-->
                <div class="item col-12 col-md-6 col-lg-3 py-4 p-md-4">
                    <div class="item-inner shadow rounded-4 p-4">
                        <a class="item-link" href="help-article.html">
                            <h3 class="item-heading">
                                <div class="help-article-icon-holder mb-2"></div>Integrating Lorem Ipsum with Other
                                Software
                            </h3>
                            <div class="item-desc">
                                <span class="rate-icon me-2"><i class="fa-solid fa-thumbs-up"></i></span> 23 people
                                found this article helpful
                            </div><!--//item-meta-->
                        </a>
                    </div><!--//item-inner-->
                </div><!--//item-->
                <div class="item col-12 col-md-6 col-lg-3 py-4 p-md-4">
                    <div class="item-inner shadow rounded-4 p-4">
                        <a class="item-link" href="help-article.html">
                            <h3 class="item-heading">
                                <div class="help-article-icon-holder mb-2"></div>Troubleshooting Common Issues in
                            </h3>
                            <div class="item-desc">
                                <span class="rate-icon me-2"><i class="fa-solid fa-thumbs-up"></i></span> 19 people
                                found this article helpful
                            </div><!--//item-meta-->
                        </a>
                    </div><!--//item-inner-->
                </div><!--//item-->
            </div><!--//row-->
        </div><!--//help-featured-articles-section-->
        </div><!--//container-->
    </section><!--//help-featured-section-->

    <!-- Javascript -->
    <script src="/sistem-penerimaan-karyawan/assets/plugins/popper.min.js"></script>
    <script src="/sistem-penerimaan-karyawan/assets/plugins/bootstrap/js/bootstrap.min.js"></script>
    <script
        src="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/extensions/sweetalert2/sweetalert2.min.js"></script>
    <script src="/sistem-penerimaan-karyawan/assets/js/sweet-alert.js"></script>
</body>

</html>