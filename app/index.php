<?php

include_once('./functions/init-session.php');

if (isset($_SESSION['user']) && isset($_SESSION['user']['role'])) {
    switch ($_SESSION['user']['role']) {
        case 'General Manager':
            header("Location: /pages/general-manager/beranda");
            exit();
        case 'Departement':
            header("Location: /pages/departemen/beranda");
            exit();
        case 'HRD':
            header("Location: /pages/hrd/beranda");
            exit();
        case 'Pelamar':
            header("Location: /pages/public/landing-page");
            exit();
        case 'Admin':
            header("Location: /pages/admin/beranda");
            exit();
        default:
            header("Location: /pages/public/landing-page");
            exit();
    }
} else {
    header("Location: /pages/public/landing-page");
    exit();
}
