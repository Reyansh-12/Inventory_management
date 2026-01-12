<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET");
header("Content-Type: application/json");

include __DIR__ . "/../../controllers/dbConnection.php";
// include __DIR__ . "/dbConnection.php";

$BASE_URL = "http://localhost/Inventory_management";
$sql = "SELECT p.id, p.product_name AS name, p.price, p.quantity,p.category, p.discount, p.image_path AS image
        FROM product_list p";
$result = $con->query($sql);

$products = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $imageUrl = null;
        if (!empty($row['image'])) {
            $imageUrl = $BASE_URL . $row['image'];
        }

        $products[] = [
            "id"       => (int)$row["id"],
            "name"     => $row["name"],
            "price"    => $row["price"],
            "quantity" => (int)$row["quantity"],
            "category" => $row["category"],
            "discount" => $row["discount"],
            "image"    => $imageUrl
        ];
    }
}

echo json_encode($products);
