<?php
require_once('menu-item.php');
require_once('./../../../functions/init-session.php');

$menuItem = [
    [
        'isTitle' => true,
        'name' => 'Main Menu'
    ],
    [
        'isTitle' => false,
        'name' => 'Beranda',
        'url' => '/beranda',
        'icon' => 'house-door-fill',
        'submenu' => [],
    ],
    [
        'isTitle' => false,
        'name' => 'Permintaan Karyawan',
        'url' => '/permintaan-karyawan',
        'icon' => 'person-plus-fill',
        'submenu' => [],
    ],
    [
        'isTitle' => false,
        'name' => 'Data Pelamar',
        'url' => '/data-pelamar',
        'icon' => 'newspaper',
        'submenu' => [],
    ],
    [
        'isTitle' => false,
        'name' => 'Hasil Seleksi',
        'url' => '/hasil-seleksi',
        'icon' => 'buildings-fill',
        'submenu' => [],
    ],
    [
        'isTitle' => false,
        'name' => 'Data Department',
        'url' => '/data-departemen',
        'icon' => 'buildings-fill',
        'submenu' => [],
    ],
    [
        'isTitle' => false,
        'name' => 'Data Divisi',
        'url' => '/data-divisi',
        'icon' => 'buildings-fill',
        'submenu' => [],
    ],
    [
        'isTitle' => false,
        'name' => 'Data Karyawan',
        'url' => '/data-karyawan',
        'icon' => 'buildings-fill',
        'submenu' => [],
    ],
    [
        'isTitle' => true,
        'name' => 'Pengaturan'
    ],
];
?>
<div class="sidebar-wrapper active">
    <div class="sidebar-header position-relative">
        <div class="position-relative">
            <div class="d-flex flex-column align-items-center">
                <div class="logo">
                    <a href="<?= BASE_URL ?>">
                        <img src="/assets/images/app-logo.png" alt="Logo"
                            style="width: 100px; height: 100px; object-fit: cover;">
                    </a>
                </div>
                <h5 class="mt-2">Grand Pasundan</h5>
            </div>

            <div class="sidebar-toggler x" style="top: 0px; right: 0px;">
                <a href="#" class="sidebar-hide"><i class="bi bi-x bi-middle"></i></a>
            </div>
        </div>

        <h5 class="text-center">
            <?= $_SESSION['user']['name'] ?>
        </h5>
    </div>

    <div class="sidebar-menu">
        <ul class="menu">
            <?php renderMenu($menuItem) ?>
            <li class="sidebar-item">
                <a href=<?= BASE_URL . '/beranda/logout.php' ?> class="sidebar-link text-danger">
                    <i class="bi bi-box-arrow-left text-danger"></i>
                    <span>Logout</span>
                </a>
            </li>
        </ul>
    </div>
</div>