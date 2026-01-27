<?php
define("BASE_PATH", dirname(__DIR__, 3));
include BASE_PATH . "/src/Layouts/Links.php";
include BASE_PATH . "/src/controllers/dbConnection.php";

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
    <title>User List</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
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
                            <li>
                                <div class="activity-user">
                                    <a href="profile.html" title="" data-toggle="tooltip"
                                        data-original-title="Lesley Grauer">
                                        <img alt="Lesley Grauer" src="/Backend/src/assets/images/img-01.jpg"
                                            class=" img-fluid">
                                    </a>
                                </div>
                                <div class="activity-content">
                                    <div class="timeline-content">
                                        <a href="profile.html" class="name">Elwis Mathew </a> added a new product <a
                                            href="javascript:void(0);">Redmi Pro 7 Mobile</a>
                                        <span class="time">4 mins ago</span>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="activity-user">
                                    <a href="profile.html" title="" data-toggle="tooltip"
                                        data-original-title="Lesley Grauer">
                                        <img alt="Lesley Grauer" src="/Backend/src/assets/images/img-01.jpg"
                                            class=" img-fluid">
                                    </a>
                                </div>
                                <div class="activity-content">
                                    <div class="timeline-content">
                                        <a href="profile.html" class="name">Elizabeth Olsen</a> added a new product
                                        category <a href="javascript:void(0);">Desktop Computers</a>
                                        <span class="time">6 mins ago</span>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="activity-user">
                                    <a href="profile.html" title="" data-toggle="tooltip"
                                        data-original-title="Lesley Grauer">
                                        <img alt="Lesley Grauer" src="/Backend/src/assets/images/img-01.jpg"
                                            class=" img-fluid">
                                    </a>
                                </div>
                                <div class="activity-content">
                                    <div class="timeline-content">
                                        <div class="timeline-content">
                                            <a href="profile.html" class="name">William Smith</a> added a new sales list
                                            for<a href="javascript:void(0);">January Month</a>
                                            <span class="time">12 mins ago</span>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="activity-user">
                                    <a href="" title="" data-toggle="tooltip"
                                        data-original-title="Lesley Grauer">
                                        <img alt="Lesley Grauer" src="/Backend/src/assets/images/img-01.jpg"
                                            class=" img-fluid">
                                    </a>
                                </div>
                                <div class="activity-content">
                                    <div class="timeline-content">
                                        <a href="profile.html" class="name">Lesley Grauer</a> has updated invoice <a
                                            href="javascript:void(0);">#987654</a>
                                        <span class="time">4 mins ago</span>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
    </div>
</body>
</html>