<?php
include "dbConnection.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    if (!isset($data['cart']) || empty($data['cart'])) {
        echo json_encode(["status" => "error", "message" => "Cart empty"]);
        exit;
    }

    foreach ($data['cart'] as $item) {
        $id = (int)$item['id'];
        $qty = (int)$item['qty'];

        // Reduce quantity
        $update = $con->prepare("UPDATE product_list SET quantity = quantity - ? WHERE id = ? AND quantity >= ?");
        $update->bind_param("iii", $qty, $id, $qty);
        $update->execute();
    }

    echo json_encode(["status" => "success"]);
}
?>
