<?php
require_once('./../../../functions/init-session.php');
require_once('./../../../functions/check-user-session.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>

    <link rel="shortcut icon" href="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/compiled/svg/favicon.svg"
        type="image/x-icon">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/compiled/css/app.css">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/compiled/css/iconly.css">
</head>

<body>
    
    <!-- Start content here -->

    <div id="app">
        <div class="container-sm d-flex justify-content-center align-items-center" style="min-height: 100vh">
            <div class="card" style="width: 50%;">
                <div class="card-header">
                    <h5 class="card-title text-center">Register</h5>
                </div>
                <div class="card-body">
                    <form action="action.php" method="POST">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" name="username" id="" class="form-control" autofocus required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" id="" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                            <input type="nama_lengkap" name="nama_lengkap" id="" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="username" class="form-label">Password</label>
                            <input type="password" name="password" id="" class="form-control" required>
                        </div>

                        <div class="d-flex justify-content-center flex-column" style="width: 100%">
                            <button type="submit" class="btn btn-primary m-auto" style="width: 10rem">REGISTER</button>

                            <a href="../sign-in" class="mt-4 text-center" style="text-decoration: none">
                                Sudah punya akun? Login di sini
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- End content -->
    
    <script
        src="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/compiled/js/app.js"></script>
</body>

</html>