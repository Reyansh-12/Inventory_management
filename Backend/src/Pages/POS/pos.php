<?php
define("BASE_PATH", dirname(__DIR__, 3));
include BASE_PATH . "/src/Layouts/Links.php";
include BASE_PATH . "/src/controllers/dbConnection.php";

$sql = "SELECT `id`,`product_name`, `category`, `brand_name`, `price`, `quantity`, `image_path` FROM `product_list`";
$result = $con->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="POS - Bootstrap Admin Template">
    <meta name="keywords" content="admin, estimates, bootstrap, business, corporate, creative, management, minimal, modern, html5, responsive">
    <meta name="author" content="Dreamguys - Bootstrap Admin Template">
    <meta name="robots" content="noindex, nofollow">
    <title>Dreams POS Admin Template</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .category-card {
            width: 10rem;
        }
        .slider-container {
            position: relative;
        }
        .slider-btn {
            background: red !important;
            border-radius: 50%;
            width: 10px;
            height: 15px;
            color: black !important;
        }
        .pos-column {
        max-height: calc(100vh - 0px); 
        overflow-y: auto;
        }
        .product-card {
            background: #fff;
            border-radius: 10px;
            transition: 0.3s;
        }
        .product-card:hover {
            transform: translateY(-5px);
        }
        .product-image img {
            max-height: 120px;
            object-fit: contain;
        }
    </style>
</head>
<body>
    <div id="global-loader">
        <div class="whirly-loader"> </div>
    </div>
    <div class="d-flex row">
        <div class="col-md-3">
            <?php include BASE_PATH . "/src/Layouts/Sidebar.php"; ?>
        </div>
        <div class="col-md-9">
            <?php include BASE_PATH . "/src/Layouts/Header.php"; ?>
        </div>
    </div>
    <div class="page-wrapper">
        <div class="content">
            <div class="row">
                <div class="col-lg-12">
                    <div class="page-header">
                        <div class="page-title">
                            <h4>Categories</h4>
                            <h6>Manage your purchases</h6>
                        </div>
                        <div class="page-btn">
                            <a href="/Backend/src/Pages/Users/NewUser.php" class="btn btn-added"><img src="/Backend/src/assets/images/icons/plus.svg" alt="img">Add User</a>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-8 pos-column">
                                <div id="categorySlider" class="carousel slide slider-container" data-bs-ride="false">
                                    <button class="carousel-control-prev slider-btn" type="button" data-bs-target="#categorySlider" data-bs-slide="prev" id="prevBtn">
                                        <span class="carousel-control-prev-icon"></span>
                                    </button>
                                    <button class="carousel-control-next slider-btn" type="button" data-bs-target="#categorySlider" data-bs-slide="next" id="nextBtn">
                                        <span class="carousel-control-next-icon"></span>
                                    </button>
                                    <div class="carousel-inner">
                                        <div class="carousel-item active">
                                            <div class="card m-2 category-card">
                                                <img src="/Backend/assets/images/product/product62.png" class="card-img-top" alt="Hair care">
                                                <div class="card-body text-center"><span>Hair care</span></div>
                                            </div>
                                        </div>
                                        <div class="carousel-item">
                                            <div class="card m-2 category-card">
                                                <img src="/Backend/assets/images/product/product63.png" class="card-img-top" alt="Face skin">
                                                <div class="card-body text-center"><span>Face skin</span></div>
                                            </div>
                                        </div>
                                        <div class="carousel-item">
                                            <div class="card m-2 category-card">
                                                <img src="/Backend/assets/images/product/product64.png" class="card-img-top" alt="Skin Care">
                                                <div class="card-body text-center"><span>Skin Care</span></div>
                                            </div>
                                        </div>
                                        <div class="carousel-item">
                                            <div class="card m-2 category-card">
                                                <img src="/Backend/assets/images/product/product65.png" class="card-img-top" alt="Blusher">
                                                <div class="card-body text-center"><span>Blusher</span></div>
                                            </div>
                                        </div>
                                        <div class="carousel-item">
                                            <div class="card m-2 category-card">
                                                <img src="/Backend/assets/images/product/product66.png" class="card-img-top" alt="Natural">
                                                <div class="card-body text-center"><span>Natural</span></div>
                                            </div>
                                        </div>
                                        <div class="carousel-item">
                                            <div class="card m-2 category-card">
                                                <img src="/Backend/assets/images/product/product67.png" class="card-img-top" alt="Lip Stick">
                                                <div class="card-body text-center"><span>Lip Stick</span></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tabs_container">
                                <div class="tab_content active" data-tab="fruits">
                                    <div class="row">
                                        <?php
                                            if (!$result) {
                                                echo "Error: " . $con->error;
                                            } else {
                                            if ($result->num_rows > 0) {
                                                while ($row = $result->fetch_assoc()) {
                                        ?>
                                        <div class="col-lg-4 col-sm-6 mb-4">
                                            <div class="product-card p-3 shadow-sm rounded">
                                                <div class="product-image text-center">
                                                    <img src="/Backend/assets/images/favicon1.png" class="img-fluid mb-2" alt="product">
                                                </div>
                                                <h5 class="text-muted">Fruits</h5>
                                                <h4><?php echo $row['product_name']; ?></h4>
                                                <h6 class="text-primary"><?php echo $row['price']; ?></h6>
                                            <div class="check-product mt-2">
                                                <i class="fa fa-check"></i>
                                            </div>
                                        </div>
                                    </div>
                                <?php
                            }
                        }
                    }
                ?>
    </div>
