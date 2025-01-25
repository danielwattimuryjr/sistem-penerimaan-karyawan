<?php

require_once('./../../../functions/init-conn.php');
require_once('./../../../functions/init-session.php');


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $queryStr = "SELECT id_user, name, role FROM user WHERE user_name = ? AND password = ? AND role != 'Pelamar'";
    $stmt = $conn->prepare($queryStr);

    $stmt->bind_param('ss', $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        $_SESSION['user'] = $user;

        $redirectUrl = '';
        switch ($user['role']) {
            case 'General Manager':
                $redirectUrl = "../../general-manager/beranda";
                break;
            case 'Departement':
                $redirectUrl = "../../departemen/beranda";
                break;
            case 'HRD':
                $redirectUrl = "../../hrd/beranda";
                break;
            case 'Admin':
                $redirectUrl = "../../admin/beranda";
                break;
            default:
                $redirectUrl = "../sign-in";
                break;
        }
        header("Location: " . $redirectUrl . "?type=success&message=" . urlencode("Login berhasil!"));
        exit;
    } else {
        header("Location: ../sign-in?type=error&message=" . urlencode("Username atau password salah."));
        exit;
    }
}