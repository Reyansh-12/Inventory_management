<?php 
include('/xampp/htdocs/Inventory_management/Backend/src/Layouts/Links.php');
include('/xampp/htdocs/Inventory_management/Backend/src/controllers/dbConnection.php');
header("Cache-Control: no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");

ini_set('display_errors', 0);
$productId = isset($_GET['id']) ? intval($_GET['id']) : null;
if (isset($_GET['delete']) && $productId) {
    $deleteSql = "DELETE FROM `product_list` WHERE `id` = $productId";
    mysqli_query($con, $deleteSql);
    header("Location: /Backend/src/Pages/products/ProductList.php?deleted=1");
    exit;
}
$productName = $category = $brandName = $price = $quantity = $minQty = $description = $status = "";

if ($productId) {
    $sql = "SELECT * FROM `product_list` WHERE `id` = $productId";
    $result = mysqli_query($con, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        $productName = $row['product_name'];
        $category = $row['category'];
        $brandName = $row['brand_name'];
        $price = $row['price'];
        $quantity = $row['quantity'];
        $minQty = $row['minquantity'];
        $description = $row['description'];
        $status = $row['status'];
    }
}
if (isset($_POST['submit'])) {

    $productName = $_POST['productname'];
    $category = $_POST['categoryselector'];
    $brandName = $_POST['brand'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];
    $minQty = $_POST['minquantity'];
    $description = $_POST['description'];
    $status = $_POST['status'];

    if ($productId) {
        $updateSql = "UPDATE `product_list` SET 
            product_name='$productName',
            category='$category',
            brand_name='$brandName',
            price='$price',
            quantity='$quantity',
            minquantity='$minQty',
            description='$description',
            status='$status'
            WHERE id=$productId";

        mysqli_query($con, $updateSql);

    } else {
        $insertSql = "INSERT INTO `product_list`
            (product_name, category, brand_name, price, quantity, minquantity, description, status)
            VALUES ('$productName','$category','$brandName','$price','$quantity','$minQty','$description','$status')";

        mysqli_query($con, $insertSql);
    }

    header("Location: /Backend/src/Pages/products/ProductList.php?success=1");
    exit;
}
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
    <title>Dreams Pos admin template</title>
    <style>
        .parsley-required {
            color: orangered;
        }

        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
    </style>
</head>

