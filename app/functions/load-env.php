<?php
$envFilePath = __DIR__ . '/../.env';

if (file_exists($envFilePath)) {
    $envVariables = parse_ini_file($envFilePath);

    foreach ($envVariables as $key => $value) {
        putenv("$key=$value");

        $_ENV[$key] = $value;
    }

    define('MAIL_HOST', getenv('MAIL_HOST'));
    define('MAIL_PASSWORD', getenv('MAIL_PASSWORD'));
    define('MAIL_PORT', getenv('MAIL_PORT'));
    define('MAIL_ADDRESS', getenv('MAIL_ADDRESS'));
    define('MAIL_USERNAME', getenv('MAIL_USERNAME'));

    define('DB_HOST', getenv('DB_HOST'));
    define('DB_USERNAME', getenv('DB_USERNAME'));
    define('DB_PASSWORD', getenv('DB_PASSWORD'));
    define('DB_PORT', getenv('DB_PORT'));
    define('DB_DATABASE', getenv('DB_DATABASE'));

    define('SITE_URL', getenv('SITE_URL'));
} else {
    echo "Error: .env file not found!";
}
