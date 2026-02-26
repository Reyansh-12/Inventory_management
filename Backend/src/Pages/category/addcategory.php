<?php
session_start();
define("BASE_PATH", dirname(__DIR__, 3));
include BASE_PATH . "/src/Layouts/Links.php";
include BASE_PATH . "/src/controllers/dbConnection.php";
$editData = [];
$categoryId = null;

if (isset($_GET['categoryId'])) {
    $categoryId = intval($_GET['categoryId']);

    $stmt = $con->prepare("SELECT * FROM category WHERE id = ?");
    $stmt->bind_param("i", $categoryId);
    $stmt->execute();

    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $editData = $result->fetch_assoc();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $categoryName = trim($_POST['categoryname']);

    $brandArray = $_POST['brandname'] ?? [];
    if (is_array($brandArray)) {
        $brandName = implode(', ', $brandArray);
    } else {
        $brandName = trim($brandArray);
    }
    $brandName = mysqli_real_escape_string($con, $brandName);

    $status = $_POST['status'] ?? 'Active';
    $createdOn = date('Y-m-d H:i:s');
    $imagePath = null;

    if (!empty($_FILES['uploadImage']['name'])) {
        $uploadDir = BASE_PATH . "/src/Pages/category/categoryImages/";
        if (!is_dir($uploadDir))
            mkdir($uploadDir, 0777, true);

        $extension = pathinfo($_FILES['uploadImage']['name'], PATHINFO_EXTENSION);
        $newName = 'cat_' . time() . '_' . rand(100, 999) . '.' . $extension;

        if (move_uploaded_file($_FILES['uploadImage']['tmp_name'], $uploadDir . $newName)) {
            $imagePath = "/Backend/src/Pages/category/categoryImages/" . $newName;
        }
    }

    if ($categoryId) {
        $imgSql = $imagePath ? ", image_path = '$imagePath'" : "";
        $sql = "UPDATE category SET category = '$categoryName', brands = '$brandName', status = '$status' $imgSql WHERE id = $categoryId";
        mysqli_query($con, $sql);
        header("Location: /Backend/src/Pages/category.php?updated=1");
        exit;
    } else {
        $sql = "INSERT INTO category (category, brands, status, image_path, created_on)
        VALUES ( '$categoryName', '$brandName', '$status', " . ($imagePath ? "'$imagePath'" : "NULL") . ", '$createdOn')";
        mysqli_query($con, $sql);
        header("Location: /Backend/src/Pages/category.php?added=1");
        exit;
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-container--default .select2-selection--multiple {
            border: 1px solid var(--border-color) !important;
            border-radius: 8px !important;
            padding: 5px !important;
            background-color: var(--bg-light);
        }

        .select2-container--default.select2-container--focus .select2-selection--multiple {
            border-color: var(--primary-blue) !important;
        }

        .select2-selection__choice {
            background-color: var(--primary-blue) !important;
            color: white !important;
            border: none !important;
            border-radius: 4px !important;
            padding: 2px 8px !important;
        }

        .select2-selection__choice__remove {
            color: white !important;
            margin-right: 5px !important;
        }
    </style>
    <style>
        .parsley-required,
        .parsley-type,
        .parsley-minlength {
            color: orangered;
        }

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

        .form-group label {
            font-weight: 600;
            color: #4a5568;
            font-size: 0.85rem;
            margin-bottom: 8px;
            display: block;
        }

        .form-control,
        .form-select {
            border: 1px solid var(--border-color) !important;
            border-radius: 8px !important;
            padding: 10px 14px !important;
            font-size: 0.9rem !important;
            background-color: var(--bg-light);
            transition: all 0.2s ease;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--primary-blue) !important;
            background-color: #fff;
            box-shadow: 0 0 0 3px rgba(103, 146, 255, 0.1) !important;
            outline: none;
        }

        .image-upload-new {
            border: 2px dashed #cbd5e0;
            border-radius: 12px;
            padding: 30px;
            background: var(--bg-light);
            transition: all 0.3s;
            cursor: pointer;
            text-align: center;
        }

        .image-upload-new:hover {
            border-color: var(--primary-blue);
            background: #fff;
        }

        #previewImg {
            max-height: 80px !important;
            margin-bottom: 12px;
            border-radius: 8px;
            object-fit: contain;
        }

        .btn-submit {
            background: var(--primary-blue) !important;
            border: none;
            color: white !important;
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

        .parsley-errors-list {
            list-style: none;
            padding: 0;
            margin: 4px 0 0 0;
            font-size: 0.75rem;
            color: #e53e3e;
        }

        .select2-selection__choice {
            background: linear-gradient(135deg, #6792ff, #4f46e5) !important;
            color: #fff !important;
            font-weight: 600;
            border-radius: 20px !important;
            padding: 4px 12px !important;
            margin-top: 6px !important;
        }

        .select2-selection__choice__remove {
            color: #fff !important;
            margin-right: 6px !important;
            font-weight: bold;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice__display{
            padding-left: 12px !important;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove{
            left: 2px !important;
            top: 4px !important;
            background: none !important;
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
                        <h4 class="fw-bold"><?= $categoryId ? 'Edit Category' : 'Create New Category' ?></h4>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb" style="font-size: 0.85rem;">
                                <li class="breadcrumb-item"><a href="/Backend/src/Pages/category.php"
                                        class="text-primary">Inventory</a></li>
                                <li class="breadcrumb-item active">
                                    <?= $categoryId ? 'Modify Category' : 'Add Category' ?>
                                </li>
                            </ol>
                        </nav>
                    </div>
                    <div class="page-btn">
                        <a href="/Backend/src/Pages/category.php" class="btn btn-sm btn-outline-secondary rounded-pill">
                            <i class="bi bi-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <form id="myForm" method="POST" enctype="multipart/form-data" data-parsley-validate>
                            <div class="row g-4">
                                <div class="col-lg-7">
                                    <h5 class="mb-4 text-primary fs-6">Category Details</h5>
                                    <div class="form-group mb-4">
                                        <label for="categoryName">Category Name <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="categoryName" name="categoryname"
                                            value="<?= htmlspecialchars($editData['category'] ?? '') ?>"
                                            oninput="this.value = this.value.replace(/[^a-zA-Z\s]/g, '')"
                                            placeholder="e.g. Skincare, Makeup" data-parsley-required="true"
                                            data-parsley-trigger="change"
                                            data-parsley-error-message="Category name is required.">
                                    </div>

                                    <div class="form-group mb-4">
                                        <label for="brandName">Associated Brands <span
                                                class="text-danger">*</span></label>
                                        <select class="form-control select2-tags" id="brandName" name="brandname[]"
                                            multiple="multiple" data-parsley-required="true"
                                            data-parsley-trigger="change"
                                            data-parsley-error-message="Please select at least one brand."
                                            data-parsley-errors-container="#brandError">
                                            <?php
                                            if (!empty($editData['brands'])) {
                                                // Database se comma separated brands ko array mein badalna
                                                $brands = explode(',', $editData['brands']);
                                                foreach ($brands as $brand) {
                                                    $brand = trim($brand);
                                                    echo "<option value='$brand' selected>$brand</option>";
                                                }
                                            }
                                            ?>
                                        </select>
                                        <small class="text-muted" style="font-size: 0.7rem;">write and press enter
                                            (e.g. L'Oreal, Lakme)</small>
                                        <div id="brandError"></div>
                                    </div>
                                </div>

                                <div class="col-lg-5">
                                    <h5 class="mb-4 text-primary fs-6">Configuration</h5>
                                    <div class="form-group mb-4">
                                        <label for="label">Status</label>
                                        <select class="form-select" name="status" id="label">
                                            <option value="Active" <?= ($editData['status'] ?? '') == 'Active' ? 'selected' : '' ?>>Active</option>
                                            <option value="Inactive" <?= ($editData['status'] ?? '') == 'Inactive' ? 'selected' : '' ?>>Inactive</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label>Category Thumbnail <span class="text-danger">*</span></label>
                                        <div class="image-upload-new"
                                            style="display: flex; justify-content: center; align-items: center;"
                                            onclick="document.getElementById('uploadImage').click()">
                                            <input type="file" id="uploadImage" name="uploadImage" accept="image/*"
                                                hidden data-parsley-required="true"
                                                data-parsley-error-message="Image field required"
                                                data-parsley-errors-container="#imageError">
                                            <div id="uploadBox">
                                                <img src="<?= !empty($editData['image_path']) ? htmlspecialchars($editData['image_path']) : '/Backend/src/assets/images/icons/upload.svg' ?>"
                                                    id="previewImg" alt="img">
                                                <h4 id="fileName" class="text-muted fs-6 fw-normal">
                                                    <?= !empty($editData['image_path']) ? 'Click to change image' : 'Drag & drop category icon' ?>
                                                </h4>
                                            </div>
                                        </div>
                                        <div id="imageError"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-12 d-flex justify-content-end gap-2 mt-5 pt-3 border-top">
                                <button class="btn btn-cancel" type="button"
                                    onclick="window.location.href='/Backend/src/Pages/category.php'">Cancel</button>
                                <button class="btn btn-submit" type="submit">
                                    <?= $categoryId ? 'Update Category' : 'Save Category' ?>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="toast position-fixed top-0 end-0 m-3 text-white" style="z-index: 999" id="actionToast" role="alert">
        <div class="d-flex">
            <div class="toast-body" id="toastMessage"></div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
    <script>
const imageInput = document.getElementById('uploadImage');
const imageError = document.getElementById('imageError');
const fileNameEl = document.getElementById('fileName');
const previewImg = document.getElementById('previewImg');

imageInput.addEventListener('change', function () {
    const file = this.files[0];

    // Clear previous error
    imageError.innerHTML = '';

    if (!file) return;

    // ✅ Allowed file types
    const allowedTypes = [
        'image/jpeg',
        'image/jpg',
        'image/png',
        'image/webp',
        'image/svg+xml'
    ];

    // ✅ Size limits
    const minSize = 100 * 1024;       // 100 KB
    const maxSize = 5 * 1024 * 1024;  // 5 MB

    // ❌ Type validation
    if (!allowedTypes.includes(file.type)) {
        showImageError('Only JPG, JPEG, PNG, WEBP, SVG files are allowed');
        resetImage();
        return;
    }

    // ❌ Minimum size validation
    if (file.size < minSize) {
        showImageError('Image size must be at least 100 KB');
        resetImage();
        return;
    }

    // ❌ Maximum size validation
    if (file.size > maxSize) {
        showImageError('Image size must not exceed 5 MB');
        resetImage();
        return;
    }

    // ✅ Valid image
    fileNameEl.innerText = file.name;

    const reader = new FileReader();
    reader.onload = function (e) {
        previewImg.src = e.target.result;
    };
    reader.readAsDataURL(file);
});

function showImageError(message) {
    imageError.innerHTML = `<span class="text-danger" style="font-size:12px;">${message}</span>`;
}

function resetImage() {
    imageInput.value = '';
    fileNameEl.innerText = 'Drag & drop category icon';
    previewImg.src = '/Backend/src/assets/images/icons/upload.svg';
}
</script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function () {
            $('.select2-tags').select2({
                tags: true, // Naye tags allow karne ke liye
                tokenSeparators: [',', ' '], // Comma ya Space dabane par tag ban jaye
                placeholder: "Select or type brands"
            });
        });

        // Aapka purana Image preview script yahan rahega...
    </script>

</body>

</html>