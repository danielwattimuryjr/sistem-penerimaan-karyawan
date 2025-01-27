<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $keyword = $_POST['search'];
    header("Location: /sistem-penerimaan-karyawan/pages/public/landing-page?search=" . urlencode($keyword));
    exit;
}