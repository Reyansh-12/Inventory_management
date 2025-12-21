<?php
define("BASE_PATH", dirname(__DIR__, 2));
include BASE_PATH . "/src/Layouts/Links.php";
include BASE_PATH . "/src/controllers/dbConnection.php";

$sql = "SELECT `id`, `product_name`, `category`, `brand_name`, `minQuantity`, 
        `price`, `quantity`, `description`, `discount`, `status`, `expired_date` 
        FROM `product_list` 
        WHERE `quantity` <= `minQuantity`";

$result = $con->query($sql);

if (isset($_GET['lowStockId'])) {
    $lowStockId = $_GET['lowStockId'];

    $stmt = $con->prepare("DELETE FROM `product_list` WHERE `id` = ?");
    $stmt->bind_param("i", $lowStockId);
    $stmt->execute();
    $stmt->close();

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minimum Stock Products</title>
    <link rel="stylesheet" href="/Backend/src/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="/Backend/src/assets/css/style.css">
</head>

<body>
    <div class="main-wrapper">
        <?php include BASE_PATH . "/src/Layouts/Sidebar.php"; ?>
        <?php include BASE_PATH . "/src/Layouts/Header.php"; ?>
        <div class="page-wrapper">
            <div class="content">
                <div class="page-header">
                    <div class="page-title">
                        <h4>Products at Minimum Stock</h4>
                        <h6>Products where quantity equals minQuantity</h6>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Product Name</th>
                                        <th>Category</th>
                                        <th>Brand Name</th>
                                        <th>Quantity</th>
                                        <th>Min Quantity</th>
                                        <th>Price</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<tr>";
                                            echo "<td><a href='' class='text-truncate w-50' data-bs-toggle='tooltip' data-bs-title='" . $row['product_name'] . "'>" . $row['product_name'] . "</a></td>";
                                            echo "<td>" . $row['category'] . "</td>";
                                            echo "<td>" . $row['brand_name'] . "</td>";
                                            echo "<td>" . $row['quantity'] . "</td>";
                                            echo "<td>" . $row['minQuantity'] . "</td>";
                                            echo "<td>" . $row['price'] . "</td>";
                                            echo "<td>
                                                <a class='btn btn-sm' href='?lowStockId=" . $row['id'] . "' onclick='return confirm(\"Are you sure to delete?\");' data-bs-toggle='tooltip' data-bs-title='Delete'>
                                                    <img src='/Backend/src/assets/images/icons/delete.svg' alt='img'>
                                                </a>
                                              </td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='7'>No products at minimum stock.</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>