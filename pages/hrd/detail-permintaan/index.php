<?php
require_once('./../../../functions/init-session.php');
require_once('./../../../functions/init-conn.php');
require_once('./../../../functions/string-helpers.php');
require_once('./../../../functions/page-protection.php');

$id_permintaan = isset($_GET['id_permintaan']) ? $_GET['id_permintaan'] : null;

if (!$id_permintaan) {
    $type = 'error';
    $message = 'Data permintaan tidak ditemukan';
    header("Location: /sistem-penerimaan-karyawan/pages/hrd/beranda?type=$type&message=" . urlencode($message));
    exit();
}
$getPermintaanQueryStr = "SELECT p.id_permintaan, p.tanggal_permintaan, p.id_divisi, d.nama_divisi, p.posisi, p.jumlah_permintaan, p.jenis_kelamin, p.status_kerja, p.tanggal_mulai, p.tanggal_selesai, p.keperluan, p.status_permintaan
FROM permintaan p
JOIN divisi d ON p.id_divisi = d.id_divisi
WHERE p.id_permintaan = ?
LIMIT 1";

$getPermintaanStmt = $conn->prepare($getPermintaanQueryStr);
$getPermintaanStmt->bind_param("i", $id_permintaan);
$getPermintaanStmt->execute();
$permintaan = $getPermintaanStmt->get_result()->fetch_assoc();
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Detail Permintaan Karyawan</title>

    <?php require_once('./../_components/styles.php'); ?>
</head>

<body>
    <?php require_once('./../_components/navbar.php'); ?>

    <div class="container-sm mt-3 mt-lg-5">
        <div class="card" style="width: 100%;">
            <div class="card-body">
                <h5 class="card-title text-center mb-3">Detail Permintaan Karyawan</h5>

                <dl class="row">
                    <dt class="col-sm-3">Tanggal Permintaan</dt>
                    <dd class="col-sm-9"><?= $permintaan['tanggal_permintaan'] ?></dd>

                    <dt class="col-sm-3">Status</dt>
                    <dd class="col-sm-9"><span
                            class="badge
                                    <?= $permintaan['status_permintaan'] === 'Disetujui' ? 'bg-success' : 'bg-danger'; ?>">
                            <?= htmlspecialchars(ucfirst($permintaan['status_permintaan'])); ?>
                        </span></dd>

                    <dt class="col-sm-3">Departement</dt>
                    <dd class="col-sm-9"><?= $permintaan['nama_divisi'] ?></dd>

                    <dt class="col-sm-3">Untuk Posisi</dt>
                    <dd class="col-sm-9"><?= $permintaan['posisi'] ?></dd>

                    <dt class="col-sm-3">Jumlah</dt>
                    <dd class="col-sm-9"><?= $permintaan['jumlah_permintaan'] ?></dd>

                    <dt class="col-sm-3">Jenis Kelamin</dt>
                    <dd class="col-sm-9">
                        <?= is_null($permintaan['jenis_kelamin']) ? '-' : ($permintaan['jenis_kelamin'] ? 'Laki-laki' : 'Perempuan') ?>
                    </dd>

                    <dt class="col-sm-3">Status Kerja</dt>
                    <dd class="col-sm-9"><?= toTitleCase($permintaan['status_kerja']) ?> </dd>

                    <dt class="col-sm-3">Tanggal Mulai</dt>
                    <dd class="col-sm-9"><?= $permintaan['tanggal_mulai'] ?> </dd>

                    <dt class="col-sm-3">Tanggal Selesai</dt>
                    <dd class="col-sm-9"><?= $permintaan['tanggal_selesai'] ?? '-' ?> </dd>

                    <dt class="col-sm-3">Keperluan</dt>
                    <dd class="col-sm-9"><?= $permintaan['keperluan'] ?? '-' ?> </dd>
                </dl>
            </div>
        </div>
    </div>

    <?php require_once('./../_components/scripts.php'); ?>
</body>

</html>