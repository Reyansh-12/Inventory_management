<?php 
    // $server = "localhost";
    // $username = "root";
    // $password = "codeberg@2023"; 
    // $database = "inventory_management";
    // $con = mysqli_connect($server, $username, $password, $database);
    
?>
<?php
$servername = "sql311.infinityfree.com";
$username = "if0_40854183";        // yeh tumhara username hai
$password = "Reyansh082001";    // yeh tumhara password hai
$dbname = "if0_40854183_cosmetic"; // yeh tumhara database name hai

$con = new mysqli($servername, $username, $password, $dbname);

if ($con->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $con->connect_error]));
}
?>
