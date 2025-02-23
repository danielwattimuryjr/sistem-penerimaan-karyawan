<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('./../../../vendor/autoload.php');
require_once('./../../../functions/init-conn.php');
require_once('./../../../functions/string-helpers.php');

use PhpOffice\PhpSpreadsheet\IOFactory;

function redirectToHome($type, $message)
{
    header("Location: /pages/admin/beranda?type=$type&message=" . urlencode($message));
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["file"])) {
    $file = $_FILES["file"]["tmp_name"];

    // Check if the file exists
    if (!file_exists($file)) {
        redirectToHome('error', 'File tidak terupload. Coba sekali lagi');
    }

    try {
        $spreadsheet = IOFactory::load($file);
        $sheetNames = $spreadsheet->getSheetNames();
        $lastSheetIndex = count($sheetNames) - 1;

        if ($lastSheetIndex < 0) {
            redirectToHome('error', 'File Excel tidak memiliki sheet');
        }

        $sheet = $spreadsheet->getSheet($lastSheetIndex);
        $data = $sheet->toArray();
        $departments = [];
        $currentDepartment = null;

        foreach ($data as $row) {
            $col1 = isset($row[0]) ? trim($row[0]) : null;
            $col2 = isset($row[1]) ? trim($row[1]) : null;
            $col3 = isset($row[2]) ? trim($row[2]) : null;

            // Cek apakah baris ini adalah department baru
            if (
                $col1 !== null && (
                    preg_match('/^DEPT\s*:\s*(.+)$/i', $col1, $matches) ||
                    preg_match('/^Dept\.?\s*(.+)$/i', $col1, $matches)
                )
            ) {
                $deptName = ucwords(strtolower(trim($matches[1])));
                $deptSlug = strtolower(str_replace(" ", "_", $deptName)); // Slug untuk email dan username

                // Cek apakah departemen sudah ada
                $found = false;
                foreach ($departments as &$dept) {
                    if (strtoupper($dept["department"]) === strtoupper($deptName)) {
                        $currentDepartment = &$dept;
                        $found = true;
                        break;
                    }
                }

                // Jika belum ada, buat departemen baru
                if (!$found) {
                    $departments[] = [
                        "department" => $deptName,
                        "email" => "{$deptSlug}@grand-pasundan.com",
                        "username" => "dep.{$deptSlug}",
                        "password" => "password",
                        "role" => "Departement",
                        "divisions" => [] // Array untuk menyimpan divisi-divisi
                    ];
                    $currentDepartment = &$departments[count($departments) - 1];
                }
                continue;
            }

            // Tambah karyawan ke departemen saat ini
            if ($currentDepartment !== null && is_numeric($col1) && !empty($col3)) {
                // Transform nama divisi agar seragam (lowercase)
                $division = strtolower(trim($col3));

                // Jika divisi belum ada di department, buat array baru
                if (!isset($currentDepartment["divisions"][$division])) {
                    $currentDepartment["divisions"][$division] = [];
                }

                // Generate email untuk employee
                $employeeEmail = toSnakeCase($col2) . "@grand-pasundan.com";

                // Tambah karyawan ke divisi
                $currentDepartment["divisions"][$division][] = [
                    "name" => $col2,
                    "email" => $employeeEmail
                ];
            }
        }

        // Sort departments by name
        usort($departments, function ($a, $b) {
            return strcmp($a["department"], $b["department"]);
        });

        mysqli_begin_transaction($conn);
        try {
            foreach ($departments as $dept) {
                // Jika department adalah HRD, langsung masukkan ke tabel user
                if (strtolower($dept["department"]) === "hrd") {
                    foreach ($dept["divisions"] as $division => $employees) {
                        foreach ($employees as $employee) {
                            // Buat username dalam format "hrd.nama_dengan_snake_case"
                            $username = "hrd." . strtolower(str_replace(" ", "_", $employee["name"]));
                            $password = "password"; // Tidak di-hash sesuai permintaan

                            // Cek apakah user sudah ada berdasarkan email
                            $stmt = $conn->prepare("SELECT id_user FROM user WHERE email = ?");
                            $stmt->bind_param("s", $employee["email"]);
                            $stmt->execute();
                            $result = $stmt->get_result()->fetch_assoc();
                            $stmt->close();

                            if (!$result || !isset($result['id_user'])) {
                                // Insert user dengan role HRD
                                $stmt = $conn->prepare("INSERT INTO user (name, user_name, email, role, password) VALUES (?, ?, ?, ?, ?)");
                                $stmt->bind_param("sssss", $employee["name"], $username, $employee["email"], $role, $password);
                                $role = "HRD"; // Set role HRD
                                $stmt->execute();
                                $stmt->close();
                            }
                        }
                    }
                    continue; // Skip proses insert department/division untuk HRD
                }

                // Cek apakah department sudah ada
                $stmt = $conn->prepare("SELECT id_user FROM user WHERE name = ? AND role = 'Departement'");
                $stmt->bind_param("s", $dept["department"]);
                $stmt->execute();
                $result = $stmt->get_result()->fetch_assoc();
                $stmt->close();

                if (!$result || !isset($result['id_user'])) {
                    $stmt = $conn->prepare("INSERT INTO user (name, user_name, email, role, password) VALUES (?, ?, ?, ?, ?)");
                    $stmt->bind_param("sssss", $dept["department"], $dept["username"], $dept["email"], $dept["role"], $dept["password"]);
                    $stmt->execute();
                    $id_user = $stmt->insert_id;
                    $stmt->close();
                } else {
                    $id_user = $result['id_user']; // Simpan id_user dari database jika sudah ada
                }

                foreach ($dept["divisions"] as $division => $employees) {
                    // Cek apakah divisi sudah ada
                    $stmt = $conn->prepare("SELECT id_divisi FROM divisi WHERE nama_divisi = ? AND id_user = ?");
                    $stmt->bind_param("si", $division, $id_user);
                    $stmt->execute();
                    $result = $stmt->get_result()->fetch_assoc(); // Ambil hasil query
                    $stmt->close();

                    if (!$result || !isset($result['id_divisi'])) {
                        $jumlah_personil = count($employees);
                        $stmt = $conn->prepare("INSERT INTO divisi (id_user, nama_divisi, jumlah_personil) VALUES (?, ?, ?)");
                        $stmt->bind_param("isi", $id_user, $division, $jumlah_personil);
                        $stmt->execute();
                        $id_divisi = $stmt->insert_id;
                        $stmt->close();
                    } else {
                        $id_divisi = $result['id_divisi']; // Ambil ID divisi jika sudah ada
                    }

                    foreach ($employees as $employee) {
                        // Cek apakah email sudah ada di database
                        $stmt = $conn->prepare("SELECT id_karyawan FROM karyawan WHERE email = ?");
                        $stmt->bind_param("s", $employee['email']);
                        $stmt->execute();
                        $result = $stmt->get_result()->fetch_assoc();
                        $stmt->close();

                        if (!$result || !isset($result['id_karyawan'])) {
                            // Jika email belum ada, lakukan INSERT
                            $stmt = $conn->prepare("INSERT INTO karyawan (id_divisi, name, email) VALUES (?, ?, ?)");
                            $stmt->bind_param("iss", $id_divisi, $employee["name"], $employee['email']);
                            $stmt->execute();
                            $stmt->close();
                        }
                    }
                }
            }

            mysqli_commit($conn);
            redirectToHome('success', 'Data berhasil diimport!');
        } catch (Exception $e) {
            mysqli_rollback($conn);
            redirectToHome('error', 'Gagal import data: ' . $e->getMessage());
        }
    } catch (Exception $e) {
        redirectToHome('error', 'Error loading file: ' . $e->getMessage());
    }
} else {
    redirectToHome('error', 'Method tidak valid');
}
