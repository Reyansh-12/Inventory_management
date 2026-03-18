<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit;
}

include __DIR__ . "/../../controllers/dbConnection.php";

if (!isset($con) || !$con) {
    echo json_encode(["status" => "error", "message" => "Database variable \$con is not defined."]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

if ($data) {
    $firstName = mysqli_real_escape_string($con, $data['name']);
    $lastName  = mysqli_real_escape_string($con, $data['lastName']);
    $email     = mysqli_real_escape_string($con, $data['email']);
    $message   = mysqli_real_escape_string($con, $data['message']);

    $sql = "INSERT INTO `user_contact` (`firstName`, `lastName`, `Email`, `message`) 
            VALUES ('$firstName', '$lastName', '$email', '$message')";

    if (mysqli_query($con, $sql)) {
        echo json_encode(["status" => "success", "message" => "Data saved!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "SQL Error: " . mysqli_error($con)]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "No data in payload"]);
}
?>