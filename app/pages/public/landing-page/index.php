<?php
require_once('./../../../functions/init-conn.php');
require_once('./../../../functions/string-helpers.php');
require_once('./../../../functions/init-session.php');

$keyword = isset($_GET['search']) ? trim($_GET['search']) : '';

if (!empty($keyword)) {
    $query = "SELECT id_lowongan, nama_lowongan, u.name
              FROM lowongan l
              JOIN permintaan p ON l.id_permintaan = p.id_permintaan
              JOIN user u ON p.id_user = u.id_user
              WHERE nama_lowongan LIKE ?
              AND l.closed = 0";
    $stmt = $conn->prepare($query);
    $searchParam = "%$keyword%";
    $stmt->bind_param("s", $searchParam);
} else {
    $query = "SELECT id_lowongan, nama_lowongan, u.name
              FROM lowongan l
              JOIN permintaan p ON l.id_permintaan = p.id_permintaan
              JOIN user u ON p.id_user = u.id_user
              AND l.closed = 0";
    $stmt = $conn->prepare($query);
}

$stmt->execute();
$result = $stmt->get_result();
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
                <nav class="navbar navbar-expand-lg" style="position: relative;">
                    <div class="d-flex align-items-center justify-content-center" style="width: 100%">
                        <a href="/pages/public/landing-page">
                            <img src="/assets/images/app-logo.png" alt="logo" style="width: 200px;">
                        </a>
                    </div><!--//site-logo-->

                    <div style="position: absolute; top: 1em; right: 0;">
                        <?php if (isset($_SESSION['user'])): ?>
                            <div class="dropdown">
                                <button class="btn" type="button" data-bs-toggle="dropdown"
                                    aria-expanded="false"><?= $_SESSION['user']['name'] ?></button>

                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="/pages/public/history">History</a></li>
                                    <li><a class="dropdown-item text-danger"
                                            href="/pages/public/landing-page/logout.php">Logout</a></li>
                                </ul>
                            </div>
                        <?php else: ?>
                            <a class="btn btn-light" href="/pages/public/sign-in">
                                Sign In
                            </a>
                        <?php endif; ?>
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
                        <form class="search-form w-100" method="post" action="search.php">
                            <input type="text" placeholder="Cari Lowongan Pekerjaan" name="search"
                                class="form-control search-input"
                                value="<?= htmlspecialchars($keyword, ENT_QUOTES, 'UTF-8') ?>">
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
                <?php if ($result->num_rows === 0): ?>
                    <div class="item col-12 text-center">
                        <p class="text-danger">Oops.. Belum ada lowongan pekerjaan</p>
                    </div>
                <?php else: ?>
                    <?php while ($l = $result->fetch_assoc()): ?>
                        <?php
                        $detailUrl = '/pages/public/detail-lowongan?id_lowongan=' . $l['id_lowongan'];
                        ?>

                        <div class="item col-12 col-md-6 py-4 p-md-4">
                            <div class="item-inner shadow rounded-4 p-4">
                                <a class="item-link" href="<?= $detailUrl ?>">
                                    <h3 class="item-heading"><?= toTitleCase($l['nama_lowongan']) ?></h3>
                                    <div class="item-desc"><?= $l['name'] ?></div>
                                </a>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php endif; ?>
            </div><!--//row-->
        </div><!--//help-featured-articles-section-->
        </div><!--//container-->
    </section><!--//help-featured-section-->

    <!-- Javascript -->
    <script src="/assets/plugins/popper.min.js"></script>
    <script src="/assets/plugins/bootstrap/js/bootstrap.min.js"></script>
    <script
        src="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/extensions/sweetalert2/sweetalert2.min.js"></script>
    <script src="/assets/js/sweet-alert.js"></script>
</body>

</html>