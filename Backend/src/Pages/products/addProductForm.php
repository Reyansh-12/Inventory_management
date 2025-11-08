<?php include('/xampp/htdocs/Inventory_management/Backend/src/Layouts/Links.php');
include('/xampp/htdocs/Inventory_management/Backend/src/controllers/dbConnection.php');
ini_set('display_errors', 0);
$productName = $_POST['productname'];
$category = $_POST['categoryselector'];     
$brandName = $_POST['brand'];
$price = $_POST['price'];
$quantity = $_POST['quantity'];
$createdBy = 'Admin';   

if(isset($_POST['submit'])) {
    $sql = "INSERT INTO `product_list`(`product_name`, `category`, `brand_name`, `price`, `quantity`, `created_by`) 
    VALUES ('$productName','$category','$brandName','$price','$quantity','$createdBy')";
    $result = mysqli_query($con, $sql);
}
$productId = $_GET['productId'];

if(isset($_GET['productId'])) {
    $productId = $_GET['productId'];
    $updateSQl = "UPDATE `product_list` SET `product_name`='$productName',`category`='$category',`brand_name`='$brandName',`price`='$price',`quantity`='$quantity',`created_by`='$createdBy' WHERE `id` = $productId";
    $con->query($updateSQl);
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
    <style>
        .parsley-required{
            color: orangered;
        }
    </style>
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
                        <h4>Product Add</h4>
                        <h6>Create new product</h6>
                    </div>
                    <div class="page-btn">
                        <a href="/Backend/src/Pages/products/ProductList.php" class="btn btn-added"><i class="bi bi-arrow-left me-1 fw-bold"></i>Back to Product</a>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <form action="#" method="POST" data-parsley-validate>
                            <div class="row">
                                <div class="col-lg-4 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="productName">Product Name <span class="text-danger">*</span></label>
                                        <input type="text" name="productname" id="productName" placeholder="Enter product name" data-parsley-required>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for='categorySelector'>Category <span class="text-danger">*</span></label>
                                        <select class="form-select" name="categoryselector" id="categorySelector" data-parsley-required>
                                            <option disabled selected>Choose Category</option>
                                            <option>Computers</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for='brand'>Brand <span class="text-danger">*</span></label>
                                        <select class="form-select" name="brand" id="brand" data-parsley-required>
                                            <option disabled selected>Choose Brand</option>
                                            <option>Brand</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for='minQuantity'>Minimum Qty <span class="text-danger">*</span></label>
                                        <input type="text" name="minquantity" id="minQuantity" placeholder="Enter Minimum Qty" data-parsley-required>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for='quantity'>Quantity <span class="text-danger">*</span></label>
                                        <input type="text" name="quantity" id="quantity" placeholder="Enter Quantity" data-parsley-required>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for='description'>Description <span class="text-danger">*</span></label>
                                        <textarea class="form-control" name="description" id="description" placeholder='Enter a product description' data-parsley-required></textarea>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="discout">Discount <span class="text-danger">*</span></label>
                                        <select class="form-select mt-2" name="discount" id="discout" data-parsley-required>
                                            <option disabled selected>Percentage</option>
                                            <option>10%</option>
                                            <option>20%</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="price">Price <span class="text-danger">*</span></label>
                                        <input type="text" name="price" id="price" placeholder="Enter Price" data-parsley-required>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="label"> Status <span class="text-danger">*</span></label>
                                        <select class="form-select" name="status" id="label" data-parsley-id="1601" data-parsley-required>
                                            <option disabled selected>Closed</option>
                                            <option>Open</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="productImage"> Product Image <span class="text-danger">*</span></label>
                                        <div class="image-upload">
                                            <input type="file" name="imageBox" id="productImage">
                                            <div class="image-uploads">
                                                <img src="assets/img/icons/upload.svg" alt="img">
                                                <h4>Drag and drop a file to upload</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <button class="btn btn-submit me-2" name="submit" type="submit">Submit</button>
                                    <button class="btn btn-cancel" type="cancel" name="cancel">Cancel</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</body>

</html>