<?php
session_start();
define("BASE_PATH", dirname(__DIR__, 3));
include BASE_PATH . "/src/controllers/dbConnection.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cartItems = $_SESSION['order_cart'] ?? [];
    $status = $_POST['status'];
    $customer_id = $_SESSION['selected_customer_id'] ?? 0;
    
    $customerName = "Walk-in Customer";
    if ($customer_id > 0) {
        $c_res = mysqli_query($con, "SELECT name FROM customers WHERE id = $customer_id");
        if($c_row = mysqli_fetch_assoc($c_res)) $customerName = $c_row['name'];
    }

    $order_id = rand(1000, 9999);

    if (empty($cartItems)) {
        echo json_encode(['success' => false, 'message' => 'Cart is empty']);
        exit;
    }

    $error = false;
    foreach ($cartItems as $item) {
        $p_id = $item['id']; 
        $qty = $item['quantity'];
        $price = $item['price'];
        $total = $price * $qty;

        $p_query = mysqli_query($con, "SELECT category, brand_name, product_name, image_path FROM product_list WHERE id = '$p_id'");
        $p_data = mysqli_fetch_assoc($p_query);

        $p_name = $p_data['product_name'];
        $cat = $p_data['category'];
        $brand = $p_data['brand_name'];
        $img = $p_data['image_path'];

        $insert = "INSERT INTO `order_list`(`order_id`, `customer`, `product`, `category`, `brand`, `quantity`, `status`, `total_amount`, `price`, `image_path`, `created`) 
                   VALUES ('$order_id', '$customerName', '$p_name', '$cat', '$brand', '$qty', '$status', '$total', '$price', '$img', NOW())";
        
        if (mysqli_query($con, $insert)) {
            mysqli_query($con, "UPDATE product_list SET quantity = quantity - $qty WHERE id = '$p_id'");
        } else {
            $error = true;
        }
    }

    if (!$error) {
        unset($_SESSION['order_cart']); 
        unset($_SESSION['selected_customer_id']);
        echo json_encode(['success' => true, 'order_id' => $order_id]);
        
    } else {
        echo json_encode(['success' => false, 'message' => 'Database Error']);
    }
}
?>