</div>


                                    <!-- <div class="tab_content" data-tab="headphone">
                                        <div class="row ">
                                            <div class="col-lg-3 col-sm-6 d-flex ">
                                                <div class="productset flex-fill">
                                                    <div class="productsetimg">
                                                        <img src="/Backend/assets/images/icons/upload.svg" alt="img">
                                                        <h6>Qty: 5.00</h6>
                                                        <div class="check-product">
                                                            <i class="fa fa-check"></i>
                                                        </div>
                                                    </div>
                                                    <div class="productsetcontent">
                                                        <h5>Headphones</h5>
                                                        <h4>Earphones</h4>
                                                        <h6>150.00</h6>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-sm-6 d-flex ">
                                                <div class="productset flex-fill">
                                                    <div class="productsetimg">
                                                        <img src="assets/img/product/product45.jpg" alt="img">
                                                        <h6>Qty: 5.00</h6>
                                                        <div class="check-product">
                                                            <i class="fa fa-check"></i>
                                                        </div>
                                                    </div>
                                                    <div class="productsetcontent">
                                                        <h5>Headphones</h5>
                                                        <h4>Earphones</h4>
                                                        <h6>150.00</h6>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-sm-6 d-flex ">
                                                <div class="productset flex-fill">
                                                    <div class="productsetimg">
                                                        <img src="assets/img/product/product36.jpg" alt="img">
                                                        <h6>Qty: 5.00</h6>
                                                        <div class="check-product">
                                                            <i class="fa fa-check"></i>
                                                        </div>
                                                    </div>
                                                    <div class="productsetcontent">
                                                        <h5>Headphones</h5>
                                                        <h4>Earphones</h4>
                                                        <h6>150.00</h6>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab_content" data-tab="Accessories">
                                        <div class="row">
                                            <div class="col-lg-3 col-sm-6 d-flex ">
                                                <div class="productset flex-fill">
                                                    <div class="productsetimg">
                                                        <img src="assets/img/product/product32.jpg" alt="img">
                                                        <h6>Qty: 1.00</h6>
                                                        <div class="check-product">
                                                            <i class="fa fa-check"></i>
                                                        </div>
                                                    </div>
                                                    <div class="productsetcontent">
                                                        <h5>Accessories</h5>
                                                        <h4>Sunglasses</h4>
                                                        <h6>15.00</h6>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-sm-6 d-flex ">
                                                <div class="productset flex-fill">
                                                    <div class="productsetimg">
                                                        <img src="assets/img/product/product46.jpg" alt="img">
                                                        <h6>Qty: 1.00</h6>
                                                        <div class="check-product">
                                                            <i class="fa fa-check"></i>
                                                        </div>
                                                    </div>
                                                    <div class="productsetcontent">
                                                        <h5>Accessories</h5>
                                                        <h4>Pendrive</h4>
                                                        <h6>150.00</h6>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-sm-6 d-flex ">
                                                <div class="productset flex-fill">
                                                    <div class="productsetimg">
                                                        <img src="assets/img/product/product55.jpg" alt="img">
                                                        <h6>Qty: 1.00</h6>
                                                        <div class="check-product">
                                                            <i class="fa fa-check"></i>
                                                        </div>
                                                    </div>
                                                    <div class="productsetcontent">
                                                        <h5>Accessories</h5>
                                                        <h4>Mouse</h4>
                                                        <h6>150.00</h6>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab_content" data-tab="Shoes">
                                        <div class="row">
                                            <div class="col-lg-3 col-sm-6 d-flex ">
                                                <div class="productset flex-fill">
                                                    <div class="productsetimg">
                                                        <img src="assets/img/product/product60.jpg" alt="img">
                                                        <h6>Qty: 1.00</h6>
                                                        <div class="check-product">
                                                            <i class="fa fa-check"></i>
                                                        </div>
                                                    </div>
                                                    <div class="productsetcontent">
                                                        <h5>Shoes</h5>
                                                        <h4>Red nike</h4>
                                                        <h6>1500.00</h6>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab_content" data-tab="computer">
                                        <div class="row">
                                            <div class="col-lg-3 col-sm-6 d-flex ">
                                                <div class="productset flex-fill">
                                                    <div class="productsetimg">
                                                        <img src="assets/img/product/product56.jpg" alt="img">
                                                        <h6>Qty: 1.00</h6>
                                                        <div class="check-product">
                                                            <i class="fa fa-check"></i>
                                                        </div>
                                                    </div>
                                                    <div class="productsetcontent">
                                                        <h5>Computers</h5>
                                                        <h4>Desktop</h4>
                                                        <h6>1500.00</h6>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab_content" data-tab="Snacks">
                                        <div class="row">
                                            <div class="col-lg-3 col-sm-6 d-flex ">
                                                <div class="productset flex-fill">
                                                    <div class="productsetimg">
                                                        <img src="assets/img/product/product47.jpg" alt="img">
                                                        <h6>Qty: 1.00</h6>
                                                        <div class="check-product">
                                                            <i class="fa fa-check"></i>
                                                        </div>
                                                    </div>
                                                    <div class="productsetcontent">
                                                        <h5>Snacks</h5>
                                                        <h4>Duck Salad</h4>
                                                        <h6>1500.00</h6>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-sm-6 d-flex ">
                                                <div class="productset flex-fill">
                                                    <div class="productsetimg">
                                                        <img src="assets/img/product/product48.png" alt="img">
                                                        <h6>Qty: 1.00</h6>
                                                        <div class="check-product">
                                                            <i class="fa fa-check"></i>
                                                        </div>
                                                    </div>
                                                    <div class="productsetcontent">
                                                        <h5>Snacks</h5>
                                                        <h4>Breakfast board</h4>
                                                        <h6>1500.00</h6>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-sm-6 d-flex ">
                                                <div class="productset flex-fill">
                                                    <div class="productsetimg">
                                                        <img src="assets/img/product/product57.jpg" alt="img">
                                                        <h6>Qty: 1.00</h6>
                                                        <div class="check-product">
                                                            <i class="fa fa-check"></i>
                                                        </div>
                                                    </div>
                                                    <div class="productsetcontent">
                                                        <h5>Snacks</h5>
                                                        <h4>California roll</h4>
                                                        <h6>1500.00</h6>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-sm-6 d-flex ">
                                                <div class="productset flex-fill">
                                                    <div class="productsetimg">
                                                        <img src="assets/img/product/product58.jpg" alt="img">
                                                        <h6>Qty: 1.00</h6>
                                                        <div class="check-product">
                                                            <i class="fa fa-check"></i>
                                                        </div>
                                                    </div>
                                                    <div class="productsetcontent">
                                                        <h5>Snacks</h5>
                                                        <h4>Sashimi</h4>
                                                        <h6>1500.00</h6>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab_content" data-tab="watch">
                                        <div class="row">
                                            <div class="col-lg-3 col-sm-6 d-flex ">
                                                <div class="productset flex-fill">
                                                    <div class="productsetimg">
                                                        <img src="/Backend/assets/images/icons/upload.svg" alt="img">
                                                        <h6>Qty: 1.00</h6>
                                                        <div class="check-product">
                                                            <i class="fa fa-check"></i>
                                                        </div>
                                                    </div>
                                                    <div class="productsetcontent">
                                                        <h4>Watch</h4>
                                                        <h5>Watch</h5>
                                                        <h6>1500.00</h6>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-sm-6 d-flex ">
                                                <div class="productset flex-fill">
                                                    <div class="productsetimg">
                                                        <img src="assets/img/product/product51.jpg" alt="img">
                                                        <h6>Qty: 1.00</h6>
                                                    </div>
                                                    <div class="productsetcontent">
                                                        <h4>Watch</h4>
                                                        <h5>Watch</h5>
                                                        <h6>1500.00</h6>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab_content" data-tab="cycle">
                                        <div class="row">
                                            <div class="col-lg-3 col-sm-6 d-flex ">
                                                <div class="productset flex-fill">
                                                    <div class="productsetimg">
                                                        <img src="assets/img/product/product52.jpg" alt="img">
                                                        <h6>Qty: 1.00</h6>
                                                        <div class="check-product">
                                                            <i class="fa fa-check"></i>
                                                        </div>
                                                    </div>
                                                    <div class="productsetcontent">
                                                        <h4>Cycle</h4>
                                                        <h5>Cycle</h5>
                                                        <h6>1500.00</h6>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-sm-6 d-flex ">
                                                <div class="productset flex-fill">
                                                    <div class="productsetimg">
                                                        <img src="assets/img/product/product53.jpg" alt="img">
                                                        <h6>Qty: 1.00</h6>
                                                        <div class="check-product">
                                                            <i class="fa fa-check"></i>
                                                        </div>
                                                    </div>
                                                    <div class="productsetcontent">
                                                        <h4>Cycle</h4>
                                                        <h5>Cycle</h5>
                                                        <h6>1500.00</h6>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab_content" data-tab="fruits1">
                                        <div class="row ">
                                            <div class="col-lg-3 col-sm-6 d-flex ">
                                                <div class="productset flex-fill">
                                                    <div class="productsetimg">
                                                        <img src="assets/img/product/product29.jpg" alt="img">
                                                        <h6>Qty: 5.00</h6>
                                                        <div class="check-product">
                                                            <i class="fa fa-check"></i>
                                                        </div>
                                                    </div>
                                                    <div class="productsetcontent">
                                                        <h5>Fruits</h5>
                                                        <h4>Orange</h4>
                                                        <h6>150.00</h6>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-sm-6 d-flex ">
                                                <div class="productset flex-fill">
                                                    <div class="productsetimg">
                                                        <img src="assets/img/product/product31.jpg" alt="img">
                                                        <h6>Qty: 1.00</h6>
                                                        <div class="check-product">
                                                            <i class="fa fa-check"></i>
                                                        </div>
                                                    </div>
                                                    <div class="productsetcontent">
                                                        <h5>Fruits</h5>
                                                        <h4>Strawberry</h4>
                                                        <h6>15.00</h6>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-sm-6 d-flex ">
                                                <div class="productset flex-fill">
                                                    <div class="productsetimg">
                                                        <img src="assets/img/product/product35.jpg" alt="img">
                                                        <h6>Qty: 5.00</h6>
                                                        <div class="check-product">
                                                            <i class="fa fa-check"></i>
                                                        </div>
                                                    </div>
                                                    <div class="productsetcontent">
                                                        <h5>Fruits</h5>
                                                        <h4>Banana</h4>
                                                        <h6>150.00</h6>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-sm-6 d-flex ">
                                                <div class="productset flex-fill">
                                                    <div class="productsetimg">
                                                        <img src="assets/img/product/product37.jpg" alt="img">
                                                        <h6>Qty: 5.00</h6>
                                                        <div class="check-product">
                                                            <i class="fa fa-check"></i>
                                                        </div>
                                                    </div>
                                                    <div class="productsetcontent">
                                                        <h5>Fruits</h5>
                                                        <h4>Limon</h4>
                                                        <h6>1500.00</h6>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab_content" data-tab="headphone1">
                                        <div class="row ">
                                            <div class="col-lg-3 col-sm-6 d-flex ">
                                                <div class="productset flex-fill">
                                                    <div class="productsetimg">
                                                        <img src="assets/img/product/product44.jpg" alt="img">
                                                        <h6>Qty: 5.00</h6>
                                                        <div class="check-product">
                                                            <i class="fa fa-check"></i>
                                                        </div>
                                                    </div>
                                                    <div class="productsetcontent">
                                                        <h5>Headphones</h5>
                                                        <h4>Earphones</h4>
                                                        <h6>150.00</h6>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-sm-6 d-flex ">
                                                <div class="productset flex-fill">
                                                    <div class="productsetimg">
                                                        <img src="assets/img/product/product45.jpg" alt="img">
                                                        <h6>Qty: 5.00</h6>
                                                        <div class="check-product">
                                                            <i class="fa fa-check"></i>
                                                        </div>
                                                    </div>
                                                    <div class="productsetcontent">
                                                        <h5>Headphones</h5>
                                                        <h4>Earphones</h4>
                                                        <h6>150.00</h6>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-sm-6 d-flex ">
                                                <div class="productset flex-fill">
                                                    <div class="productsetimg">
                                                        <img src="assets/img/product/product36.jpg" alt="img">
                                                        <h6>Qty: 5.00</h6>
                                                        <div class="check-product">
                                                            <i class="fa fa-check"></i>
                                                        </div>
                                                    </div>
                                                    <div class="productsetcontent">
                                                        <h5>Headphones</h5>
                                                        <h4>Earphones</h4>
                                                        <h6>150.00</h6>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div> -->
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="card">
                                    <div class="card-header pos-column">
                                        <h5>Order List</h5>
                                        <p>Transaction ID: #65565</p>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <button class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#addCustomerModal">
                                                <i class="bi bi-person-plus"></i> Add Customer
                                            </button>
                                        </div>
                                        <div class="mb-3">
                                            <select class="form-select">
                                                <option>Walk-in Customer</option>
                                                <option>Chris Moris</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <select class="form-select">
                                                <option>Product</option>
                                                <option>Barcode</option>
                                            </select>
                                        </div>
                                        <div class="text-end">
                                            <button class="btn btn-outline-primary">
                                                <i class="bi bi-upc-scan"></i> Scan Barcode
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <h6>Total Items: 4</h6>
                                        <a href="#" class="btn btn-danger btn-sm">Clear All</a>
                                    </div>
                                </div>
                                <div class="row mt-4">
                                    <div class="col-12">
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Product</th>
                                                        <th>Quantity</th>
                                                        <th>Price</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>Pineapple</td>
                                                        <td>
                                                            <div class="input-group">
                                                                <button class="btn btn-outline-secondary">-</button>
                                                                <input type="text" class="form-control text-center" value="1">
                                                                <button class="btn btn-outline-secondary">+</button>
                                                            </div>
                                                        </td>
                                                        <td>$3.00</td>
                                                        <td>
                                                            <button class="btn btn-danger btn-sm">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="row mt-4">
                                            <div class="col-lg-6">
                                                <h5>Subtotal: $55.00</h5>
                                                <h5>Tax: $5.00</h5>
                                                <h5>Total: $60.00</h5>
                                            </div>
                                            <div class="col-lg-6 text-end">
                                                <button class="btn btn-success">
                                                    <i class="bi bi-cart-check"></i> Checkout
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="addCustomerModal" tabindex="-1" aria-labelledby="addCustomerModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addCustomerModalLabel">Add Customer</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="mb-3">
                                <label for="customerName" class="form-label">Customer Name</label>
                                <input type="text" class="form-control" id="customerName">
                            </div>
                            <div class="mb-3">
                                <label for="customerEmail" class="form-label">Email</label>
                                <input type="email" class="form-control" id="customerEmail">
                            </div>
                            <div class="mb-3">
                                <label for="customerPhone" class="form-label">Phone</label>
                                <input type="text" class="form-control" id="customerPhone">
                            </div>
                            <button type="submit" class="btn btn-primary">Add Customer</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            const carousel = document.querySelector('#categorySlider');
            const prevBtn = document.getElementById('prevBtn');
            const nextBtn = document.getElementById('nextBtn');
            const slides = carousel.querySelectorAll('.carousel-item');


            function updateButtons() {
                const activeIndex = [...slides].findIndex(slide => slide.classList.contains('active'));


                prevBtn.disabled = activeIndex <= 0;
                nextBtn.disabled = activeIndex >= slides.length - 1;
            }


            carousel.addEventListener('slid.bs.carousel', updateButtons);


            updateButtons();
(function(){
  const left = document.querySelector('.col-lg-8.pos-column');
  const right = document.querySelector('.col-lg-4.pos-column');
  if (!left || !right) return;

  let fromLeft = false;
  let fromRight = false;

  left.addEventListener('scroll', () => {
    if (fromRight) { fromRight = false; return; }
    fromLeft = true;
    right.scrollTop = left.scrollTop;
  });

  right.addEventListener('scroll', () => {
    if (fromLeft) { fromLeft = false; return; }
    fromRight = true;
    left.scrollTop = right.scrollTop;
  });
})();
        </script>
</body>

</html>