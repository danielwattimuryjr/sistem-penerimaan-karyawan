<?php
    require_once ('./../../../../functions/init-session.php');
    require_once ('./../../../../functions/init-conn.php');
    if (!$_SESSION['user']) {
        header("Location: /sistem-penerimaan-karyawan/pages/auth/sign-in");
    }

    $getDivisiQueryStr = "SELECT id_divisi, nama_divisi FROM divisi";
    $getDivisiStmt = $conn->prepare($getDivisiQueryStr);
    $getDivisiStmt->execute();
    $getDivisiResult = $getDivisiResult->get_result();

    $conn->close();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Tambah Lowongan</title>

    <!--  Bootstrap 5.3 CSS  -->
    <link rel="stylesheet" href="/sistem-penerimaan-karyawan/assets/css/bootstrap.min.css" crossorigin="anonymous">

    <style>
        body {
            background-color: #f1f1f1f1;
        }
    </style>
</head>
<body>
    <?php require_once ('./../../_components/navbar.php'); ?>

    <div class="container-sm mt-3 mt-lg-5">
        <div class="card" style="width: 100%;">
            <div class="card-body">
              <h5 class="card-title text-center">Tambah Lowongan Pekerjaan</h5>
              
              <form>
                <div class="row mb-3">
                  <div class="col-md-6">
                    <label for="startDate" class="form-label">Tanggal Mulai</label>
                    <div class="input-group">
                      <input type="date" class="form-control" id="startDate" name="tgl_mulai" required>
                      <span class="input-group-text"><i class="bi bi-calendar"></i></span>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <label for="endDate" class="form-label">Tanggal Selesai</label>
                    <div class="input-group">
                      <input type="date" class="form-control" id="endDate" name="tgl_selesai" required>
                      <span class="input-group-text"><i class="bi bi-calendar"></i></span>
                    </div>
                  </div>
                </div>
                <div class="mb-3">
                  <label for="division" class="form-label">Pilih Divisi</label>
                  <select id="division" class="form-select" name="id_divisi">
                    <option selected disabled>Pilih Divisi</option>
                    <?php foreach ($getDivisiResult as $res) { ?>
                      <option value="<?= $res['id_divisi'] ?>">
                        <?= $res['nama_divisi'] ?>
                      </option>
                    <?php } ?>
                  </select>
                </div>
                <div class="mb-3">
                  <label for="poster" class="form-label">Upload Poster Lowongan Pekerjaan</label>
                  <input type="file" class="form-control" id="poster" name="poster_lowongan">
                </div>
                <div class="mb-3">
                  <label for="description" class="form-label">Deskripsi Pekerjaan</label>
                  <textarea id="description" class="form-control" rows="5" name="deskripsi"></textarea>
                </div>
                <button type="submit" class="btn btn-primary w-100">Simpan</button>
              </form>
            </div>
        </div>
    </div>


    <!--  Bootstrap 5.3 JS  -->
    <script src="/sistem-penerimaan-karyawan/assets/js/popper.min.js" crossorigin="anonymous"></script>
    <script src="/sistem-penerimaan-karyawan/assets/js/bootstrap.min.js" crossorigin="anonymous"></script>

    <!--  SweetAlert2  -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>