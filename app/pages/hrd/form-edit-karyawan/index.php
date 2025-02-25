<?php
require_once('./../../../functions/init-session.php');
require_once('./../../../functions/init-conn.php');
require_once('./../../../functions/page-protection.php');

$id_karyawan = isset($_GET['id_karyawan']) ? $_GET['id_karyawan'] : null;

if (!$id_karyawan) {
  $type = 'error';
  $message = 'Data karyawan tidak ditemukan';
  header("Location: /pages/hrd/data-karyawan?type=$type&message=" . urlencode($message));
  exit();
}

$karyawanSql = "SELECT id_karyawan, name, email, tempat_lahir, nomor_telepon, tanggal_lahir, jenis_kelamin, pendidikan_terakhir, alamat, id_divisi FROM karyawan WHERE id_karyawan = ? LIMIT 1";
$karyawanStmt = $conn->prepare($karyawanSql);
$karyawanStmt->bind_param('i', $id_karyawan);
$karyawanStmt->execute();
$karyawan = $karyawanStmt->get_result()->fetch_assoc();


$sql = "
    SELECT
        id_divisi,
        nama_divisi,
        nama_department,
        jumlah_personil AS jumlah_max,
        current_karyawan AS jumlah_saat_ini,
        isInNeed
    FROM divisi_status
";

$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
$divisi = [];
while ($row = $result->fetch_assoc()) {
  $divisi[] = $row;
}
$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Form Karyawan</title>

  <link rel="shortcut icon" href="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/compiled/svg/favicon.svg"
    type="image/x-icon">

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/compiled/css/app.css">

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/compiled/css/iconly.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/scss/pages/sweetalert2.scss">
  <link rel="stylesheet"
    href="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/extensions/sweetalert2/sweetalert2.min.css">
</head>

<body>

  <!-- Start content here -->

  <div id="app">
    <div id="sidebar">
      <?php require_once('./../_components/sidebar.php'); ?>
    </div>
    <div id="main">
      <header class="mb-3">
        <a href="#" class="burger-btn d-block d-xl-none">
          <i class="bi bi-justify fs-3"></i>
        </a>
      </header>
      <!-- Content -->
      <div class="page-heading">
        <h5 class="card-title">Formulir Edit Karyawan</h5>
      </div>
      <div class="page-content">
        <section class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-body">
                <form action="update.php" method="POST">
                  <input type="hidden" name="id_karyawan" value="<?= $karyawan['id_karyawan'] ?>">
                  <div class="mb-3">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" class="form-control" name="name" value="<?= $karyawan['name'] ?>" required>
                  </div>

                  <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" value="<?= $karyawan['email'] ?>" required>
                  </div>

                  <div class="mb-3">
                    <label class="form-label">Tempat, Tanggal Lahir</label>
                    <div class="row">
                      <div class="col">
                        <input type="text" class="form-control" name="tempat_lahir"
                          value="<?= $karyawan['tempat_lahir'] ?>" required>
                      </div>
                      <div class="col">
                        <input type="date" name="tanggal_lahir" class="form-control"
                          value="<?= $karyawan['tanggal_lahir'] ?>" required>
                      </div>
                    </div>
                  </div>

                  <div class="mb-3">
                    <label class="form-label">Nomor HP</label>
                    <input type="text" class="form-control" name="nomor_telepon"
                      value="<?= $karyawan['nomor_telepon'] ?>" required>
                  </div>

                  <div class="mb-3">
                    <label class="form-label">Jenis Kelamin</label>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" value="1" name="jenis_kelamin"
                        <?= ($karyawan['jenis_kelamin'] == 1) ? 'checked' : '' ?> required>
                      <label class="form-check-label">
                        Laki-laki
                      </label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" value="0" name="jenis_kelamin"
                        <?= ($karyawan['jenis_kelamin'] == 0) ? 'checked' : '' ?> required>
                      <label class="form-check-label">
                        Perempuan
                      </label>
                    </div>
                  </div>

                  <div class="mb-3">
                    <label class="form-label">Pendidikan Terakhir</label>
                    <select class="form-select" name="pendidikan_terakhir" required>
                      <option disabled>-- PILIH PENDIDIKAN TERAKHIR --</option>
                      <option value="SMA/SMK" <?= ($karyawan['pendidikan_terakhir'] == 'SMA/SMK') ? 'selected' : '' ?>>
                        SMA/SMK</option>
                      <option value="Diploma" <?= ($karyawan['pendidikan_terakhir'] == 'Diploma') ? 'selected' : '' ?>>
                        Diploma</option>
                      <option value="Sarjana" <?= ($karyawan['pendidikan_terakhir'] == 'Sarjana') ? 'selected' : '' ?>>
                        Sarjana</option>
                    </select>
                  </div>

                  <div class="mb-3">
                    <label class="form-label">Alamat</label>
                    <textarea cols="30" rows="5" class="form-control" name="alamat"
                      required><?= $karyawan['alamat'] ?></textarea>
                  </div>

                  <div class="mb-3">
                    <label class="form-label">Divisi</label>
                    <select class="form-select" name="id_divisi" required>
                      <option selected disabled>-- PILIH DIVISI --</option>
                      <?php foreach ($divisi as $d): ?>
                        <option value="<?= $d['id_divisi'] ?>" <?= ($d['id_divisi'] == $karyawan['id_divisi']) ? 'selected' : '' ?>>
                          <?= htmlspecialchars($d['nama_department'] . ' - ' . $d['nama_divisi']) ?>
                          (Max: <?= $d['jumlah_max'] ?>; Current <?= $d['jumlah_saat_ini'] ?>)
                        </option>
                      <?php endforeach; ?>
                    </select>
                  </div>

                  <button type="submit" class="btn btn-primary">Submit</button>
                </form>
              </div>
            </div>
          </div>
        </section>
      </div>
      <!-- End Content -->
    </div>
  </div>

  <!-- End content -->

  <script
    src="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js"></script>
  <script src="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/compiled/js/app.js"></script>
  <script src="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/extensions/tinymce/tinymce.min.js"></script>
  <script src="/assets/js/tiny-mce.js"></script>
  <script
    src="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/extensions/sweetalert2/sweetalert2.min.js"></script>
  <script src="/assets/js/sweet-alert.js"></script>
</body>

</html>