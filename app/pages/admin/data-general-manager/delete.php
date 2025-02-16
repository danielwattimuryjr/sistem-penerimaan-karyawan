<?php
require_once('./../../../functions/init-conn.php');

$id_user = isset($_GET['id_user']) ? $_GET['id_user'] : null;

if (!$id_user) {
    $type = 'error';
    $message = 'Data General Manager tidak ditemukan';
    header("Location: /pages/admin/data-general-manager?type=$type&message=" . urlencode($message));
    exit();
}

$queryStr = "DELETE FROM user WHERE id_user = ?";
$stmt = $conn->prepare($queryStr);
$stmt->bind_param('i', $id_user);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        $type = 'success';
        $message = 'Data General Manager berhasil dihapus.';
    } else {
        $type = 'error';
        $message = 'Data General Manager tidak ditemukan.';
    }
} else {
    $type = 'error';
    $message = 'Gagal menghapus data General Manager. Silakan coba lagi.';
}

header("Location: /pages/admin/data-general-manager?type=$type&message=" . urlencode($message));
exit();
