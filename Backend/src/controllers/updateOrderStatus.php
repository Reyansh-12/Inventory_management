<?php
include __DIR__ . '/dbConnection.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = mysqli_real_escape_string($con, $_POST['order_id']);
    $status   = mysqli_real_escape_string($con, $_POST['status']);

    $allowed = ['Pending', 'Delivered', 'Canceled'];
    
    if (in_array($status, $allowed)) {
        $query = "UPDATE order_list SET status = '$status' WHERE order_id = '$order_id'";
        
        if (mysqli_query($con, $query)) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => mysqli_error($con)]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid status value']);
    }
}
?>