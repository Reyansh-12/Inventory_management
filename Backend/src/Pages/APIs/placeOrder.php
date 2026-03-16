<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

$conn = new mysqli("localhost", "root", "", "your_db_name");

if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "DB Connection Failed"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

if (!empty($data['items'])) {
    $customer = $data['customer_name'];
    $total_order_amount = $data['total_amount'];
    $order_id = "ORD-" . rand(1000, 9999); 
    $status = "Pending";
    $seen = 0;
    
    $success = true;

    foreach ($data['items'] as $item) {
        $product = $item['name'];
        $price = $item['price'];
        $qty = $item['qty'];
        $image = $item['image'];
        $total = $price * $qty;
        
        $category = "General"; 
        $brand = "Default";

        $sql = "INSERT INTO `order_list` 
                (`order_id`, `customer`, `product`, `category`, `brand`, `quantity`, `status`, `total_amount`, `price`, `seen`, `image_path`, `created`) 
                VALUES ('$order_id', '$customer', '$product', '$category', '$brand', '$qty', '$status', '$total', '$price', '$seen', '$image', NOW())";

        if (!$conn->query($sql)) {
            $success = false;
            break;
        }
    }

    if ($success) {
        echo json_encode(["success" => true, "message" => "Order saved to DB"]);
    } else {
        echo json_encode(["success" => false, "message" => $conn->error]);
    }
} else {
    echo json_encode(["success" => false, "message" => "No items in cart"]);
}

$conn->close();
?>