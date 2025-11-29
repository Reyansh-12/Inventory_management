<?php
require_once __DIR__ . "/env.php";

$host = env("DB_HOST");
$user = env("DB_USER");
$pass = env("DB_PASS");
$db   = env("DB_NAME");
$port = env("DB_PORT", 3306);

$conn = new mysqli($host, $user, $pass, $db, $port);

if ($conn->connect_error) {
    die("DB Connection Failed: " . $conn->connect_error);
}
?>