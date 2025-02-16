<?php

require_once ('load-env.php');

$servername = getenv('DB_HOST') ?? "127.0.0.1";
$username = getenv('DB_USERNAME') ?? "root";
$password = getenv('DB_PASSWORD') ?? "";
$database = getenv('DB_DATABASE') ?? "sistem_penerimaan_karyawan";

$conn = mysqli_connect($servername, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
