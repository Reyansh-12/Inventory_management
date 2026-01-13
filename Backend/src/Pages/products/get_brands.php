<?php
require_once "../../../src/controllers/dbConnection.php";

header('Content-Type: application/json');

$categoryId = isset($_GET['category_id']) ? (int)$_GET['category_id'] : 0;
$brands = [];

if ($categoryId > 0) {
    $stmt = mysqli_prepare($con, "SELECT brands FROM category WHERE id=? AND status='Active'");
    mysqli_stmt_bind_param($stmt, "i", $categoryId);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($res)) {
        if (!empty($row['brands'])) {
            $brands = array_map('trim', explode(',', $row['brands']));
        }
    }
}

echo json_encode($brands);
exit;
