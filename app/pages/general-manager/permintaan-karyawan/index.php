<?php
require_once('./../../../functions/init-conn.php');
require_once('./../../../functions/page-protection.php');
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
                                                <th>Jabatan</th>
                                                <th>Status</th>
                                                <th>Lowongan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $no = 1 ?>
                                            <?php foreach ($result as $res) { ?>
                                                <?php
                                                $baseDetailUrl = '/pages/general-manager/detail-permintaan';
                                                $params = ['id_permintaan' => $res['id_permintaan']];
                                                $detailUrl = $baseDetailUrl . '?' . http_build_query($params);
                                                ?>
                                                <tr>
                                                    <td><?= $no++ ?></td>
                                                    <td><?= $res['name'] ?></td>
                                                    <td>
                                                        <?php if ($res['status_permintaan'] === false || $res['status_permintaan'] === 'Pending') { ?>
                                                                        <form action="update-status.php" method="POST"
                                                                            style="display: inline;">
                                                                            <input type="hidden" name="id_permintaan"
                                                                                value="<?= htmlspecialchars($res['id_permintaan']); ?>">
                                                                            <input type="hidden" name="status_permintaan" value="Disetujui">
                                                                            <button type="submit"
                                                                                class="btn btn-outline-success btn-sm">Setuju</button>
                                                                        </form>
                                                                        <form action="update-status.php" method="POST"
                                                                            style="display: inline;">
                                                                            <input type="hidden" name="id_permintaan"
                                                                                value="<?= htmlspecialchars($res['id_permintaan']); ?>">
                                                                            <input type="hidden" name="status_permintaan" value="Ditolak">
                                                                            <button type="submit"
                                                                                class="btn btn-outline-danger btn-sm">Tolak</button>
                                                                        </form>
                                                        <?php } else { ?>
                                                                        <span
                                                                            class="badge
                                                                    <?= $res['status_permintaan'] === 'Disetujui' ? 'bg-success' : 'bg-danger'; ?>">
                                                                            <?= htmlspecialchars(ucfirst($res['status_permintaan'])); ?>
                                                                        </span>
                                                        <?php } ?>
                                                        <a href="<?= $detailUrl ?>"
                                                            class="btn btn-outline-primary btn-sm">Lihat Detail</a>
                                                    </td>
                                                    <td>
                                                    <?php if ($res['id_lowongan']): ?>
                                                                    <a href="/pages/general-manager/detail-lowongan-pekerjaan?id_lowongan=<?= $res['id_lowongan'] ?>">Lihat Lowongan</a>
                                                    <?php else: ?>
                                                                    <p>Belum ada lowongan</p>
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