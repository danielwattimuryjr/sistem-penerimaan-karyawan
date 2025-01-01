<?php
require_once('./../../../functions/init-conn.php');
require_once('./../../../functions/page-protection.php');

$idLowongan = $_GET['id_lowongan'];
if (!isset($idLowongan)) {
    $message = 'id lowongan tidak valid';
    header("Location: /sistem-penerimaan-karyawan/pages/hrd/beranda?type=error&message=" . urlencode($message));
    exit();
}

$lowonganQuery = "SELECT
    l.id_lowongan,
    l.nama_lowongan,
    l.poster_lowongan,
    l.deskripsi,
    l.tgl_mulai,
    l.tgl_selesai,
    l.id_permintaan
FROM lowongan l
JOIN permintaan p ON l.id_permintaan = p.id_permintaan
WHERE l.id_lowongan = ?";
$lowonganStmt = $conn->prepare($lowonganQuery);
$lowonganStmt->bind_param('i', $idLowongan);
$lowonganStmt->execute();
$lowonganResult = $lowonganStmt->get_result();
$lowonganData = $lowonganResult->fetch_assoc();
if (!$lowonganResult->num_rows) {
    $message = 'Lowongan tidak ditemukan';
    header("Location: /sistem-penerimaan-karyawan/pages/hrd/beranda?type=error&message=" . urlencode($message));
    exit();
}

$permintaanQuery = "SELECT 
    p.id_permintaan, 
    p.jumlah_permintaan, 
    d.nama_divisi
FROM permintaan p
LEFT JOIN lowongan l ON p.id_permintaan = l.id_permintaan
INNER JOIN divisi d ON p.id_divisi = d.id_divisi
WHERE p.status_permintaan = ? 
AND (l.id_lowongan IS NULL OR l.id_lowongan = ?)";
$permintaanStmt = $conn->prepare($permintaanQuery);
$status = 'Disetujui';
$permintaanStmt->bind_param('si', $status, $idLowongan);
$permintaanStmt->execute();
$permintaanResult = $permintaanStmt->get_result();
$permintaanData = $permintaanResult->fetch_all(MYSQLI_ASSOC);

$persyaratanQuery = "SELECT
    pengalaman_kerja,
    umur,
    pendidikan
FROM persyaratan
WHERE id_lowongan = ?";
$persyaratanStmt = $conn->prepare($persyaratanQuery);
$persyaratanStmt->bind_param('i', $idLowongan);
$persyaratanStmt->execute();
$persyaratanResult = $persyaratanStmt->get_result();
$persyaratanData = $persyaratanResult->fetch_assoc();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Edit Lowongan Pekerjaan</title>

    <?php require_once ('./../_components/styles.php'); ?>
</head>
<body>
<?php require_once ('./../_components/navbar.php'); ?>

<div class="container-sm mt-3 mt-lg-5">
    <div class="card" style="width: 100%;">
        <div class="card-body">
            <h5 class="card-title text-center">Edit Lowongan Pekerjaan</h5>

            <form action="edit-lowongan-request.php" method="post" class="mt-4" enctype="multipart/form-data">
                <input type="hidden" name="id_lowongan" value="<?= $lowonganData['id_lowongan'] ?>">
                <div class="mb-3">
                    <label for="" class="form-label">Nama Lowongan</label>
                    <input type="text" name="nama_lowongan" id="" class="form-control" value="<?= $lowonganData['nama_lowongan'] ?>" required>
                </div>
                <div class="row mb-3">
                    <div class="col-12 col-lg-6">
                        <label for="" class="form-label">Tanggal Mulai</label>
                        <input type="date" name="tanggal_mulai" id="" class="form-control" value="<?= $lowonganData['tgl_mulai'] ?>" required>
                    </div>
                    <div class="col-12 col-lg-6">
                        <label for="" class="form-label">Tanggal Selesai</label>
                        <input type="date" name="tanggal_selesai" id="" class="form-control" value="<?= $lowonganData['tgl_selesai'] ?>" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="" class="form-label">Permintaan</label>
                    <select name="id_permintaan" id="" class="form-control">
                        <option value="" selected disabled>-- PILIH PERMINTAAN --</option>
                        <?php if (!empty($permintaanData)) { ?>
                            <?php foreach ($permintaanData as $pd) { ?>
                                <option value="<?= $pd['id_permintaan']; ?>" <?= ($pd['id_permintaan'] === $lowonganData['id_permintaan']) ? 'selected' : ''; ?>>
                                    [<?= htmlspecialchars($pd['nama_divisi']); ?>] - (<?= $pd['jumlah_permintaan']; ?> orang)
                                </option>
                            <?php } ?>
                        <?php } else { ?>
                            <option value="">Tidak ada data permintaan</option>
                        <?php } ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="" class="form-label">Poster Lowongan</label>
                    <input type="file" name="poster_lowongan" id="" class="form-control">
                    <div class="form-text">Poster saat ini: <a href="/sistem-penerimaan-karyawan/assets/uploads/poster/<?= $lowonganData['poster_lowongan'] ?>" target="_blank"><?= $lowonganData['poster_lowongan'] ?></a></div>
                </div>
                <div class="mb-4">
                    <label for="" class="form-label">Deskripsi Pekerjaan</label>
                    <textarea name="deskripsi" id="" cols="30" rows="5" class="form-control" required><?= $lowonganData['deskripsi'] ?></textarea>
                </div>

                <p>Isi persyaratan untuk lowongan, pada bagian di bawah ini:</p>

                <div class="row mb-3">
                    <div class="col-12 col-lg-6">
                        <label for="" class="form-label">Umur</label>
                        <input type="number" name="umur" id="" class="form-control" value="<?= $persyaratanData['umur'] ?>" required>
                    </div>
                    <div class="col-12 col-lg-6">
                        <label for="" class="form-label">Pendidikan Terakhir</label>
                        <select name="pendidikan" class="form-select" required>
                            <option selected disabled>-- PILIH PENDIDIKAN TERAKHIR --</option>
                            <option value="SMA/SMK" <?php echo ($persyaratanData['pendidikan'] === 'SMA/SMK') ? 'selected' : ''; ?>>
                                SMA/SMK
                            </option>
                            <option value="Diploma" <?php echo ($persyaratanData['pendidikan'] === 'Diploma') ? 'selected' : ''; ?>>
                                Diploma
                            </option>
                            <option value="Sarjana" <?php echo ($persyaratanData['pendidikan'] === 'Sarjana') ? 'selected' : ''; ?>>
                                Sarjana
                            </option>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="" class="form-label">Pengalaman Kerja</label>
                    <textarea name="pengalaman_kerja" id="" cols="30" rows="5" class="form-control"><?= $persyaratanData['pengalaman_kerja'] ?></textarea>
                </div>

                <button type="submit" class="btn btn-warning">Update</button>
            </form>
        </div>
    </div>

</div>

<?php require_once ('./../_components/scripts.php'); ?>
</body>
</html>