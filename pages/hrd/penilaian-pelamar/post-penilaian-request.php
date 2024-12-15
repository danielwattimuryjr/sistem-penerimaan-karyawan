<?php
require_once('./../../../functions/init-session.php');
require_once('./../../../functions/init-conn.php');
require_once('./../../../functions/page-protection.php');
require_once('./../../../functions/weighted-product.php');

if (!isset($_POST['id_pelamaran']) || empty($_POST['id_pelamaran'])) {
    $type = 'error';
    $message = "ID pelamar tidak ditemukan";
    header('Location: /sistem-penerimaan-karyawan/pages/hrd/data-pelamar?type=error&message=' . urlencode($message));
    exit();
}

$id_pelamaran = $_POST['id_pelamaran'];

$nilai_tes_tertulis = (float) $_POST['nilai_tes_tertulis'];
$nilai_tes_wawancara = $_POST['nilai_tes_wawancara'];
$nilai_tes_praktek = $_POST['nilai_tes_praktek'];
$nilai_tes_psikotes = (float) $_POST['nilai_tes_psikotes'];
$nilai_tes_kesehatan = $_POST['nilai_tes_kesehatan'];

try {
    $conn->begin_transaction();

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
        "idssds",
        $id_pelamaran,
        $nilai_tes_tertulis,
        $nilai_tes_wawancara,
        $nilai_tes_praktek,
        $nilai_tes_psikotes,
        $nilai_tes_kesehatan
    );
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $queryLowongan = "SELECT id_lowongan FROM pelamaran WHERE id_pelamaran = ?";
        $stmtLowongan = $conn->prepare($queryLowongan);
        $stmtLowongan->bind_param("i", $id_pelamaran);
        $stmtLowongan->execute();
        $resultLowongan = $stmtLowongan->get_result();

        if ($rowLowongan = $resultLowongan->fetch_assoc()) {
            $id_lowongan = $rowLowongan['id_lowongan'];

            $weightedResults = hitungWeightedProduct($id_lowongan);

            foreach ($weightedResults as $rank => $result) {
                $queryHasil = "
                INSERT INTO hasil (
                    id_penilaian,
                    vector_s,
                    hasil_akhir,
                    peringkat
                ) VALUES (?, ?, ?, ?)
                ON DUPLICATE KEY UPDATE
                    vector_s = VALUES(vector_s),
                    hasil_akhir = VALUES(hasil_akhir),
                    peringkat = VALUES(peringkat)
                ";

                $stmtHasil = $conn->prepare($queryHasil);
                $rankTmp = $rank + 1;
                $stmtHasil->bind_param(
                    "iddi",
                    $result['id_penilaian'],
                    $result['vector_s'],
                    $result['hasil_akhir'],
                    $rankTmp
                );
                $stmtHasil->execute();
            }

            $conn->commit();

            $type = 'success';
            $message = 'Penilaian berhasil disimpan dan peringkat diperbaharui.';
        } else {
            throw new Exception("Lowongan tidak ditemukan untuk ID pelamaran yang diberikan.");
        }
    } else {
        throw new Exception("Tidak ada perubahan pada data penilaian.");
    }

    $stmt->close();
    $conn->close();

    header('Location: /sistem-penerimaan-karyawan/pages/hrd/data-pelamar?type=' . $type . '&message=' . urlencode($message));
    exit();
} catch (Exception $e) {
    $conn->rollback();

    $type = 'error';
    $message = "Terjadi kesalahan: " . $e->getMessage();
    header('Location: /sistem-penerimaan-karyawan/pages/hrd/data-pelamar?type=error&message=' . urlencode($message));
    exit();
}
