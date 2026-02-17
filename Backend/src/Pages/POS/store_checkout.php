<?php
session_start();

$data = json_decode(file_get_contents('php://input'), true);

if (!$data) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid data']);
    exit;
}

$cart = $data['cart'] ?? [];
$customer_id = $data['customer_id'] ?? null;

if (empty($customer_id)) {
    echo json_encode(['status' => 'error', 'message' => 'Customer not selected']);
    exit;
}

$cleanCart = [];

foreach ($cart as $item) {
    $cleanCart[] = [
        "id" => $item['id'] ?? null,
        "name" => $item['name'] ?? '',
        "price" => (float)($item['price'] ?? 0),
        "quantity" => (int)($item['quantity'] ?? $item['qty'] ?? 1),
    ];
}

$_SESSION['order_cart'] = $cleanCart;

$_SESSION['selected_customer_id'] = (int)$customer_id;

$_SESSION['order_customer_id'] = (int)$customer_id;

echo json_encode(['status' => 'success']);
exit;
?>
