<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
include "../../controllers/dbConnection.php";

$customer_id = $_GET['customer_id'] ?? 0;

if($customer_id > 0) {
    // Orders table se latest order ka address fetch karna
    $sql = "SELECT address, city, pincode, phone FROM orders 
            WHERE customer_id = ? 
            ORDER BY id DESC LIMIT 1";
            
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "i", $customer_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if($row = mysqli_fetch_assoc($result)) {
        echo json_encode(["success" => true, "data" => $row]);
    } else {
        echo json_encode(["success" => false, "message" => "First order"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid User"]);
}
?>