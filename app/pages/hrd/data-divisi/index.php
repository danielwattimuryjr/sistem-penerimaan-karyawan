<?php
require_once('./../../../functions/init-conn.php');
require_once('./../../../functions/page-protection.php');

$id_department = isset($_GET['id_department']) ? $_GET['id_department'] : null;
$queryStr = "SELECT
    d.id_divisi,
    d.nama_divisi,
    u.name AS nama_department,
    d.jumlah_personil
FROM divisi d
JOIN user u ON d.id_user = u.id_user";

if ($id_department) {
    $queryStr .= " WHERE d.id_user = ?";
}

$stmt = $conn->prepare($queryStr);

if ($id_department) {
    $stmt->bind_param('i', $id_department);
}

$stmt->execute();
$result = $stmt->get_result();
$stmt->close();

if ($id_department) {
    $sql = "SELECT name FROM user WHERE id_user = ? AND role = 'Departement' LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id_department);
    $stmt->execute();
    $departmentData = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Divisi</title>

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
                <h3>Data Divisi</h3>
            </div>
            <div class="page-content">
                <section class="row">
                    <div class="col-12">
                        <div class="card">
                            <div
                                class="card-header d-flex flex-column flex-md-row justify-content-start justify-content-md-between align-items-start align-items-md-center">
                                <h5 class="card-title">Data Divisi
                                    <?= $id_department ? '(' . $departmentData['name'] . ')' : '(All)' ?>
                                </h5>
                                <?php
                                $createDivisiUrl = BASE_URL . "/form-create-divisi";
                                if ($id_department) {
                                    $createDivisiUrl .= "?" . http_build_query(['id_department' => $id_department]);
                                }
                                ?>
                                <a href="<?= $createDivisiUrl ?>" class="btn btn-sm btn-primary">
                                    Tambah Divisi
                                </a>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive datatable-minimal">
                                    <table class="table" id="data-table">
                                        <thead>
                                            <tr>
                                                <td>No</td>
                                                <td>Nama Divisi</td>
                                                <td>Department</td>
                                                <td>Jumlah Anggota (Max.)</td>
                                                <td>Actions</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $no = 1 ?>
                                            <?php foreach ($result as $row): ?>
                                                <?php
                                                $idDivisi = $row['id_divisi'];
                                                $editUrl = "/pages/hrd/form-edit-divisi?id_divisi=$idDivisi";
                                                $deleteUrl = "delete.php?id_divisi=$idDivisi";
                                                ?>
                                                <tr>
                                                    <td><?= $no++ ?></td>
                                                    <td><?= $row['nama_divisi'] ?></td>
                                                    <td><?= $row['nama_department'] ?></td>
                                                    <td><?= $row['jumlah_personil'] ?></td>
                                                    <td>
                                                        <div class="btn-group">
                                                            <a href="<?= $editUrl ?>"
                                                                class="btn btn-sm btn-warning">Edit</a>
                                                            <a href="<?= $deleteUrl ?>"
                                                                class="btn btn-sm btn-danger">Hapus</a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
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