<?php
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Access-Control-Allow-Credentials: true");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit;
}

include "../../controllers/dbConnection.php";

$data = json_decode(file_get_contents("php://input"), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($data)) {
    
    $order_id = "ORD-" . rand(100000, 999999); 
    $customer_id = (int)$data['customer_id'];
    $customer = mysqli_real_escape_string($con, $data['customer']);
    $email = mysqli_real_escape_string($con, $data['email']);
    $phone = mysqli_real_escape_string($con, $data['phone']);
    $address = mysqli_real_escape_string($con, $data['address']);
    $nearby = mysqli_real_escape_string($con, $data['nearby']);
    $city = mysqli_real_escape_string($con, $data['city']);
    $pincode = mysqli_real_escape_string($con, $data['pincode']);
    $shipping = (float)$data['shipping_charge'];
    $payment_method = mysqli_real_escape_string($con, $data['payment_method']);
    $grand_total = (float)$data['total_amount'];
    
    $items = $data['items'];

    $p_names = []; $p_ids = []; $p_qtys = []; $p_prices = []; $p_imgs = [];

    mysqli_begin_transaction($con);

    try {
        foreach ($items as $item) {
            $p_id = (int)$item['id'];
            $buy_qty = (int)$item['qty'];

            $check_sql = "SELECT `quantity`, `product_name` FROM `product_list` WHERE `id` = $p_id FOR UPDATE";
            $res = mysqli_query($con, $check_sql);
            $product = mysqli_fetch_assoc($res);

            if (!$product || $product['quantity'] < $buy_qty) {
                throw new Exception("Stock Issue: " . ($product['product_name'] ?? "Unknown Product") . " is out of stock!");
            }

            $update_stock = "UPDATE `product_list` SET `quantity` = `quantity` - $buy_qty WHERE `id` = $p_id";
            mysqli_query($con, $update_stock);

            $p_names[] = $item['name'];
            $p_ids[] = $item['id'];
            $p_qtys[] = $item['qty'];
            $p_prices[] = $item['price'];
            $p_imgs[] = $item['image'];
        }

        $final_names = mysqli_real_escape_string($con, implode(", ", $p_names));
        $final_ids = mysqli_real_escape_string($con, implode(", ", $p_ids));
        $final_qtys = mysqli_real_escape_string($con, implode(", ", $p_qtys));
        $final_prices = mysqli_real_escape_string($con, implode(", ", $p_prices));
        $final_imgs = mysqli_real_escape_string($con, implode(", ", $p_imgs));

        $sql = "INSERT INTO `order_list` (
            `order_id`, `customer_id`, `customer`, `email`, `phone`, `address`, `nearby`, `city`, `pincode`, 
            `product`, `product_id`, `category`, `brand`, `quantity`, `shipping_charge`, 
            `payment_method`, `status`, `total_amount`, `price`, `seen`, `image_path`, `created`
        ) VALUES (
            '$order_id', $customer_id, '$customer', '$email', '$phone', '$address', '$nearby', '$city', '$pincode', 
            '$final_names', '$final_ids', 'Multiple', 'Cosmelina', '$final_qtys', $shipping, 
            '$payment_method', 'Pending', $grand_total, '$final_prices', 0, '$final_imgs', NOW()
        )";

        if (!mysqli_query($con, $sql)) {
            throw new Exception("Order could not be saved: " . mysqli_error($con));
        }

        mysqli_commit($con);
        echo json_encode(["success" => true, "order_id" => $order_id, "message" => "Order placed and stock updated!"]);

    } catch (Exception $e) {
        mysqli_rollback($con);
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid Request"]);
}
?>