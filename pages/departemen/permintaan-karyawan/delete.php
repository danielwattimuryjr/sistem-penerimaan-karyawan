<?php
require_once('./../../../functions/init-conn.php');

$id_permintaan = isset($_GET['id_permintaan']) ? $_GET['id_permintaan'] : null;

if (!$id_permintaan) {
    $type = 'error';
    $message = 'Data permintaan tidak ditemukan';
    header("Location: /sistem-penerimaan-karyawan/pages/departemen/beranda?type=$type&message=" . urlencode($message));
    exit();
}

$queryStr = "DELETE FROM permintaan WHERE id_permintaan = ?";
$stmt = $conn->prepare($queryStr);
$stmt->bind_param('i', $id_permintaan);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        $type = 'success';
        $message = 'Data permintaan berhasil dihapus.';
    } else {
        $type = 'error';
        $message = 'Data permintaan tidak ditemukan.';

    }
} else {
    $type = 'error';
    $message = 'Gagal menghapus data permintaan. Silakan coba lagi.';
}

header("Location: /sistem-penerimaan-karyawan/pages/departemen/permintaan-karyawan?type=$type&message=" . urlencode($message));
exit();

