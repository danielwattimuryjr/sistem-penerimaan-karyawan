<?php
require_once('./../../../functions/init-session.php');
require_once('./../../../functions/init-conn.php');
require_once('./../../../functions/page-protection.php');

$queryStr = "
SELECT 
    h.id_hasil,
    h.hasil_akhir,
    h.peringkat,
    u.nama_lengkap,
    d.nama_divisi,
    p.id_pelamaran,
    h.status
FROM hasil h
JOIN penilaian pn ON h.id_penilaian = pn.id_penilaian
JOIN pelamaran p ON pn.id_pelamaran = p.id_pelamaran
JOIN user u ON p.id_user = u.id_user
JOIN lowongan l ON p.id_lowongan = l.id_lowongan
JOIN permintaan pm ON l.id_permintaan = pm.id_permintaan
JOIN divisi d ON pm.id_divisi = d.id_divisi
ORDER BY h.peringkat ASC
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

    <?php require_once('./../_components/data-table-styles.php'); ?>
    <?php require_once('./../_components/styles.php'); ?>
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
                    <th>Aksi</th>
                </tr>
                </thead>
                <tbody>
                <?php $no = 1 ?>
                <?php foreach ($result as $res) {?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($res['nama_lengkap']) ?></td>
                        <td><?= $res['hasil_akhir'] ?></td>
                        <td><?= $res['peringkat'] ?></td>
                        <td>
                            <?php if (!$res['status']) { ?>
                                <form action="update-status.php" method="POST" style="display: inline;">
                                    <input type="hidden" name="id_hasil" value="<?= htmlspecialchars($res['id_hasil']); ?>">
                                    <input type="hidden" name="status" value="Diterima">
                                    <button type="submit" class="btn btn-outline-success">Terima</button>
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

<?php require_once ('./../_components/scripts.php'); ?>
<?php require_once ('./../_components/data-tables-script.php'); ?>
</body>
</html>