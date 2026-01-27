<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Content-Type: application/json");

define("BASE_PATH", dirname(__DIR__, 3));
require_once BASE_PATH . "/src/controllers/dbConnection.php";

$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    echo json_encode([
        "status" => "error",
        "message" => "No input data received"
    ]);
    exit;
}

$transaction_id = $data['transaction_id'] ?? null;
$customer_id    = $data['customer_id'] ?? null;
$cart           = $data['cart'] ?? [];

if (!$transaction_id || empty($cart)) {
    echo json_encode([
        "status" => "error",
        "message" => "Invalid checkout data"
    ]);
    exit;
}

mysqli_begin_transaction($con);

try {
    foreach ($cart as $item) {

        $pid   = (int)$item['id'];
        $qty   = (int)$item['qty'];
        $price = (float)$item['price'];

        $res = mysqli_query(
            $con,
            "SELECT quantity FROM product_list WHERE id=$pid FOR UPDATE"
        );

        $row = mysqli_fetch_assoc($res);

        if (!$row || $row['quantity'] < $qty) {
            throw new Exception("Stock not available");
        }

        mysqli_query(
            $con,
            "UPDATE product_list 
             SET quantity = quantity - $qty 
             WHERE id = $pid"
        );

        mysqli_query(
            $con,
            "INSERT INTO order_items
            (transaction_id, product_id, quantity, price)
            VALUES
            ('$transaction_id', $pid, $qty, $price)"
        );
    }

    mysqli_commit($con);
    $_SESSION['transaction_id'] = 'ID' . date('YmdHis') . rand(100, 999);

    echo json_encode([
        'status' => 'success',
        'message' => 'Checkout completed successfully'
    ]);
    exit;

    echo json_encode([
        "status" => "success",
        "message" => "Checkout completed successfully"
    ]);
} catch (Exception $e) {
    mysqli_rollback($con);
    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]);
}
