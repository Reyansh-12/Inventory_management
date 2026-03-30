<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");

// Using require to ensure the connection exists
require_once '../../controllers/dbConnection.php'; 

// Check if $con or $conn is used in your dbConnection.php
$db = isset($con) ? $con : (isset($conn) ? $conn : null);

if (!$db) {
    echo json_encode(["success" => false, "message" => "Database connection variable not found"]);
    exit;
}

$email = isset($_GET['email']) ? trim($_GET['email']) : '';

if (!empty($email)) {
    $safe_email = mysqli_real_escape_string($db, $email);
    
    // Ensure these column names match your 'new_user' table exactly
    $sql = "SELECT id, user_name, user_email, user_contact, user_role, image_path FROM new_user WHERE user_email = '$safe_email' LIMIT 1";
    $result = mysqli_query($db, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        echo json_encode(["success" => true, "user" => $row]);
    } else {
        echo json_encode(["success" => false, "message" => "User not found in database"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Email parameter is missing"]);
}
?>