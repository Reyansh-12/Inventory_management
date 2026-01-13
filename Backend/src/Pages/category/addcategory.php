<?php
session_start();
define("BASE_PATH", dirname(__DIR__, 3));
include BASE_PATH . "/src/Layouts/Links.php";
include BASE_PATH . "/src/controllers/dbConnection.php";
$editData = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $categoryName = trim($_POST['categoryname']);
    $brandName    = trim($_POST['brandname']);
    $status       = $_POST['status'] ?? 'Active';
    $createdOn    = date('Y-m-d H:i:s');
    $imagePath    = null;

    if (!empty($_FILES['uploadImage']['name'])) {

        $uploadDir = BASE_PATH . "/src/Pages/category/categoryImages/";
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

        $extension = pathinfo($_FILES['uploadImage']['name'], PATHINFO_EXTENSION);
        $newName   = 'cat_' . time() . '_' . rand(100, 999) . '.' . $extension;

        if (move_uploaded_file($_FILES['uploadImage']['tmp_name'], $uploadDir . $newName)) {
            $imagePath = "/Backend/src/Pages/category/categoryImages/" . $newName;
        }
    }

    if (empty($categoryName) || empty($brandName)) {
        die("Category aur Brand required hai");
    }

    if (isset($_GET['categoryId'])) {

        $id = intval($_GET['categoryId']);

        $sql = "
        UPDATE category SET
            category = '$categoryName',
            brands = '$brandName',
            status = '$status',
            image_path = " . ($imagePath ? "'$imagePath'" : "image_path") . "
        WHERE id = $id
        ";

        mysqli_query($con, $sql);
        header("Location: /Backend/src/Pages/category.php?updated=1");
        exit;
    } else {

        $sql = "
        INSERT INTO category (category, brands, status, image_path, created_on)
        VALUES (
            '$categoryName',
            '$brandName',
            '$status',
            " . ($imagePath ? "'$imagePath'" : "NULL") . ",
            '$createdOn'
        )
        ";

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
    <meta name="keywords" content="admin, estimates, bootstrap, business, corporate, creative, management, minimal, modern,  html5, responsive">
    <meta name="author" content="Dreamguys - Bootstrap Admin Template">
    <meta name="robots" content="noindex, nofollow">
    <title>Dreams Pos admin template</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" />
    <style>
        .parsley-required,
        .parsley-type,
        .parsley-minlength {
            color: orangered;
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
                        <a href="/Backend/src/Pages/category.php" class="fw-bold text-secondary fs-6"><i class="bi bi-arrow-left me-1 fw-bold"></i>Back to category list</a>
                    </div>
                    <div class="page-title">
                        <h6>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="/Backend/src/Pages/category.php">Category List</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Add Category</li>
                                </ol>
                            </nav>
                        </h6>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <form action="#" id="myForm" method="POST" enctype="multipart/form-data" data-parsley-validate>
                            <input type="hidden" name="user_id">
                            <div class="row">
                                <div class="col-lg-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="categoryName">Category Name <span class="text-danger">*</span></label>
                                        <input type="text" id="categoryName" name="categoryname" placeholder="Category name" value="<?= $editData['category'] ?? '' ?>" maxlength="150" data-parsley-required data-parsley-required-message="Category name is required">
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="label"> Status</label>
                                        <select class="form-select" name="status" id="label" data-parsley-id="1601" maxlength="200">
                                            <option value="" disabled <?= empty($editData['status']) ? 'selected' : '' ?>>Choose Status</option>
                                            <option value="Active" <?= ($editData['status'] ?? '') == 'Active' ? 'selected' : '' ?>>Active</option>
                                            <option value="Inactive" <?= ($editData['status'] ?? '') == 'Inactive' ? 'selected' : '' ?>>Inactive</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-10 col-sm-12 col-12">
                                    <div class="form-group">
                                        <label for="brandName">Brand Name <span class="text-danger">*</span></label>
                                        <input type="text" id="brandName" name="brandname" value="<?= $editData['brands'] ?? '' ?>" placeholder="Brand name" maxlength="150" data-parsley-required data-parsley-required-message="Brand name is required">
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <button class="w-100 btn btn-success" style='margin-top: 30px'>Add brand</button>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="uploadImage">Profile Picture (optional)</label>
                                <div class="image-upload image-upload-new">
                                    <input type="file" id="uploadImage" name="uploadImage" accept="image/*">
                                    <div class="image-uploads" id="uploadBox">
                                        <img src="/Backend/src/assets/images/icons/upload.svg" id="previewImg" alt="img" style="max-height:60px;">
                                        <h4 id="fileName">Drag and drop a file to upload</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 d-flex justify-content-end">
                                <button class="btn btn-cancel me-2" type="<?= $userId ? 'button' : 'reset' ?>" name="reset" id="resetButton">Reset</button>
                                <button class="btn btn-submit" name="submit" type="submit">Submit</button>
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
        document.getElementById('uploadImage').addEventListener('change', function() {
            const file = this.files[0];

            if (!file) return;

            const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];

            if (!allowedTypes.includes(file.type)) {
                alert('Sirf JPG, PNG, WEBP image allowed hai');
                this.value = '';
                return;
            }

            document.getElementById('fileName').innerText = file.name;

            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('previewImg').src = e.target.result;
            };
            reader.readAsDataURL(file);
        });
    </script>

</body>

</html>