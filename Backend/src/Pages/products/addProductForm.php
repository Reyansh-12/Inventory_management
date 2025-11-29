<?php
include('/var/www/html/Inventory_management/Backend/src/Layouts/Links.php');
include('/var/www/html/Inventory_management/Backend/src/controllers/dbConnection.php');

$productname = isset($_POST['productname']) ? $_POST['productname'] : '';
$categoryselector = isset($_POST['categoryselector']) ? $_POST['categoryselector'] : '';
$brand = isset($_POST['brand']) ? $_POST['brand'] : '';
$minquantity = isset($_POST['minquantity']) ? $_POST['minquantity'] : '';
$quantity = isset($_POST['quantity']) ? $_POST['quantity'] : '';
$description = isset($_POST['description']) ? $_POST['description'] : '';
$price = isset($_POST['price']) ? $_POST['price'] : '';
$status = isset($_POST['status']) ? $_POST['status'] : '';
$discount = isset($_POST['discount']) ? $_POST['discount'] : '';
 

if (isset($_POST['submit'])) {
    $productname = mysqli_real_escape_string($con, $_POST['productname']);
    $categoryselector = mysqli_real_escape_string($con, $_POST['categoryselector']);
    $brand = mysqli_real_escape_string($con, $_POST['brand']);
    $minquantity = mysqli_real_escape_string($con, $_POST['minquantity']);
    $quantity = mysqli_real_escape_string($con, $_POST['quantity']);
    $description = mysqli_real_escape_string($con, $_POST['description']);
    $price = mysqli_real_escape_string($con, $_POST['price']);
    $status = mysqli_real_escape_string($con, $_POST['status']);
    $discount = mysqli_real_escape_string($con, $_POST['discount']);
    $stmt = $con->prepare("INSERT INTO `product_list` (`product_name`, `category`, `brand_name`, `minQuantity`, `price`, `quantity`, `description`, `discount`, `status`) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssss", $productname, $categoryselector, $brand, $minquantity, $price, $quantity, $description, $discount, $status);
    $stmt->execute();
    $stmt->close();

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
    <?php include('/var/www/html/Inventory_management/Backend/src/Layouts/Sidebar.php'); ?>
</div>
<div class="col-md-9">
    <?php include('/var/www/html/Inventory_management/Backend/src/Layouts/Header.php'); ?>
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
                        <form action="#" id="myForm" method="POST" data-parsley-validate>
                            <div class="row">
                                <div class="col-lg-4 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="productName">Product Name <span class="text-danger">*</span></label>
                                        <input type="text" id="productName" name="productname" value="<?php echo htmlspecialchars($productName); ?>" placeholder="Enter product name" data-parsley-required-message="Product name is required" data-parsley-required>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for='categorySelector'>Category <span class="text-danger">*</span></label>
                                        <select class="form-select" onchange="enableBrandSelector()" name="categoryselector" value="<?php echo htmlspecialchars($category) ?>" id="categorySelector" data-parsley-required-message="Select category" data-parsley-required>
                                            <option disabled selected>Choose Category</option>
                                            <option value="haircare" <?php if ($category == "haircare") echo "selected"; ?>>Hair care</option>
                                            <option value="skincare" <?php if ($category == "skincare") echo "selected"; ?>>Skin care</option>
                                            <option value="lipstick" <?php if ($category == "lipstick") echo "selected"; ?>>Lip Stick</option>
                                            <option value="faceskin" <?php if ($category == "faceskin") echo "selected"; ?>>Face Skin</option>
                                            <option value="blusher" <?php if ($category == "blusher") echo "selected"; ?>>Blusher</option>
                                            <option value="natural" <?php if ($category == "natural") echo "selected"; ?>>Natural</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for='brand'>Brand <span class="text-danger">*</span></label>
                                        <select class="form-select" id="brandSelect" name="brand" value="<?php echo htmlspecialchars($brandName) ?>" data-parsley-required-message="Select brand" data-parsley-required>
                                            <option disabled selected>Choose Brand</option>
                                            <option class="haircare" value="loreal" <?php if ($brandName == "loreal") echo "selected"; ?>>L'Oréal</option>
                                            <option class="haircare" value="Pantene" <?php if ($brandName == "Pantene") echo "selected"; ?>>Pantene</option>
                                            <option class="haircare" value="Dove" <?php if ($brandName == "Dove") echo "selected"; ?>>Dove</option>
                                            <option class="haircare" value="Moroccanoil" <?php if ($brandName == "Moroccanoil") echo "selected"; ?>>Moroccanoil</option>
                                            <option class="haircare" value="Herbal Essences" <?php if ($brandName == "Herbal Essences") echo "selected"; ?>>Herbal Essences</option>
                                            <option class="skincare" value="CeraVe" <?php if ($brandName == "CeraVe") echo "selected"; ?>>CeraVe</option>
                                            <option class="skincare" value="The Ordinary" <?php if ($brandName == "The Ordinary") echo "selected"; ?>>The Ordinary</option>
                                            <option class="skincare" value="Neutrogena" <?php if ($brandName == "Neutrogena") echo "selected"; ?>>Neutrogena</option>
                                            <option class="skincare" value="Biotique" <?php if ($brandName == "Biotique") echo "selected"; ?>>Biotique</option>
                                            <option class="skincare" value="La Roche-Posay" <?php if ($brandName == "La Roche-Posay") echo "selected"; ?>>La Roche-Posay</option>
                                            <option class="skincare" value="Clinique" <?php if ($brandName == "Clinique") echo "selected"; ?>>Clinique</option>
                                            <option class="lipstick" value="MAC Cosmetics" <?php if ($brandName == "MAC Cosmetics") echo "selected"; ?>>MAC Cosmetics</option>
                                            <option class="lipstick" value="Charlotte Tilbury" <?php if ($brandName == "Charlotte Tilbury") echo "selected"; ?>>Charlotte Tilbury</option>
                                            <option class="lipstick" value="Revlon" <?php if ($brandName == "Revlon") echo "selected"; ?>>Revlon</option>
                                            <option class="lipstick" value="Huda Beauty" <?php if ($brandName == "Huda Beauty") echo "selected"; ?>>Huda Beauty</option>
                                            <option class="lipstick" value="Maybelline" <?php if ($brandName == "Maybelline") echo "selected"; ?>>Maybelline</option>
                                            <option class="natural" value="Burt’s Bees" <?php if ($brandName == "Burt’s Bees") echo "selected"; ?>>Burt’s Bees</option>
                                            <option class="natural" value="The Body Shop" <?php if ($brandName == "The Body Shop") echo "selected"; ?>>The Body Shop</option>
                                            <option class="natural" value="Dr. Bronner’s" <?php if ($brandName == "Dr. Bronner’s") echo "selected"; ?>>Dr. Bronner’s</option>
                                            <option class="natural" value="Weleda" <?php if ($brandName == "Weleda") echo "selected"; ?>>Weleda</option>
                                            <option class="blusher" value="Dandelion" <?php if ($brandName == "Dandelion") echo "selected"; ?>>Dandelion</option>
                                            <option class="blusher" value="Rockateur" <?php if ($brandName == "Rockateur") echo "selected"; ?>>Rockateur</option>
                                            <option class="blusher" value="Tarte (Amazonian Clay Blush)" <?php if ($brandName == "Tarte (Amazonian Clay Blush)") echo "selected"; ?>>Tarte (Amazonian Clay Blush)</option>
                                            <option class="blusher" value="NARS (“Orgasm”)" <?php if ($brandName == "NARS (“Orgasm”)") echo "selected"; ?>>NARS (“Orgasm”)</option>
                                            <option class="blusher" value="Milani" <?php if ($brandName == "Milani") echo "selected"; ?>>Milani</option>
                                            <option class="blusher" value="Rare Beauty" <?php if ($brandName == "Rare Beauty") echo "selected"; ?>>Rare Beauty</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-4 col-12">
                                    <div class="form-group">
                                        <label for='minQuantity'>Minimum Qty <span class="text-danger">*</span></label>
                                        <input type="number" class="p-2 rounded col-lg-12 border-secondary border-1 border-secondary border" value="<?php echo htmlspecialchars($minQuantity); ?>" name="minquantity" id="minQuantity" placeholder="Enter Minimum Qty" data-parsley-required-message="Minimun quantity field is required" data-parsley-required>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-4 col-12">
                                    <div class="form-group">
                                        <label for='quantity'>Quantity <span class="text-danger">*</span></label>
                                        <input type="number" class="p-2 rounded col-lg-12 border-secondary border-1 border-secondary border" value="<?php echo htmlspecialchars($quantity) ?>" name="quantity" id="quantity" placeholder="Enter Quantity" data-parsley-required-message="Quantity field is required" data-parsley-required>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-4 col-12">
                                    <div class="form-group">
                                        <label for='datepicker'>Expired Date <span class="text-danger">*</span></label>
                                        <input type="text" class="p-2 rounded col-lg-12 border-secondary border-1 border-secondary border" value="<?php echo htmlspecialchars($quantity) ?>" name="expiredDate" id="datepicker" placeholder="Select expired date" data-parsley-required-message="Expired date is required" data-parsley-required>
                                        
                                    </div>
                                </div>
                                <div class="class=col-lg-4 col-sm-4 col-12"></div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="productDescription">Product Description <span class="text-danger">*</span></label>
                                        <textarea class="form-control" name="description" id="description" placeholder="Enter product description" data-parsley-required-message="Product description field is required" data-parsley-required><?php echo htmlspecialchars($description) ?></textarea>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="discout">Discount <span class="text-danger">*</span></label>
                                        <select name="discount" class="form-select mt-2" id="discout" data-parsley-required-message="Discount field is required" value="<?php echo htmlspecialchars($discount) ?>" data-parsley-required>
                                            <option disabled>Percentage</option>
                                            <option value="10%" <?php if ($discount == "10%") echo "selected"; ?>>10%</option>
                                            <option value="20%" <?php if ($discount == "20%") echo "selected"; ?>>20%</option>
                                        </select>

                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="price">Price <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control p-2 rounded-end col-lg-12 border-secondary border-1 border-secondary border" value="<?php echo htmlspecialchars($price) ?>" name="price" id="price" placeholder="Enter Price" data-parsley-required-message="Price field is required" data-parsley-required>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="label"> Status</label>
                                        <select class="form-select" name="status" id="label" data-parsley-id="1601" value="<?php echo htmlspecialchars($status) ?>">
                                            <option value="" disabled selected>Choose Status</option>
                                            <option>Active</option>
                                            <option>Unactive</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="productImage"> Product Image (Optional)</label>
                                        <div class="image-upload">
                                            <input type="file" name="imageBox" id="productImage">
                                            <div class="image-uploads">
                                                <img src="/Backend/assets/images/icons/upload.svg" alt="img">
                                                <h4>Drag and drop a file to upload</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12 d-flex justify-content-end">
                                    <button class="btn btn-cancel me-2" type="reset" name="reset" id="resetButton">Reset</button>
                                    <button class="btn btn-submit" name="submit" type="submit">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $('#productName').on('input', function() {
            let inputValue = $(this).val();
            let filteredValue = inputValue.replace(/[0-9]/g, '');
            $(this).val(filteredValue);
        });
        let parsleyForm = $('#myForm').parsley();
        $('#resetButton').on('click', function() {
            parsleyForm.reset();
        });

        function enableBrandSelector() {
            const primarySelect = document.getElementById('categorySelector');
            const secondarySelect = document.getElementById('brandSelect');

            if (primarySelect.value !== "") {
                secondarySelect.disabled = false;
            } else {
                secondarySelect.disabled = true;
                secondarySelect.value = "";
            }
        }
        $('#brandSelect option:not(:first)').hide();
        $('#categorySelector').change(function() {
            var selectedCategory = $(this).val();
            $('#brandSelect option').show();
            $('#brandSelect').val('');
            if (selectedCategory) {
                $('#brandSelect option:not(:first)').not('.' + selectedCategory).hide();
            }
        });
         $(function () {
    $("#datepicker").datepicker({
        dateFormat: "yy-mm-dd",
        changeMonth: true,
        changeYear: true,
        showAnim: "fadeIn"
    });
});
    </script>
</body>

</html>