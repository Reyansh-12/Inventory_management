<?php
session_start();

// Destroy session
session_unset();
session_destroy();

// Back button cache fix
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Redirect
header("Location: signin.php");
exit();
