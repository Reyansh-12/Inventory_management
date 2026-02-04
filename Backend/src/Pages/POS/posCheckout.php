<?php
session_start();
define("BASE_PATH", dirname(__DIR__, 3));
include BASE_PATH . "/src/Layouts/Links.php";
include BASE_PATH . "/src/controllers/dbConnection.php";

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$data = json_decode(file_get_contents('php://input'), true);
$customerId = $data['customerId'] ?? null;
$cartItems = $data['cartItems'] ?? [];

if (!$customerId || empty($cartItems)) {
    echo json_encode(['status' => 'error', 'message' => 'Missing customer or cart items']);
    exit;
}

$orderId = 'ORD' . date('YmdHis') . rand(100, 999);

foreach ($cartItems as $item) {
    $productId = (int) $item['id'];
    $quantity  = (int) $item['qty'];
    $price     = (float) $item['price'];
    $total     = $quantity * $price;

    $res = $con->query("SELECT category, brand FROM product_list WHERE id=$productId LIMIT 1");
    if ($res && $res->num_rows > 0) {
        $row = $res->fetch_assoc();
        $category = $row['category'] ?? '';
        $brand = $row['brand'] ?? '';
    } else {
        $category = '';
        $brand = '';
    }

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
