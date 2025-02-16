<?php
require_once('./../../../functions/init-session.php');
require_once('./../../../functions/init-conn.php');
require_once('./../../../functions/page-protection.php');

$queryStr = "SELECT
	l.nama_lowongan,
    vvwp.nama_pelamar as nama_lengkap,
    vvwp.vektor_y as hasil_akhir,
    vvwp.peringkat,
    h.status,
    vvwp.id_hasil
FROM vektor_v_weighted_product vvwp
JOIN lowongan l ON vvwp.id_lowongan = l.id_lowongan
JOIN hasil h ON vvwp.id_hasil = h.id_hasil
ORDER BY l.nama_lowongan, vvwp.peringkat;";

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
    <title>Hasil Seleksi</title>

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
                <h3>Daftar Hasil Seleksi</h3>
            </div>
            <div class="page-content">
                <section class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive datatable-minimal">
                                    <table class="table" id="data-table">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Lowongan</th>
                                                <th>Nama</th>
                                                <th>Nilai Akhir</th>
                                                <th>Peringkat</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $no = 1 ?>
                                            <?php foreach ($result as $res) { ?>
                                                    <tr>
                                                        <td><?= $no++ ?></td>
                                                        <td><?= $res['nama_lowongan'] ?></td>
                                                        <td><?= htmlspecialchars($res['nama_lengkap']) ?></td>
                                                        <td><?= $res['hasil_akhir'] ?></td>
                                                        <td><?= $res['peringkat'] ?></td>
                                                        <td>
                                                            <?php if (!$res['status']) { ?>
                                                                    <form action="update-status.php" method="POST"
                                                                        style="display: inline;">
                                                                        <input type="hidden" name="id_hasil"
                                                                            value="<?= htmlspecialchars($res['id_hasil']); ?>">
                                                                        <input type="hidden" name="status" value="Diterima">
                                                                        <button type="submit"
                                                                            class="btn btn-outline-success btn-sm">Terima</button>
                                                                    </form>
                                                                    <form action="update-status.php" method="POST"
                                                                        style="display: inline;">
                                                                        <input type="hidden" name="id_hasil"
                                                                            value="<?= htmlspecialchars($res['id_hasil']); ?>">
                                                                        <input type="hidden" name="status" value="Ditolak">
                                                                        <button type="submit"
                                                                            class="btn btn-outline-danger btn-sm">Tolak</button>
                                                                    </form>
                                                            <?php } else { ?>
                                                                    <span
                                                                        class="badge
                                                                                <?= $res['status'] === 'Diterima' ? 'bg-success' : 'bg-danger'; ?>">
                                                                        <?= htmlspecialchars(ucfirst($res['status'])); ?>
                                                                    </span>
                                                            <?php } ?>
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