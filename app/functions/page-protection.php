<?php

require_once('init-session.php');

if (!$_SESSION['user']) {
    header("Location: ");
}