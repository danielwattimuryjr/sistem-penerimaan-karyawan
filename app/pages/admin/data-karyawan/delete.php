<?php
require_once('./../../../functions/init-conn.php');

$id_karyawan = isset($_GET['id_karyawan']) ? $_GET['id_karyawan'] : null;

if (!$id_karyawan) {
    $type = 'error';
    $message = 'Data karyawan tidak ditemukan';
    header("Location: /pages/admin/data-karyawan?type=$type&message=" . urlencode($message));
    exit();
}

$queryStr = "DELETE FROM karyawan WHERE id_karyawan = ?";
$stmt = $conn->prepare($queryStr);
$stmt->bind_param('i', $id_karyawan);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        $type = 'success';
        $message = 'Data karyawan berhasil dihapus.';
    } else {
        $type = 'error';
        $message = 'Data karyawan tidak ditemukan.';

    }
} else {
    $type = 'error';
    $message = 'Gagal menghapus data karyawan. Silakan coba lagi.';
}

header("Location: /pages/admin/data-karyawan?type=$type&message=" . urlencode($message));
exit();
