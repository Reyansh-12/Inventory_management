<?php

function loadEnv($path)
{
    if (!file_exists($path)) {
        die(".env file missing at: $path");
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {
        if (strpos(trim($line), "#") === 0) continue;
        if (!strpos($line, "=")) continue;

        list($key, $value) = explode("=", $line, 2);
        $_ENV[$key] = trim($value);
    }
}

loadEnv(__DIR__ . "/.env");

function env($key, $default = null)
{
    return $_ENV[$key] ?? $default;
}

if (PHP_OS_FAMILY === 'Windows') {
    $password = "";
} else {
    $password = "codeberg@2023";
}

?>