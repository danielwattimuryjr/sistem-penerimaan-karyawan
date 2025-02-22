<?php

require_once('load-env.php');

$conn = mysqli_connect(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
