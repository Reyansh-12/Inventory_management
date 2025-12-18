<?php
define("BASE_PATH", dirname(__DIR__, 3));
include BASE_PATH . "/src/Layouts/Links.php";
include BASE_PATH . "/src/controllers/dbConnection.php";

$productId = $_GET['productId'] ?? null;
$isEdit = false;
$editData = [];

if ($productId) {
    $isEdit = true;
    $stmt = mysqli_prepare($con, "SELECT * FROM `product_list` WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $productId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if ($result && mysqli_num_rows($result) > 0) {
        $editData = mysqli_fetch_assoc($result);
    }
    mysqli_stmt_close($stmt);
}

if (isset($_POST['submit'])) {
    $productname = $_POST['productname'] ?? '';
    $category = $_POST['categoryselector'] ?? '';
    $brandName = $_POST['brand'] ?? '';
    $minquantity = $_POST['minquantity'] ?? '';
    $quantity = $_POST['quantity'] ?? '';
    $description = $_POST['description'] ?? '';
    $discount = $_POST['discount'] ?? '';
    $price = $_POST['price'] ?? '';
    $status = $_POST['status'] ?? '';
    $expiredDate = $_POST['expiredDate'] ?? '';
    $image = $_FILES['imageBox'] ?? '';
    $imagePath = null;
    if (!empty($_FILES['imageBox']['name'])) {

        $uploadDir = BASE_PATH . "/Backend/assets/images/";
        
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        $fileName = basename($_FILES["imageBox"]["name"]);
        $targetPath = $uploadDir . $fileName;
    
        if (move_uploaded_file($_FILES["imageBox"]["tmp_name"], $targetPath)) {
            $imagePath = "http://localhost/Inventory_management/Backend/assets/images/" . $fileName;
        }
    }
    if ($isEdit) {
        $stmt = mysqli_prepare($con, "UPDATE `product_list` 
            SET product_name=?, category=?, brand_name=?, minQuantity=?, price=?, quantity=?, description=?, discount=?, status=?, expired_date=?
            WHERE id=?");
        mysqli_stmt_bind_param($stmt, "sssiddssssi", $productname, $category, $brandName, $minquantity, $price, $quantity, $description, $discount, $status, $expiredDate, $productId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    } else {
        $stmt = mysqli_prepare($con, "INSERT INTO `product_list` 
            (product_name, category, brand_name, minQuantity, price, quantity, description, discount, status, image_path, expired_date) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ? , ?)");
        mysqli_stmt_bind_param($stmt, "sssiddsssss", $productname, $category, $brandName, $minquantity, $price, $quantity, $description, $discount, $status, $imagePath, $expiredDate);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
    if ($isEdit) {
    header("Location: ProductList.php?updated=1");
} else {
    header("Location: ProductList.php?added=1");
}
exit();

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
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>

    <style>
        .parsley-required,.parsley-minlength, .parsley-gteMinquantity {
            color: orangered;
        }

        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
    </style>
    <style>
@keyframes shrink { 
    from { width: 100%; } 
    to { width: 0%; } 
}

.stylish-datepicker {
    padding: 12px;
    border-radius: 8px;
    border: 1px solid #6c757d;
    font-size: 15px;
    transition: 0.3s ease-in-out;
}

.stylish-datepicker:focus {
    border-color: #3f51b5;
    box-shadow: 0 0 5px rgba(63, 81, 181, 0.4);
    outline: none;
}

.ui-datepicker {
    background: #fff;
    border: 2px solid #3f51b5;
    padding: 10px;
    border-radius: 10px;
    font-size: 14px;
}

.ui-datepicker-header {
    background: #3f51b5;
    color: #fff;
    border-radius: 8px 8px 0 0;
}

.ui-state-default {
    padding: 6px;
    border-radius: 5px;
}

.ui-state-highlight {
    background: #eceff1 !important;
    border-radius: 5px;
}

.ui-state-active {
    background: #3f51b5 !important;
    color: #fff !important;
    border-radius: 5px;
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
                    <?php include BASE_PATH . "/src/Layouts/Sidebar.php"; ?>
                </div>
                <div class="col-md-9">
                    <?php include BASE_PATH . "/src/Layouts/Header.php"; ?>
                </div>
            </div>
        <div class="page-wrapper">
            <div class="content">
                <div class="page-header">
                    <div class="page-btn">
                        <a href="/Backend/src/Pages/products/ProductList.php" class="btn btn-added"><i class="bi bi-arrow-left me-1 fw-bold"></i>Back to Product</a>
                    </div>
                    <div class="page-title">
                        <h4>Add Cosmetic Product</h4>
                        <h6>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="ProductList.php">Cosmetic List</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Cosmetic Form</li>
                                </ol>
                            </nav>
                        </h6>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <form action="#" id="myForm" method="POST" enctype="multipart/form-data" data-parsley-validate>
                            <input type="hidden" name="user_id" value="<?php echo $editData['id'] ?? ''; ?>">
                            <div class="row">
                                <div class="col-lg-4 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="productName">Product Name <span class="text-danger">*</span></label>
                                        <input type="text" id="productName" name="productname" value="<?php echo htmlspecialchars($editData['product_name'] ?? '') ?>" placeholder="Enter product name" maxlength="100" data-parsley-minlength="3" data-parsley-required-message="Product name is required" data-parsley-required>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for='categorySelector'>Category <span class="text-danger">*</span></label>
                                        <select class="form-select" onchange="enableBrandSelector()" name="categoryselector" id="categorySelector" data-parsley-required-message="Select category" data-parsley-required>
                                            <option disabled selected>Choose Category</option>
                                            <?php
                                            $categories = ['haircare' => 'Hair care', 'skincare' => 'Skin care', 'lipstick' => 'Lip Stick', 'faceskin' => 'Face Skin', 'blusher' => 'Blusher', 'natural' => 'Natural'];
                                            foreach ($categories as $key => $value) {
                                                $selected = ($editData['category'] ?? '') == $key ? 'selected' : '';
                                                echo "<option value=\"$key\" $selected>$value</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for='brand'>Brand <span class="text-danger">*</span></label>
                                        <select class="form-select" id="brandSelect" name="brand" disabled data-parsley-required-message="Select brand" data-parsley-required>
                                            <option disabled selected>Choose Brand</option>
                                            <?php
                                            $brands = [
                                                'haircare' => ['loreal' => "L'Oréal", 'Pantene' => 'Pantene', 'Dove' => 'Dove', 'Moroccanoil' => 'Moroccanoil', 'Herbal Essences' => 'Herbal Essences'],
                                                'skincare' => ['CeraVe' => 'CeraVe', 'The Ordinary' => 'The Ordinary', 'Neutrogena' => 'Neutrogena', 'Biotique' => 'Biotique', 'La Roche-Posay' => 'La Roche-Posay', 'Clinique' => 'Clinique'],
                                                'lipstick' => ['MAC Cosmetics' => 'MAC Cosmetics', 'Charlotte Tilbury' => 'Charlotte Tilbury', 'Revlon' => 'Revlon', 'Huda Beauty' => 'Huda Beauty', 'Maybelline' => 'Maybelline'],
                                                'natural' => ['Burt’s Bees' => 'Burt’s Bees', 'The Body Shop' => 'The Body Shop', 'Dr. Bronner’s' => 'Dr. Bronner’s', 'Weleda' => 'Weleda'],
                                                'blusher' => ['Dandelion' => 'Dandelion', 'Rockateur' => 'Rockateur', 'Tarte (Amazonian Clay Blush)' => 'Tarte (Amazonian Clay Blush)', 'NARS (“Orgasm”)' => 'NARS (“Orgasm”)', 'Milani' => 'Milani', 'Rare Beauty' => 'Rare Beauty'],
                                                'faceskin' => ['Fenty Beauty' => 'Fenty Beauty', 'Estée Lauder' => 'Estée Lauder', 'Maybelline Fit Me' => 'DermaCare', 'L’Oréal True Match' => 'L’Oréal True Match', 'Revlon ColorStay' => 'Revlon ColorStay'],
                                            ];

                                            foreach ($brands as $cat => $brandList) {
                                                foreach ($brandList as $bKey => $bName) {
                                                    $selected = ($editData['brand_name'] ?? '') == $bKey ? 'selected' : '';
                                                    echo "<option class=\"$cat\" value=\"$bKey\" $selected>$bName</option>";
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-4 col-12">
                                    <div class="form-group">
                                        <label for='minQuantity'>Min Quantity <span class="text-danger">*</span></label>
                                        <input type="number" class="p-2 rounded col-lg-12 border-secondary border-1 border-secondary border" value="<?php echo htmlspecialchars($editData['minQuantity'] ?? '') ?>" name="minquantity" id="minQuantity" placeholder="Enter Minimum Qty" data-parsley-required-message="Minimum quantity field is required" data-parsley-required>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-4 col-12">
                                    <div class="form-group">
                                        <label for='quantity'>Max Quantity <span class="text-danger">*</span></label>
                                        <input type="number" name="quantity" id="quantity" class="p-2 rounded col-lg-12 border-secondary border-1 border-secondary border" value="<?php echo htmlspecialchars($editData['quantity'] ?? '') ?>" placeholder="Enter Quantity" data-parsley-gte-minquantity data-parsley-required data-parsley-required-message="Quantity field is required">
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-4 col-12">
                                    <div class="form-group">
                                        <label for='datepicker'>Expired Date <span class="text-danger">*</span></label>
                                        <!-- <input type="text" class="p-2 rounded col-lg-12 border-secondary border-1 border-secondary border" name="expiredDate" id="datepicker" placeholder="Select expired date" value="<?php echo htmlspecialchars($editData['expired_date'] ?? '') ?>" data-parsley-required-message="Expired date is required" data-parsley-required> -->
                                        <input type="text" class="form-control stylish-datepicker" name="expiredDate" id="datepicker" placeholder="Select expired date" value="<?php echo htmlspecialchars($editData['expired_date'] ?? '') ?>" data-parsley-required data-parsley-required-message="Expired date is required">
                                    </div>
                                </div>

                                <div class="col-lg-4 col-sm-4 col-12"></div>

                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="productDescription">Product Description</label>
                                        <textarea class="form-control" name="description" maxlength="1000" id="description" placeholder="Enter product description"><?php echo htmlspecialchars($editData['description'] ?? '') ?></textarea>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="discout">Discount <span class="text-danger">*</span></label>
                                        <select name="discount" class="form-select mt-2" id="discout" data-parsley-required-message="Discount field is required" data-parsley-required>
                                            <option disabled>Percentage</option>
                                            <option value="10" <?= ($editData['discount'] ?? '') == '10' ? 'selected' : '' ?>>10%</option>
                                            <option value="20" <?= ($editData['discount'] ?? '') == '20' ? 'selected' : '' ?>>20%</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="price">Price <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control p-2 rounded-end col-lg-12 border-secondary border-1 border-secondary border" value="<?php echo htmlspecialchars($editData['price'] ?? '') ?>" name="price" id="price" placeholder="Enter Price" data-parsley-required-message="Price field is required" data-parsley-required>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="label"> Status</label>
                                        <select class="form-select" name="status" id="label" data-parsley-id="1601">
                                            <option value="" disabled <?= empty($editData['status']) ? 'selected' : '' ?>>Choose Status</option>
                                            <option value="Active" <?= ($editData['status'] ?? '') == 'Active' ? 'selected' : '' ?>>Active</option>
                                            <option value="Inactive" <?= ($editData['status'] ?? '') == 'Inactive' ? 'selected' : '' ?>>Inactive</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="productImage"> Product Image <span class="text-danger">*</span></label>
                                        <div class="image-upload mb-0">
                                            <input type="file" name="imageBox" id="productImage" accept="image/*">
                                            <div class="image-uploads">
                                                <img src="/Backend/assets/images/icons/upload.svg" alt="img">
                                                <h4>Drag and drop a file to upload</h4>
                                            </div>
                                        </div>
                                        <div id="imageError" class="text-danger"></div>
                                    </div>
                                </div>
                                <div class="col-lg-12 d-flex justify-content-end">
                                    <button class="btn btn-cancel me-2" type="reset" name="reset" id="resetButton">Reset</button>
                                    <button class="btn btn-submit" name="submit" type="submit"><?= $productId ? 'Update' : 'Submit' ?></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 999;">
    <div id="actionToast" class="toast border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body" id="toastMessage">
                Action completed!
            </div>
            <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
        <div class="toast-timer" style="height: 4px; background: rgba(0,0,0,0.2); animation: shrink 3s linear forwards;"></div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script> 
    

        let parsleyForm = $('#myForm').parsley();
        $('#resetButton').on('click', function() {
            parsleyForm.reset();
        });
        document.addEventListener("DOMContentLoaded", function() {
    const params = new URLSearchParams(window.location.search);

    let toastElement = document.getElementById("actionToast");
    let toastMessage = document.getElementById("toastMessage");

    if (params.get("added") === "1") {
        toastMessage.textContent = "Product added successfully!";
        toastElement.classList.add("text-bg-success", "text-white");
    } 
    else if (params.get("updated") === "1") {
        toastMessage.textContent = "Product updated successfully!";
        toastElement.classList.add("text-bg-success", "text-white");
    } 
    else {
        return; 
    }

    let timerBar = toastElement.querySelector(".toast-timer");
    timerBar.style.animation = "none";
    timerBar.offsetHeight;
    timerBar.style.animation = "shrink 5s linear forwards";

    let toast = new bootstrap.Toast(toastElement, { delay: 3000 });
    toast.show();

    setTimeout(() => {
        window.history.replaceState({}, document.title, window.location.pathname);
    }, 3500);
});


function enableBrandSelector() {
    let category = document.getElementById("categorySelector").value;
    let brandSelect = document.getElementById("brandSelect");

    brandSelect.removeAttribute("disabled");

    let options = brandSelect.querySelectorAll("option");

    options.forEach(option => {
        if (option.classList.contains(category) || option.value === "Choose Brand") {
            option.style.display = "block"; 
        } else {
            option.style.display = "none";    
        }
    });

    brandSelect.value = "";
}

$(function () {
    $("#datepicker").datepicker({
        dateFormat: "yy-mm-dd",
        changeMonth: true,
        changeYear: true,
        showAnim: "slideDown",
        minDate: 0,
        yearRange: "2024:2035",
        onSelect: function () {
            $(this).parsley().validate();
        }
    });
});

document.getElementById('productImage').addEventListener('change', function () {
    const file = this.files[0];
    const title = this.closest('.image-upload').querySelector('h4');

    if (file) {
        title.textContent = file.name;   
    } else {
        title.textContent = "Drag and drop a file to upload"; 
    }
});
</script>
<script>
window.Parsley.addValidator('gteMinquantity', {
    validateNumber: function (value) {
        const minQty = Number($('#minQuantity').val());

        if (isNaN(minQty)) {
            return true; 
        }

        return Number(value) >= minQty;
    },
    messages: {
        en: 'Max quantity must be greater than or equal to Min quantity'
    }
});
$('#minQuantity').on('input change', function () {
    $('#quantity').parsley().validate();
});
</script>

</body>
</html>