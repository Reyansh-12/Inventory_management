<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit;
}

include __DIR__ . "/../../controllers/dbConnection.php";

$input = json_decode(file_get_contents("php://input"), true);


if (!isset($input['id']) || empty($input['id'])) {
    echo json_encode(["success" => false, "error" => "Missing product ID"]);
    exit;
}

$productId = (int)$input['id'];

$con->begin_transaction();

try {
    $selectSql = "SELECT quantity FROM product_list WHERE id = ? FOR UPDATE";
    $selectStmt = $con->prepare($selectSql);
    $selectStmt->bind_param("i", $productId);
    $selectStmt->execute();
    $res = $selectStmt->get_result();

    if ($res->num_rows === 0) {
        $con->rollback();
        echo json_encode(["success" => false, "error" => "Product not found"]);
        exit;
    }

    $row = $res->fetch_assoc();
    $currentQty = (int)$row['quantity'];

    if ($currentQty <= 0) {
        $con->rollback();
        echo json_encode(["success" => false, "error" => "Out of stock"]);
        exit;
    }

    $updateSql = "UPDATE product_list SET quantity = quantity - 1 WHERE id = ?";
    $updateStmt = $con->prepare($updateSql);
    $updateStmt->bind_param("i", $productId);
    $updateStmt->execute();

    $selectStmt->execute();
    $res2 = $selectStmt->get_result();
    $newQtyRow = $res2->fetch_assoc();
    $newQty = (int)$newQtyRow['quantity'];

    $con->commit();

    echo json_encode(["success" => true, "newQuantity" => $newQty]);
} catch (Exception $e) {
    $con->rollback();
    echo json_encode(["success" => false, "error" => $e->getMessage()]);
}
