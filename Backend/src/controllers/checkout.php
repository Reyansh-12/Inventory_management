<?php
include BASE_PATH . "/src/controllers/dbConnection.php";
header('Content-Type: application/json');

$cart = $_POST['cart'];
$con->begin_transaction();

foreach ($cart as $item) {

    $res = $con->query(
        "SELECT quantity FROM product_list WHERE id={$item['id']} FOR UPDATE"
    );
    $stock = $res->fetch_assoc()['quantity'];

    if ($stock < $item['qty']) {
        $con->rollback();
        echo json_encode([
            "success" => false,
            "message" => "Not enough stock for {$item['name']}"
        ]);
        exit;
    }

    $con->query(
        "UPDATE product_list
         SET quantity = quantity - {$item['qty']}
         WHERE id = {$item['id']}"
    );
}

$con->commit();
echo json_encode(["success" => true]);
