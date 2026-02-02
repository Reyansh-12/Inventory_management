<?php
session_start();
include "../../controllers/dbConnection.php";

// Enable mysqli errors for debugging
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Get JSON input from AJAX
$data = json_decode(file_get_contents('php://input'), true);
$customerId = $data['customerId'] ?? null;
$cartItems = $data['cartItems'] ?? [];

if (!$customerId || empty($cartItems)) {
    echo json_encode(['status' => 'error', 'message' => 'Missing customer or cart items']);
    exit;
}

// Generate unique order ID
$orderId = 'ORD' . date('YmdHis') . rand(100, 999);

foreach ($cartItems as $item) {
    $productId = (int) $item['id'];
    $quantity  = (int) $item['qty'];
    $price     = (float) $item['price'];
    $total     = $quantity * $price;

    // Get product category and brand
    $res = $con->query("SELECT category, brand FROM product_list WHERE id=$productId LIMIT 1");
    if ($res && $res->num_rows > 0) {
        $row = $res->fetch_assoc();
        $category = $row['category'] ?? '';
        $brand = $row['brand'] ?? '';
    } else {
        $category = '';
        $brand = '';
    }

    // Insert into order_list
    $stmt = $con->prepare("INSERT INTO order_list 
        (order_id, customer, product, category, brand, quantity, status, price, total_amount, created) 
        VALUES (?, ?, ?, ?, ?, ?, 'Pending', ?, ?, NOW())");

    $stmt->bind_param(
        "sssssidd",
        $orderId,
        $customerId,
        $productId,
        $category,
        $brand,
        $quantity,
        $price,
        $total
    );

    if (!$stmt->execute()) {
        echo json_encode(['status' => 'error', 'message' => $stmt->error]);
        exit;
    }
}

echo json_encode(['status' => 'success', 'order_id' => $orderId]);
