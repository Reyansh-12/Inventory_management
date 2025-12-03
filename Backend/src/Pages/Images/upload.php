<?php
define("BASE_PATH", dirname(__DIR__, 3));
include BASE_PATH . "/src/Layouts/Links.php";
include BASE_PATH . "/src/controllers/dbConnection.php";
if (!is_dir("uploads")) {
    mkdir("uploads", 0777, true);
}

if (isset($_FILES['image'])) {

    $imageName = $_FILES["image"]["name"];
    $tempPath  = $_FILES["image"]["tmp_name"];

    $uploadDir = BASE_PATH . "/uploads/";  // now uploads at Backend/uploads/
$newName = time() . "-" . basename($_FILES["image"]["name"]);
$filePath = $uploadDir . $newName;

if (move_uploaded_file($_FILES["image"]["tmp_name"], $filePath)) {
    $dbPath = "uploads/" . $newName;  // this path is stored in DB

    $sql = "INSERT INTO images (image_path) VALUES ('$dbPath')";
    $conn->query($sql);
}
 else {
        echo "Failed to move uploaded file.";
    }

} else {
    echo "No file uploaded.";
}
