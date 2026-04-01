<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json; charset=UTF-8");

// 1. Database Connection
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "your_database_name"; // Apne database ka naam yahan likhein

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    echo json_encode(["error" => "Connection failed: " . $conn->connect_error]);
    exit();
}

// 2. Get Email from React Request
$email = isset($_GET['email']) ? $_GET['email'] : '';

if (!empty($email)) {
    // 3. SQL Query (Prepared Statement for Security)
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
    
    // 4. Send Response
    if (count($orders) > 0) {
        echo json_encode($orders);
    } else {
        echo json_encode([]); // Khali array agar koi order na mile
    }
} else {
    echo json_encode(["error" => "Email parameter is missing"]);
}

$conn->close();
?>