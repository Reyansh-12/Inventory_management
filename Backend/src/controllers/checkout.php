<?php
define("BASE_PATH", dirname(__DIR__, 3));
include BASE_PATH . "/src/controllers/dbConnection.php";
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

$cart = $data['cart'] ?? [];
$customerId = $data['customer_id'] ?? null;
$transactionId = $data['transaction_id'] ?? null;

if (!$cart || !$customerId || !$transactionId) {
    echo json_encode(["status" => "error", "message" => "Invalid request"]);
    exit;
}

try {
    $con->begin_transaction();

    foreach ($cart as $item) {
        $res = $con->query("SELECT quantity FROM product_list WHERE id={$item['id']} FOR UPDATE");
        if (!$res || $res->num_rows === 0) throw new Exception("Product {$item['name']} not found");
        $stock = (int)$res->fetch_assoc()['quantity'];
        if ($stock < $item['qty']) throw new Exception("Not enough stock for {$item['name']}");
        $con->query("UPDATE product_list SET quantity = quantity - {$item['qty']} WHERE id = {$item['id']}");
    }

    $con->commit();
    echo json_encode(["status" => "success", "message" => "Checkout successful!"]);
} catch (Exception $e) {
    $con->rollback();
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
