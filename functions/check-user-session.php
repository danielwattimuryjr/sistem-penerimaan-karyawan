<?php

require_once ('init-session.php');

if (isset($_SESSION['user'])) {
    switch ($_SESSION['user']['role']) {
        case 'General Manager':
            header("Location: /sistem-penerimaan-karyawan/pages/general-manager/beranda");
            break;
        case 'Departement':
            header("Location: /sistem-penerimaan-karyawan/pages/departemen/beranda");
            break;
        case 'HRD':
            header("Location: /sistem-penerimaan-karyawan/pages/hrd/beranda");
            break;
        case 'Pelamar':
            header("Location: /sistem-penerimaan-karyawan/pages/pelamar/beranda");
            break;
        default:
            header("Location: /sistem-penerimaan-karyawan/pages/auth/sign-in");
            break;
    }
}

