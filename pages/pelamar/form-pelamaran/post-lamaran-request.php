<?php

require_once('./../../../functions/init-session.php');
require_once('./../../../functions/init-conn.php');
require_once('./../../../functions/page-protection.php');

// Ambil data dari POST
$idLowongan = $_POST['id_lowongan'];
$pengalamanKerja = $_POST['pengalaman_kerja'];
$file = $_FILES['curiculum_vitae'];

// Fungsi redirect dengan debugging
function redirectWithMessage($type, $message, $idLowongan) {
    header("Location: /sistem-penerimaan-karyawan/pages/pelamar/detail-lowongan-pekerjaan?id_lowongan=$idLowongan&type=$type&message=" . urlencode($message));
    exit();
}

// Cek metode request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirectWithMessage('error', 'Invalid Method', $idLowongan);
}

// Cek session user
$user = $_SESSION['user'];
if (empty($user['id_user'])) {
    redirectWithMessage('error', 'Id Pelamar tidak ditemukan', $idLowongan);
}

if ($user['role'] !== 'Pelamar') {
    redirectWithMessage('error', 'User bukan seorang pelamar', $idLowongan);
}

// Validasi data
if (!$idLowongan || !$pengalamanKerja || !$file) {
    redirectWithMessage('error', 'Data tidak lengkap', $idLowongan);
}

// Validasi file
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

// Buat nama file unik
$uniqueFileName = uniqid() . '_' . $fileName;
$uploadDir = __DIR__ . '/uploads/cv';
$uploadPath = $uploadDir . '/' . $uniqueFileName;

// Periksa dan buat direktori jika tidak ada
if (!is_dir($uploadDir)) {
    if (!@mkdir($uploadDir, 0777, true)) {
        die("Gagal membuat direktori. Periksa hak akses.");
    }
}

// Debugging file upload
if (!is_uploaded_file($fileTmp)) {
    redirectWithMessage('error', 'File tidak valid atau tidak ditemukan', $idLowongan);
}

// Proses upload file
if (!move_uploaded_file($fileTmp, $uploadPath)) {
    echo "File Temp: $fileTmp<br>";
    echo "Upload Path: $uploadPath<br>";
    echo "is_uploaded_file: " . (is_uploaded_file($fileTmp) ? "true" : "false") . "<br>";
    redirectWithMessage('error', 'Gagal mengunggah file', $idLowongan);
}

// Debugging: Informasi file berhasil diunggah
echo "File berhasil diunggah ke: $uploadPath<br>";

// Lanjutkan dengan query database
$idUser = $user['id_user'];
try {
    // Mulai transaksi
    $conn->begin_transaction();

    // Contoh query insert ke database
    $query = "INSERT INTO pelamaran (id_user, id_lowongan, pengalaman_kerja, curiculum_vitae) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('iiss', $idUser, $idLowongan, $pengalamanKerja, $uploadPath);

    if (!$stmt->execute()) {
        throw new Exception("Gagal menyimpan data: " . $stmt->error);
    }

    // Commit transaksi
    $conn->commit();

    redirectWithMessage('success', 'Data berhasil disimpan', $idLowongan);
} catch (Exception $e) {
    // Rollback transaksi jika terjadi error
    $conn->rollback();
    redirectWithMessage('error', 'Terjadi kesalahan: ' . $e->getMessage(), $idLowongan);
} finally {
    // Tutup statement
    $stmt->close();
    $conn->close();
}
