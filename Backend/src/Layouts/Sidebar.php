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
    <link rel="stylesheet" href="/Backend/src/assets/plugins/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="/Backend/src/assets/css/style.css">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.13.1/font/bootstrap-icons.min.css"
        integrity="sha512-t7Few9xlddEmgd3oKZQahkNI4dS6l80+eGEzFQiqtyVYdvcSG2D3Iub77R20BdotfRPA9caaRkg1tyaJiPmO0g=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
    <?php $currentPage = basename($_SERVER['PHP_SELF']);?>

    <div class="sidebar" id="sidebar">
        <div class="sidebar-inner slimscroll">
            <div id="sidebar-menu" class="sidebar-menu">
                <ul>
                    <li class="<?php echo ($currentPage == 'Dashboard.php') ? 'active' : ''; ?>">
                        <a href="/Backend/src/Pages/Dashboard.php"><i class="bi bi-speedometer2"></i><span>
                                Dashboard</span> </a>
                    </li>
                    <li
                        class="<?php echo in_array($currentPage, ['ProductList.php', 'addProductForm.php']) ? 'active' : ''; ?>">
                        <a href="/Backend/src/Pages/products/ProductList.php"><i
                                class="bi bi-box-seam"></i><span>Cosmetic Products</span> </a>
                    </li>
                    <li
                        class="<?php echo in_array($currentPage, ['category.php', 'addcategory.php']) ? 'active' : ''; ?>">
                        <a href="/Backend/src/Pages/category.php"><i class="bi bi-tags"></i><span> Category</span> </a>
                    </li>
                    <li class="<?php echo ($currentPage == 'expiredProduct.php') ? 'active' : ''; ?>">
                        <a href="/Backend/src/Pages/expiredProduct.php"><i class="bi bi-calendar-x"></i><span> Expired
                                Products</span> </a>
                    </li>
                    <li class="<?php echo ($currentPage == 'lowStocks.php') ? 'active' : ''; ?>">
                        <a href="/Backend/src/Pages/lowStocks.php"><i class="bi bi-graph-down-arrow"></i><span>Low Stock
                                Alerts</span> </a>
                    </li>
                    <li class="<?php echo ($currentPage == 'pos.php') ? 'active' : ''; ?>">
                        <a href="/Backend/src/Pages/POS/pos.php"><i class="bi bi-receipt"></i><span>POS Billing</span>
                        </a>
                    </li>
                    <li
                        class="<?php echo in_array($currentPage, ['supplierlist.php', 'supplierForm.php']) ? 'active' : ''; ?>">
                        <a href="/Backend/src/Pages/supplierlist.php"><i class="bi bi-truck"></i><span>Suppliers</span>
                        </a>
                    </li>
                    <li class="<?php echo in_array($currentPage, ['UsersList.php', 'NewUser.php']) ? 'active' : ''; ?>">
                        <a href="/Backend/src/Pages/Users/UsersList.php"><i class="bi bi-people"></i><span>
                                Users</span></a>
                    </li>
                    <li class="<?php echo ($currentPage == 'notification.php') ? 'active' : ''; ?>">
                        <a href="/Backend/src/Pages/notification/notification.php"><i
                                class="bi bi-clipboard-data"></i><span>Order History</span> </a>
                    </li>
                    <li class="<?php echo ($currentPage == 'userProfile.php') ? 'active' : ''; ?>">
                        <a href="/Backend/src/Pages/userProfile.php"><i
                                class="bi bi-person-circle"></i><span>Profile</span> </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</body>

</html>