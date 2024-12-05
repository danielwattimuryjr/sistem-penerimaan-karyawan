<?php
require_once('./../../../functions/init-session.php');
require_once('./../../../functions/init-conn.php');
if (!$_SESSION['user']) {
    header("Location: /sistem-penerimaan-karyawan/pages/auth/sign-in");
}

$queryStr = "SELECT id_permintaan, p.id_divisi, d.nama_divisi, jumlah_permintaan, status_permintaan 
             FROM permintaan p 
             JOIN divisi d ON p.id_divisi = d.id_divisi";

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
    <title>Permintaan Karyawan</title>

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
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="card-title text-center">Daftar Permintaan Karyawan</h5>

                <a href="../form-tambah-permintaan-karyawan" class="btn btn-sm btn-primary">Tambah Permintaan</a>
            </div>

            <table class="table table-bordered" id="data-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Divisi</th>
                        <th>Jumlah Permintaan</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1 ?>
                    <?php foreach ($result as $res) {?>
                        <?php
                            $baseEditUrl = '/sistem-penerimaan-karyawan/pages/departemen/form-edit-permintaan-karyawan';
                            $baseDeleteUrl = '/sistem-penerimaan-karyawan/pages/departemen/permintaan-karyawan/delete.php';
                            $params = ['id_permintaan' => $res['id_permintaan']];
                            $editUrl = $baseEditUrl . '?' . http_build_query($params);
                            $deleteUrl = $baseDeleteUrl . '?' . http_build_query($params);
                        ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= $res['nama_divisi'] ?></td>
                            <td><?= $res['jumlah_permintaan'] ?></td>
                            <td>
                                <span class="badge
                                    <?= $res['status_permintaan'] === 'Disetujui' ? 'bg-success' : 'bg-danger'; ?>">
                                    <?= htmlspecialchars(ucfirst($res['status_permintaan'])); ?>
                                </span>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a type="button" class="btn btn-sm btn-warning" href="<?= $editUrl ?>">Update</a>
                                    <a type="button" class="btn btn-sm btn-danger" href="<?= $deleteUrl ?>">Delete</a>
                                </div>
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

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    new DataTable('#data-table');
</script>
</body>
</html>