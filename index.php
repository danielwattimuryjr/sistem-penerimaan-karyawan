<?php

include_once('./functions/init-session.php');

if (isset($_SESSION['user']) && isset($_SESSION['user']['role'])) {
    switch ($_SESSION['user']['role']) {
        case 'General Manager':
            header("Location: /sistem-penerimaan-karyawan/pages/general-manager/beranda");
            exit();
        case 'Departement':
            header("Location: /sistem-penerimaan-karyawan/pages/departemen/beranda");
            exit();
        case 'HRD':
            header("Location: /sistem-penerimaan-karyawan/pages/hrd/beranda");
            exit();
        case 'Pelamar':
            header("Location: /sistem-penerimaan-karyawan/pages/public/landing-page");
            exit();
        case 'Admin':
            header("Location: /sistem-penerimaan-karyawan/pages/admin/beranda");
            exit();
        default:
            header("Location: /sistem-penerimaan-karyawan/pages/public/landing-page");
            exit();
    }
} else {
    header("Location: /sistem-penerimaan-karyawan/pages/public/landing-page");
    exit();
}
