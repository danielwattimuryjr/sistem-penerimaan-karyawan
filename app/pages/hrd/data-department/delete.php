<?php
require_once('./../../../functions/init-conn.php');

$id_divisi = isset($_GET['id_divisi']) ? $_GET['id_divisi'] : null;

if (!$id_divisi) {
    $type = 'error';
    $message = 'Data divisi tidak ditemukan';
    header("Location: /pages/hrd/data-department?type=$type&message=" . urlencode($message));
    exit();
}

$queryStr = "DELETE FROM divisi WHERE id_divisi = ?";
$stmt = $conn->prepare($queryStr);
$stmt->bind_param('i', $id_divisi);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        $type = 'success';
        $message = 'Data divisi berhasil dihapus.';
    } else {
        $type = 'error';
        $message = 'Data divisi tidak ditemukan.';

    }
} else {
    $type = 'error';
    $message = 'Gagal menghapus data divisi. Silakan coba lagi.';
}

header("Location: /pages/hrd/data-department?type=$type&message=" . urlencode($message));
exit();

