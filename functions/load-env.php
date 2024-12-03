<?php
$envFilePath = __DIR__ . '/../.env';

if (file_exists($envFilePath)) {
    $envVariables = parse_ini_file($envFilePath);

    foreach ($envVariables as $key => $value) {
        putenv("$key=$value");

        $_ENV[$key] = $value;
    }

//    $dbHost = getenv('DB_HOST');
//    echo "Database Host: $dbHost";
} else {
    echo "Error: .env file not found!";
}

