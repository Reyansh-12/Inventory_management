<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

include __DIR__ . "/../../controllers/dbConnection.php";

$BASE_URL = "http://localhost/Inventory_management";

$sql = "SELECT id, category, image_path 
        FROM category 
        WHERE status = 'active'";

$result = $con->query($sql);

$categories = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $categories[] = [
            "id" => (int)$row["id"],
            "name" => $row["category"],
            "image" => $BASE_URL . $row["image_path"]
        ];
    }
}

echo json_encode($categories);
