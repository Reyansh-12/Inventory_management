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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.13.1/font/bootstrap-icons.min.css" integrity="sha512-t7Few9xlddEmgd3oKZQahkNI4dS6l80+eGEzFQiqtyVYdvcSG2D3Iub77R20BdotfRPA9caaRkg1tyaJiPmO0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
    <?php $currentPage = basename($_SERVER['PHP_SELF']);?>

    <div class="sidebar" id="sidebar">
        <div class="sidebar-inner slimscroll">
            <div id="sidebar-menu" class="sidebar-menu">
                <ul>
                    <li class="<?php echo ($currentPage == 'Dashboard.php') ? 'active' : ''; ?>">
                        <a href="/Backend/src/Pages/Dashboard.php"><img src="/Backend/src/assets/images/icons/dashboard.svg" alt="img"><span> Dashboard</span> </a>
                    </li>
                    <li class="<?php echo ($currentPage == 'ProductList.php') ? 'active' : ''; ?>">
                        <a href="/Backend/src/Pages/products/ProductList.php"><img src="/Backend/src/assets/images/icons/product.svg" alt="img"><span> Products</span> </a>
                    </li>
                    <li class="<?php echo ($currentPage == 'addProductForm.php') ? 'active' : ''; ?>">
                        <a href="/Backend/src/Pages/products/addProductForm.php"><img src="/Backend/src/assets/images/icons/sales1.svg" alt="img"><span>Create Product</span> </a>
                    </li>
                    <li class="<?php echo ($currentPage == 'expiredProduct.php') ? 'active' : ''; ?>">
                        <a href="/Backend/src/Pages/expiredProduct.php"><i class="bi bi-clock-history"></i><span> Expired Products</span> </a>
                    </li>
                    <li class="<?php echo ($currentPage == 'lowStocks.php') ? 'active' : ''; ?>">
                        <a href="/Backend/src/Pages/lowStocks.php"><i class="bi bi-graph-down-arrow"></i><span> Low Stocks</span> </a>
                    </li>
                    <li class="<?php echo ($currentPage == 'category.php') ? 'active' : ''; ?>">
                        <a href="/Backend/src/Pages/category.php"><img src="/Backend/src/assets/images/icons/quotation1.svg" alt="img"><span> Category</span> </a>
                    </li>
                    <li class="<?php echo ($currentPage == 'pos.php') ? 'active' : ''; ?>">
                        <a href="/Backend/src/Pages/POS/pos.php"><img src="/Backend/src/assets/images/icons/return1.svg" alt="img"><span>POS</span> </a>
                    </li>
                    <li class="<?php echo ($currentPage == 'supplierlist.php' || $currentDir == "Supplier") ? 'active' : ''; ?>">
                        <a href="/Backend/src/Pages/supplierlist.php"><span>Suppliers</span> </a>
                    </li>
                    <li class="<?php echo ($currentPage == 'userProfile.php') ? 'active' : ''; ?>">
                        <a href="/Backend/src/Pages/userProfile.php"><i data-feather="file"></i><span>Profile</span> </a>
                    </li>
                    <li class="<?php echo ($currentPage == 'UsersList.php' || $currentDir == 'Users') ? 'active' : ''; ?>">
                        <a href="/Backend/src/Pages/Users/UsersList.php">
                            <img src="/Backend/src/assets/images/icons/users1.svg" alt="img">
                            <span> Users</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</body>
</html>