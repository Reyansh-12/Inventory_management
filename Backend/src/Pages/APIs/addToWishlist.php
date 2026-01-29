<?php
require_once "../../config/session.php";
require_once "../../controllers/dbConnection.php";

header("Content-Type: application/json");

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(["message" => "Not logged in"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['product_id'])) {
    http_response_code(400);
    echo json_encode(["message" => "Invalid product"]);
    exit;
}

$userId = $_SESSION['user_id'];
$productId = $data['product_id'];

$check = $con->prepare(
    "SELECT id FROM wishlist WHERE user_id = ? AND product_id = ?"
);
$check->bind_param("ii", $userId, $productId);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    echo json_encode(["message" => "Already in wishlist"]);
    exit;
}

$stmt = $con->prepare(
    "INSERT INTO wishlist (user_id, product_id) VALUES (?, ?)"
);
$stmt->bind_param("ii", $userId, $productId);

if ($stmt->execute()) {
    echo json_encode(["message" => "Added"]);
} else {
    http_response_code(500);
    echo json_encode(["message" => "DB error"]);
}
exit;