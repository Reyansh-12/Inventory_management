<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Content-Type: application/json; charset=UTF-8");

include "../../controllers/dbConnection.php"; 

$email = isset($_GET['email']) ? mysqli_real_escape_string($con, $_GET['email']) : '';
$product = isset($_GET['product']) ? mysqli_real_escape_string($con, $_GET['product']) : '';

if (empty($email) || empty($product)) {
    echo json_encode(["hasBought" => false, "message" => "Missing data"]);
    exit;
}

$sql = "SELECT id FROM order_list 
        WHERE email = '$email' 
        AND product = '$product' 
        AND status = 'Delivered' 
        LIMIT 1";

$result = mysqli_query($con, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    echo json_encode(["hasBought" => true]);
} else {
    echo json_encode(["hasBought" => false]);
}
?>