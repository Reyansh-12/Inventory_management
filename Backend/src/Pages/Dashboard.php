<?php
require_once __DIR__ . "/../config/path.php";
include(BASE_PATH . "/Backend/src/Layouts/Links.php");
include(BASE_PATH . '/Backend/src/controllers/dbConnection.php');
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'Admin') {
    header("Location: /Backend/src/Pages/Auth/signin.php");
    exit();
}

// header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
// header("Pragma: no-cache");

// if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'Admin') {
//     header("Location: /Backend/src/Pages/Auth/signin.php");
//     exit();
// }

function getCount($table) {
    global $con;

    $allowedTables = [
        'new_user',
        'supplier',
        'product_list',
    ];

    if (!in_array($table, $allowedTables)) {
        return 0;
    }
    $query = "SELECT COUNT(*) AS total FROM `$table`";
    $result = mysqli_query($con, $query);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        return $row['total'];
    }
    return 0;
}

$expiredProducts = "SELECT `id`, `product_name`, `category`, `brand_name`, `expired_date` FROM `product_list` WHERE expired_date <= CURDATE()";
$result = $con->query($expiredProducts);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="description" content="POS - Bootstrap Admin Template">
    <meta name="keywords" content="admin, estimates, bootstrap, business, corporate, creative, management, minimal, modern,  html5, responsive">
    <meta name="author" content="Dreamguys - Bootstrap Admin Template">
    <meta name="robots" content="noindex, nofollow">
    <title>BRANCY – Cosmetic Store</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.5/css/dataTables.dataTables.css" />
    
</head>
<body>
    <div id="global-loader">
        <div class="whirly-loader"> </div>
    </div>
    <div class="main-wrapper">
        <div class="d-flx row">
            <div class="col-md-3">
                <?php include(BASE_PATH . "/Backend/src/Layouts/Sidebar.php"); ?>
            </div>
            <div class="col-md-9">
                <?php include BASE_PATH . "/Backend/src/Layouts/Header.php"; ?>
            </div>
        </div>
    </div>
    <div class="page-wrapper">
            <div class="content">
                <div class="row">
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="dash-widget">
                            <div class="dash-widgetimg">
                                <span><img src="/Backend/src/assets/images/icons/dash1.svg" alt="img"></span>
                            </div>
                            <div class="dash-widgetcontent">
                                <h5>$<span class="counters" data-count="307144.00">$307,144.00</span></h5>
                                <h6>Total Purchase Due</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="dash-widget dash1">
                            <div class="dash-widgetimg">
                                <span><img src="/Backend/src/assets/images/icons/dash2.svg" alt="img"></span>
                            </div>
                            <div class="dash-widgetcontent">
                                <h5>$<span class="counters" data-count="4385.00">$4,385.00</span></h5>
                                <h6>Total Sales Due</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="dash-widget dash2">
                            <div class="dash-widgetimg">
                                <span><img src="/Backend/src/assets/images/icons/dash3.svg" alt="img"></span>
                            </div>
                            <div class="dash-widgetcontent">
                                <h5>$<span class="counters" data-count="385656.50">385,656.50</span></h5>
                                <h6>Total Sale Amount</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="dash-widget dash3">
                            <div class="dash-widgetimg">
                                <span><img src="/Backend/src/assets/images/icons/dash4.svg" alt="img"></span>
                            </div>
                            <div class="dash-widgetcontent">
                                <h5>$<span class="counters" data-count="40000.00">400.00</span></h5>
                                <h6>Total Sale Amount</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12 d-flex">
                        <div class="dash-count">
                            <div class="dash-counts">
                                <h4><?= getCount('new_user') ?></h4>
                                <h5>Total Customers</h5>
                            </div>
                            <div class="dash-imgs">
                                <i data-feather="user"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12 d-flex">
                        <div class="dash-count das1">
                            <div class="dash-counts">
                                <h4><?= getCount('product_list') ?></h4>
                                <h5>Total Products</h5>
                            </div>
                            <div class="dash-imgs">
                                <i data-feather="user-check"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12 d-flex">
                        <div class="dash-count das2">
                            <div class="dash-counts">
                                <h4><?= getCount('supplier') ?></h4>
                                <h5>Total supplier</h5>
                            </div>
                            <div class="dash-imgs">
                                <i data-feather="file-text"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12 d-flex">
                        <div class="dash-count das3">
                            <div class="dash-counts">
                                <h4>105</h4>
                                <h5>Sales Orders</h5>
                            </div>
                            <div class="dash-imgs">
                                <i data-feather="file"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>
</body>
</html>