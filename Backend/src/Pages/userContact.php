<?php
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}
header("Content-Type: application/json");
include __DIR__ . "/../../controllers/dbConnection.php";

$user = json_decode(file_get_contents('php://input'), true);
if (!$user) {
    die(json_encode(['status' => 0, 'message' => 'Invalid JSON input']));
}
$stmt = $con->prepare("INSERT INTO user_contact (firstName, lastName, Email, `message`) VALUES (?, ?, ?, ?)");
if (!$stmt) {
    die(json_encode(['status' => 0, 'message' => 'Prepare failed', 'error' => $con->error]));
}
$created_at = date('Y-m-d H:i:s');
$stmt->bind_param(
    "ssss",
    $user['firstName'],
    $user['lastName'],
    $user['email'],
    $user['message']
);
if ($stmt->execute()) {
    echo json_encode(['status' => 1, 'message' => 'Record created successfully']);
} else {
    echo json_encode(['status' => 0, 'message' => 'Insert failed', 'error' => $stmt->error]);
}
$stmt->close();
$con->close();
