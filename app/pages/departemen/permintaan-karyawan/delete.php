<?php
require_once('./../../../functions/init-conn.php');

$id_permintaan = isset($_GET['id_permintaan']) ? $_GET['id_permintaan'] : null;

if (!$id_permintaan) {
    $type = 'error';
    $message = 'Data permintaan tidak ditemukan';
    header("Location: /pages/departemen/beranda?type=$type&message=" . urlencode($message));
    exit();
}

$getPermintaanQueryStr = "SELECT id_permintaan, id_user, jumlah_permintaan, status_permintaan FROM permintaan WHERE id_permintaan = ? LIMIT 1";
$getPermintaanStmt = $conn->prepare($getPermintaanQueryStr);
$getPermintaanStmt->bind_param("i", $id_permintaan);
$getPermintaanStmt->execute();
$getPermintaanResult = $getPermintaanStmt->get_result()->fetch_assoc();

if ($getPermintaanResult['status_permintaan'] !== 'Pending') {
    $type = 'error';
    $message = 'Data yang telah disetujui, tidak bisa diubah atau dihapus';
    header("Location: /pages/departemen/permintaan-karyawan?type=$type&message=" . urlencode($message));
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

header("Location: /pages/departemen/permintaan-karyawan?type=$type&message=" . urlencode($message));
exit();
