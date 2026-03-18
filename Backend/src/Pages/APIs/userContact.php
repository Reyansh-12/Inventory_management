<?php 
// Faltu ke spaces ya warnings ko JSON se bahar rakhne ke liye buffer start
ob_start(); 

error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit;
}

// Database Connection
include __DIR__ . "/../../controllers/dbConnection.php";

// Raw input read karein
$json = file_get_contents('php://input');
$data = json_decode($json, true);

// Buffer saaf karein taaki sirf niche wala JSON output ho
if (ob_get_length()) ob_clean(); 

if ($data && isset($data['name'], $data['email'])) {
    
    // Check connection
    if (!$con) {
        echo json_encode(["status" => "error", "message" => "DB Connection Failed"]);
        exit;
    }

    $firstName = mysqli_real_escape_string($con, $data['name']);
    $lastName  = mysqli_real_escape_string($con, $data['lastName']);
    $email     = mysqli_real_escape_string($con, $data['email']);
    $message   = mysqli_real_escape_string($con, $data['message']);

    // Query matching your table structure
    $sql = "INSERT INTO `user_contact` (`firstName`, `lastName`, `Email`, `message`, `created_at`) 
            VALUES ('$firstName', '$lastName', '$email', '$message', NOW())";

    if (mysqli_query($con, $sql)) {
        echo json_encode(["status" => "success", "message" => "Message saved!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "SQL Error: " . mysqli_error($con)]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid data received"]);
}

// Ensure no other output after JSON
exit;
?>