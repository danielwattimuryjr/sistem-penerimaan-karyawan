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

    <?php require_once('./../_components/data-table-styles.php'); ?>
    <?php require_once('./../_components/styles.php'); ?>
</head>
<body>
<?php require_once('./../_components/navbar.php'); ?>

<div class="container-sm mt-3 mt-lg-5">
    <div class="card" style="width: 100%;">
        <div class="card-body">
            <h5 class="card-title text-center">Daftar Permintaan Karyawan</h5>

            <table class="table table-bordered" id="data-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Divisi</th>
                        <th>Jumlah Permintaan</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1 ?>
                    <?php foreach ($result as $res) {?>
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
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once ('./../_components/scripts.php'); ?>
<?php require_once ('./../_components/data-tables-script.php'); ?>
</body>
</html>