<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json; charset=UTF-8");

include '../../controllers/dbConnection.php'; 

// Agar dbConnection mein variable ka naam $con hai toh check karein
$db = isset($con) ? $con : $conn;

if (!$db) {
    echo json_encode(["success" => false, "message" => "Database connection failed"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

if (!empty($data['user_email'])) {
    $name = mysqli_real_escape_string($db, $data['user_name']);
    $contact = mysqli_real_escape_string($db, $data['user_contact']);
    $email = mysqli_real_escape_string($db, $data['user_email']);

    $sql = "UPDATE new_user SET user_name = '$name', user_contact = '$contact' WHERE user_email = '$email'";

    if (mysqli_query($db, $sql)) {
        echo json_encode(["success" => true, "message" => "Updated successfully"]);
    } else {
        echo json_encode(["success" => false, "message" => "SQL Error: " . mysqli_error($db)]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid Data"]);
}
?>