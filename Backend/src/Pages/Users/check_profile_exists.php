<?php
include "../../controllers/dbConnection.php";
session_start();

$userId = $_SESSION['user_id'] ?? 0;
$type   = $_POST['type'] ?? '';
$value  = trim($_POST['value'] ?? '');

$response = ['exists' => false];

if ($type === 'email') {
    $stmt = $con->prepare(
        "SELECT id FROM new_user WHERE user_email = ? AND id != ? LIMIT 1"
    );
    $stmt->bind_param("si", $value, $userId);
}
elseif ($type === 'phone') {
    $stmt = $con->prepare(
        "SELECT id FROM new_user WHERE user_contact = ? AND id != ? LIMIT 1"
    );
    $stmt->bind_param("si", $value, $userId);
}

if (!empty($stmt)) {
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $response['exists'] = true;
    }
}

echo json_encode($response);