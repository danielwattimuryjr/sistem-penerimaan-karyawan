<?php
require_once('menu-item.php');

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
        'name' => 'Data General Manager',
        'url' => '/data-general-manager',
        'icon' => 'person-plus-fill',
        'submenu' => [],
    ],
    [
        'isTitle' => false,
        'name' => 'Data HRD',
        'url' => '/data-hrd',
        'icon' => 'newspaper',
        'submenu' => [],
    ],
    [
        'isTitle' => false,
        'name' => 'Data Departemen',
        'url' => '/data-departemen',
        'icon' => 'newspaper',
        'submenu' => [],
    ],
    [
        'isTitle' => false,
        'name' => 'Data Divisi',
        'url' => '/data-divisi',
        'icon' => 'newspaper',
        'submenu' => [],
    ],
    [
        'isTitle' => false,
        'name' => 'Data Karyawan',
        'url' => '/data-karyawan',
        'icon' => 'newspaper',
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
