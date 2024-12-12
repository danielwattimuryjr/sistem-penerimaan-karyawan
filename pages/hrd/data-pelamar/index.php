<?php
require_once('./../../../functions/init-session.php');
require_once('./../../../functions/init-conn.php');
require_once('./../../../functions/page-protection.php');

$queryStr = " SELECT
  p.id_pelamaran,
  u.nama_lengkap,
  d.nama_divisi
 FROM pelamaran p
 JOIN user u ON p.id_user = u.id_user
 JOIN lowongan l ON p.id_lowongan = l.id_lowongan
 JOIN permintaan pe ON l.id_permintaan = pe.id_permintaan
 JOIN divisi d ON pe.id_divisi = d.id_divisi ";

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
    <title>Data Pelamar</title>

    <?php require_once('./../_components/data-table-styles.php'); ?>
    <?php require_once('./../_components/styles.php'); ?>
</head>
<body>
<?php require_once('./../_components/navbar.php'); ?>

<div class="container-sm mt-3 mt-lg-5">
    <div class="card" style="width: 100%;">
        <div class="card-body">
            <h5 class="card-title text-center">Daftar Pelamar</h5>

            <table class="table table-bordered" id="data-table">
                <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Divisi</th>
                    <th>Aksi</th>
                </tr>
                </thead>
                <tbody>
                <?php $no = 1 ?>
                <?php foreach ($result as $res) {?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $res['nama_lengkap'] ?></td>
                        <td><?= $res['nama_divisi'] ?></td>
                        <td>
                            <a href="<?= "/sistem-penerimaan-karyawan/pages/hrd/detail-pelamar?id_pelamaran=" . $res['id_pelamaran']?>" class="btn btn-sm btn-primary">Lihat Detail</a>
                            <a href="<?= "/sistem-penerimaan-karyawan/pages/hrd/penilaian-pelamar?id_pelamaran=" . $res['id_pelamaran']?>" class="btn btn-sm btn-secondary">Nilai</a>
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