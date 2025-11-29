<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

include('/var/www/html/Inventory_management/Backend/src/controllers/dbConnection.php');

// Query only required fields
$sql = "SELECT id, product_name AS name, price
        FROM product_list";
$result = $con->query($sql);

$products = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = [
            "id"    => $row["id"],
            "name"  => $row["name"],
            "price" => $row["price"]
        ];
    }
}

echo json_encode($products);
?>