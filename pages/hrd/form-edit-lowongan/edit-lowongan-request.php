<?php
require_once('./../../../functions/init-conn.php');
require_once('./../../../functions/page-protection.php');

$idLowongan = $_POST['id_lowongan'];

// Redirect helper function
function redirectWithMessage($type, $message) {
    header("Location: /sistem-penerimaan-karyawan/pages/hrd/beranda?type=$type&message=" . urlencode($message));
    exit();
}

// Validate request method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirectWithMessage('error', 'Invalid Method');
}

// Retrieve and validate POST data
$idLowongan = intval($_POST['id_lowongan'] ?? 0);
$namaLowongan = trim($_POST['nama_lowongan'] ?? '');
$tanggalMulai = trim($_POST['tanggal_mulai'] ?? '');
$tanggalSelesai = trim($_POST['tanggal_selesai'] ?? '');
$idPermintaan = intval($_POST['id_permintaan'] ?? 0);
$deskripsi = trim($_POST['deskripsi'] ?? '');
$umur = intval($_POST['umur'] ?? 0);
$pendidikan = trim($_POST['pendidikan'] ?? '');
$pengalamanKerja = trim($_POST['pengalaman_kerja'] ?? '');
$file = $_FILES['poster_lowongan'] ?? null;

if (empty($idLowongan)) {
    redirectWithMessage('error', 'ID Lowongan tidak ditemukan.');
}

// Validate required fields
if (empty($namaLowongan) || empty($tanggalMulai) || empty($tanggalSelesai) || empty($idPermintaan) || empty($umur) || empty($pendidikan)) {
    redirectWithMessage('error', 'Semua data harus diisi.');
}

// Validate date logic
if (strtotime($tanggalMulai) > strtotime($tanggalSelesai)) {
    redirectWithMessage('error', 'Tanggal mulai tidak boleh setelah tanggal selesai.');
}

$conn->begin_transaction();

try {
    // Retrieve the current poster file name
    $currentPosterQuery = "SELECT poster_lowongan FROM lowongan WHERE id_lowongan = ?";
    $stmtPoster = $conn->prepare($currentPosterQuery);
    $stmtPoster->bind_param('i', $idLowongan);
    $stmtPoster->execute();
    $resultPoster = $stmtPoster->get_result();
    $currentPoster = $resultPoster->fetch_assoc()['poster_lowongan'];

    // Prepare file upload logic (if a new file is uploaded)
    $posterUpdateQuery = "";
    $posterUpdateParams = [];
    if ($file && $file['error'] === UPLOAD_ERR_OK) {
        $allowedExtensions = ['png', 'jpg', 'jpeg', 'pdf'];
        $fileName = $file['name'];
        $fileSize = $file['size'];
        $fileTmp = $file['tmp_name'];
        $fileExt = pathinfo($fileName, PATHINFO_EXTENSION);

        if (!in_array(strtolower($fileExt), $allowedExtensions)) {
            redirectWithMessage('error', 'Format file tidak didukung');
        }

        if ($fileSize > 1044070) {
            redirectWithMessage('error', 'Ukuran tidak boleh lebih besar dari 1MB');
        }

        $uniqueFileName = uniqid() . '_' . $fileName;
        $uploadDir = __DIR__ . '/../../../assets/uploads/poster';
        $uploadPath = $uploadDir . '/' . $uniqueFileName;

        if (!is_uploaded_file($fileTmp)) {
            redirectWithMessage('error', 'File tidak valid atau tidak ditemukan');
        }

        if (!move_uploaded_file($fileTmp, $uploadPath)) {
            redirectWithMessage('error', 'Gagal mengunggah file');
        }

        // Delete old poster file
        if ($currentPoster && file_exists($uploadDir . '/' . $currentPoster)) {
            unlink($uploadDir . '/' . $currentPoster);
        }

        // Add poster update logic
        $posterUpdateQuery = ", poster_lowongan = ?";
        $posterUpdateParams = [$uniqueFileName];
    }

    // Update `lowongan` data
    $updateLowonganQuery = "
        UPDATE lowongan
        SET nama_lowongan = ?, deskripsi = ?, tgl_mulai = ?, tgl_selesai = ?, id_permintaan = ? $posterUpdateQuery
        WHERE id_lowongan = ?
    ";
    $stmtLowongan = $conn->prepare($updateLowonganQuery);

    $params = array_merge(
        [$namaLowongan, $deskripsi, $tanggalMulai, $tanggalSelesai, $idPermintaan],
        $posterUpdateParams,
        [$idLowongan]
    );

    $stmtLowongan->bind_param(str_repeat('s', count($params) - 1) . 'i', ...$params);
    $stmtLowongan->execute();

    // Update `persyaratan` data
    $updatePersyaratanQuery = "
        UPDATE persyaratan
        SET pengalaman_kerja = ?, umur = ?, pendidikan = ?
        WHERE id_lowongan = ?
    ";
    $stmtPersyaratan = $conn->prepare($updatePersyaratanQuery);
    $stmtPersyaratan->bind_param('sisi', $pengalamanKerja, $umur, $pendidikan, $idLowongan);
    $stmtPersyaratan->execute();

    // Commit transaction
    $conn->commit();
    redirectWithMessage('success', 'Lowongan berhasil diperbarui.');
} catch (Exception $e) {
    $conn->rollback();
    redirectWithMessage('error', 'Terjadi kesalahan: ' . $e->getMessage());
}