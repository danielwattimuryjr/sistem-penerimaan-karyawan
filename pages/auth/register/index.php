<?php
require_once ('./../../../functions/init-session.php');
require_once ('./../../../functions/check-user-session.php');
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sign In</title>

    <?php
        require_once ('./../_components/styles.php');
    ?>
</head>
<body>
<div class="container-sm d-flex justify-content-center align-items-center" style="min-height: 100vh">
    <div class="card" style="width: 60%;">
        <div class="card-body">
            <h5 class="card-title text-center">REGISTER</h5>

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
                    <button type="submit" class="btn btn-primary m-auto" style="width: 10rem">LOGIN</button>

                    <a href="../sign-in" class="mt-2 text-center" style="text-decoration: none">
                        Sudah punya akun? Login di sini
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
    require_once ('./../_components/scripts.php');
?>
</body>
</html>