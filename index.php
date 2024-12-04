<?php

include_once ('./functions/init-session.php');

if (!isset($_SESSION['user'])) {
    header("Location: pages/auth/sign-in");
    exit();
}