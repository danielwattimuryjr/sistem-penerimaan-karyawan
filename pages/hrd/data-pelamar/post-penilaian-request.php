<?php
require_once('./../../../functions/init-session.php');
require_once('./../../../functions/init-conn.php');
require_once('./../../../functions/page-protection.php');

// Validasi id_pelamaran dari form
if (!isset($_GET['id_pelamaran']) || empty($_GET['id_pelamaran'])) {
    $type = 'error';
    $message = "ID pelamar tidak ditemukan";
    header('Location: /sistem-penerimaan-karyawan/pages/hrd/data-pelamar?type=error&message=' . urlencode($message));
    exit();
}

$id_pelamaran = $_GET['id_pelamaran'];

// Validasi data POST
$errors = [];
if (empty($_POST['nilai_tes_tertulis']) || $_POST['nilai_tes_tertulis'] < 1 || $_POST['nilai_tes_tertulis'] > 5) {
    $errors[] = "Nilai Tes Tertulis harus diisi dan berada di antara 1-5.";
}
if (empty($_POST['nilai_tes_wawancara']) || $_POST['nilai_tes_wawancara'] < 1 || $_POST['nilai_tes_wawancara'] > 5) {
    $errors[] = "Nilai Tes Wawancara harus diisi dan berada di antara 1-5.";
}
if (empty($_POST['nilai_tes_praktek']) || $_POST['nilai_tes_praktek'] < 1 || $_POST['nilai_tes_praktek'] > 5) {
    $errors[] = "Nilai Tes Praktek harus diisi dan berada di antara 1-5.";
}
if (empty($_POST['nilai_tes_psikotes']) || $_POST['nilai_tes_psikotes'] < 1 || $_POST['nilai_tes_psikotes'] > 5) {
    $errors[] = "Nilai Tes Psikotes harus diisi dan berada di antara 1-5.";
}
if (empty($_POST['nilai_tes_kesehatan']) || $_POST['nilai_tes_kesehatan'] < 1 || $_POST['nilai_tes_kesehatan'] > 5) {
    $errors[] = "Nilai Tes Kesehatan harus diisi dan berada di antara 1-5.";
}

if (!empty($errors)) {
    $type = 'error';
    $message = implode(" ", $errors);
    header('Location: /sistem-penerimaan-karyawan/pages/hrd/penilaian-pelamar?id_pelamaran=' . $id_pelamaran . '&type=error&message=' . urlencode($message));
    exit();
}

// Ambil data dari POST
$nilai_tes_tertulis = (float) $_POST['nilai_tes_tertulis'];
$nilai_tes_wawancara = (float) $_POST['nilai_tes_wawancara'];
$nilai_tes_praktek = (float) $_POST['nilai_tes_praktek'];
$nilai_tes_psikotes = (float) $_POST['nilai_tes_psikotes'];
$nilai_tes_kesehatan = (float) $_POST['nilai_tes_kesehatan'];

try {
    // Query UPSERT untuk menyimpan atau memperbarui data
    $queryStr = "
        INSERT INTO penilaian (
            id_pelamaran, 
            nilai_tes_tertulis, 
            nilai_tes_wawancara, 
            nilai_tes_praktek, 
            nilai_tes_psikotes, 
            nilai_tes_kesehatan
        ) VALUES (?, ?, ?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE
            nilai_tes_tertulis = VALUES(nilai_tes_tertulis),
            nilai_tes_wawancara = VALUES(nilai_tes_wawancara),
            nilai_tes_praktek = VALUES(nilai_tes_praktek),
            nilai_tes_psikotes = VALUES(nilai_tes_psikotes),
            nilai_tes_kesehatan = VALUES(nilai_tes_kesehatan)
    ";

    $stmt = $conn->prepare($queryStr);
    $stmt->bind_param(
        "ifffff",
        $id_pelamaran,
        $nilai_tes_tertulis,
        $nilai_tes_wawancara,
        $nilai_tes_praktek,
        $nilai_tes_psikotes,
        $nilai_tes_kesehatan
    );
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $type = 'success';
        $message = "Penilaian berhasil disimpan.";
    } else {
        $type = 'error';
        $message = "Tidak ada perubahan pada data penilaian.";
    }

    $stmt->close();
    $conn->close();

    // Redirect ke halaman sebelumnya dengan pesan
    header('Location: /sistem-penerimaan-karyawan/pages/hrd/data-pelamar?type=' . $type . '&message=' . urlencode($message));
    exit();

} catch (Exception $e) {
    $type = 'error';
    $message = "Terjadi kesalahan: " . $e->getMessage();
    header('Location: /sistem-penerimaan-karyawan/pages/hrd/data-pelamar?type=error&message=' . urlencode($message));
    exit();
}
