<?php

require_once ('./../../../functions/init-session.php');

session_destroy();

header("Location: /sistem-penerimaan-karyawan/pages/auth/sign-in");
