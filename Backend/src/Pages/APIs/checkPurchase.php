<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
include "../../controllers/dbConnection.php"; 

$customer_name = $_GET['customer']; 
$product_name = $_GET['product'];   

$query = "SELECT id FROM order_list 
          WHERE customer = ? 
          AND product = ? 
          AND status = 'Delivered' 
          LIMIT 1";

$stmt = mysqli_prepare($con, $query);
mysqli_stmt_bind_param($stmt, "ss", $customer_name, $product_name);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) > 0) {
    echo json_encode(["hasBought" => true]);
} else {
    echo json_encode(["hasBought" => false]);
}
?>