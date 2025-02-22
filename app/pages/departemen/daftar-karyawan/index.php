<?php
require_once('./../../../functions/init-conn.php');
require_once('./../../../functions/page-protection.php');
$user = $_SESSION['user'];

$queryStr = "SELECT 
    k.id_karyawan,
    k.name,
    k.email,
    k.nomor_telepon,
    d.nama_divisi
FROM karyawan k
JOIN divisi d ON k.id_divisi = d.id_divisi
WHERE d.id_user = ?;";

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
  <title>Daftar Karyawan</title>

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
        <h3>Daftar Karyawan</h3>
      </div>
      <div class="page-content">
        <section class="row">
          <div class="col-12">
            <div class="card">
              <div
                class="card-header d-flex flex-column flex-md-row justify-content-start justify-content-md-between align-items-start align-items-md-center">
                <h5 class="card-title">Daftar Karyawan</h5>

                <a href="/pages/departemen/form-create-karyawan" class="btn btn-sm btn-primary">
                  Tambah Karyawan
                </a>
              </div>
              <div class="card-body">
                <div class="table-responsive datatable-minimal">
                  <table class="table" id="data-table">
                    <thead>
                      <tr>
                        <td>No</td>
                        <td>Nama</td>
                        <td>Divisi</td>
                        <td>Nomor Telepon</td>
                        <td>Actions</td>
                      </tr>
                    </thead>
                    <tbody>
                      <?php $no = 1 ?>
                      <?php foreach ($result as $row): ?>
                        <?php
                        $idKaryawan = $row['id_karyawan'];
                        $editUrl = "/pages/departemen/form-edit-karyawan?id_karyawan=$idKaryawan";
                        $deleteUrl = "delete.php?id_karyawan=$idKaryawan";
                        ?>
                        <tr>
                          <td><?= $no++ ?></td>
                          <td><strong><?= $row['name'] ?></strong><br><?= $row['email'] ?></td>
                          <td><?= $row['nama_divisi'] ?></td>
                          <td><?= $row['nomor_telepon'] ?></td>
                          <td>
                            <div class="btn-group">
                              <a href="<?= $editUrl ?>" class="btn btn-sm btn-warning">Edit</a>
                              <a href="<?= $deleteUrl ?>" class="btn btn-sm btn-danger">Hapus</a>
                            </div>
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