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
        <style>
            /* --- Professional Sidebar Overhaul --- */
#sidebar {
    background: #1a1d21; /* Deep charcoal/navy background */
    box-shadow: 4px 0 10px rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
}

.sidebar-menu {
    padding: 15px 0;
}

.sidebar-menu ul li {
    margin: 4px 12px;
}

.sidebar-menu ul li a {
    color: #a0aec0 !important; /* Muted grey for inactive items */
    display: flex;
    align-items: center;
    padding: 12px 15px;
    border-radius: 8px;
    font-size: 0.9rem;
    font-weight: 500;
    transition: all 0.2s ease;
    text-decoration: none !important;
}

/* Hover Effect */
.sidebar-menu ul li a:hover {
    background: rgba(103, 146, 255, 0.08);
    color: #6792ff !important; /* Primary Brand Blue */
}

.sidebar-menu ul li a i {
    font-size: 1.1rem;
    margin-right: 12px;
    transition: transform 0.2s ease;
}

.sidebar-menu ul li a:hover i {
    transform: scale(1.1);
}

/* Active State Styling */
.sidebar-menu ul li.active a {
    background: #6792ff !important;
    color: #ffffff !important;
    box-shadow: 0 4px 12px rgba(103, 146, 255, 0.3);
}

.sidebar-menu ul li.active i {
    color: #ffffff !important;
}

/* Slimscroll Polish */
.slimscroll {
    scrollbar-width: thin;
    scrollbar-color: #2d3748 transparent;
}
        </style>
</head>

<body>
    <?php $currentPage = basename($_SERVER['PHP_SELF']);?>

    <div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <li class="menu-title" style="color: #4a5568; font-size: 0.7rem; text-transform: uppercase; letter-spacing: 1px; padding: 10px 25px;">Main Menu</li>

                <li class="<?php echo ($currentPage == 'Dashboard.php') ? 'active' : ''; ?>">
                    <a href="/Backend/src/Pages/Dashboard.php"><i class="bi bi-grid-1x2"></i><span> Dashboard</span></a>
                </li>

                <li class="<?php echo in_array($currentPage, ['ProductList.php', 'addProductForm.php']) ? 'active' : ''; ?>">
                    <a href="/Backend/src/Pages/products/ProductList.php"><i class="bi bi-box-seam"></i><span>Products</span></a>
                </li>

                <li class="<?php echo in_array($currentPage, ['category.php', 'addcategory.php']) ? 'active' : ''; ?>">
                    <a href="/Backend/src/Pages/category.php"><i class="bi bi-tags"></i><span>Category</span></a>
                </li>

                <li class="<?php echo ($currentPage == 'expiredProduct.php') ? 'active' : ''; ?>">
                    <a href="/Backend/src/Pages/expiredProduct.php"><i class="bi bi-calendar-x"></i><span>Expired</span></a>
                </li>

                <li class="<?php echo ($currentPage == 'lowStocks.php') ? 'active' : ''; ?>">
                    <a href="/Backend/src/Pages/lowStocks.php"><i class="bi bi-exclamation-triangle"></i><span>Low Stock</span></a>
                </li>

                <li class="<?php echo ($currentPage == 'pos.php') ? 'active' : ''; ?>">
                    <a href="/Backend/src/Pages/POS/pos.php"><i class="bi bi-printer"></i><span>POS Billing</span></a>
                </li>

                <li class="<?php echo in_array($currentPage, ['supplierlist.php', 'supplierForm.php']) ? 'active' : ''; ?>">
                    <a href="/Backend/src/Pages/supplierlist.php"><i class="bi bi-truck"></i><span>Suppliers</span></a>
                </li>

                <li class="<?php echo in_array($currentPage, ['UsersList.php', 'NewUser.php']) ? 'active' : ''; ?>">
                    <a href="/Backend/src/Pages/Users/UsersList.php"><i class="bi bi-people"></i><span>Users</span></a>
                </li>

                <li class="<?php echo ($currentPage == 'notification.php') ? 'active' : ''; ?>">
                    <a href="/Backend/src/Pages/notification/notification.php"><i class="bi bi-clock-history"></i><span>Order History</span></a>
                </li>

                <li class="<?php echo ($currentPage == 'userProfile.php') ? 'active' : ''; ?>">
                    <a href="/Backend/src/Pages/userProfile.php"><i class="bi bi-person-circle"></i><span>Profile</span></a>
                </li>
            </ul>
        </div>
    </div>
</div>
</body>

</html>