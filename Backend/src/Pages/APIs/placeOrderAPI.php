<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Content-Type: application/json");

// CORS handle karne ke liye (Options request)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit;
}

include "../../controllers/dbConnection.php"; 

$data = json_decode(file_get_contents("php://input"), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($data)) {
    
    // Unique Order ID (Prefix + Random + Timestamp for uniqueness)
    $order_id = "ORD-" . rand(100000, 999999); 

    $customer_id = (int)$data['customer_id'];
    $customer = mysqli_real_escape_string($con, $data['customer']);
    $email = mysqli_real_escape_string($con, $data['email']);
    $phone = mysqli_real_escape_string($con, $data['phone']);
    $address = mysqli_real_escape_string($con, $data['address']);
    $city = mysqli_real_escape_string($con, $data['city']);
    $pincode = mysqli_real_escape_string($con, $data['pincode']);
    $shipping = (float)$data['shipping_charge'];
    $payment_method = mysqli_real_escape_string($con, $data['payment_method']);
    
    // Note: total_amount pure order ka sum hai jo frontend se aa raha hai
    $grand_total = (float)$data['total_amount'];
    
    $items = $data['items'];
    $success_count = 0;

    // Transaction Start (Taaki saare items ya to insert hon ya ek bhi nahi)
    mysqli_begin_transaction($con);

    try {
        foreach ($items as $item) {
            $p_name = mysqli_real_escape_string($con, $item['name']);
            $p_id = (int)$item['id'];
            $p_qty = (int)$item['qty'];
            $p_price = (float)$item['price'];
            $image = mysqli_real_escape_string($con, $item['image']);
            
            // Default values handle karein
            $category = isset($item['category']) ? mysqli_real_escape_string($con, $item['category']) : 'General';
            $brand = isset($item['brand']) ? mysqli_real_escape_string($con, $item['brand']) : 'Cosmelina';

            $sql = "INSERT INTO `order_list` (
                `order_id`, `customer_id`, `customer`, `email`, `phone`, `address`, `city`, `pincode`, 
                `product`, `product_id`, `category`, `brand`, `quantity`, `shipping_charge`, 
                `payment_method`, `status`, `total_amount`, `price`, `seen`, `image_path`, `created`
            ) VALUES (
                '$order_id', $customer_id, '$customer', '$email', '$phone', '$address', '$city', '$pincode', 
                '$p_name', $p_id, '$category', '$brand', $p_qty, $shipping, 
                '$payment_method', 'Pending', $grand_total, $p_price, 0, '$image', NOW()
            )";

            if (!mysqli_query($con, $sql)) {
                throw new Exception(mysqli_error($con));
            }
            $success_count++;
        }

        // Sab sahi raha to commit karein
        mysqli_commit($con);
        echo json_encode(["success" => true, "order_id" => $order_id, "message" => "Order placed successfully!"]);

    } catch (Exception $e) {
        // Error aane par sab kuch rollback kar dein
        mysqli_rollback($con);
        http_response_code(500);
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid Request"]);
}
?>