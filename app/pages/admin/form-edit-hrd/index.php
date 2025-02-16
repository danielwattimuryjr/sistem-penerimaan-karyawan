<?php
require_once('./../../../functions/init-session.php');
require_once('./../../../functions/init-conn.php');
require_once('./../../../functions/page-protection.php');

$id_user = isset($_GET['id_user']) ? $_GET['id_user'] : null;

if (!$id_user) {
    $type = 'error';
    $message = 'Data HRD tidak ditemukan';
    header("Location: " . BASE_URL . "/data-hrd?type=$type&message=" . urlencode($message));
    exit();
}
$getHrdQueryStr = "SELECT name, email, password, user_name, nomor_telepon, tempat_lahir, tanggal_lahir,jenis_kelamin, alamat FROM user WHERE id_user = ?";

$getHrdStmt = $conn->prepare($getHrdQueryStr);
$getHrdStmt->bind_param('i', $id_user);
$getHrdStmt->execute();
$hrd = $getHrdStmt->get_result()->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form HRD</title>

    <link rel="shortcut icon" href="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/compiled/svg/favicon.svg"
        type="image/x-icon">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/compiled/css/app.css">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/compiled/css/iconly.css">
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/scss/pages/sweetalert2.scss">
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/extensions/sweetalert2/sweetalert2.min.css">
</head>

<>
    
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
                <h3>Formulir HRD</h3>
            </div>
            <div class="page-content">
                <section class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <form action="update-hrd-request.php" method="POST">
                                    <input type="hidden" name="id_user" value="<?= $id_user ?>">
                                    <div class="mb-3">
                                        <label class="form-label">Nama Lengkap</label>
                                        <input type="text" class="form-control" placeholder="Nama Lengkap" name="name"
                                            required value="<?= $hrd['name'] ?>">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Email</label>
                                        <input type="email" class="form-control" placeholder="Email" name="email"
                                            required value="<?= $hrd['email'] ?>">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Username</label>
                                        <input type="text" class="form-control" placeholder="username" name="user_name"
                                            required value="<?= $hrd['user_name'] ?>">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Nomor Telepon</label>
                                        <input type="text" class="form-control" name="nomor_telepon"
                                            placeholder="Nomor HP Aktif" required value="<?= $hrd['nomor_telepon'] ?>">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Tempat, Tanggal Lahir</label>
                                        <div class="row">
                                            <div class="col">
                                                <input type="text" class="form-control" name="tempat_lahir"
                                                    placeholder="Tempat" required value="<?= $hrd['tempat_lahir'] ?>">
                                            </div>
                                            <div class="col">
                                                <input type="date" name="tanggal_lahir" id="" class="form-control"
                                                    required value="<?= $hrd['tanggal_lahir'] ?>">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Jenis Kelamin</label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" value="1" name="jenis_kelamin"
                                                <?= $hrd['jenis_kelamin'] ? 'checked' : '' ?>>
                                            <label class="form-check-label">
                                                Laki-laki
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" value="0" name="jenis_kelamin"
                                                <?= !is_null(['jenis_kelamin']) && !$hrd['jenis_kelamin'] ? 'checked' : '' ?>>
                                            <label class="form-check-label">
                                                Perempuan
                                            </label>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Alamat</label>
                                        <textarea name="alamat" id="" cols="30" rows="5" class="form-control"
                                            required><?= $hrd['alamat'] ?></textarea>
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
    </body>
    <script
        src="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/extensions/sweetalert2/sweetalert2.min.js"></script>
    <script src="/assets/js/sweet-alert.js"></script>

</html>