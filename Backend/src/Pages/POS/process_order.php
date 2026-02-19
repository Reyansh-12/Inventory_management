<?php
session_start();
include __DIR__ . "/src/controllers/dbConnection.php";

$cartItems = $_SESSION['order_cart'] ?? [];
if (empty($cartItems)) {
    die("Cart is empty!");
}

// Customer info
$c_id = $_SESSION['selected_customer_id'] ?? null;
$customerData = ['name' => 'Walk-in Customer', 'phone' => 'N/A', 'email' => 'N/A'];

if ($c_id) {
    $c_id = (int)$c_id;
    $res = mysqli_query($con, "SELECT name, phone, email FROM customers WHERE id = $c_id LIMIT 1");
    if ($row = mysqli_fetch_assoc($res)) {
        $customerData = $row;
    }
}

// Generate a random 4-digit order ID
$order_id = rand(1000, 9999);

// Payment method
$status = $_POST['payment_method'] ?? 'cash';

// Insert each cart item into order_list
foreach ($cartItems as $item) {
    $name = mysqli_real_escape_string($con, $item['name']);
    $category = mysqli_real_escape_string($con, $item['category'] ?? '');
    $brand = mysqli_real_escape_string($con, $item['brand'] ?? '');
    $quantity = (int)($item['quantity'] ?? 1);
    $price = (float)$item['price'];
    $total_amount = $price * $quantity;
    $image_path = mysqli_real_escape_string($con, $item['image_path'] ?? '');
    $customer_name = mysqli_real_escape_string($con, $customerData['name']);

    $query = "INSERT INTO order_list (`order_id`, `customer`, `product`, `category`, `brand`, `quantity`, `status`, `total_amount`, `price`, `image_path`, `created`)
              VALUES ('$order_id', '$customer_name', '$name', '$category', '$brand', $quantity, '$status', $total_amount, $price, '$image_path', NOW())";
    
    mysqli_query($con, $query);
}

// Clear cart after order
unset($_SESSION['order_cart']);
unset($_SESSION['selected_customer_id']);

// Redirect or show success
header("Location: pos.php?order_success=1&order_id=$order_id");
exit;
