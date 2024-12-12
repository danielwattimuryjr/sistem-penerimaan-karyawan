<?php
require_once('./../../../functions/init-conn.php');
require_once('./../../../functions/page-protection.php');

// Redirect helper function
function redirectWithMessage($type, $message) {
    header("Location: /sistem-penerimaan-karyawan/pages/hrd/beranda?type=$type&message=" . urlencode($message));
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
$deskripsi = trim($_POST['deskripsi'] ?? '');
$umur = intval($_POST['umur'] ?? 0);
$pendidikan = trim($_POST['pendidikan'] ?? '');
$pengalamanKerja = trim($_POST['pengalaman_kerja'] ?? '');

// Ensure required fields are not empty
if (empty($namaLowongan) || empty($tanggalMulai) || empty($tanggalSelesai) || empty($idPermintaan) || empty($umur) || empty($pendidikan)) {
    redirectWithMessage('error', 'Semua data harus diisi.');
}

// Validate date format
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $tanggalMulai) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $tanggalSelesai)) {
    redirectWithMessage('error', 'Format tanggal tidak valid.');
}

// Validate date logic
if (strtotime($tanggalMulai) > strtotime($tanggalSelesai)) {
    redirectWithMessage('error', 'Tanggal mulai tidak boleh setelah tanggal selesai.');
}

$conn->begin_transaction();

try {
    // Insert data into `lowongan` table
    $insertLowonganQuery = "
        INSERT INTO lowongan (id_permintaan, nama_lowongan, deskripsi, tgl_mulai, tgl_selesai)
        VALUES (?, ?, ?, ?, ?)
    ";
    $stmtLowongan = $conn->prepare($insertLowonganQuery);
    $stmtLowongan->bind_param('issss', $idPermintaan, $namaLowongan, $deskripsi, $tanggalMulai, $tanggalSelesai);
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

    // Commit transaction
    $conn->commit();
    redirectWithMessage('success', 'Lowongan dan persyaratan berhasil ditambahkan.');
} catch (Exception $e) {
    $conn->rollback();
    redirectWithMessage('error', 'Terjadi kesalahan: ' . $e->getMessage());
}
