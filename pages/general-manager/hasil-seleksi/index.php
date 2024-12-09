<?php
require_once('./../../../functions/init-session.php');
require_once('./../../../functions/init-conn.php');
if (!$_SESSION['user']) {
    header("Location: /sistem-penerimaan-karyawan/pages/auth/sign-in");
}

$queryStr = "
    SELECT 
        h.id_hasil AS id,
        h.nama_lengkap AS nama,
        p.nilai AS nilai_akhir,
        h.peringkat,
        h.status
    FROM hasil h
    JOIN penilaian p ON h.id_penilaian = p.id_penilaian
";

$stmt = $conn->prepare($queryStr);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
$conn->close();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Hasil Seleksi</title>

    <!--  Bootstrap 5.3 CSS  -->
    <link rel="stylesheet" href="/sistem-penerimaan-karyawan/assets/css/bootstrap.min.css" crossorigin="anonymous">

    <!--  Data Table CSS  -->
    <link rel="stylesheet" href="/sistem-penerimaan-karyawan/assets/css/datatables.min.css" crossorigin="anonymous">

    <style>
        body {
            background-color: #f1f1f1f1;
        }
    </style>
</head>
<body>
<?php require_once('./../_components/navbar.php'); ?>

<div class="container-sm mt-3 mt-lg-5">
    <div class="card" style="width: 100%;">
        <div class="card-body">
            <h5 class="card-title text-center">Daftar Hasil Seleksi</h5>

            <table class="table table-bordered" id="data-table">
                <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Nilai Akhir</th>
                    <th>Peringkat</th>
                    <th>Status</th>
                </tr>
                </thead>
                <tbody>
                    <?php $no = 1; ?>
                    <?php foreach ($result as $res) {?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= $res['nama'] ?></td>
                            <td><?= $res['nilai_akhir'] ?></td>
                            <td><?= $res['peringkat'] ?></td>
                            <td>
                                <?php if ($res['status'] === false) { ?>
                                    <form action="update-status.php" method="POST" style="display: inline;">
                                        <input type="hidden" name="id_hasil" value="<?= htmlspecialchars($res['id_hasil']); ?>">
                                        <input type="hidden" name="status" value="Diterima">
                                        <button type="submit" class="btn btn-outline-success">Setuju</button>
                                    </form>
                                    <form action="update-status.php" method="POST" style="display: inline;">
                                        <input type="hidden" name="id_hasil" value="<?= htmlspecialchars($res['id_hasil']); ?>">
                                        <input type="hidden" name="status" value="Ditolak">
                                        <button type="submit" class="btn btn-outline-danger">Tolak</button>
                                    </form>
                                <?php } else { ?>
                                    <span class="badge
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


<!--  Bootstrap 5.3 JS  -->
<script src="/sistem-penerimaan-karyawan/assets/js/popper.min.js" crossorigin="anonymous"></script>
<script src="/sistem-penerimaan-karyawan/assets/js/bootstrap.min.js" crossorigin="anonymous"></script>

<!--  Data Table  -->
<script src="/sistem-penerimaan-karyawan/assets/js/jquery-3.7.1.min.js" crossorigin="anonymous"></script>
<script src="/sistem-penerimaan-karyawan/assets/js/datatables.min.js" crossorigin="anonymous"></script>
<script src="/sistem-penerimaan-karyawan/assets/js/dataTables.bootstrap5.js" crossorigin="anonymous"></script>

<script>
    new DataTable('#data-table');
</script>
</body>
</html>