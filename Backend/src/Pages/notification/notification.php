<?php
define("BASE_PATH", dirname(__DIR__, 3));
include BASE_PATH . "/src/Layouts/Links.php";
include BASE_PATH . "/src/controllers/dbConnection.php";
$order_list = "SELECT `id`, `order_id`, `customer`, `product`, `category`, `brand`, `quantity`, `status`, `total_amount`, `price`, `created` FROM `order_list` ORDER BY `id` DESC";
$resut = mysqli_query($con, $order_list);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="description" content="POS - Bootstrap Admin Template">
    <meta name="keywords"
        content="admin, estimates, bootstrap, business, corporate, creative, management, minimal, modern,  html5, responsive">
    <meta name="author" content="Dreamguys - Bootstrap Admin Template">
    <meta name="robots" content="noindex, nofollow">
    <title>User List</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="/Backend/src/assets/css/history.css">
</head>

<body>
    <div id="global-loader">
        <div class="whirly-loader"> </div>
    </div>
    <div class="main-wrapper">
        <div class="d-flx row">
            <div class="col-md-3">
                <?php include BASE_PATH . "/src/Layouts/Sidebar.php"; ?>
            </div>
            <div class="col-md-9">
                <?php include BASE_PATH . "/src/Layouts/Header.php"; ?>
            </div>
        </div>
    </div>
    <div class="page-wrapper">
        <div class="content">
            <div class="page-header">
                <div class="page-title">
                    <h4>All Notifications</h4>
                    <h6>View your all activities</h6>
                </div>
            </div>

            <div class="activity">
                <div class="activity-box">
                    <ul class="activity-list">
                        <?php 
                        while ($row = mysqli_fetch_assoc($resut)) {
                            
                        echo "<div class='order-card mb-3'>";
                        echo "    <div class='order-header'>";
                        echo "        <div class='order-left'>";
                        echo "            <input type='checkbox' />";
                        echo "            <span class='order-id'>Order #".$row['order_id']."</span>";
                        echo "            <span class='badge paid'>Paid</span>";
                        echo "        </div>";
                        echo "        <div class='order-total'>₹ ".$row['total_amount']."</div>";
                        echo "    </div>";

                        echo "    <div class='order-meta'>";
                        echo "        <span class='meta-item'>";
                        echo "           Name: <a href='#'>".$row['customer']."</a>";
                        echo "       </span>";
                        echo "       <span class='meta-item'>";
                        echo "           | Transaction ID: <a href='#'>#ID20260202061023742</a>";
                        echo "       </span>";
                        echo "       <span class='meta-item'>";
                        echo "           | ".$row['created']."";
                        echo "       </span>";
                        echo "   </div>";

                        echo "    <div class='product'>";
                        echo "        <img src='/Backend/src/uploads/products/featured/product_6979a7b1479cf2.85124957.webp' alt='Product' class='product-img' />";
                        echo "        <div class='product-info'>";
                        echo "            <div class='product-name'>";
                        echo $row['product'];
                        echo "            </div>";
                        echo "            <div class='product-meta'>";
                        echo "                <span>Category: <a href='#'>".$row['category']."</a></span>";
                        echo "                <span>Brand: <a href='#'>".$row['brand']."</a></span>";
                        echo "                <span>Quantity: ".$row['quantity']."</span>";
                        echo "            </div>";
                        // echo "            <div class='product-meta'>";
                        // echo "                <span class='meta-item'>";
                        // echo "                    Payment mode: <a href='#'>Cash</a>";
                        // echo "                </span>";
                        // echo "            </div>";
                        echo "        </div>";
                        echo "    </div>";

                        echo "</div>";
                    }
                        ?>

                    </ul>
                </div>
            </div>
        </div>
    </div>
</body>

</html>