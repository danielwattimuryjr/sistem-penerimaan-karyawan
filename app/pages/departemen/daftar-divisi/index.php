<?php
require_once('./../../../functions/init-conn.php');
require_once('./../../../functions/page-protection.php');

$user = $_SESSION['user'];

$queryStr = "SELECT
    id_divisi,
    nama_divisi,
    jumlah_personil AS jumlah_max,
    current_karyawan AS jumlah_saat_ini,
    isInNeed
FROM divisi_status
WHERE id_department = ?";

$stmt = $conn->prepare($queryStr);
$stmt->bind_param('i', $user['id_user']);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Daftar Divisi</title>

  <link rel="shortcut icon" href="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/compiled/svg/favicon.svg"
    type="image/x-icon">

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/compiled/css/app.css">

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/compiled/css/iconly.css">
  <link rel="stylesheet"
    href="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/scss/pages/datatables.scss">
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
        <h3>Daftar Divisi</h3>
      </div>
      <div class="page-content">
        <section class="row">
          <div class="col-12">
            <div class="card">
              <h5 class="card-title">Daftar Divisi</h5>
              <div class="card-body">
                <div class="table-responsive datatable-minimal">
                  <table class="table" id="data-table">
                    <thead>
                      <tr>
                        <td>No</td>
                        <td>Nama Divisi</td>
                        <td>Jumlah Anggota (Max.)</td>
                        <td>Jumlah Anggota (Saat ini)</td>
                        <td>Actions</td>
                      </tr>
                    </thead>
                    <tbody>
                      <?php $no = 1 ?>
                      <?php foreach ($result as $row): ?>
                        <?php
                        $idDivisi = $row['id_divisi'];
                        $formPermintaanUrl = $row['isInNeed'] ? "/pages/departemen/form-tambah-permintaan-karyawan?id_divisi=$idDivisi" : null;
                        ?>
                        <tr>
                          <td><?= $no++ ?></td>
                          <td><?= $row['nama_divisi'] ?></td>
                          <td><?= $row['jumlah_max'] ?></td>
                          <td><?= $row['jumlah_saat_ini'] ?></td>
                          <td>
                            <?php if ($formPermintaanUrl): ?>
                              <a href="<?= $formPermintaanUrl ?>" class="btn btn-sm btn-primary">Request</a>
                            <?php endif; ?>
                          </td>
                        </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </section>
      </div>
    </div>
  </div>

  <!-- End content -->

  <script
    src="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js"></script>
  <script src="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/compiled/js/app.js"></script>
  <script src="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/extensions/jquery/jquery.min.js"></script>
  <script
    src="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/extensions/datatables.net/js/jquery.dataTables.min.js"></script>
  <script
    src="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/extensions/datatables.net-bs5/js/dataTables.bootstrap5.min.js"></script>
  <script src="/assets/js/data-table.js"></script>
  <script
    src="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/extensions/sweetalert2/sweetalert2.min.js"></script>
  <script src="/assets/js/sweet-alert.js"></script>
</body>

</html>