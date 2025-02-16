<?php
require_once('./../../../functions/init-session.php');
require_once('./../../../functions/init-conn.php');
require_once('./../../../functions/page-protection.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form General Manager</title>

    <link rel="shortcut icon" href="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/compiled/svg/favicon.svg"
        type="image/x-icon">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/compiled/css/app.css">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/compiled/css/iconly.css">
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/scss/pages/sweetalert2.scss">
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/extensions/sweetalert2/sweetalert2.min.css">
</head>


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
            <h3>Formulir General Manager</h3>
        </div>
        <div class="page-content">
            <section class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="store-general-manager-request.php" method="POST">
                                <div class="mb-3">
                                    <label class="form-label">Nama Lengkap</label>
                                    <input type="text" class="form-control" placeholder="Nama Lengkap" name="name"
                                        required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" placeholder="Email" name="email" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Username</label>
                                    <input type="text" class="form-control" placeholder="username" name="user_name"
                                        required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Password</label>
                                    <input type="password" class="form-control" placeholder="password" name="password"
                                        required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Nomor Telepon</label>
                                    <input type="text" class="form-control" name="nomor_telepon"
                                        placeholder="Nomor HP Aktif" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Tempat, Tanggal Lahir</label>
                                    <div class="row">
                                        <div class="col">
                                            <input type="text" class="form-control" name="tempat_lahir"
                                                placeholder="Tempat" required>
                                        </div>
                                        <div class="col">
                                            <input type="date" name="tanggal_lahir" id="" class="form-control" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Jenis Kelamin</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" value="1" name="jenis_kelamin">
                                        <label class="form-check-label">
                                            Laki-laki
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" value="0" name="jenis_kelamin">
                                        <label class="form-check-label">
                                            Perempuan
                                        </label>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Alamat</label>
                                    <textarea name="alamat" id="" cols="30" rows="5" class="form-control"
                                        required></textarea>
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