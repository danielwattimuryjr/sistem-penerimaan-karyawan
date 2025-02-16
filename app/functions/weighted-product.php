<?php

function konversiNilaiKategori($nilai) {
    $skala = [
        "Sangat Kurang" => 1,
        "Kurang" => 2,
        "Cukup" => 3,
        "Baik" => 4,
        "Sangat Baik" => 5,
    ];
    return $skala[$nilai] ?? 0;
}

function konversiRentangNilai($nilai){
    if ($nilai >= 0 && $nilai <= 20) return 1;
    if ($nilai >= 21 && $nilai <= 40) return 2;
    if ($nilai >= 41 && $nilai <= 60) return 3;
    if ($nilai >= 61 && $nilai <= 80) return 4;
    if ($nilai >= 81 && $nilai <= 100) return 5;
    return 0;
}

function hitungWeightedProduct($id_lowongan) {
    global $conn;
    $bobot = [0.2, 0.2, 0.4, 0.1, 0.1]; // Bobot untuk masing-masing kriteria

    // Ambil data peserta dan penilaian berdasarkan lowongan
    $query = "
        SELECT 
            p.id_penilaian, 
            p.nilai_tes_tertulis, 
            p.nilai_tes_wawancara, 
            p.nilai_tes_praktek, 
            p.nilai_tes_psikotes, 
            p.nilai_tes_kesehatan
        FROM penilaian p
        JOIN pelamaran l ON l.id_pelamaran = p.id_pelamaran
        WHERE l.id_lowongan = ?
    ";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id_lowongan);
    $stmt->execute();
    $result = $stmt->get_result();

    $peserta = [];
    $total_vector_s = 0;

    while ($row = $result->fetch_assoc()) {
        $nilai = [
            konversiRentangNilai($row['nilai_tes_tertulis']),
            konversiNilaiKategori($row['nilai_tes_wawancara']),
            konversiNilaiKategori($row['nilai_tes_praktek']),
            konversiRentangNilai($row['nilai_tes_psikotes']),
            konversiNilaiKategori($row['nilai_tes_kesehatan']),
        ];

        $vector_s = 1;
        foreach ($nilai as $i => $n) {
            $vector_s *= pow($n, $bobot[$i]);
        }

        $peserta[] = [
            "id_penilaian" => $row['id_penilaian'],
            "vector_s" => $vector_s,
        ];

        $total_vector_s += $vector_s;
    }

    foreach ($peserta as &$p) {
        $p['hasil_akhir'] = $p['vector_s'] / $total_vector_s;
    }

    usort($peserta, function ($a, $b) {
        return $b['hasil_akhir'] <=> $a['hasil_akhir'];
    });

    return $peserta;
}
