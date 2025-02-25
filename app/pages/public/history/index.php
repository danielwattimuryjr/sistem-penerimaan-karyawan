<?php
require_once('./../../../functions/init-conn.php');
require_once('./../../../functions/string-helpers.php');
require_once('./../../../functions/init-session.php');

$userSession = isset($_SESSION['user']) ? $_SESSION['user'] : null;

if (!$userSession) {
    header("Location: /pages/public/landing-page?status=error&message=" . urlencode('Belum login'));
    exit();
}

$sql = "SELECT vps.*, COALESCE(vvwp.vektor_y, '-') AS final_score, COALESCE(vvwp.peringkat, '-') AS peringkat FROM view_pelamaran_status vps JOIN vektor_v_weighted_product vvwp ON vps.id_pelamaran = vvwp.id_pelamaran WHERE vps.id_user = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $userSession['id_user']);
$stmt->execute();
$result = $stmt->get_result();
$historyData = $result->fetch_all(MYSQLI_ASSOC); // Fetch all rows
$stmt->close();
$conn->close();
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
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/scss/pages/datatables.scss">
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
    </div><!--//page-header-wrapper-->

    <div class="help-content-wrapper theme-section pt-4">
        <div class="container">
            <div class="row">
                <div class="col">
                    <section class="main-section order-lg-last">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive datatable-minimal">
                                    <table class="table" id="data-table">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Lowongan</th>
                                                <th>Nilai Akhir</th>
                                                <th>Peringkat</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $no = 1; ?>
                                            <?php foreach ($historyData as $res): ?>
                                                <tr>
                                                    <td><?= $no++ ?></td>
                                                    <td><?= toTitleCase(htmlspecialchars($res['nama_lowongan'])) ?></td>
                                                    <td><?= $res['final_score'] ?></td>
                                                    <td><?= $res['peringkat'] ?></td>
                                                    <td>
                                                        <?php if ($res['isApproved']): ?>
                                                            <p class="text-success">Diterima</p>
                                                        <?php else: ?>
                                                            <p class="text-secondary">Pending</p>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
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
    <script src="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/extensions/jquery/jquery.min.js"></script>
    <script
        src="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/extensions/datatables.net/js/jquery.dataTables.min.js"></script>
    <script
        src="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/extensions/datatables.net-bs5/js/dataTables.bootstrap5.min.js"></script>
    <script src="/assets/js/data-table.js"></script>
</body>

</html>
