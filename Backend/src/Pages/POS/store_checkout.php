<?php
session_start();
$data = json_decode(file_get_contents('php://input'), true);

if ($data) {
    $cart = $data['cart'] ?? [];
    $customer_id = $data['customer_id'] ?? null;

    // Save cart and customer in session or process order in DB
    $_SESSION['order_cart'] = $cart;
    $_SESSION['order_customer_id'] = $customer_id;

    // Respond success
    echo json_encode(['status' => 'success']);
    exit;
}

echo json_encode(['status' => 'error', 'message' => 'Invalid data']);
?>
