<?php

require_once('./../../../functions/init-session.php');
require_once('./../../../functions/init-conn.php');
require_once('./../../../functions/page-protection.php');

$idLowongan = $_POST['id_lowongan'];
$pengalamanKerja = $_POST['pengalaman_kerja'];
$file = $_FILES['curiculum_vitae'];

function redirectWithMessage($type, $message, $idLowongan) {
    header("Location: /sistem-penerimaan-karyawan/pages/pelamar/detail-lowongan-pekerjaan?id_lowongan=$idLowongan&type=$type&message=" . urlencode($message));
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirectWithMessage('error', 'Invalid Method', $idLowongan);
}

$user = $_SESSION['user'];
if (empty($user['id_user'])) {
    redirectWithMessage('error', 'Id Pelamar tidak ditemukan', $idLowongan);
}

if ($user['role'] !== 'Pelamar') {
    redirectWithMessage('error', 'User bukan seorang pelamar', $idLowongan);
}

if (!$idLowongan || !$pengalamanKerja || !$file) {
    redirectWithMessage('error', 'Data tidak lengkap', $idLowongan);
}

$allowedExtensions = ['png', 'jpg', 'jpeg', 'pdf'];
$fileName = $file['name'];
$fileSize = $file['size'];
$fileTmp = $file['tmp_name'];
$fileExt = pathinfo($fileName, PATHINFO_EXTENSION);

if (!in_array(strtolower($fileExt), $allowedExtensions)) {
    redirectWithMessage('error', 'Format file tidak didukung', $idLowongan);
}

if ($fileSize > 1044070) {
    redirectWithMessage('error', 'Ukuran tidak boleh lebih besar dari 1MB', $idLowongan);
}

$uniqueFileName = uniqid() . '_' . $fileName;
$uploadDir = __DIR__ . '/../../../assets/uploads/cv';
$uploadPath = $uploadDir . '/' . $uniqueFileName;

if (!is_uploaded_file($fileTmp)) {
    redirectWithMessage('error', 'File tidak valid atau tidak ditemukan', $idLowongan);
}

if (!move_uploaded_file($fileTmp, $uploadPath)) {
    redirectWithMessage('error', 'Gagal mengunggah file', $idLowongan);
}

$idUser = $user['id_user'];
try {
    $conn->begin_transaction();

    $query = "INSERT INTO pelamaran (id_user, id_lowongan, pengalaman_kerja, curiculum_vitae) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('iiss', $idUser, $idLowongan, $pengalamanKerja, $uniqueFileName);

    if (!$stmt->execute()) {
        throw new Exception("Gagal menyimpan data: " . $stmt->error);
    }

    $conn->commit();

    redirectWithMessage('success', 'Data berhasil disimpan', $idLowongan);
} catch (Exception $e) {
    $conn->rollback();
    redirectWithMessage('error', 'Terjadi kesalahan: ' . $e->getMessage(), $idLowongan);
} finally {
    $stmt->close();
    $conn->close();
}