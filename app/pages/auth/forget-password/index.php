<?php
require_once('./../../../functions/init-session.php');
require_once('./../../../functions/check-user-session.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forget Password</title>

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
                    <div class="d-flex flex-column align-items-center">
                        <div class="logo">
                            <img src="/assets/images/app-logo.png" alt="Logo"
                                style="width: 100px; height: 100px; object-fit: cover;">
                        </div>
                        <h5 class="card-title text-center">Lupa Password</h5>
                    </div>
                </div>
                <div class="card-body">
                    <form action="send-mail.php" method="POST">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="text" name="email" id="" class="form-control" autofocus>
                        </div>

                        <div class="d-flex justify-content-center flex-column" style="width: 100%">
                            <button type="submit" class="btn btn-primary m-auto" style="width: 10rem">
                                Lupa Password
                            </button>

                            <a href="../sign-in" class="mt-4 text-center" style="text-decoration: none">
                                Kembali kehalaman Login
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