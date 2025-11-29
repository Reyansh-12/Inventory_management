<?php
function env($key) {
    $envPath = __DIR__ . '/.env';

    if (!file_exists($envPath)) {
        die(".env file missing");
    }

    $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {
        list($k, $v) = explode("=", $line, 2);
        if ($k === $key) return $v;
    }

    return null;
}

$host = env("DB_HOST");
$db   = env("DB_NAME");
$user = env("DB_USER");
$pass = env("DB_PASS");

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection Failed: " . $conn->connect_error);
}
?>
