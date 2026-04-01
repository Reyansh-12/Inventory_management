<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
include 'db_connection.php'; // Your DB connection file

$email = $_GET['email'] ?? '';

if (!empty($email)) {
    // Fetch unique addresses used by this email in previous orders
    // GROUP BY address prevents duplicate boxes for the same location
    $sql = "SELECT name, phone, address, nearby, city, pincode 
            FROM order_list 
            WHERE email = '$email' 
            GROUP BY address, pincode 
            ORDER BY created DESC";
            
    $result = mysqli_query($conn, $sql);
    $addresses = [];

    while($row = mysqli_fetch_assoc($result)) {
        $addresses[] = $row;
    }

    echo json_encode(["success" => true, "addresses" => $addresses]);
} else {
    echo json_encode(["success" => false, "message" => "Email is required"]);
}
?>