<?php
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");

include "../../controllers/dbConnection.php"; // Aapka connection path

if (isset($_GET['email'])) {
    $email = mysqli_real_escape_string($con, $_GET['email']);
    
    // Query to fetch orders for the specific user
    $sql = "SELECT * FROM `order_list` WHERE `email` = '$email' ORDER BY `created` DESC";
    $result = mysqli_query($con, $sql);

    $orders = [];
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $orders[] = $row;
        }
    }
    echo json_encode($orders);
} else {
    echo json_encode(["message" => "Email parameter missing"]);
}
?>