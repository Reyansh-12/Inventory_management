<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

include "../../controllers/dbConnection.php"; 

$data = json_decode(file_get_contents("php://input"), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($data)) {
    $product_id = (int)$data['product_id'];
    $customer_name = mysqli_real_escape_string($con, $data['customer_name']);
    $rating = (int)$data['rating'];
    $title = mysqli_real_escape_string($con, $data['title']);
    $comment = mysqli_real_escape_string($con, $data['comment']);

    $sql = "INSERT INTO product_reviews (product_id, customer_name, rating, title, comment, status, created_at) 
            VALUES ($product_id, '$customer_name', $rating, '$title', '$comment', 'Pending', NOW())";

    if (mysqli_query($con, $sql)) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "message" => mysqli_error($con)]);
    }
}
?>