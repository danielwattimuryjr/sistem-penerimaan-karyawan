<?php

require_once('./../../../functions/init-session.php');

session_destroy();

header("Location: /sistem-penerimaan-karyawan/pages/public/landing-page?type=success&message=" . urlencode('Berhasil Logout!'));
