<?php
require_once('./../../../functions/init-conn.php');
require_once('./../../../functions/page-protection.php');

// if (!empty($_POST)) {
//     // Convert $_POST to JSON
//     header('Content-Type: application/json');
//     echo json_encode($_POST, JSON_PRETTY_PRINT);
// } else {
//     echo 'No POST data received.';
// }

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

// Validate user session
$user = $_SESSION['user'] ?? null;
if (empty($user['id_user'])) {
    redirectWithMessage('error', 'Id Pelamar tidak ditemukan');
}

// Retrieve and validate POST data
$namaLowongan = trim($_POST['nama_lowongan'] ?? '');
$tanggalMulai = trim($_POST['tanggal_mulai'] ?? '');
$tanggalSelesai = trim($_POST['tanggal_selesai'] ?? '');
$idPermintaan = intval($_POST['id_permintaan'] ?? 0);
$umur = intval($_POST['umur'] ?? 0);
$pendidikan = trim($_POST['pendidikan'] ?? '');
$pengalamanKerja = trim($_POST['pengalaman_kerja'] ?? '');
$file = $_FILES['poster_lowongan'];
$fpTesTertulis = $_POST["fp_tes_tertulis"] ?? 0;
$fpTesWawancara = $_POST["fp_tes_wawancara"] ?? 0;
$fpTesPraktek = $_POST["fp_tes_praktek"] ?? 0;
$fpTesPsikotes = $_POST["fp_tes_psikotes"] ?? 0;
$fpTesKesehatan = $_POST["fp_tes_kesehatan"] ?? 0;
$fpPendidikan = $_POST["fp_pendidikan"] ?? 0;
$fpUmur = $_POST["fp_umur"] ?? 0;
$fpPengalamanKerja = $_POST["fp_pengalaman_kerja"] ?? 0;

// Ensure required fields are not empty
if (empty($namaLowongan) || empty($tanggalMulai) || empty($tanggalSelesai) || empty($idPermintaan) || empty($umur) || empty($pendidikan) || !$file || ($fpTesTertulis === null || $fpTesTertulis === 0) || ($fpTesWawancara === null || $fpTesWawancara === 0) || ($fpTesPraktek === null || $fpTesPraktek === 0) || ($fpTesPsikotes === null || $fpTesPsikotes === 0) || ($fpTesKesehatan === null || $fpTesKesehatan === 0) || ($fpPendidikan === null || $fpPendidikan === 0) || ($fpUmur === null || $fpUmur === 0) || ($fpPengalamanKerja === null || $fpPengalamanKerja === 0)) {
    redirectWithMessage('error', 'Semua data harus diisi.');
}

$allowedExtensions = ['png', 'jpg', 'jpeg', 'pdf'];
$fileName = $file['name'];
$fileSize = $file['size'];
$fileTmp = $file['tmp_name'];
$fileExt = pathinfo($fileName, PATHINFO_EXTENSION);

// Validate date format
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $tanggalMulai) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $tanggalSelesai)) {
    redirectWithMessage('error', 'Format tanggal tidak valid.');
}

// Validate date logic
if (strtotime($tanggalMulai) > strtotime($tanggalSelesai)) {
    redirectWithMessage('error', 'Tanggal mulai tidak boleh setelah tanggal selesai.');
}

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

$conn->begin_transaction();

try {
    // Insert data into `lowongan` table
    $insertLowonganQuery = "
        INSERT INTO lowongan (id_permintaan, nama_lowongan, tgl_mulai, tgl_selesai, poster_lowongan)
        VALUES (?, ?, ?, ?, ?)
    ";
    $stmtLowongan = $conn->prepare($insertLowonganQuery);
    $stmtLowongan->bind_param('issss', $idPermintaan, $namaLowongan, $tanggalMulai, $tanggalSelesai, $uniqueFileName);
    $stmtLowongan->execute();

    // Get the inserted `lowongan` ID
    $idLowongan = $conn->insert_id;

    // Insert data into `persyaratan` table
    $insertPersyaratanQuery = "
        INSERT INTO persyaratan (id_lowongan, pengalaman_kerja, umur, pendidikan)
        VALUES (?, ?, ?, ?)
    ";
    $stmtPersyaratan = $conn->prepare($insertPersyaratanQuery);
    $stmtPersyaratan->bind_param('isis', $idLowongan, $pengalamanKerja, $umur, $pendidikan);
    $stmtPersyaratan->execute();

    $insertIntoFaktorPenilaianQuery = "
    INSERT INTO faktor_penilaian (id_lowongan, nama_faktor, bobot)
    VALUES (?, ?, ?)
";
    $stmtFaktorPenilaian = $conn->prepare($insertIntoFaktorPenilaianQuery);

    // Bind and execute for each factor
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
        $stmtFaktorPenilaian->bind_param('ssd', $idLowongan, $namaFaktor, $bobot);
        $stmtFaktorPenilaian->execute();
    }

    // Commit transaction
    $conn->commit();
    redirectWithMessage('success', 'Lowongan dan persyaratan berhasil ditambahkan.');
} catch (Exception $e) {
    $conn->rollback();
    redirectWithMessage('error', 'Terjadi kesalahan: ' . $e->getMessage());
}
