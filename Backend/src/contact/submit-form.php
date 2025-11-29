<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

include('/xampp/htdocs/Inventory_management/Backend/src/controllers/dbConnection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Validate fields exist
    if (!isset($_POST['firstName'], $_POST['lastName'], $_POST['email'], $_POST['message'])) {
        echo "Missing required fields";
        exit;
    }

    $firstName = mysqli_real_escape_string($con, $_POST['firstName']);
    $lastName  = mysqli_real_escape_string($con, $_POST['lastName']);
    $email     = mysqli_real_escape_string($con, $_POST['email']);
    $message   = mysqli_real_escape_string($con, $_POST['message']);

    $sql = "INSERT INTO contact_submissions (first_name, last_name, email, message) 
            VALUES ('$firstName', '$lastName', '$email', '$message')";

    if (mysqli_query($con, $sql)) {
        echo "New record created successfully";
    } else {
        echo "Error: " . mysqli_error($con);
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
</body>
</html>