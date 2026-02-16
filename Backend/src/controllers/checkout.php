<?php
session_start();
include "dbConnection.php"; 

$data = json_decode(file_get_contents('php://input'), true);

$cart = $data['cart'] ?? [];
$customer_id = $data['customer_id'] ?? null;

if (empty($cart)) {
    echo json_encode(['status'=>'error','message'=>'Cart is empty']);
    exit;
}

$order_id = 'ORD' . date('YmdHis') . rand(100, 999);

$insert_success = true;
foreach ($cart as $item) {
    $product_id = intval($item['id']);
    $product_name = mysqli_real_escape_string($con, $item['name']);
    $category = mysqli_real_escape_string($con, $item['category'] ?? 'Unknown'); // default
    $brand = mysqli_real_escape_string($con, $item['brand'] ?? 'Generic'); // default
    $qty = intval($item['qty']);
    $price = floatval($item['price']);
    $total_amount = $price * $qty;
    $status = 'Pending';
    $created = date('Y-m-d H:i:s');

    $sql = "INSERT INTO order_list 
            (`order_id`,`customer`,`product`,`category`,`brand`,`quantity`,`status`,`total_amount`,`price`,`created`) 
            VALUES 
            ('$order_id', '$customer_id', '$product_name', '$category', '$brand', $qty, '$status', $total_amount, $price, '$created')";

    if (!mysqli_query($con, $sql)) {
        $insert_success = false;
        $error = mysqli_error($con);
        break;
    }
}

if ($insert_success) {
    echo json_encode(['status'=>'success','message'=>'Order placed successfully!']);
} else {
    echo json_encode(['status'=>'error','message'=>'Failed to save order: ' . $error]);
}
