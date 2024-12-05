<?php

// Get id_lowongan
$id_lowongan = isset($_GET['id_lowongan']) ? $_GET['id_lowongan'] : null;

if (!$id_lowongan) {
  header('Location : /sistem-penerimaan-karyawan/pages/depatemen/beranda');
  exit();
}

$queryStr = "SELECT nama_lowongan, deskripsi FROM "
?>