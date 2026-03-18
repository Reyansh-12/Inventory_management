<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
include "../../controllers/dbConnection.php";

$customer_id = $_GET['customer_id'] ?? 0;

if($customer_id > 0) {
    // User ke last order se address details nikaalna
    $query = "SELECT address, city, pincode, phone FROM orders WHERE customer_id = ? ORDER BY id DESC LIMIT 1";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "i", $customer_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if($row = mysqli_fetch_assoc($result)) {
        echo json_encode(["success" => true, "data" => $row]);
    } else {
        echo json_encode(["success" => false, "message" => "No previous address found"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid User ID"]);
}
?>