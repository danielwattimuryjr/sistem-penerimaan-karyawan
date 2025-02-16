<?php
require_once('./../../../functions/init-conn.php');
require_once('./../../../functions/page-protection.php');

$idLowongan = $_POST['id_lowongan'];

// Redirect helper function
function redirectWithMessage($type, $message)
{
    header("Location: /pages/hrd/permintaan-karyawan?type=$type&message=" . urlencode($message));
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
$fpTesTertulis = $_POST["fp_tes_tertulis"] ?? 0;
$fpTesWawancara = $_POST["fp_tes_wawancara"] ?? 0;
$fpTesPraktek = $_POST["fp_tes_praktek"] ?? 0;
$fpTesPsikotes = $_POST["fp_tes_psikotes"] ?? 0;
$fpTesKesehatan = $_POST["fp_tes_kesehatan"] ?? 0;
$fpPendidikan = $_POST["fp_pendidikan"] ?? 0;
$fpUmur = $_POST["fp_umur"] ?? 0;
$fpPengalamanKerja = $_POST["fp_pengalaman_kerja"] ?? 0;

if (empty($idLowongan)) {
    redirectWithMessage('error', 'ID Lowongan tidak ditemukan.');
}

// Validate required fields
if (empty($namaLowongan) || empty($tanggalMulai) || empty($tanggalSelesai) || empty($idPermintaan) || empty($umur) || empty($pendidikan) || ($fpTesTertulis === null || $fpTesTertulis === 0) || ($fpTesWawancara === null || $fpTesWawancara === 0) || ($fpTesPraktek === null || $fpTesPraktek === 0) || ($fpTesPsikotes === null || $fpTesPsikotes === 0) || ($fpTesKesehatan === null || $fpTesKesehatan === 0) || ($fpPendidikan === null || $fpPendidikan === 0) || ($fpUmur === null || $fpUmur === 0) || ($fpPengalamanKerja === null || $fpPengalamanKerja === 0)) {
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

    $updateFaktorPenilaianQuery = "
    UPDATE faktor_penilaian SET bobot = ? WHERE id_lowongan = ? AND nama_faktor = ?
";
    $stmtFaktorPenilaian = $conn->prepare($updateFaktorPenilaianQuery);
    $faktorPenilaian = [
        'tes_tertulis' => $fpTesTertulis,
        'tes_wawancara' => $fpTesWawancara,
        'tes_praktek' => $fpTesPraktek,
        'tes_psikotes' => $fpTesPsikotes,
        'tes_kesehatan' => $fpTesKesehatan,
        'pendidikan' => $fpPendidikan,
        'umur' => $fpUmur,
        'pengalaman_kerja' => $fpPengalamanKerja
    ];
    foreach ($faktorPenilaian as $namaFaktor => $bobot) {
        $stmtFaktorPenilaian->bind_param('dss', $bobot, $idLowongan, $namaFaktor);
        $stmtFaktorPenilaian->execute();
    }

    // Commit transaction
    $conn->commit();
    redirectWithMessage('success', 'Lowongan berhasil diperbarui.');
} catch (Exception $e) {
    $conn->rollback();
    redirectWithMessage('error', 'Terjadi kesalahan: ' . $e->getMessage());
}