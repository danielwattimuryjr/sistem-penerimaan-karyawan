<?php

require_once('./../../../functions/init-session.php');
require_once('./../../../functions/init-conn.php');

$idLowongan = $_POST['id_lowongan'];
$idUser = $_SESSION['user']['id_user'];
$namaPelamar = $_POST['name'];
$email = $_POST['email'];
$tempatLahir = $_POST['tempat_lahir'];
$tanggalLahir = $_POST['tanggal_lahir'];
$nomorTelepon = $_POST['nomor_telepon'];
$jenisKelamin = $_POST['jenis_kelamin'];
$pendidikanTerakhir = $_POST['pendidikan_terakhir'];
$alamat = $_POST['alamat'];
$pengalamanKerja = $_POST['pengalaman_kerja'];
$file = $_FILES['curiculum_vitae'];

function redirectWithMessage($type, $message, $idLowongan)
{
    header("Location: /pages/public/detail-lowongan?id_lowongan=$idLowongan&type=$type&message=" . urlencode($message));
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirectWithMessage('error', 'Invalid Method', $idLowongan);
}

if (!$idLowongan || !$pengalamanKerja || !$file || !$namaPelamar || !$email || !$tempatLahir || !$tanggalLahir || !$nomorTelepon || !$jenisKelamin || !$pendidikanTerakhir || !$alamat) {
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

$uniqueFileName = uniqid() . '_' . $fileName;
$uploadDir = __DIR__ . '/../../../assets/uploads/cv';
$uploadPath = $uploadDir . '/' . $uniqueFileName;

if (!is_uploaded_file($fileTmp)) {
    redirectWithMessage('error', 'File tidak valid atau tidak ditemukan', $idLowongan);
}

if (!move_uploaded_file($fileTmp, $uploadPath)) {
    redirectWithMessage('error', 'Gagal mengunggah file', $idLowongan);
}

try {
    $conn->begin_transaction();

    $query = "INSERT INTO pelamaran (name, id_user, email, tempat_lahir, tanggal_lahir, nomor_telepon, jenis_kelamin, pendidikan_terakhir, alamat, id_lowongan, pengalaman_kerja, curiculum_vitae) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('sissssissiis', $namaPelamar, $idUser, $email, $tempatLahir, $tanggalLahir, $nomorTelepon, $jenisKelamin, $pendidikanTerakhir, $alamat, $idLowongan, $pengalamanKerja, $uniqueFileName);

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
