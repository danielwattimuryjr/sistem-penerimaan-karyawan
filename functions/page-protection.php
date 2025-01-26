<?php

require_once('init-session.php');
require_once('string-helpers.php');

if (!$_SESSION['user']) {
    // header("Location: /sistem-penerimaan-karyawan");
}

// Get the current URL
$currentUrl = $_SERVER['REQUEST_URI'];

// Extract the role from the URL
// Assuming the URL structure is "/sistem-permintan-karyawan/pages/[role]/[page-folder]"
$parts = explode('/', trim($currentUrl, '/'));
$roleFromUrl = $parts[2] ?? ''; // [role] is at index 3 (adjust if the structure changes)

$roleFromUrlSnakeCase = toKebabCase($roleFromUrl);

$userRole = $_SESSION['user']['role'] ?? '';
$userRoleSnakeCase = toKebabCase($userRole);


// Check if the roles match
if ($roleFromUrlSnakeCase !== $userRoleSnakeCase) {
    header("Location: /sistem-penerimaan-karyawan/403"); // Redirect to a 403 Forbidden page or appropriate error page
    exit();
}