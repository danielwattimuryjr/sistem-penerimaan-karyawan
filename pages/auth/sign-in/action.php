<?php

require_once ('./../../../functions/init-conn.php');
require_once ('./../../../functions/init-session.php');
require_once ('./../../../functions/swal.php');


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $queryStr = "SELECT id_user, user_name, password, email, role FROM user WHERE user_name = ? AND password = ?";
    $stmt = $conn->prepare($queryStr);

    $stmt->bind_param('ss', $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "Login successful!";
        $_SESSION['user'] = $result->fetch_assoc();

        switch ($_SESSION['user']['role']) {
            case 'General Manager':
                header("Location: ../../general-manager/beranda");
                break;
            case 'Departement':
                header("Location: ../../departemen/beranda");
                break;
            case 'HRD':
                header("Location: ../../hrd/beranda");
                break;
            case 'Pelamar':
                header("Location: ../../pelamar/beranda");
                break;
            default:
                header("Location: ../sign-in");
                break;
        }
    } else {
        header("Location: ../sign-in");
        exit;
    }
}