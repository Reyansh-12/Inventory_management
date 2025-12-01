<?php

// Detect OS
$isWindows = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';

// BASE DIR (auto detect)
if ($isWindows) {
    // Windows (XAMPP)
    $BASE_DIR = "C:/xampp/htdocs/Inventory_management";
} else {
    // Ubuntu (Apache2)
    $BASE_DIR = "/var/www/html/Inventory_management";
}

// Finally define BASE_PATH
define("BASE_PATH", $BASE_DIR);

?>
