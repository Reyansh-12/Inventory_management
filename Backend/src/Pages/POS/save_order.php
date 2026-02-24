<?php
session_start();
require_once __DIR__ . "/../../controllers/dbConnection.php";

header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

// DEBUG: Session check
if (!isset($_SESSION['order_cart'])) {
    echo json_encode([
        'success' => false,
        'message' => 'SESSION order_cart not found'
    ]);
    exit;
}

$cartItems = $_SESSION['order_cart'];

if (empty($cartItems)) {
    echo json_encode([
        'success' => false,
        'message' => 'Cart is empty'
    ]);
    exit;
}

$order_id = 'ORD-' . strtoupper(uniqid());
$customer = mysqli_real_escape_string($con, $_POST['customer'] ?? 'Walk-in');
$status   = mysqli_real_escape_string($con, $_POST['status'] ?? 'Cash');

mysqli_begin_transaction($con);

try {

    foreach ($cartItems as $item) {

        // DEBUG safety
        if (!isset($item['name'], $item['price'], $item['quantity'])) {
            throw new Exception('Cart item structure invalid');
        }

        $product  = mysqli_real_escape_string($con, $item['name']);
        $category = mysqli_real_escape_string($con, $item['category'] ?? 'General');
        $brand    = mysqli_real_escape_string($con, $item['brand'] ?? 'N/A');
        $qty      = (int)$item['quantity'];
        $price    = (float)$item['price'];
        $total    = $qty * $price;
        $image    = mysqli_real_escape_string($con, $item['image_path'] ?? '');

        $sql = "INSERT INTO order_list
        (order_id, customer, product, category, brand, quantity, status, total_amount, price, seen, image_path, created)
        VALUES
        ('$order_id','$customer','$product','$category','$brand',$qty,'$status',$total,$price,0,'$image',NOW())";

        if (!mysqli_query($con, $sql)) {
            throw new Exception(mysqli_error($con));
        }
    }

    mysqli_commit($con);
    unset($_SESSION['order_cart']);

    echo json_encode([
        'success' => true,
        'order_id' => $order_id
    ]);

} catch (Exception $e) {

    mysqli_rollback($con);

    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}