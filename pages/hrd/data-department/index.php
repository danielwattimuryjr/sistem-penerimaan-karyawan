<?php
require_once('./../../../functions/init-session.php');
require_once('./../../../functions/init-conn.php');
require_once('./../../../functions/page-protection.php');

$queryStr = "
SELECT
    id_divisi,
    nama_divisi
FROM divisi
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
    <title>Daftar Departement</title>

    <?php require_once('./../_components/data-table-styles.php'); ?>
    <?php require_once('./../_components/styles.php'); ?>
</head>
<body>
<?php require_once('./../_components/navbar.php'); ?>

<div class="container-sm mt-3 mt-lg-5">
    <div class="card" style="width: 100%;">
        <div class="card-body">
            <div class="d-flex flex-column flex-lg-row justify-content-start justify-content-lg-between align-items-start align-items-lg-center">
                <h5 class="card-title">Daftar Departement</h5>

                <a href="/sistem-penerimaan-karyawan/pages/hrd/form-create-department" class="btn btn-sm btn-primary">
                    Tambah Departement
                </a>
            </div>

            <table class="table table-bordered" id="data-table">
                <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Aksi</th>
                </tr>
                </thead>
                <tbody>
                <?php $no = 1 ?>
                <?php foreach ($result as $res) { ?>
                    <?php
                        $baseEditUrl = '/sistem-penerimaan-karyawan/pages/hrd/form-edit-department';
                        $baseDeleteUrl = '/sistem-penerimaan-karyawan/pages/hrd/data-department/delete.php';
                        $params = ['id_divisi' => $res['id_divisi']];
                        $editUrl = $baseEditUrl . '?' . http_build_query($params);
                        $deleteUrl = $baseDeleteUrl . '?' . http_build_query($params);
                    ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($res['nama_divisi']) ?></td>
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

<?php require_once('./../_components/scripts.php'); ?>
<?php require_once('./../_components/data-tables-script.php'); ?>
</body>
</html>