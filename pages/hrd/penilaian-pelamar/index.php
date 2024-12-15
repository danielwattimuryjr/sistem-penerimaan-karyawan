<?php
require_once('./../../../functions/init-session.php');
require_once('./../../../functions/init-conn.php');
require_once('./../../../functions/page-protection.php');

if (!isset($_GET['id_pelamaran']) || empty($_GET['id_pelamaran'])) {
    $type = 'error';
    $message = "ID pelamar tidak ditemukan";
    header('Location: /sistem-penerimaan-karyawan/pages/hrd/data-pelamar?type=error&message=' . urlencode($message));
    exit();
}
$id_pelamaran = $_GET['id_pelamaran'];

$queryStr = "SELECT 
    u.nama_lengkap
FROM 
    user u
JOIN 
    profile p ON u.id_user = p.id_user
JOIN 
    pelamaran pel ON u.id_user = pel.id_user
WHERE 
    pel.id_pelamaran = ?";
$stmt = $conn->prepare($queryStr);
$stmt->bind_param("i", $id_pelamaran);
$stmt->execute();
$result = $stmt->get_result();
$dataPelamar = $result->fetch_assoc();
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
    <title><?= $dataPelamar['nama_lengkap'] . ' | Penilaian Pelamar' ?></title>

    <?php require_once('./../_components/data-table-styles.php'); ?>
    <?php require_once('./../_components/styles.php'); ?>
</head>
<body>
<?php require_once('./../_components/navbar.php');?>

<div class="container-sm mt-3 mt-lg-5">
    <div class="card" style="width: 100%;">
        <div class="card-body">
            <h5 class="card-title text-center">Penilaian Pelamar</h5>

            <form method="POST" action="post-penilaian-request.php">
                <input type="hidden" name="id_pelamaran" value="<?= $id_pelamaran ?>">
                <div class="mb-3">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" class="form-control" value="<?= $dataPelamar['nama_lengkap'] ?>" disabled>
                </div>

                <div class="mb-3">
                    <label for="" class="form-label">Tes Tertulis</label>
                    <input type="number" name="nilai_tes_tertulis" id="" class="form-control" min="1" max="100" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Tes Wawancara</label>
                    <select class="form-select" name="nilai_tes_wawancara" required>
                        <option selected disabled>-- PILIH PENILAIAN --</option>
                        <option value="Sangat Kurang">Sangat Kurang</option>
                        <option value="Kurang">Kurang</option>
                        <option value="Cukup">Cukup</option>
                        <option value="Baik">Baik</option>
                        <option value="Sangat Baik">Sangat Baik</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Tes Praktek</label>
                    <select class="form-select" name="nilai_tes_praktek" required>
                        <option selected disabled>-- PILIH PENILAIAN --</option>
                        <option value="Sangat Kurang">Sangat Kurang</option>
                        <option value="Kurang">Kurang</option>
                        <option value="Cukup">Cukup</option>
                        <option value="Baik">Baik</option>
                        <option value="Sangat Baik">Sangat Baik</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Tes Psikotes</label>
                    <input type="number" name="nilai_tes_psikotes" id="" class="form-control" min="1" max="100" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Tes Kesehatan</label>
                    <select class="form-select" name="nilai_tes_kesehatan" required>
                        <option selected disabled>-- PILIH PENILAIAN --</option>
                        <option value="Sangat Kurang">Sangat Kurang</option>
                        <option value="Kurang">Kurang</option>
                        <option value="Cukup">Cukup</option>
                        <option value="Baik">Baik</option>
                        <option value="Sangat Baik">Sangat Baik</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Simpan</button>
            </form>
        </div>
    </div>
</div>

<?php require_once ('./../_components/scripts.php'); ?>
<?php require_once ('./../_components/data-tables-script.php'); ?>
</body>
</html>