<?php
// fetchReviewsAPI.php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
include "../../controllers/dbConnection.php"; 

$product_id = $_GET['product_id'];

$sql = "SELECT * FROM reviews WHERE product_id = '$product_id' AND status = 'approved' ORDER BY created_at DESC";
$result = mysqli_query($con, $sql);

$reviews = [];
while($row = mysqli_fetch_assoc($result)) {
    $reviews[] = $row;
}

echo json_encode($reviews);
?>