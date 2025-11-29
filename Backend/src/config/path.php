<?php

// Detect OS
$isWindows = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';

// BASE PATH (auto detect)
if ($isWindows) {
    // Windows (XAMPP)
    $BASE_DIR = "C:/xampp/htdocs/Inventory_management";
} else {
    // Ubuntu (Apache2)
    $BASE_DIR = "/var/www/html/Inventory_management";
}
?>





<!-- anywhere to run -->
<?php
$BASE_PATH = realpath(__DIR__ . "/../../../");
define("BASE_PATH", $BASE_PATH);


// define("BASE_PATH", $BASE_DIR);
?>