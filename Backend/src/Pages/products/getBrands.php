<?php
include __DIR__ . '/../../controllers/dbConnection.php';

if (!isset($_GET['category_id'])) {
    echo json_encode([]);
    exit;
}

$categoryId = intval($_GET['category_id']);

$stmt = mysqli_prepare($con, "SELECT brands FROM category WHERE id=? AND status='Active'");
mysqli_stmt_bind_param($stmt, "i", $categoryId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$brands = [];

if ($row = mysqli_fetch_assoc($result)) {
    if (!empty($row['brands'])) {
        $brands = array_map('trim', explode(',', $row['brands']));
    }
}

mysqli_stmt_close($stmt);

header('Content-Type: application/json');
echo json_encode($brands);