<body>
    <div id="global-loader">
        <div class="whirly-loader"> </div>
    </div>
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
                        <form action="#" method="POST" enctype="multipart/form-data" data-parsley-validate>
                            <div class="row">
                                <div class="col-lg-4 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="productName">Product Name <span class="text-danger">*</span></label>
                                        <input type="text" name="productname" value="<?php echo $productName; ?>" id="productName" placeholder="Enter product name" data-parsley-required-message="Please enter product name" required>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for='categorySelector'>Category <span class="text-danger">*</span></label>
                                        <select class="form-select" name="categoryselector" id="categorySelector" data-parsley-required-message="Select category" required>
                                            <option value="" disabled <?php if($category==='' ) echo 'selected'; ?>>Choose Category</option>
                                            <option <?php if($category==='Skin care') echo 'selected'; ?>>Skin care</option>
                                            <option <?php if($category==='Hair care') echo 'selected'; ?>>Hair care</option>
                                            <option <?php if($category==='Eye Liner') echo 'selected'; ?>>Eye Liner</option>
                                            <option <?php if($category==='Foundation') echo 'selected'; ?>>Foundation</option>
                                            <option <?php if($category==='Sunscrean') echo 'selected'; ?>>Sunscrean</option>
                                            <option <?php if($category==='Face Powder') echo 'selected'; ?>>Face Powder</option>
                                            <option <?php if($category==='Face Mask') echo 'selected'; ?>>Face Mask</option>
                                            <option <?php if($category==='Eyebrow') echo 'selected'; ?>>Eyebrow</option>
                                            <option <?php if($category==='Moisturizer') echo 'selected'; ?>>Moisturizer</option>
                                            <option <?php if($category==='Tanning') echo 'selected'; ?>>Tanning</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for='brand'>Brand <span class="text-danger">*</span></label>
                                        <select class="form-select" name="brand" id="brand" data-parsley-required-message="Select brand" required>
                                            <option value="" disabled <?php if($brandName==='' ) echo 'selected'; ?>>Choose Brand</option>
                                            <option <?php if($brandName==='Lakme') echo 'selected'; ?>>Lakme</option>
                                            <option <?php if($brandName==='IYZA') echo 'selected'; ?>>IYZA</option>
                                            <option <?php if($brandName==='Chanel') echo 'selected'; ?>>Chanel</option>
                                            <option <?php if($brandName==='Loreal') echo 'selected'; ?>>Loreal</option>
                                            <option <?php if($brandName==='Dior') echo 'selected'; ?>>Dior</option>
                                            <option <?php if($brandName==='Lotus Herbals') echo 'selected'; ?>>Lotus Herbals</option>
                                            <option <?php if($brandName==='Huda Beauty') echo 'selected'; ?>>Huda Beauty</option>
                                            <option <?php if($brandName==='Clarins') echo 'selected'; ?>>Clarins</option>
                                            <option <?php if($brandName==='Biotique') echo 'selected'; ?>>Biotique</option>
                                            <option <?php if($brandName==='Grande Cosmetic') echo 'selected'; ?>>Grande Cosmetic</option>
                                            <option <?php if($brandName==='Maybelline') echo 'selected'; ?>>Maybelline</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for='minQuantity'>Minimum Qty <span class="text-danger">*</span></label>
                                        <input type="number" class="p-2 rounded col-lg-12 border border-secondary" value="<?php echo htmlspecialchars($minQty); ?>" name="minquantity" id="minQuantity" placeholder="Enter Minimum Qty" data-parsley-required-message="Please enter minimum quantity" data-parsley-required>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for='quantity'>Quantity <span class="text-danger">*</span></label>
                                        <input type="number" class="p-2 rounded col-lg-12 border border-secondary" name="quantity" id="quantity" value="<?php echo htmlspecialchars($quantity); ?>" placeholder="Enter Quantity" data-parsley-required-message="Please enter quantity" data-parsley-required>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="productDescription">Product Description <span class="text-danger">*</span></label>
                                        <textarea class="form-control" name="description" id="description" placeholder="Enter product description" data-parsley-required-message="Enter product description" required><?php echo htmlspecialchars($description); ?></textarea>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="discout">Discount <span class="text-danger">*</span></label>
                                        <select class="form-select mt-2" name="discount" id="discout" data-parsley-required-message="Select discount" required>
                                            <option disabled selected>Percentage</option>
                                            <option>10%</option>
                                            <option>20%</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="price">Price <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control p-2 rounded-end col-lg-12 border border-secondary" name="price" id="price" value="<?php echo htmlspecialchars($price); ?>" placeholder="Enter Price" data-parsley-required-message="Please enter price" data-parsley-required>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="label"> Status <span class="text-danger">*</span></label>
                                        <select class="form-select" name="status" id="label" data-parsley-id="1601" data-parsley-required-message="Select status" required>
                                            <option value="" disabled <?php if($status==='') echo 'selected'; ?>>Choose Status</option>
                                            <option value="Active" <?php if($status==='Active') echo 'selected'; ?>>Active</option>
                                            <option value="Unactive" <?php if($status==='Unactive') echo 'selected'; ?>>Unactive</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="productImage"> Product Image <span class="text-danger">*</span></label>
                                        <div class="image-upload">
                                            <input type="file" name="imageBox" id="productImage">
                                            <div class="image-uploads">
                                                <img src="/Backend/src/assets/images/icons/upload.svg" alt="img">
                                                <h4>Drag and drop a file to upload</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12 d-flex justify-content-end">
                                    <button class="btn btn-cancel me-2" type="reset" name="reset">Reset</button>
                                    <button class="btn btn-submit" name="submit" type="submit">Submit</button>
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