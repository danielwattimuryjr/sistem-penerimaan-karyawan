<?php
    require_once('./../../../functions/init-conn.php');
    require_once('./../../../functions/page-protection.php');

    $recordsPerPage = 2;
    $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
    $offset = ($page - 1) * $recordsPerPage;

    $totalQuery = "SELECT COUNT(*) as total FROM lowongan";
    $result = $conn->query($totalQuery);
    $totalRecords = $result->fetch_assoc()['total'];
    $totalPages = ceil($totalRecords / $recordsPerPage);

    $queryStr = "SELECT id_lowongan, nama_lowongan FROM lowongan LIMIT $recordsPerPage OFFSET $offset";
    $dataResult = $conn->query($queryStr);
    $conn->close();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Beranda</title>

    <!--  Bootstrap 5.3 CSS  -->
    <link rel="stylesheet" href="/sistem-penerimaan-karyawan/assets/css/bootstrap.min.css" crossorigin="anonymous">

    <style>
        body {
            background-color: #f1f1f1f1;
        }
    </style>
</head>
<body>
    <?php require_once ('./../_components/navbar.php'); ?>

    <div class="container-sm mt-3 mt-lg-5">
        <div class="card" style="width: 100%;">
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <?php if ($dataResult->num_rows > 0): ?>
                        <?php while ($row = $dataResult->fetch_assoc()): ?>
                            <?php
                                $baseDetailUrl = '/sistem-penerimaan-karyawan/pages/departemen/beranda/detail-lowongan-pekerjaan';
                                $params = [
                                    'id_lowongan' => $row['id_lowongan']
                                ];
                                $detailUrl = $baseDetailUrl . '?' . http_build_query($params);
                            ?>
                            <li class="list-group-item">
                                <div class="d-flex gap-3 gap-md-4 flex-column flex-md-row align-items-center">
                                    <img src="https://placehold.co/150" alt="" style="width: 150px">
                                    <div class="d-flex flex-column text-center text-lg-start">
                                        <h3><?= htmlspecialchars($row['nama_lowongan']); ?></h3>
                                        <a href="<?= $detailUrl ?>" class="btn btn-secondary">Lihat Selengkapnya</a>
                                    </div>
                                </div>
                            </li>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <li class="list-group-item text-center text-danger">Tidak ada lowongan</li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
        <nav class="mt-2 d-flex justify-content-center justify-content-md-end">
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


    <!--  Bootstrap 5.3 JS  -->
    <script src="/sistem-penerimaan-karyawan/assets/js/popper.min.js" crossorigin="anonymous"></script>
    <script src="/sistem-penerimaan-karyawan/assets/js/bootstrap.min.js" crossorigin="anonymous"></script>

    <!--  SweetAlert2  -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>