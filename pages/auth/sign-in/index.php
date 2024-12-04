<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sign In</title>

    <!--  Bootstrap 5.3 CSS  -->
    <link rel="stylesheet" href="/sistem-penerimaan-karyawan/assets/css/bootstrap.min.css" crossorigin="anonymous">

    <style>
        body {
            background-color: #f1f1f1f1;
        }
    </style>
</head>
<body>
    <div class="container-sm d-flex justify-content-center align-items-center" style="min-height: 100vh">
        <div class="card" style="width: 60%;">
            <div class="card-body">
                <h5 class="card-title text-center">LOGIN</h5>

                <form action="action.php" method="POST">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" name="username" id="" class="form-control" autofocus required>
                    </div>

                    <div class="mb-3">
                        <label for="username" class="form-label">Password</label>
                        <input type="password" name="password" id="" class="form-control" required>
                    </div>

                    <div class="d-flex justify-content-center flex-column" style="width: 100%">
                        <button type="submit" class="btn btn-primary m-auto" style="width: 10rem">LOGIN</button>

                        <a href="../forget-password" class="mt-2 text-center" style="text-decoration: none">
                            Lupa Password?
                        </a>
                    </div>
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