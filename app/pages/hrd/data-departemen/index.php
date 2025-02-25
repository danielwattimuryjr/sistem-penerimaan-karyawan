<?php
require_once('./../../../functions/init-session.php');
require_once('./../../../functions/init-conn.php');
require_once('./../../../functions/page-protection.php');

$queryStr = "
SELECT
    name,
    email,
    user_name,
    id_user
FROM user
WHERE role = 'Departement'
";

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
    <title>Data Department</title>

    <link rel="shortcut icon" href="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/compiled/svg/favicon.svg"
        type="image/x-icon">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/compiled/css/app.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/compiled/css/iconly.css">
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/scss/pages/datatables.scss">
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
                <h3>Daftar Department</h3>
            </div>
            <div class="page-content">
                <section class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">Daftar Departement</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive datatable-minimal">
                                    <table class="table" id="data-table">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama</th>
                                                <th>Alamat Email</th>
                                                <th>Username</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $no = 1 ?>
                                            <?php foreach ($result as $res) { ?>
                                                <?php
                                                $baseEditUrl = BASE_URL . '/form-edit-departemen';
                                                $baseDeleteUrl = BASE_URL . '/data-departemen/delete.php';
                                                $params = ['id_user' => $res['id_user']];
                                                $editUrl = $baseEditUrl . '?' . http_build_query($params);
                                                $deleteUrl = $baseDeleteUrl . '?' . http_build_query($params);
                                                $divisiUrl = BASE_URL . '/data-divisi?' . http_build_query(['id_department' => $res['id_user']]);
                                                ?>
                                                <tr>
                                                    <td><?= $no++ ?></td>
                                                    <td><?= htmlspecialchars($res['name']) ?></td>
                                                    <td><?= htmlspecialchars($res['email']) ?></td>
                                                    <td><?= htmlspecialchars($res['user_name']) ?></td>
                                                    <td>
                                                        <a type="button" class="btn btn-sm btn-primary"
                                                            href="<?= $divisiUrl ?>">Lihat Divisi</a>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
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

    <script
        src="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/compiled/js/app.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/extensions/jquery/jquery.min.js"></script>
    <script
        src="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/extensions/datatables.net/js/jquery.dataTables.min.js"></script>
    <script
        src="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/extensions/datatables.net-bs5/js/dataTables.bootstrap5.min.js"></script>
    <script src="/assets/js/data-table.js"></script>
    <script
        src="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/extensions/sweetalert2/sweetalert2.min.js"></script>
    <script src="/assets/js/sweet-alert.js"></script>
</body>

</html>