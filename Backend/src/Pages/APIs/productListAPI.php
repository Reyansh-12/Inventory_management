<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET");
header("Content-Type: application/json");

include __DIR__ . "/../../controllers/dbConnection.php";

$sql = "SELECT p.id AS id, p.product_name AS name, p.price, p.quantity, p.image_path AS image
        FROM product_list p";
$result = $con->query($sql);

$products = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        var_dump($row);

        $imageUrl = !empty($row['image_path']) ? $row['image_path'] : null;

        $products[] = [
            "id"       => (int)$row["id"],
            "name"     => $row["name"],
            "price"    => $row["price"],
            "quantity" => isset($row["quantity"]) ? (int)$row["quantity"] : null,
            "image"    => $imageUrl
        ];
    }
}

echo json_encode($products);
