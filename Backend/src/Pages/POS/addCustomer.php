<?php
include BASE_PATH . "/src/controllers/dbConnection.php";

header('Content-Type: application/json');

$stmt = $con->prepare(
  "INSERT INTO customers (name, phone, email, address, city, country)
   VALUES (?, ?, ?, ?, ?, ?)"
);

$stmt->bind_param(
  "ssssss",
  $_POST['name'],
  $_POST['phone'],
  $_POST['email'],
  $_POST['address'],
  $_POST['city'],
  $_POST['country']
);

if ($stmt->execute()) {
    echo json_encode([
        "success" => true,
        "id" => $stmt->insert_id,
        "name" => $_POST['name']
    ]);
} else {
    echo json_encode(["success" => false]);
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