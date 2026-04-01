<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json; charset=UTF-8");

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "your_database_name"; 

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    echo json_encode(["error" => "Connection failed: " . $conn->connect_error]);
    exit();
}

$email = isset($_GET['email']) ? $_GET['email'] : '';

if (!empty($email)) {
    $sql = "SELECT id, order_id, product, brand, quantity, status, total_amount, price, image_path, created, payment_method, address, city 
            FROM order_list 
            WHERE email = ? 
            ORDER BY created DESC";
            
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $orders = [];
    while($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }
    
    if (count($orders) > 0) {
        echo json_encode($orders);
    } else {
        echo json_encode([]); 
    }
} else {
    echo json_encode(["error" => "Email parameter is missing"]);
}

$conn->close();
?>