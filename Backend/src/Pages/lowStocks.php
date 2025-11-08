
<?php include('/xampp/htdocs/Inventory_management/Backend/src/Layouts/Links.php');
include('/xampp/htdocs/Inventory_management/Backend/src/controllers/dbConnection.php');

$sql = "SELECT `id`,`warehouse`, `store`, `product_name`, `category`, `quantity`, `quantity_alert` FROM `low_stocks`";
$result = $con->query($sql);

if(isset($_GET['lowStockId'])) {
    $lowStockId = $_GET['lowStockId'];
    $deleteSql = "DELETE FROM `low_stocks` WHERE `id` = $lowStockId";
    $con->query($deleteSql);
}
?>;

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="description" content="POS - Bootstrap Admin Template">
    <meta name="keywords" content="admin, estimates, bootstrap, business, corporate, creative, management, minimal, modern,  html5, responsive">
    <meta name="author" content="Dreamguys - Bootstrap Admin Template">
    <meta name="robots" content="noindex, nofollow">
    <title>Dreams Pos admin template</title>
    
</head>

<body>
    <!-- <div id="global-loader">
        <div class="whirly-loader"> </div>
    </div> -->
    <div class="main-wrapper">
        
        <div class="d-flx row">
           <div class="col-md-3">
              <?php include('/xampp/htdocs/Inventory_management/Backend/src/Layouts/Sidebar.php') ?>
           </div>
           <div class="col-md-9">
                <?php include('/xampp/htdocs/Inventory_management/Backend/src/Layouts/Header.php') ?>
           </div>
        </div>
        <div class="page-wrapper">
            <div class="content">
                <div class="page-header">
                    <div class="page-title">
                        <h4>Low Stocks</h4>
                        <h6>Manage your low stocks</h6>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="table-top">
                            <div class="search-set">
                                <div class="search-path">
                                    <a class="btn btn-filter" id="filter_search">
                                        <img src="/Backend/src/assets/images/icons/filter.svg" alt="img">
                                        <span><img src="/Backend/src/assets/images/icons/closes.svg" alt="img"></span>
                                    </a>
                                </div>
                                <div class="search-input">
                                    <a class="btn btn-searchset"><img src="/Backend/src/assets/images/icons/search-white.svg" alt="img"></a>
                                </div>
                            </div>
                            <div class="wordset">
                                <ul>
                                    <li>
                                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="pdf"><img src="/Backend/src/assets/images/icons/pdf.svg" alt="img"></a>
                                    </li>
                                    <li>
                                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="excel"><img src="/Backend/src/assets/images/icons/excel.svg" alt="img"></a>
                                    </li>
                                    <li>
                                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="print"><img src="/Backend/src/assets/images/icons/printer.svg" alt="img"></a>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="card mb-0" id="filter_inputs">
                            <div class="card-body pb-0">
                                <div class="row">
                                    <div class="col-lg-12 col-sm-12">
                                        <div class="row">
                                            <div class="col-lg col-sm-6 col-12">
                                                <div class="form-group">
                                                    <select class="select">
                                                        <option>Choose Product</option>
                                                        <option>Macbook pro</option>
                                                        <option>Orange</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg col-sm-6 col-12">
                                                <div class="form-group">
                                                    <select class="select">
                                                        <option>Choose Category</option>
                                                        <option>Computers</option>
                                                        <option>Fruits</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg col-sm-6 col-12">
                                                <div class="form-group">
                                                    <select class="select">
                                                        <option>Choose Sub Category</option>
                                                        <option>Computer</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg col-sm-6 col-12">
                                                <div class="form-group">
                                                    <select class="select">
                                                        <option>Brand</option>
                                                        <option>N/D</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg col-sm-6 col-12 ">
                                                <div class="form-group">
                                                    <select class="select">
                                                        <option>Price</option>
                                                        <option>150.00</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-1 col-sm-6 col-12">
                                                <div class="form-group">
                                                    <a class="btn btn-filters ms-auto"><img src="/Backend/src/assets/images/icons/search-white.svg" alt="img"></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table  datanew">
                                <thead>
                                    <tr>
                                        <th>
                                            <label class="checkboxs">
                                                <input type="checkbox" id="select-all">
                                                <span class="checkmarks"></span>
                                            </label>
                                        </th>
                                        <th>Warehouse</th>
                                        <th>Store</th>
                                        <th>Product Name</th>
                                        <th>Category</th>
                                        <th>Qty</th>
                                        <th>Qty Alert</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<tr>";
                                            echo "    <td>";
                                            echo "        <label class='checkboxs'>";
                                            echo "            <input type='checkbox'>";
                                            echo "            <span class='checkmarks'></span>";
                                            echo "        </label>";
                                            echo "    </td>";
                                            echo "    <td>".$row['warehouse']."</td>";
                                            echo "    <td>".$row['store']."</td>";
                                            echo "    <td class='productimgname'>";
                                            echo "        <a href='javascript:void(0)' class='product-img'>";
                                            echo "            <img src='/Backend/src/assets/images/product/product1.jpg' alt='product'>";
                                            echo "        </a>";
                                            echo "        <a href='javascript:void(0);'>".$row['product_name']."</a>";
                                            echo "    </td>";
                                            echo "    <td>".$row['category']."</td>";
                                            echo "    <td>".$row['quantity']."</td>";
                                            echo "    <td>".$row['quantity_alert']."</td>";
                                            echo "    <td>";
                                            echo "        <a class='me-3' href='product-details.html'>";
                                            echo "            <img src='/Backend/src/assets/images/icons/eye.svg' alt='img'>";
                                            echo "        </a>";
                                            echo "        <a class='me-3' href='editproduct.html'>";
                                            echo "            <img src='/Backend/src/assets/images/icons/edit.svg' alt='img'>";
                                            echo "        </a>";
                                            echo "        <a class='confirm-text' href='/Backend/src/Pages/lowStocks.php?lowStockId=".$row['id']."'>";
                                            echo "            <img src='/Backend/src/assets/images/icons/delete.svg' alt='img'>";
                                            echo "        </a>";
                                            echo "    </td>";
                                            echo "</tr>";
                                        }
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