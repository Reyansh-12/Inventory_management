<?php
header("Content-Type: application/json");
include __DIR__ . "/../../controllers/dbConnection.php";
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    echo json_encode(null);
    exit;
}

$BASE_URL = "http://localhost/Inventory_management";

$sql = "SELECT id, product_name AS name, price, image_path 
        FROM product_list 
        WHERE id = $id LIMIT 1";

$result = $con->query($sql);

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    // Full URL
    $row['image'] = $BASE_URL . $row['image_path'];
    echo json_encode($row);
} else {
    echo json_encode(null);
}
