<?php
session_start();
include dirname(__DIR__, 3) . "/src/controllers/dbConnection.php";

$type = $_POST['type'] ?? '';
$value = $_POST['value'] ?? '';
$userId = $_POST['userId'] ?? 0;

$response = ['exists' => false];

if ($type && $value) {
    if ($type === 'email') {
        $query = mysqli_query($con, "SELECT id FROM new_user WHERE user_email='$value' AND id != '$userId'");
        if (mysqli_num_rows($query) > 0) $response['exists'] = true;
    } elseif ($type === 'phone') {
        $query = mysqli_query($con, "SELECT id FROM new_user WHERE user_contact='$value' AND id != '$userId'");
        if (mysqli_num_rows($query) > 0) $response['exists'] = true;
    }
}

echo json_encode($response);