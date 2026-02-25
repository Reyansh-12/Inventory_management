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

    if (isset($_FILES['gallery_images']) && !empty($_FILES['gallery_images']['name'][0])) {

        $uploadDir = BASE_PATH . "/src/Pages/products/gallery_images/";

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $maxImages = 5;
        $remaining = $maxImages - count($galleryImages);

        if ($remaining > 0) {

            $uploaded = 0;

            foreach ($_FILES['gallery_images']['tmp_name'] as $key => $tmpName) {

                if ($uploaded >= $remaining)
                    break;

                if ($_FILES['gallery_images']['error'][$key] !== 0)
                    continue;

                $ext = strtolower(pathinfo($_FILES['gallery_images']['name'][$key], PATHINFO_EXTENSION));
                $allowed = ['jpg', 'jpeg', 'png', 'webp'];

                if (!in_array($ext, $allowed))
                    continue;

                $fileName = uniqid("gallery_", true) . "." . $ext;
                $targetPath = $uploadDir . $fileName;

                if (move_uploaded_file($tmpName, $targetPath)) {
                    $galleryImages[] = "/Backend/src/Pages/products/gallery_images/" . $fileName;
                    $uploaded++;
                }
            }
        }
    }

    $galleryJson = json_encode($galleryImages);
    $productname = $_POST['productname'] ?? '';
    // $category = $_POST['categoryselector'] ?? '';
    $brandName = $_POST['brand'] ?? '';
    $minquantity = $_POST['minquantity'] ?? 0;
    $quantity = $_POST['quantity'] ?? 0;
    $description = $_POST['description'] ?? '';
    $discount = $_POST['discount'] ?? 0;
    $price = $_POST['price'] ?? 0;
    $status = $_POST['status'] ?? '';
    $expiredDate = $_POST['expiredDate'] ?? '';

    $categoryId = $_POST['categoryselector'] ?? '';
    $category = '';

    if ($categoryId) {
        $stmtCat = mysqli_prepare($con, "SELECT category FROM category WHERE id = ?");
        mysqli_stmt_bind_param($stmtCat, "i", $categoryId);
        mysqli_stmt_execute($stmtCat);
        mysqli_stmt_bind_result($stmtCat, $category);
        mysqli_stmt_fetch($stmtCat);
        mysqli_stmt_close($stmtCat);
    }


    $imagePath = $_POST['existing_image'] ?? '';
    // Ye part aapke PHP code mein already hai, bas path verify karein
    if (isset($_FILES['imageBox']) && $_FILES['imageBox']['error'] === 0) {
        $uploadDir = BASE_PATH . "/src/uploads/products/featured/"; // Server path
        if (!is_dir($uploadDir))
            mkdir($uploadDir, 0755, true);

        $ext = strtolower(pathinfo($_FILES['imageBox']['name'], PATHINFO_EXTENSION));
        $fileName = uniqid('product_', true) . '.' . $ext;
        $targetPath = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['imageBox']['tmp_name'], $targetPath)) {
            // DB mein save hone wala path
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
$catResult = mysqli_query($con, "SELECT id, category, brands FROM category WHERE status='Active'");
if (isset($_FILES['imageBox']) && $_FILES['imageBox']['error'] === 0) {

    $minSize = 100 * 1024;       // 100 KB
    $maxSize = 5 * 1024 * 1024;  // 5 MB

    $allowedExt = ['jpg', 'jpeg', 'png', 'webp', 'svg'];
    $allowedMime = [
        'image/jpeg',
        'image/png',
        'image/webp',
        'image/svg+xml'
    ];

    $fileSize = $_FILES['imageBox']['size'];
    $ext = strtolower(pathinfo($_FILES['imageBox']['name'], PATHINFO_EXTENSION));
    $mime = mime_content_type($_FILES['imageBox']['tmp_name']);

    if (!in_array($ext, $allowedExt) || !in_array($mime, $allowedMime)) {
        die("Invalid image type. Allowed: PNG, JPG, JPEG, WEBP, SVG");
    }

    if ($fileSize < $minSize) {
        die("Image must be at least 100 KB");
    }

    if ($fileSize > $maxSize) {
        die("Image must not exceed 5 MB");
    }

    $uploadDir = BASE_PATH . "/src/uploads/products/featured/";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $fileName = uniqid('product_', true) . '.' . $ext;
    $targetPath = $uploadDir . $fileName;

    if (move_uploaded_file($_FILES['imageBox']['tmp_name'], $targetPath)) {
        $imagePath = '/Backend/src/uploads/products/featured/' . $fileName;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="description" content="POS - Bootstrap Admin Template">
    <meta name="keywords"
        content="admin, estimates, bootstrap, business, corporate, creative, management, minimal, modern,  html5, responsive">
    <meta name="author" content="Dreamguys - Bootstrap Admin Template">
    <meta name="robots" content="noindex, nofollow">
    <title>Dreams Pos admin template</title>
    <link rel="stylesheet" href="/Backend/src/assets/css/addProductForm.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" />
    <style>
        /* --- Add Product Professional Overhaul --- */
        :root {
            --primary-blue: #6792ff;
            --border-color: #e2e8f0;
            --bg-light: #f8fafc;
        }

        .card {
            border: none !important;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            border-radius: 16px !important;
        }

        /* Form Controls */
        .form-group label {
            font-weight: 600;
            color: #4a5568;
            font-size: 0.85rem;
            margin-bottom: 8px;
        }

        .form-control,
        .form-select,
        input[type="number"],
        input[type="text"] {
            border: 1px solid var(--border-color) !important;
            border-radius: 8px !important;
            padding: 10px 14px !important;
            font-size: 0.9rem !important;
            transition: all 0.2s ease;
            background-color: var(--bg-light);
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--primary-blue) !important;
            background-color: #fff;
            box-shadow: 0 0 0 3px rgba(103, 146, 255, 0.1) !important;
            outline: none;
        }

        /* Image Upload Zone Polish */
        .image-upload {
            border: 2px dashed #cbd5e0;
            border-radius: 12px;
            padding: 25px;
            background: var(--bg-light);
            transition: all 0.3s;
            cursor: pointer;
        }

        .image-upload:hover {
            border-color: var(--primary-blue);
            background: #fff;
        }

        #imagePreview {
            max-height: 80px !important;
            margin-bottom: 10px;
            border-radius: 6px;
        }

        /* Gallery Wrapper Styling */
        .gallery-wrapper {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            margin-top: 10px;
        }

        .upload-box {
            width: 80px;
            height: 80px;
            border: 1px dashed var(--primary-blue);
            background: rgba(103, 146, 255, 0.05);
            border-radius: 10px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: var(--primary-blue);
        }

        /* Button Styling */
        .btn-submit {
            background: var(--primary-blue) !important;
            border: none;
            padding: 10px 30px;
            font-weight: 700;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(103, 146, 255, 0.25);
        }

        .btn-cancel {
            background: #f1f5f9 !important;
            color: #64748b !important;
            border: none;
            padding: 10px 25px;
            font-weight: 600;
            border-radius: 8px;
        }

        /* --- Professional Datepicker Overhaul --- */
        .ui-datepicker {
            background: #ffffff !important;
            border: 1px solid #e2e8f0 !important;
            border-radius: 12px !important;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1) !important;
            padding: 10px !important;
            font-family: 'Inter', sans-serif !important;
            width: 280px !important;
        }

        .ui-datepicker-header {
            background: transparent !important;
            border: none !important;
            color: #2d3748 !important;
            padding: 10px 0 !important;
        }

        /* Next/Prev Buttons */
        .ui-datepicker-prev,
        .ui-datepicker-next {
            cursor: pointer !important;
            top: 12px !important;
            border-radius: 50% !important;
            background: #f7fafc !important;
        }

        .ui-datepicker-prev:hover,
        .ui-datepicker-next:hover {
            background: #edf2f9 !important;
        }

        /* Days Header */
        .ui-datepicker th {
            color: #a0aec0 !important;
            font-size: 0.75rem !important;
            font-weight: 600 !important;
            text-transform: uppercase !important;
            padding: 10px 0 !important;
        }

        /* Day Cells */
        .ui-datepicker td span,
        .ui-datepicker td a {
            text-align: center !important;
            padding: 8px !important;
            border-radius: 8px !important;
            border: none !important;
            color: #4a5568 !important;
            font-size: 0.85rem !important;
            transition: all 0.2s ease !important;
        }

        /* Hover & Active States */
        .ui-datepicker td a:hover {
            background: rgba(103, 146, 255, 0.1) !important;
            color: #6792ff !important;
        }

        .ui-datepicker-current-day a {
            background: #6792ff !important;
            color: #ffffff !important;
            box-shadow: 0 4px 10px rgba(103, 146, 255, 0.3) !important;
        }

        /* Highlight Today */
        .ui-state-highlight {
            background: transparent !important;
            border: 1px solid #6792ff !important;
            color: #6792ff !important;
        }

        #imagePreview {
            max-width: 100%;
            max-height: 150px;
            /* Aap height apne hisaab se set kar sakte hain */
            object-fit: contain;
            border-radius: 8px;
            margin-bottom: 10px;
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
                    <div class="page-title">
                        <h4 class="fw-bold"><?= $isEdit ? 'Update Cosmetic Product' : 'Add New Cosmetic' ?></h4>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="ProductList.php" class="text-primary">Inventory</a>
                                </li>
                                <li class="breadcrumb-item active"><?= $isEdit ? 'Edit Mode' : 'New Entry' ?></li>
                            </ol>
                        </nav>
                    </div>
                    <div class="page-btn">
                        <a href="ProductList.php" class="btn btn-sm btn-outline-secondary rounded-pill">
                            <i class="bi bi-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <form id="myForm" method="POST" enctype="multipart/form-data" data-parsley-validate>
                            <input type="hidden" name="product_id" value="<?= htmlspecialchars($productId ?? '') ?>">
                            <input type="hidden" name="existing_image"
                                value="<?= htmlspecialchars($editData[' image_path'] ?? '') ?>">
                            <input type="hidden" name="removed_gallery_images" id="removedGalleryImages" value="[]">
                            <div class="row">
                                <div class="col-lg-8 border-end pr-lg-4">
                                    <h5 class="mb-4 text-primary fs-6">Basic Information</h5>
                                    <div class="row">
                                        <div class="col-lg-12 col-sm-12 col-12 mb-3">
                                            <div class="form-group">
                                                <label>Product Name <span class="text-danger">*</span></label>
                                                <input type="text" name="productname" class="form-control" oninput="this.value = this.value.replace(/[^a-zA-Z\s.&-]/g, '')"
                                                    value="<?= htmlspecialchars($editData['product_name'] ?? '') ?>"
                                                    placeholder="e.g. Cerave Hydrating Cleanser" data-parsley-required
                                                    data-parsley-error-message="Enter product name.">
                                            </div>
                                        </div>
                                        <div class="col-lg-6 mb-3">
                                            <div class="form-group">
                                                <label>Category <span class="text-danger">*</span></label>
                                                <select class="form-select" name="categoryselector" id="categorySelect"
                                                    onchange="loadBrands(this.value)" data-parsley-required="true"
                                                    data-parsley-error-message="Select a category.">
                                                    <option value="">Select Category</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 mb-3">
                                            <div class="form-group">
                                                <label>Brand <span class="text-danger">*</span></label>
                                                <select class="form-select" name="brand" id="brandSelect" disabled>
                                                    <option value="">Select Brand</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 mb-3">
                                            <div class="form-group">
                                                <label>Product Description</label>
                                                <textarea class="form-control" name="description" rows="4" oninput="this.value = this.value.replace(/[^a-zA-Z\s.&-]/g, '')"
                                                    placeholder="Brief details about the product..."><?= htmlspecialchars($editData['description'] ?? '') ?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-4 pl-lg-4">
                                    <h5 class="mb-4 text-primary fs-6">Pricing & Stock</h5>
                                    <div class="form-group mb-3">
                                        <label>Price per Unit (₹) <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white border-end-0">₹</span>
                                            <input type="number" name="price" class="form-control border-start-0"
                                                value="<?= htmlspecialchars($editData['price'] ?? '') ?>"
                                                placeholder="0.00" data-parsley-required="true" min="1" step="0.01"
                                                data-parsley-min="1"
                                                data-parsley-min-message="Price must be greater than 0"
                                                data-parsley-required-message="Price field required"
                                                data-parsley-type="number" data-parsley-errors-container="#priceError">
                                        </div>
                                        <div id="priceError" class="text-danger small mt-1"></div>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label>Discount (%)</label>
                                        <div class="input-group">
                                            <input type="number" name="discount" class="form-control border-end-0"
                                                value="<?= htmlspecialchars($editData['discount'] ?? '0') ?>"
                                                placeholder="0" min="0" max="100" data-parsley-range="[0, 100]"
                                                data-parsley-range-message="Discount must be between 0 and 100"
                                                data-parsley-type="number">
                                            <span class="input-group-text bg-white border-start-0">%</span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group mb-3">
                                                <label>Stock Qty <span class="text-danger">*</span></label>
                                                <input type="number" name="quantity" class="form-control"
                                                    value="<?= htmlspecialchars($editData['quantity'] ?? '') ?>" min="1"
                                                    data-parsley-required="true" data-parsley-min="1"
                                                    data-parsley-error-message="Enter stock quantity."
                                                    data-parsley-min-message="Stock quantity must be at least 1."
                                                    data-parsley-type="integer">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group mb-3">
                                                <label>Min Qty <span class="text-danger">*</span></label>
                                                <input type="number" name="minquantity"
                                                    class="form-control border-start-0"
                                                    value="<?= htmlspecialchars($editData['minQuantity'] ?? '') ?>"
                                                    min="1" data-parsley-required="true" data-parsley-min="1"
                                                    data-parsley-error-message="Enter minimum quantity."
                                                    data-parsley-min-message="Minimum quantity must be at least 1."
                                                    data-parsley-type="integer">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label>Status</label>
                                        <select class="form-select" name="status">
                                            <option value="Active" <?= (isset($editData['status']) && $editData['status'] == 'Active') ? 'selected' : '' ?>>Active</option>
                                            <option value="Inactive" <?= (isset($editData['status']) && $editData['status'] == 'Inactive') ? 'selected' : '' ?>>Inactive</option>
                                        </select>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label>Expiry Date <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white border-end-0"><i
                                                    class="bi bi-calendar3"></i></span>
                                            <input type="text" name="expiredDate" id="datepicker"
                                                class="form-control border-start-0"
                                                value="<?= htmlspecialchars($editData['expired_date'] ?? '') ?>"
                                                placeholder="YYYY-MM-DD" readonly
                                                style="cursor: pointer; background-color: #f8fafc;"
                                                data-parsley-required="true" data-parsley-errors-container="#dateError"
                                                data-parsley-error-message="Select expiry date.">
                                        </div>
                                        <div id="dateError" class="text-danger small mt-1"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-4 pt-4 border-top">
                                <div class="col-lg-12 mb-3">
                                    <h5 class="text-primary fs-6">Product Media</h5>
                                </div>
                                <div class="col-lg-6">
                                    <label class="form-label d-block">
                                        Main Featured Image <span class="text-danger">*</span>
                                    </label>

                                    <div class="image-upload" onclick="document.getElementById('productImage').click()"
                                        data-parsley-errors-container="#imageError">

                                        <input type="file" name="imageBox" id="productImage" hidden
                                            data-parsley-required="true" data-parsley-errors-container="#imageError"
                                            data-parsley-required-message="Featured image is required">

                                        <div class="text-center">
                                            <img id="imagePreview"
                                                src="<?= !empty($editData['image_path']) ? $editData['image_path'] : '/Backend/assets/images/icons/upload.svg' ?>">
                                            <p class="text-muted small" id="imageUploadTitle">
                                                Click to browse or drag and drop
                                            </p>
                                        </div>
                                    </div>
                                    <small class="text-muted d-block mt-1">
                                        Allowed: PNG, JPG, JPEG, WEBP, SVG · Size: 100 KB – 5 MB
                                    </small>
                                    <div id="imageError" class="text-danger small mt-1"></div>
                                </div>
                                <div class="col-lg-6">
                                    <label class="form-label d-block">Gallery (Up to 5 images)</label>
                                    <div class="gallery-wrapper">
                                        <div class="upload-box"
                                            onclick="document.getElementById('galleryInput').click()">
                                            <i class="bi bi-plus-lg fs-4"></i>
                                            <span style="font-size: 10px;">ADD MORE</span>
                                        </div>
                                        <input type="file" id="galleryInput" name="gallery_images[]" multiple
                                            accept="image/png,image/jpeg,image/webp,image/svg+xml" hidden>
                                        <div id="galleryPreview" class="gallery-preview d-flex gap-2"></div>
                                        <div id="galleryError" class="text-danger small mt-1"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4 pt-4 d-flex justify-content-end gap-2">
                                <button type="button" class="btn btn-cancel" id="resetButton">Cancel</button>
                                <button type="submit" name="submit" class="btn btn-submit">Save Product</button>
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
            <div class="toast-timer"
                style="height: 4px; background: rgba(0,0,0,0.2); animation: shrink 3s linear forwards;"></div>
        </div>
    </div>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/Backend/src/assets/js/products/addProductForm.js"></script>
    <script>
        $(document).ready(function () {
            const isEditMode = <?= $isEdit ? 'true' : 'false' ?>;
            const existingDate = $('#datepicker').val();

            $("#datepicker").datepicker({
                dateFormat: "yy-mm-dd",
                changeMonth: true,
                changeYear: true,
                showAnim: "fadeIn",
                minDate: isEditMode && existingDate ? null : 0,
                beforeShow: function (input, inst) {
                    setTimeout(() => {
                        $('.ui-datepicker').css('z-index', 9999);
                    }, 0);
                },
                beforeShowDay: function (date) {
                    const today = new Date();
                    today.setHours(0, 0, 0, 0);
                    if (date.getTime() === today.getTime()) {
                        return [true, "ui-state-highlight", "Today"];
                    }
                    return [true, ""];
                },
                onSelect: function (dateText, inst) {
                    $(this).parsley().validate();
                }
            });

            if (isEditMode && existingDate) {
                $('#datepicker').parsley().validate();
            }
        });
        const isEditMode = <?= $isEdit ? 'true' : 'false' ?>;
        document.addEventListener("DOMContentLoaded", function () {
            if (isEditMode) {
                filterBrandsByCategory();
            }
        });
        $('#resetButton').on('click', function () {
            if (<?= $productId ? 'true' : 'false' ?>) {
                window.location.href = "ProductList.php";
            }
        });

        document.getElementById('resetButton').addEventListener('click', function () {
            const fileInput = document.getElementById('productImage');
            const preview = document.getElementById('imagePreview');
            const title = document.getElementById('imageUploadTitle');

            if (!<?= $isEdit ? 'true' : 'false' ?>) {
                fileInput.value = '';
                preview.src = '/Backend/assets/images/icons/upload.svg';
                title.textContent = 'Drag and drop a file to upload';
            } else {
                preview.src =
                    "<?= htmlspecialchars($editData['image_path'] ?? '/Backend/assets/images/icons/upload.svg') ?>";
                title.textContent = "<?= !empty($editData['image_path'])
                    ? htmlspecialchars(basename($editData['image_path']))
                    : 'Drag and drop a file to upload' ?>";
                fileInput.value = '';
            }
        });

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
    <script>
        const allCategories = [
            <?php
            mysqli_data_seek($catResult, 0);
            while ($cat = mysqli_fetch_assoc($catResult)) {
                echo "{
            id: '{$cat['id']}', 
            name: '" . addslashes($cat['category']) . "', 
            brands: '" . addslashes($cat['brands'] ?? '') . "'
        },";
            }
            ?>
        ];

        document.addEventListener("DOMContentLoaded", function () {
            const categorySelect = document.getElementById('categorySelect');
            const isEdit = <?= $isEdit ? 'true' : 'false' ?>;
            const savedCategoryName = "<?= $editData['category'] ?? '' ?>";

            categorySelect.innerHTML = '<option value="">Select Category</option>';
            allCategories.forEach(cat => {
                let option = document.createElement('option');
                option.value = cat.id;
                option.textContent = cat.name;

                if (isEdit && cat.name === savedCategoryName) {
                    option.selected = true;
                }
                categorySelect.appendChild(option);
            });

            if (categorySelect.value) {
                loadBrands(categorySelect.value, "<?= addslashes($editData['brand_name'] ?? '') ?>");
            }
        });



    </script>
    <script>
        document.addEventListener('input', function (e) {
            if (e.target.type === 'number') {
                if (e.target.value < 0) {
                    e.target.value = '';
                }
            }
        });
    </script>
    <script>
        document.addEventListener('keydown', function (e) {
            if (e.target.type === 'number') {
                if (['-', '+', 'e', 'E'].includes(e.key)) {
                    e.preventDefault();
                }
            }
        });

        document.addEventListener('input', function (e) {
            if (e.target.type === 'number') {
                if (e.target.value < 0) {
                    e.target.value = '';
                }
            }
        });
    </script>
    <script>
        document.getElementById('productImage').addEventListener('change', function () {
            $(this).parsley().validate();
        });
    </script>
    <script>
        function loadBrands(categoryId, selectedBrand = "") {
            const brandSelect = document.getElementById('brandSelect');

            brandSelect.innerHTML = '<option value="">Select Brand</option>';
            brandSelect.disabled = true;

            // Remove validation when disabled
            $(brandSelect).parsley().removeConstraint('required');

            if (!categoryId) return;

            const selectedCat = allCategories.find(c => c.id == categoryId);
            if (!selectedCat || !selectedCat.brands) return;

            brandSelect.disabled = false;

            $(brandSelect).parsley().addConstraint('required', true);

            selectedCat.brands.split(',').forEach(brand => {
                brand = brand.trim();
                if (!brand) return;

                let opt = document.createElement('option');
                opt.value = brand;
                opt.textContent = brand;

                if (brand === selectedBrand) opt.selected = true;
                brandSelect.appendChild(opt);
            });
        }
    </script>
    <script>
        document.getElementById('productImage').addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (!file) return;

            const minSize = 100 * 1024;       // 100 KB
            const maxSize = 5 * 1024 * 1024;  // 5 MB

            const allowedTypes = [
                'image/jpeg',
                'image/png',
                'image/webp',
                'image/svg+xml'
            ];

            const preview = document.getElementById('imagePreview');
            const title = document.getElementById('imageUploadTitle');
            const errorBox = document.getElementById('imageError');

            errorBox.innerHTML = '';

            // ❌ File type validation
            if (!allowedTypes.includes(file.type)) {
                errorBox.innerHTML = 'Only PNG, JPG, JPEG, WEBP or SVG images are allowed';
                e.target.value = '';
                preview.src = '/Backend/assets/images/icons/upload.svg';
                title.textContent = 'Click to browse or drag and drop';
                return;
            }

            // ❌ File size validation
            if (file.size < minSize) {
                errorBox.innerHTML = 'Image size must be at least 100 KB';
                e.target.value = '';
                return;
            }

            if (file.size > maxSize) {
                errorBox.innerHTML = 'Image size must not exceed 5 MB';
                e.target.value = '';
                return;
            }

            // ✅ Preview image
            const reader = new FileReader();
            reader.onload = function (event) {
                preview.src = event.target.result;
                title.textContent = file.name;
            };
            reader.readAsDataURL(file);

            // ✅ Re-validate parsley
            $(this).parsley().validate();
        });
    </script>
    <script>
        let existingCount = <?= count($existingGalleryImages) ?>;
        const maxImages = 5;

        document.getElementById('galleryInput').addEventListener('change', function (e) {
            const files = Array.from(e.target.files);
            const errorBox = document.getElementById('galleryError');

            const minSize = 100 * 1024;       // 100 KB
            const maxSize = 5 * 1024 * 1024;  // 5 MB

            const allowedTypes = [
                'image/jpeg',
                'image/png',
                'image/webp',
                'image/svg+xml'
            ];

            errorBox.innerHTML = '';

            // ❌ Max limit
            if (existingCount + files.length > maxImages) {
                errorBox.innerHTML = `You can upload maximum ${maxImages} images`;
                this.value = '';
                return;
            }

            for (let file of files) {

                if (!allowedTypes.includes(file.type)) {
                    errorBox.innerHTML = 'Only PNG, JPG, JPEG, WEBP or SVG images are allowed';
                    this.value = '';
                    return;
                }

                if (file.size < minSize) {
                    errorBox.innerHTML = 'Each image must be at least 100 KB';
                    this.value = '';
                    return;
                }

                if (file.size > maxSize) {
                    errorBox.innerHTML = 'Each image must not exceed 5 MB';
                    this.value = '';
                    return;
                }
            }

            // ✅ Preview
            files.forEach(file => {
                const reader = new FileReader();
                reader.onload = function (ev) {
                    addPreview(file, ev.target.result);
                    existingCount++;
                };
                reader.readAsDataURL(file);
            });

            this.value = '';
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const existingImages = <?= json_encode($existingGalleryImages) ?>;
            existingImages.forEach(img => {
                addPreview(null, img, true);
            });
        });
    </script>
    
</body>

</html>