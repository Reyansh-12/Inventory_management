<?php
define("BASE_PATH", dirname(__DIR__, 2));
include BASE_PATH . "/src/Layouts/Links.php";
include BASE_PATH . "/src/controllers/dbConnection.php";

if(isset($_GET['expiredProductId'])) {
    $expiredProductId = $_GET['expiredProductId'];

    $stmt = $con->prepare("DELETE FROM product_list WHERE id = ?");
    $stmt->bind_param("i", $expiredProductId);
    $stmt->execute();
    $stmt->close();

    header("Location: expiredProduct.php");
    exit();
}

$sql = "SELECT `id`, `product_name`, `expired_date` FROM `product_list` WHERE expired_date <= CURDATE()";
$result = $con->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <title>Expired Products - POS</title>
    <link rel="stylesheet" href="/Backend/src/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="/Backend/src/assets/css/style.css">
</head>
<body>
<div class="main-wrapper">

<div class="d-flx row">
            <div class="col-md-3">
                <?php include BASE_PATH . "/src/Layouts/Sidebar.php"; ?>
            </div>
            <div class="col-md-9">
                <?php include BASE_PATH . "/src/Layouts/Header.php"; ?>
            </div>

        </div>

    <div class="page-wrapper">
        <div class="content">
        <div class="page-header">
                    <div class="page-title">
                        <h4>Expired Products</h4>
                        <h6>Manage your expired products</h6>
                    </div>
                </div>
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Product Name</th>
                                    <th>Expired Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<tr>";
                                        echo "<td>".$row['product_name']."</td>";
                                        echo "<td>".$row['expired_date']."</td>";
                                        echo "<td>
                                                <a class='confirm-text' href='expiredProduct.php?expiredProductId=".$row['id']."'>
                                                    <img src='/Backend/src/assets/images/icons/delete.svg' alt='img'>
                                                </a>
                                              </td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='3'>No products expired today.</td></tr>";
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

<script src="/Backend/src/assets/js/jquery-3.6.0.min.js"></script>
<script src="/Backend/src/assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>