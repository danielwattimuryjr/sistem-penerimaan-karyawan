<?php
require_once('./../../../functions/init-conn.php');
require_once('./../../../functions/page-protection.php');

$permintaanQuery = "
    SELECT p.id_permintaan, p.jumlah_permintaan, d.nama_divisi
    FROM permintaan p
    LEFT JOIN lowongan l ON p.id_permintaan = l.id_permintaan
    INNER JOIN divisi d ON p.id_divisi = d.id_divisi
    WHERE p.status_permintaan = ? AND l.id_lowongan IS NULL
";
$stmt = $conn->prepare($permintaanQuery);
$status = 'Disetujui';
$stmt->bind_param('s', $status);
$stmt->execute();
$result = $stmt->get_result();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Tambah Lowongan Pekerjaan</title>

    <?php require_once ('./../_components/styles.php'); ?>
</head>
<body>
<?php require_once ('./../_components/navbar.php'); ?>

<div class="container-sm mt-3 mt-lg-5">
    <div class="card" style="width: 100%;">
        <div class="card-body">
            <h5 class="card-title text-center">Tambah Lowongan Pekerjaan</h5>

            <form action="post-lowongan-request.php" method="post" class="mt-4">
                <div class="mb-3">
                    <label for="" class="form-label">Nama Lowongan</label>
                    <input type="text" name="nama_lowongan" id="" class="form-control" required>
                </div>
                <div class="row mb-3">
                    <div class="col-12 col-lg-6">
                        <label for="" class="form-label">Tanggal Mulai</label>
                        <input type="date" name="tanggal_mulai" id="" class="form-control" required>
                    </div>
                    <div class="col-12 col-lg-6">
                        <label for="" class="form-label">Tanggal Selesai</label>
                        <input type="date" name="tanggal_selesai" id="" class="form-control" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="" class="form-label">Permintaan</label>
                    <select name="id_permintaan" id="" class="form-control">
                        <option value="" selected disabled>-- PILIH PERMINTAAN --</option>
                        <?php if ($result && $result->num_rows > 0): ?>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <option value="<?= $row['id_permintaan']; ?>">
                                    [<?= htmlspecialchars($row['nama_divisi']); ?>] - (<?= $row['jumlah_permintaan']; ?> orang)
                                </option>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <option value="" disabled>Tidak ada permintaan yang tersedia</option>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="" class="form-label">Deskripsi Pekerjaan</label>
                    <textarea name="deskripsi" id="" cols="30" rows="5" class="form-control" required></textarea>
                </div>

                <p>Isi persyaratan untuk lowongan, pada bagian di bawah ini:</p>

                <div class="row mb-3">
                    <div class="col-12 col-lg-6">
                        <label for="" class="form-label">Umur</label>
                        <input type="number" name="umur" id="" class="form-control" required>
                    </div>
                    <div class="col-12 col-lg-6">
                        <label for="" class="form-label">Pendidikan Terakhir</label>
                        <select name="pendidikan" class="form-select" required>
                            <option selected disabled>-- PILIH PENDIDIKAN TERAKHIR --</option>
                            <option value="SMA/SMK">
                                SMA/SMK
                            </option>
                            <option value="Diploma">
                                Diploma
                            </option>
                            <option value="Sarjana">
                                Sarjana
                            </option>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="" class="form-label">Pengalaman Kerja</label>
                    <textarea name="pengalaman_kerja" id="" cols="30" rows="5" class="form-control"></textarea>
                </div>

                <button type="submit" class="btn btn-primary">Simpan</button>
            </form>
        </div>
    </div>

</div>

<?php require_once ('./../_components/scripts.php'); ?>
</body>
</html>