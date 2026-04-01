<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
include 'db_connection.php'; 

// Email ko sanitize karein SQL Injection se bachne ke liye
$email = isset($_GET['email']) ? mysqli_real_escape_string($conn, $_GET['email']) : '';

if (!empty($email)) {
    /* Logic: Hum har unique address ka latest entry (MAX id) nikaal rahe hain, 
       taaki agar user ne ek hi jagah 2 baar order kiya ho toh wo repeat na ho.
    */
    $sql = "SELECT name, phone, address, nearby, city, pincode 
            FROM order_list 
            WHERE id IN (
                SELECT MAX(id) 
                FROM order_list 
                WHERE email = '$email' 
                GROUP BY address, pincode
            ) 
            ORDER BY id DESC LIMIT 5";
            
    $result = mysqli_query($conn, $sql);
    $addresses = [];

    if ($result) {
        while($row = mysqli_fetch_assoc($result)) {
            $addresses[] = $row;
        }
        
        // Agar addresses milte hain toh success true bhejein
        echo json_encode([
            "success" => true, 
            "addresses" => $addresses,
            "count" => count($addresses)
        ]);
    } else {
        echo json_encode(["success" => false, "message" => "Database error: " . mysqli_error($conn)]);
    }
} else {
    echo json_encode(["success" => false, "message" => "No email provided"]);
}
?>