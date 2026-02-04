<?php
include BASE_PATH . "/Backend/src/controllers/dbConnection.php";
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);
$cart = $data['cartItems'];

$con->begin_transaction();

foreach ($cart as $item) {

    $stmt = $con->prepare(
        "SELECT quantity FROM product_list WHERE id = ? FOR UPDATE"
    );
    $stmt->bind_param("i", $item['id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $stock = $result->fetch_assoc()['quantity'];

    if ($stock < $item['qty']) {
        $con->rollback();
        echo json_encode([
            "status" => "error",
            "message" => "Not enough stock for {$item['name']}"
        ]);
        exit;
    }

    $update = $con->prepare(
        "UPDATE product_list SET quantity = quantity - ? WHERE id = ?"
    );
    $update->bind_param("ii", $item['qty'], $item['id']);
    $update->execute();
}

$con->commit();
echo json_encode(["status" => "success"]);
