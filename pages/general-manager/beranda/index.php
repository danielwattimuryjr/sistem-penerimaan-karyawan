<?php
require_once('./../../../functions/init-conn.php');
require_once('./../../../functions/page-protection.php');

$recordsPerPage = 2;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * $recordsPerPage;

$totalQuery = "SELECT COUNT(*) as total FROM lowongan";
$result = $conn->query($totalQuery);
$totalRecords = $result->fetch_assoc()['total'];
$totalPages = ceil($totalRecords / $recordsPerPage);

$queryStr = "SELECT id_lowongan, nama_lowongan, poster_lowongan FROM lowongan LIMIT $recordsPerPage OFFSET $offset";
$dataResult = $conn->query($queryStr);
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beranda</title>

    <link rel="shortcut icon" href="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/compiled/svg/favicon.svg"
        type="image/x-icon">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/compiled/css/app.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/compiled/css/app-dark.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/compiled/css/iconly.css">
</head>

<body>
    <script src="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/static/js/initTheme.js"></script>
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
                <h3>Beranda</h3>
            </div>
            <div class="page-content">
                <section class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">Daftar Lowongan Pekerjaan</h5>
                            </div>
                            <div class="card-body">
                                <ul class="list-group list-group-flush">
                                    <?php if ($dataResult->num_rows > 0): ?>
                                        <?php while ($row = $dataResult->fetch_assoc()): ?>
                                            <?php
                                            $baseDetailUrl = '/sistem-penerimaan-karyawan/pages/general-manager/detail-lowongan-pekerjaan';
                                            $params = [
                                                'id_lowongan' => $row['id_lowongan']
                                            ];
                                            $detailUrl = $baseDetailUrl . '?' . http_build_query($params);
                                            ?>
                                            <li class="list-group-item">
                                                <div class="d-flex gap-3 gap-md-4 flex-column flex-md-row align-items-center">
                                                    <img src="<?= '/sistem-penerimaan-karyawan/assets/uploads/poster/' . $row['poster_lowongan'] ?>"
                                                        alt="" style="width: 150px">
                                                    <div class="d-flex flex-column text-center text-lg-start">
                                                        <h3><?= htmlspecialchars($row['nama_lowongan']); ?></h3>
                                                        <a href="<?= $detailUrl ?>" class="btn btn-secondary">Lihat
                                                            Selengkapnya</a>
                                                    </div>
                                                </div>
                                            </li>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <li class="list-group-item text-center text-danger">Tidak ada lowongan</li>
                                    <?php endif; ?>
                                </ul>

                                <nav class="mt-2 d-flex justify-content-end">
                                    <ul class="pagination">
                                        <li class="page-item <?= ($page <= 1) ? 'disabled' : ''; ?>">
                                            <a class="page-link" href="?page=<?= $page - 1; ?>">Back</a>
                                        </li>
                                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                            <li class="page-item <?= ($i == $page) ? 'active' : ''; ?>">
                                                <a class="page-link" href="?page=<?= $i; ?>"><?= $i; ?></a>
                                            </li>
                                        <?php endfor; ?>
                                        <li class="page-item <?= ($page >= $totalPages) ? 'disabled' : ''; ?>">
                                            <a class="page-link" href="?page=<?= $page + 1; ?>">Next</a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
            <!-- End Content -->
        </div>
    </div>

    <!-- End content -->
    <script src="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/static/js/components/dark.js"></script>
    <script
        src="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/compiled/js/app.js"></script>
</body>

</html>