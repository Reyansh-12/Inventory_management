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

include "../../controllers/dbConnection.php"; 

$data = json_decode(file_get_contents("php://input"), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($data)) {
    $product_id     = (int)$data['product_id'];
    $customer_email = mysqli_real_escape_string($con, $data['customer_email']);
    $customer_name  = mysqli_real_escape_string($con, $data['customer_name']);
    $rating         = (int)$data['rating'];
    $title          = mysqli_real_escape_string($con, $data['title']);
    $comment        = mysqli_real_escape_string($con, $data['comment']);
    
    $status         = 'approved'; 

    $sql = "INSERT INTO `reviews` 
            (`product_id`, `customer_email`, `customer_name`, `rating`, `title`, `status`, `comment`, `created_at`) 
            VALUES 
            ($product_id, '$customer_email', '$customer_name', $rating, '$title', '$status', '$comment', NOW())";

    if (mysqli_query($con, $sql)) {
        echo json_encode([
            "success" => true, 
            "message" => "Review stored successfully"
        ]);
    } else {
        echo json_encode([
            "success" => false, 
            "message" => "Database Error: " . mysqli_error($con)
        ]);
    }
} else {
    echo json_encode([
        "success" => false, 
        "message" => "Invalid Request Method or Empty Data"
    ]);
}
?>