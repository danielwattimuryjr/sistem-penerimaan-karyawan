<?php
require_once('./../../../functions/init-session.php');
require_once('./../../../functions/init-conn.php');
if (!$_SESSION['user']) {
    header("Location: /pages/auth/sign-in");
}

$queryStr = "SELECT 
    p.id_permintaan, 
    p.id_user, 
    u.name, 
    p.jumlah_permintaan, 
    p.status_permintaan, 
    l.id_lowongan
FROM permintaan p
JOIN user u ON p.id_user = u.id_user
LEFT JOIN lowongan l ON p.id_permintaan = l.id_permintaan";

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
    <title>Permintaan Karyawan</title>

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
                <h3>Permintaan Karyawan</h3>
            </div>
            <div class="page-content">
                <section class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">Daftar Permintaan Karyawan</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive datatable-minimal">
                                    <table class="table" id="data-table">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Divisi</th>
                                                <th>Jumlah Permintaan</th>
                                                <th>Status</th>
                                                <th>Lowongan</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $no = 1 ?>
                                            <?php foreach ($result as $res) { ?>
                                                <?php
                                                $baseDetailUrl = '/pages/hrd/detail-permintaan';
                                                $params = ['id_permintaan' => $res['id_permintaan']];

                                                $detailUrl = $baseDetailUrl . '?' . http_build_query($params);
                                                ?>
                                                <tr>
                                                    <td><?= $no++ ?></td>
                                                    <td><?= $res['name'] ?></td>
                                                    <td><?= $res['jumlah_permintaan'] ?></td>
                                                    <td>
                                                        <span  class="badge
                                                    <?= $res['status_permintaan'] === 'Disetujui' ? 'bg-success' : 'bg-danger'; ?>">
                                                    <?= htmlspecialchars(ucfirst($res['status_permintaan'])); ?>
                                                        </span>
                                                    </td>
                                                        <td>
                                                        <?php if ($res['id_lowongan']): ?>
                                                            <a href="/pages/hrd/detail-lowongan-pekerjaan?id_lowongan=<?= $res['id_lowongan'] ?>">Lihat Lowongan</a>
                                                        <?php else: ?>
                                                            <?php if ($res['status_permintaan'] === 'Disetujui'): ?>
                                                                <a href="/pages/hrd/form-create-lowongan?id_permintaan=<?= $res['id_permintaan'] ?>">Tambah Lowongan</a>
                                                            <?php else: ?>
                                                                <span>Belum Disetujui</span>
                                                            <?php endif; ?>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <?php if ($res['status_permintaan'] === 'Disetujui'): ?>
                                                            <p class="text-success">Permintaan ini telah disetujui.</p>
                                                            <a href="<?= $detailUrl ?>">Detail</a>
                                                        <?php elseif ($res['status_permintaan'] === 'Ditolak'): ?>
                                                            <p class="text-danger">Permintaan ini telah ditolak.</p>
                                                            <a href="<?= $detailUrl ?>">Detail</a>
                                                        <?php else: ?>
                                                            <div class="btn-group">
                                                            <a type="button" class="btn btn-sm btn-primary"
                                                                href="<?= $detailUrl ?>">Detail</a>
                                                            </div>
                                                        <?php endif; ?>
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