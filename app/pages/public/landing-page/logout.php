<?php

require_once('./../../../functions/init-session.php');

session_destroy();

function redirectWithMessage($type, $message, $page = '/pages/public/sign-in')
{
    header("Location: $page?type=$type&message=" . urlencode($message));
    exit();
}

redirectWithMessage('success', 'Berhasil logout', '/pages/public/landing-page');
