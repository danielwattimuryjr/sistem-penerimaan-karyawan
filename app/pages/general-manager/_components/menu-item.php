<?php
define("BASE_URL", "/pages/general-manager");

function renderMenu($sidebarItems)
{
    foreach ($sidebarItems as $item) {
        if ($item['isTitle']) {
            echo '<li class="sidebar-title">' . htmlspecialchars($item['name']) . '</li>';
        } else {
            $hasSub = !empty($item['submenu']) ? 'has-sub' : '';
            echo '<li class="sidebar-item ' . $hasSub . '">';
            echo '<a href="' . BASE_URL . ($item['url'] ?? '#') . '" class="sidebar-link">';
            echo '<i class="bi bi-' . htmlspecialchars($item['icon']) . '"></i>';
            echo '<span>' . htmlspecialchars($item['name']) . '</span>';
            echo '</a>';

            if (!empty($item['submenu'])) {
                renderSubmenu($item['submenu'], 2);
            }

            echo '</li>';
        }
    }
}

function renderSubmenu($submenu, $level = 2)
{
    echo '<ul class="submenu submenu-level-' . $level . '">';
    foreach ($submenu as $sub) {
        $hasSub = !empty($sub['submenu']) ? 'has-sub' : '';
        echo '<li class="submenu-item ' . $hasSub . '">';
        echo '<a href="' . $sub['url'] . '" class="submenu-link">' . htmlspecialchars($sub['name']) . '</a>';

        if (!empty($sub['submenu'])) {
            renderSubmenu($sub['submenu'], $level + 1);
        }

        echo '</li>';
    }
    echo '</ul>';
}