<?php
define("BASE_PATH", dirname(__DIR__, 3));
include BASE_PATH . "/src/Layouts/Links.php";
include BASE_PATH . "/src/controllers/dbConnection.php";
// include BASE_PATH . '/src/Pages/products/get_brands.php';

$productId = $_GET['productId'] ?? $_POST['product_id'] ?? null;
$isEdit = false;
$editData = [];
$existingGalleryImages = [];

if ($productId) {
    $isEdit = true;
    $stmt = mysqli_prepare($con, "SELECT * FROM product_list WHERE id=?");
    mysqli_stmt_bind_param($stmt, "i", $productId);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    if ($res && mysqli_num_rows($res) > 0) {
        $editData = mysqli_fetch_assoc($res);
        $existingGalleryImages = json_decode(
            $editData['gallery_images'] ?? '[]',
            true
        ) ?? [];
    }

    mysqli_stmt_close($stmt);
}

if (isset($_POST['submit'])) {
    $galleryImages = $existingGalleryImages;
    $removedGalleryImages = json_decode($_POST['removed_gallery_images'] ?? '[]', true);


    if (!empty($removedGalleryImages)) {
        $galleryImages = array_values(
            array_diff($galleryImages, $removedGalleryImages)
        );
    }

    if (!empty($_FILES['gallery_images']['name'][0])) {

        $uploadDir = BASE_PATH . "/src/uploads/products/gallery/";

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        foreach ($_FILES['gallery_images']['tmp_name'] as $key => $tmpName) {
            if ($_FILES['gallery_images']['error'][$key] !== 0) continue;

            $ext = strtolower(pathinfo($_FILES['gallery_images']['name'][$key], PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'webp'];
            if (!in_array($ext, $allowed)) continue;

            $fileName = uniqid('gallery_', true) . '.' . $ext;
            $targetPath = $uploadDir . $fileName;

            if (move_uploaded_file($tmpName, $targetPath)) {
                $galleryImages[] = '/Backend/src/uploads/products/gallery/' . $fileName;
            }
        }
    }
    $galleryJson = json_encode($galleryImages);
    $productname = $_POST['productname'] ?? '';
    $category = $_POST['categoryselector'] ?? '';
    $brandName = $_POST['brand'] ?? '';
    $minquantity = $_POST['minquantity'] ?? 0;
    $quantity = $_POST['quantity'] ?? 0;
    $description = $_POST['description'] ?? '';
    $discount = $_POST['discount'] ?? 0;
    $price = $_POST['price'] ?? 0;
    $status = $_POST['status'] ?? '';
    $expiredDate = $_POST['expiredDate'] ?? '';

    $imagePath = $_POST['existing_image'] ?? '';
    if (isset($_FILES['imageBox']) && $_FILES['imageBox']['error'] === 0) {
        $uploadDir = BASE_PATH . "/src/uploads/products/featured/";
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
        $ext = strtolower(pathinfo($_FILES['imageBox']['name'], PATHINFO_EXTENSION));
        $fileName = uniqid('product_', true) . '.' . $ext;
        $targetPath = $uploadDir . $fileName;
        if (move_uploaded_file($_FILES['imageBox']['tmp_name'], $targetPath)) {
            $imagePath = '/Backend/src/uploads/products/featured/' . $fileName;
        }
    }
    if ($isEdit) {
        $stmt = mysqli_prepare($con, "UPDATE product_list SET product_name=?, category=?, brand_name=?, minQuantity=?, price=?, quantity=?,description=?, discount=?, status=?, image_path=?, gallery_images=?, expired_date=? WHERE id=?");
        mysqli_stmt_bind_param(
            $stmt,
            "sssiddssssssi",
            $productname,
            $category,
            $brandName,
            $minquantity,
            $price,
            $quantity,
            $description,
            $discount,
            $status,
            $imagePath,
            $galleryJson,
            $expiredDate,
            $productId
        );
    } else {
        $stmt = mysqli_prepare($con, "INSERT INTO product_list (product_name, category, brand_name, minQuantity, price, quantity, description, discount, status, image_path, gallery_images, expired_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "sssiddssssss", $productname, $category, $brandName, $minquantity, $price, $quantity, $description, $discount, $status, $imagePath, $galleryJson, $expiredDate);
    }
    if (!$stmt->execute()) {
        die("DB Error: " . mysqli_error($con));
    }
    mysqli_stmt_close($stmt);
    header("Location: ProductList.php?" . ($isEdit ? "updated=1" : "added=1"));
    exit();
}
$catResult = mysqli_query($con, "SELECT id, category FROM category WHERE status='Active'");

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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" />
    <style>
        .parsley-required,
        .parsley-minlength,
        .parsley-gteMinquantity,
        .parsley-custom-error-message {
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
            from {
                width: 100%;
            }

            to {
                width: 0%;
            }
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

        .ui-state-highlight,
        .ui-widget-content .ui-state-highlight,
        .ui-widget-header .ui-state-highlight {
            border: none !important;
        }

        .ui-state-highlight a,
        .ui-widget-content .ui-state-highlight a,
        .ui-widget-header .ui-state-highlight a {
            background: #003980 !important;
            color: white !important;
            text-align: center;
        }

        .gallery-wrapper {
            display: flex;
            gap: 10px;
            align-items: center;
            flex-wrap: wrap;
        }

        .upload-box {
            width: 100px;
            height: 100px;
            border: 2px dashed #aaa;
            border-radius: 6px;
            cursor: pointer;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: #666;
        }

        .upload-box span {
            font-size: 28px;
            line-height: 1;
        }

        .upload-box small {
            font-size: 11px;
            text-align: center;
        }

        .gallery-preview {
            display: flex;
            gap: 8px;
        }

        .gallery-item {
            position: relative;
            width: 100px;
            height: 100px;
            border: 1px solid #ddd;
            border-radius: 6px;
            overflow: hidden;
        }

        .gallery-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .remove-btn {
            position: absolute;
            top: 4px;
            right: 4px;
            width: 20px;
            height: 20px;
            background: rgba(0, 0, 0, 0.7);
            color: #fff;
            border-radius: 50%;
            display: none;
            justify-content: center;
            align-items: center;
            cursor: pointer;
        }

        .gallery-item:hover .remove-btn {
            display: flex;
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
                        <a href="/Backend/src/Pages/products/ProductList.php" class="fw-bold fs-6 text-secondary"><i class="bi bi-arrow-left me-1 fw-bold"></i>Back to Product</a>
                    </div>
                    <div class="page-title">
                        <h6>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="ProductList.php">Product List</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Add Product</li>
                                </ol>
                            </nav>
                        </h6>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <form id="myForm" method="POST" enctype="multipart/form-data" data-parsley-validate>
                            <input type="hidden" name="product_id" value="<?php $editData['id'] ?? ''; ?>">
                            <div class="row">
                                <div class="col-lg-4 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="productName">Product Name <span class="text-danger">*</span></label>
                                        <input type="text" id="productName" name="productname" value="<?php echo htmlspecialchars($editData['product_name'] ?? '') ?>" placeholder="Product name" oninput="validateProductName()" maxlength="150" data-parsley-required data-parsley-required-message="Product name is required">
                                        <small id="productNameError" class="parsley-required" style="display:none;">
                                            Please enter a product name between 3 and 100 characters long.
                                        </small>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for='categorySelector'>Category <span class="text-danger">*</span></label>
                                        <select class="form-select" onchange="loadBrands(this.value)" name="categoryselector" id="categorySelector" data-parsley-required-message="Select category" maxlength="200" data-parsley-required>
                                            <option disabled <?= empty($editData['category']) ? 'selected' : '' ?>>Choose Category</option>
                                            <?php while ($row = mysqli_fetch_assoc($catResult)) { ?>
                                                <option value="<?= $row['id'] ?>"
                                                    <?= ($isEdit && $editData['category'] == $row['id']) ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($row['category']) ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for='brand'>Brand <span class="text-danger">*</span></label>
                                        <select class="form-select" name="brand" id="brandSelect" disabled required>
                                            <option value="">Choose Brand</option>
                                        </select>

                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-4 col-12">
                                    <div class="form-group">
                                        <label for="minQuantity">Min Quantity <span class="text-danger">*</span></label>
                                        <input type="number" class="p-2 rounded col-lg-12 border border-secondary" onkeydown="return event.key !== '-'" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" value="<?php echo htmlspecialchars($editData['minQuantity'] ?? ''); ?>" name="minquantity" id="minQuantity" placeholder="Min Quantity" oninput="validateQuantity()" data-parsley-required data-parsley-error-message="Minimum quantity is required">
                                        <small id="minQuantityError" class="parsley-required" style="display:none;"></small>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-sm-4 col-12">
                                    <div class="form-group">
                                        <label for="quantity">Max Quantity <span class="text-danger">*</span></label>
                                        <input type="number" name="quantity" id="quantity" onkeydown="return event.key !== '-'" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1'); validateQuantity();" class="p-2 rounded col-lg-12 border border-secondary" value="<?php echo htmlspecialchars($editData['quantity'] ?? ''); ?>" placeholder="Max Quantity" data-parsley-required data-parsley-error-message="Maximum quantity is required">
                                        <small id="maxError" class="parsley-required" style="display:none;">Max quantity must be greater than or equal to Min quantity</small>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-sm-4 col-12">
                                    <div class="form-group">
                                        <label for='datepicker'>Expired Date <span class="text-danger">*</span></label>
                                        <!-- <input type="text" class="p-2 rounded col-lg-12 border-secondary border-1 border-secondary border" name="expiredDate" id="datepicker" placeholder="Select expired date" value="<?php echo htmlspecialchars($editData['expired_date'] ?? '') ?>" data-parsley-required-message="Expired date is required" data-parsley-required> -->
                                        <!-- <input type="text" class="form-control" name="expiredDate" id="newdatepicker" placeholder="Select expired date" autocomplete="off" value="<?php echo htmlspecialchars($editData['expired_date'] ?? '') ?>" data-parsley-required data-parsley-required-message="Expired date is required"> -->
                                        <input type="text"
                                            class="form-control"
                                            name="expiredDate"
                                            id="datepicker"
                                            placeholder="Select expired date"
                                            autocomplete="off"
                                            value="<?= htmlspecialchars($editData['expired_date'] ?? '') ?>"
                                            data-parsley-required
                                            data-parsley-required-message="Expired date is required">
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-4 col-12"></div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="productDescription">Product Description</label>
                                        <textarea class="form-control" name="description" maxlength="1000" id="description" placeholder="Product description"><?php echo htmlspecialchars($editData['description'] ?? '') ?></textarea>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="discout">Discount</label>
                                        <div class="input-group has-validation">
                                            <input type="text" name="discount" class="form-control" id="discountInput" oninput="this.value=this.value.replace(/[^0-9]/g,'')" aria-describedby="discountFeedback discount-suffix" placeholder="0 to 100" min="0" maxlength="2" step="1" value="<?= htmlspecialchars($editData['discount'] ?? '') ?>">
                                            <span class="input-group-text" id="discount-suffix">%</span>
                                            <!-- <div id="discountFeedback" class="invalid-feedback"></div> -->
                                        </div>
                                        <!-- <div id="discountError" class="parsley-required"><?php echo $discountError ?? ""; ?></div> -->
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="price">Price per unit <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="number" onkeydown="return event.key !== '-'" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" class="form-control p-2" min="0" max="1000000" step="0.01" value="<?php echo htmlspecialchars($editData['price'] ?? '') ?>" name="price" id="price" placeholder="Price per unit" data-parsley-required-message="Price field is required" data-parsley-max="1000000" data-parsley-max-message="Price cannot exceed 1,000,000" data-parsley-required data-parsley-errors-container="#priceError">
                                            <span class="input-group-text" id="discount-suffix">₹</span>
                                        </div>
                                        <div id="priceError" class="parsley-required"><?php echo $priceError ?? ""; ?></div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="label"> Status</label>
                                        <select class="form-select" name="status" id="label" data-parsley-id="1601" maxlength="200">
                                            <option value="" disabled <?= empty($editData['status']) ? 'selected' : '' ?>>Choose Status</option>
                                            <option value="Active" <?= ($editData['status'] ?? '') == 'Active' ? 'selected' : '' ?>>Active</option>
                                            <option value="Inactive" <?= ($editData['status'] ?? '') == 'Inactive' ? 'selected' : '' ?>>Inactive</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="productImage"> Product Image <span class="text-danger">*</span></label>
                                            <div class="image-upload mb-0">
                                                <input type="file" name="imageBox" id="productImage" accept="image/*" <?= $isEdit ? '' : 'data-parsley-required' ?> maxlength="2000" data-parsley-error-message="Image is required" data-parsley-errors-container="#imageError">
                                                <div class="image-uploads text-center">
                                                    <img id="imagePreview"
                                                        src="<?= !empty($editData['image_path'])
                                                                    ? htmlspecialchars($editData['image_path'])
                                                                    : '/Backend/assets/images/icons/upload.svg' ?>"
                                                        alt="Preview"
                                                        style="max-width: 100%; max-height: 48px; object-fit: contain;">

                                                    <h4 id="imageUploadTitle">
                                                        <?= !empty($editData['image_path'])
                                                            ? htmlspecialchars(basename($editData['image_path']))
                                                            : 'Drag and drop a file to upload' ?>
                                                    </h4>
                                                </div>
                                            </div>
                                            <div id="imageError" class="parsley-required"></div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="existing_image"
                                        value="<?= htmlspecialchars($editData['image_path'] ?? '') ?>">
                                    <div class="col-lg-6 col-sm-12">
                                        <div class="form-group">
                                            <input type="hidden" name="removed_gallery_images" id="removedGalleryImages">
                                            <label>Gallery Images</label>

                                            <input
                                                type="file"
                                                name="gallery_images[]"
                                                id="galleryInput"
                                                accept="image/*"
                                                multiple
                                                hidden>
                                            <small id="galleryError" class="parsley-required" style="display:none;"></small>

                                            <div class="gallery-wrapper">

                                                <div class="upload-box" id="uploadBox"
                                                    onclick="document.getElementById('galleryInput').click()">
                                                    <span>+</span>
                                                    <small id="counterText">Select up to 5 images (5 left)</small>
                                                </div>

                                                <div id="galleryPreview" class="gallery-preview"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12 d-flex justify-content-end">
                                    <button class="btn btn-cancel me-2" type="<?= $productId ? 'button' : 'reset' ?>" name="reset" id="resetButton"><?= $productId ? 'Back' : 'Reset' ?></button>
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
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            const isEditMode = <?= $isEdit ? 'true' : 'false' ?>;
            const existingDate = $('#datepicker').val();

            $("#datepicker").datepicker({
                dateFormat: "yy-mm-dd",
                changeMonth: true,
                changeYear: true,
                showAnim: "fadeIn",
                minDate: isEditMode && existingDate ? null : 0,
                beforeShow: function(input, inst) {
                    setTimeout(() => {
                        $('.ui-datepicker').css('z-index', 9999);
                    }, 0);
                },
                beforeShowDay: function(date) {
                    const today = new Date();
                    today.setHours(0, 0, 0, 0);
                    if (date.getTime() === today.getTime()) {
                        return [true, "ui-state-highlight", "Today"];
                    }
                    return [true, ""];
                },
                onSelect: function(dateText, inst) {
                    $(this).parsley().validate();
                }
            });

            if (isEditMode && existingDate) {
                $('#datepicker').parsley().validate();
            }
        });
    </script>
    <script>
        document.getElementById('productImage').addEventListener('change', function() {
            const file = this.files[0];
            const title = document.getElementById('imageUploadTitle');
            if (file) {
                title.textContent = file.name;
            } else {
                title.textContent = "Drag and drop a file to upload";
            }
        });
        const isEditMode = <?= $isEdit ? 'true' : 'false' ?>;
        document.addEventListener("DOMContentLoaded", function() {
            if (isEditMode) {
                filterBrandsByCategory();
            }
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            if (isEditMode) {
                document.getElementById("brandSelect").disabled = false;
            }
        });

        $('#discountInput').on('input', function() {
            let inputValue = $(this).val();
            let filteredValue = inputValue.replace(/[^0-9]/g, '');
            $(this).val(filteredValue);
        });
        let parsleyForm = $('#myForm').parsley();
        $('#resetButton').on('click', function() {
            parsleyForm.reset();
        });
        document.addEventListener("DOMContentLoaded", function() {

            let toastElement = document.getElementById("actionToast");
            let toastMessage = document.getElementById("toastMessage");

            if (params.get("added") === "1") {
                toastMessage.textContent = "Product added successfully!";
                toastElement.classList.add("text-bg-success", "text-white");
            } else if (params.get("updated") === "1") {
                toastMessage.textContent = "Product updated successfully!";
                toastElement.classList.add("text-bg-success", "text-white");
            } else {
                return;
            }
            let timerBar = toastElement.querySelector(".toast-timer");
            timerBar.style.animation = "none";
            timerBar.offsetHeight;
            timerBar.style.animation = "shrink 5s linear forwards";
            let toast = new bootstrap.Toast(toastElement, {
                delay: 3000
            });
            toast.show();
            setTimeout(() => {
                window.history.replaceState({}, document.title, window.location.pathname);
            }, 3500);
        });

        function filterBrandsByCategory() {
            const categorySelect = document.getElementById("categorySelector");
            const brandSelect = document.getElementById("brandSelect");
            const selectedCategory = categorySelect.value;

            if (!selectedCategory) {
                brandSelect.disabled = true;
                return;
            }
            brandSelect.disabled = false;
            const options = brandSelect.querySelectorAll("option");
            options.forEach(option => {
                if (option.disabled || option.value === "") {
                    option.style.display = "block";
                    return;
                }
                if (option.classList.contains(selectedCategory)) {
                    option.style.display = "block";
                } else {
                    option.style.display = "none";
                }
            });
        }
    </script>
    <script>
        document.getElementById('productImage').addEventListener('change', function() {
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
        $(function() {
            window.Parsley.addValidator('gteMinquantity', {
                validateNumber: function(value) {
                    let minQty = parseInt($('#minQuantity').val(), 10);
                    if (isNaN(minQty)) return true;
                    return value >= minQty;
                },
                messages: {
                    en: 'Max Quantity must be greater than or equal to Min Quantity'
                }
            });

            $('#quantity').on('keydown', function() {
                let parsleyField = $(this).parsley();
                setTimeout(() => {
                    parsleyField.validate();
                }, 0);
            });
            $('#minQuantity').on('keydown', function() {
                setTimeout(() => {
                    $('#quantity').parsley().validate();
                }, 0);
            });
        });
        $('#resetButton').on('click', function() {
            if (<?= $productId ? 'true' : 'false' ?>) {
                window.location.href = "ProductList.php";
            }
        });
    </script>
    <script>
        document.getElementById('resetButton').addEventListener('click', function() {
            const fileInput = document.getElementById('productImage');
            const preview = document.getElementById('imagePreview');
            const title = document.getElementById('imageUploadTitle');

            if (!<?= $isEdit ? 'true' : 'false' ?>) {
                fileInput.value = '';
                preview.src = '/Backend/assets/images/icons/upload.svg';
                title.textContent = 'Drag and drop a file to upload';
            } else {
                preview.src = "<?= htmlspecialchars($editData['image_path'] ?? '/Backend/assets/images/icons/upload.svg') ?>";
                title.textContent = "<?= !empty($editData['image_path'])
                                            ? htmlspecialchars(basename($editData['image_path']))
                                            : 'Drag and drop a file to upload' ?>";
                fileInput.value = '';
            }
        });
    </script>

    <script>
        function validateProductName() {
            const input = document.getElementById('productName');
            const error = document.getElementById('productNameError');

            if (input.value.length > 0 && input.value.length < 3) {
                error.style.display = 'block';
            } else {
                error.style.display = 'none';
            }
        }
    </script>
    <script>
        function validateQuantity() {
            const minInput = document.getElementById('minQuantity');
            const maxInput = document.getElementById('quantity');

            const minError = document.getElementById('minQuantityError');
            const maxError = document.getElementById('maxError');

            const minValue = minInput.value;
            const maxValue = maxInput.value;

            if (minValue === '') {
                minError.style.display = 'block';
            } else {
                minError.style.display = 'none';
            }

            if (maxValue !== '' && minValue !== '' && Number(maxValue) < Number(minValue)) {
                maxError.style.display = 'block';
            } else {
                maxError.style.display = 'none';
            }
        }
    </script>
    <script>
        document.getElementById('price').addEventListener('input', function() {
            let value = this.value;
            if (value < 0) {
                this.value = 0;
                return;
            }
            if (Number(value) > 1000000) {
                this.value = 1000000;
            }
        });
    </script>
    <script>
        document.getElementById('productImage').addEventListener('change', function() {
            const file = this.files[0];
            const preview = document.getElementById('imagePreview');
            const title = document.getElementById('imageUploadTitle');
            const errorBox = document.getElementById('imageError');

            errorBox.style.display = 'none';
            errorBox.innerText = '';

            if (!file) return;

            if (!ALLOWED_TYPES.includes(file.type)) {
                errorBox.innerText = "Only JPG, PNG, and WEBP image formats are allowed.";
                errorBox.style.display = 'block';
                resetImageInput();
                return;
            }

            if (file.size > MAX_IMAGE_SIZE) {
                errorBox.innerText = "Image size must be less than 100KB.";
                errorBox.style.display = 'block';
                resetImageInput();
                return;
            }


            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
            };
            reader.readAsDataURL(file);
            title.textContent = file.name;
        });

        function resetImageInput() {
            const input = document.getElementById('productImage');
            const preview = document.getElementById('imagePreview');
            const title = document.getElementById('imageUploadTitle');

            input.value = '';
            preview.src = '/Backend/assets/images/icons/upload.svg';
            title.textContent = 'Drag and drop a file to upload';
        }
    </script>


    <script>
        document.getElementById('price').addEventListener('input', function() {
            let value = this.value;

            if (parseFloat(value) > 1000000) {
                this.value = 1000000;
            }
        });
        document.getElementById('minQuantity').addEventListener('input', function() {
            let value = this.value;

            if (parseFloat(value) > 1000000) {
                this.value = 1000000;
            }
        });
        document.getElementById('quantity').addEventListener('input', function() {
            let value = this.value;

            if (parseFloat(value) > 1000000) {
                this.value = 1000000;
            }
        });
    </script>
    <script>
        const MAX_IMAGES = 5;
        let selectedFiles = [];
        let removedImages = [];
        let existingCount = 0;

        const input = document.getElementById('galleryInput');
        const preview = document.getElementById('galleryPreview');
        const uploadBox = document.getElementById('uploadBox');
        const counterText = document.getElementById('counterText');

        function addPreview(file = null, imagePath = null) {

            const div = document.createElement('div');
            div.className = 'gallery-item';

            const img = document.createElement('img');
            img.src = imagePath ? imagePath : URL.createObjectURL(file);

            const remove = document.createElement('div');
            remove.className = 'remove-btn';
            remove.innerHTML = '&times;';

            remove.onclick = function() {
                div.remove();

                if (file) {
                    selectedFiles = selectedFiles.filter(f => f !== file);
                    syncFilesToInput();
                }

                if (imagePath) {
                    removedImages.push(imagePath);
                    existingCount--;
                    document.getElementById('removedGalleryImages').value =
                        JSON.stringify(removedImages);
                }

                updateCounter();
            };

            div.appendChild(img);
            div.appendChild(remove);
            preview.appendChild(div);
        }

        input.addEventListener('change', function() {
            const files = Array.from(this.files);

            files.forEach(file => {
                if ((selectedFiles.length + existingCount) >= MAX_IMAGES) return;
                selectedFiles.push(file);
                addPreview(file);
            });

            syncFilesToInput();
            input.value = '';
            updateCounter();
        });

        function updateCounter() {
            const used = selectedFiles.length + existingCount;
            const left = MAX_IMAGES - used;

            counterText.innerText = `Select up to 5 images (${left} left)`;
            uploadBox.style.display = left === 0 ? 'none' : 'flex';
            input.disabled = left === 0;
        }

        function syncFilesToInput() {
            const dataTransfer = new DataTransfer();
            selectedFiles.forEach(file => dataTransfer.items.add(file));
            input.files = dataTransfer.files;
        }

        document.addEventListener("DOMContentLoaded", function() {
            const existingImages = <?= json_encode($existingGalleryImages) ?>;

            existingImages.forEach(img => {
                addPreview(null, img);
                existingCount++;
            });

            updateCounter();
        });
    </script>
    <script>
        const MAX_IMAGE_SIZE = 100 * 5120;
        const ALLOWED_TYPES = ['image/jpeg', 'image/png', 'image/webp', 'image/jpg'];
    </script>
   <script>
function loadBrands(categoryId, selectedBrand = "") {
    const brandSelect = document.getElementById("brandSelect");
    brandSelect.innerHTML = '<option value="">Loading...</option>';
    brandSelect.disabled = true;

    if (!categoryId) return;

    fetch("/Backend/src/Pages/products/get_brands.php?category_id=" + categoryId)
        .then(res => res.json())
        .then(data => {

            brandSelect.innerHTML = '<option value="">Choose Brand</option>';

            let found = false;

            data.forEach(brand => {
                const opt = document.createElement("option");
                opt.value = brand.trim();
                opt.textContent = brand.trim();

                // ✅ SAFE compare
                if (
                    selectedBrand &&
                    brand.trim().toLowerCase() === selectedBrand.trim().toLowerCase()
                ) {
                    opt.selected = true;
                    found = true;
                }

                brandSelect.appendChild(opt);
            });

            // 🛑 If saved brand NOT found
            if (selectedBrand && !found) {
                const opt = document.createElement("option");
                opt.value = selectedBrand;
                opt.textContent = selectedBrand + " (saved)";
                opt.selected = true;
                brandSelect.appendChild(opt);
            }

            brandSelect.disabled = false;
        });
}
</script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const isEditMode = <?= $isEdit ? 'true' : 'false' ?>;

    if (isEditMode) {
        const savedCategory = "<?= $editData['category'] ?? '' ?>";
        const savedBrand = "<?= addslashes($editData['brand_name'] ?? '') ?>";

        if (savedCategory) {
            loadBrands(savedCategory, savedBrand);
        }
    }
});
</script>


</body>

</html>