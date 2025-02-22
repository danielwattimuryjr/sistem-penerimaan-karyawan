<?php
require_once('./../../../functions/init-session.php');
require_once('./../../../functions/init-conn.php');

$id_divisi = isset($_GET['id_divisi']) ? $_GET['id_divisi'] : null;

if (!$id_divisi) {
  $type = 'error';
  $message = 'Data divisi tidak ditemukan';
  header("Location: /pages/departemen/daftar-divisi?type=$type&message=" . urlencode($message));
  exit();
}

$queryStr = "SELECT id_divisi, nama_divisi, jumlah_personil FROM divisi WHERE id_divisi = ? LIMIT 1";
$stmt = $conn->prepare($queryStr);
$stmt->bind_param('i', $id_divisi);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Form Divisi</title>

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
        <h5 class="card-title">Formulir Edit Divisi</h5>
      </div>
      <div class="page-content">
        <section class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-body">
                <form action="update.php" method="POST">
                  <input type="hidden" name="id_divisi" value="<?= $result['id_divisi'] ?>">
                  <div class="mb-3">
                    <label class="form-label">Nama Divisi</label>
                    <input type="text" class="form-control" name="nama_divisi" value="<?= $result['nama_divisi'] ?>"
                      required>
                  </div>

                  <div class="mb-3">
                    <label class="form-label">Jumlah Maksimum Personil</label>
                    <input type="number" class="form-control" name="jumlah_personil" min="0"
                      value="<?= $result['jumlah_personil'] ?>" required>
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