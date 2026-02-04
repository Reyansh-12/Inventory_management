<?php
$lastOrders = mysqli_query($con, "
    SELECT o.*, c.name as customer_name, p.product_name 
    FROM order_list o
    LEFT JOIN customers c ON o.customer = c.id
    LEFT JOIN product_list p ON o.product = p.id
    ORDER BY o.created DESC LIMIT 5
");

// $lastOrders = mysqli_query($con, "
//     SELECT o.*, c.name as customer_name, p.product_name 
//     FROM order_list o
//     LEFT JOIN customers c ON o.customer = c.id
//     LEFT JOIN product_list p ON o.product = p.id
//     ORDER BY o.created DESC LIMIT 5
// ");

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="/Backend/src/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="/Backend/src/assets/css/animate.css">
    <link rel="stylesheet" href="/Backend/src/assets/css/bootstrap-datetimepicker.min.css">
    <link rel="stylesheet" href="/Backend/src/assets/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="assets/plugins/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="/Backend/src/assets/css/style.css">
</head>

<body>
    <div class="header">
        <div class="header-left active">
            <a href="/Backend/src/Pages/Dashboard.php" class="logo">
                <img src="/Backend/src/assets/images/brand/logo.webp" class="ms-5" alt=""
                    style="height: 35px; width: 60px;">
            </a>
            <a href="index.html" class="logo-small">
                <img src="/Backend/src/assets/images/logo-small.png" alt="">
            </a>
            <a id="toggle_btn" href="javascript:void(0);">
            </a>
        </div>
        <a id="mobile_btn" class="mobile_btn" href="#sidebar">
            <span class="bar-icon">
                <span></span>
                <span></span>
                <span></span>
            </span>
        </a>

        <ul class="nav user-menu">

            <li class="nav-item">
                <div class="top-nav-search">
                    <a href="javascript:void(0);" class="responsive-search">
                        <i class="fa fa-search"></i>
                    </a>
                    <form action="#">
                        <div class="searchinputs">
                            <input type="text" placeholder="Search Here ...">
                            <div class="search-addon">
                                <span><img src="/Backend/src/assets/images/icons/closes.svg" alt="img"></span>
                            </div>
                        </div>
                        <a class="btn" id="searchdiv"><img src="/Backend/src/assets/images/icons/search.svg"
                                alt="img"></a>
                    </form>
                </div>
            </li>

            <li class="nav-item dropdown">
                <a href="javascript:void(0);" class="dropdown-toggle nav-link header-icon" data-bs-toggle="dropdown"
                    id="header-icon">
                    <img src="/Backend/src/assets/images/icons/notification-bing.svg" alt="img"> <span
                        class="badge rounded-pill"></span>
                </a>
                <div class="dropdown-menu notifications">
                    <div class="topnav-dropdown-header">
                        <span class="notification-title">Notifications</span>
                        <a href="javascript:void(0)" class="clear-noti"> Clear All </a>
                    </div>
                    <div class="noti-content">
                        <ul class="notification-list">
                            <?php

                            while ($row = mysqli_fetch_assoc($lastOrders)) {

                                echo "<li class='notification-message'>";
                                echo "    <a href='/Backend/src/Pages/notification/notification.php'>";
                                echo "        <div class='media d-flex'>";
                                echo "            <span class='avatar flex-shrink-0'>";
                                echo "                <img alt='' src='/Backend/src/assets/images/profiles/avatar-01.jpg'>";
                                echo "            </span>";
                                echo "            <div class='media-body flex-grow-1'>";
                                echo "                <p class='noti-details'>";
                                echo "                    <span class='noti-title'>" . $row['customer_name'] . "</span>";
                                echo "                    purchased <span class='noti-title'>" . $row['product_name'] . " </span>";
                                echo "                    (Qty: " . $row['quantity'] . ")";
                                echo "                </p>";
                                echo "                <p class='noti-time'>";
                                echo "                    <span class='notification-time'>" . $row['created'] . "</span>";
                                echo "                </p>";
                                echo "            </div>";
                                echo "        </div>";
                                echo "    </a>";
                                echo "</li>";
                            }
                            ?>
                        </ul>
                    </div>
                    <div class="topnav-dropdown-footer">
                        <a href="/Backend/src/Pages/notification/notification.php">View all Notifications</a>
                    </div>
                </div>
            </li>

            <li class="nav-item dropdown has-arrow main-drop">
                <a href="javascript:void(0);" class="dropdown-toggle nav-link userset" data-bs-toggle="dropdown">
                    <span class="user-img"><img src="/Backend/src/assets/images/profiles/avatar1.jpg" alt="">
                        <span class="status online"></span></span>
                </a>
                <div class="dropdown-menu menu-drop-user">
                    <div class="profilename">
                        <div class="profileset">
                            <span class="user-img"><img src="" alt="">
                                <span class="status online"></span></span>
                            <div class="profilesets">
                                <h6><?php ?></h6>
                                <h5>Admin</h5>
                            </div>
                        </div>
                        <hr class="m-0">
                        <a class="dropdown-item" href="/Backend/src/Pages/userProfile.php"> <i class="me-2"
                                data-feather="user"></i> My Profile</a>
                        <!-- <a class="dropdown-item" href="generalsettings.html"><i class="me-2"
                                data-feather="settings"></i>Settings</a> -->
                        <hr class="m-0">
                        <a class="dropdown-item logout pb-0" href="/Backend/src/Pages/Auth/signin.php"><img
                                src="/Backend/src/assets/images/icons/log-out.svg" class="me-2" alt="img">Logout</a>
                    </div>
                </div>
            </li>
        </ul>
        <div class="dropdown mobile-user-menu">
            <a href="javascript:void(0);" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"
                aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
            <div class="dropdown-menu dropdown-menu-right">
                <a class="dropdown-item" href="profile.html">My Profile</a>
                <!-- <a class="dropdown-item" href="generalsettings.html">Settings</a> -->
                <a class="dropdown-item" href="/Backend/src/Pages/Auth/logout.php">Logout</a>
            </div>
        </div>
    </div>
    <script src="/Backend/src/assets/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const dropdownEl = document.getElementById('header-icon');

            if (dropdownEl) {
                dropdownEl.addEventListener('show.bs.dropdown', function () {
                    console.log('Notification dropdown opened');
                });
            }
        });
    </script>
</body>
</html>