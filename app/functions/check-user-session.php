<?php

require_once('init-session.php');

if (isset($_SESSION['user'])) {
    switch ($_SESSION['user']['role']) {
        case 'General Manager':
            header("Location: /pages/general-manager/beranda");
            break;
        case 'Departement':
            header("Location: /pages/departemen/beranda");
            break;
        case 'HRD':
            header("Location: /pages/hrd/beranda");
            break;
        case 'Pelamar':
            header("Location: /pages/public/landing-page");
            break;
        default:
            header("Location: /pages/auth/sign-in");
            break;
    }
}
