<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Content-Type: application/json");

include "../../controllers/dbConnection.php"; 

$data = json_decode(file_get_contents("php://input"), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($data)) {
    
    // AGAR DB MEIN INT HAI TO SIRF rand() USE KAREIN. 
    // AGAR VARCHAR HAI TO "ORD-".rand() USE KAREIN.
    $order_id_raw = rand(100000, 999999); 
    $order_id = "ORD-" . $order_id_raw; // Database change karne ke baad ye best hai

    $customer_id = (int)$data['customer_id'];
    $customer = mysqli_real_escape_string($con, $data['customer']);
    $email = mysqli_real_escape_string($con, $data['email']);
    $phone = mysqli_real_escape_string($con, $data['phone']);
    $address = mysqli_real_escape_string($con, $data['address']);
    $city = mysqli_real_escape_string($con, $data['city']);
    $pincode = mysqli_real_escape_string($con, $data['pincode']);
    $shipping = (float)$data['shipping_charge'];
    $payment_method = mysqli_real_escape_string($con, $data['payment_method']);
    $total_amount = (float)$data['total_amount'];
    
    $items = $data['items'];
    $success_count = 0;

    foreach ($items as $item) {
        $p_name = mysqli_real_escape_string($con, $item['name']);
        $p_id = (int)$item['id'];
        $p_qty = (int)$item['qty'];
        $p_price = (float)$item['price'];
        $image = mysqli_real_escape_string($con, $item['image']);
        
        $category = isset($item['category']) ? mysqli_real_escape_string($con, $item['category']) : 'General';
        $brand = isset($item['brand']) ? mysqli_real_escape_string($con, $item['brand']) : 'None';

        $sql = "INSERT INTO `order_list` (
            `order_id`, `customer_id`, `customer`, `email`, `phone`, `address`, `city`, `pincode`, 
            `product`, `product_id`, `category`, `brand`, `quantity`, `shipping_charge`, 
            `payment_method`, `status`, `total_amount`, `price`, `seen`, `image_path`, `created`
        ) VALUES (
            '$order_id', $customer_id, '$customer', '$email', '$phone', '$address', '$city', '$pincode', 
            '$p_name', $p_id, '$category', '$brand', $p_qty, $shipping, 
            '$payment_method', 'Pending', $total_amount, $p_price, 0, '$image', NOW()
        )";

        if (mysqli_query($con, $sql)) {
            $success_count++;
        }
    }

    if ($success_count > 0) {
        echo json_encode(["success" => true, "order_id" => $order_id]);
    } else {
        http_response_code(500);
        echo json_encode(["success" => false, "message" => mysqli_error($con)]);
    }
}
?>