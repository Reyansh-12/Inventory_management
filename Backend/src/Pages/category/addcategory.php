<?php
session_start();
define("BASE_PATH", dirname(__DIR__, 3));
include BASE_PATH . "/src/Layouts/Links.php";
include BASE_PATH . "/src/controllers/dbConnection.php";


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
                                        <input type="text" id="categoryName" name="categoryname" placeholder="Category name" maxlength="150" data-parsley-required data-parsley-required-message="Category name is required">
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
                                        <input type="text" id="brandName" name="brandname" placeholder="Brand name" maxlength="150" data-parsley-required data-parsley-required-message="Brand name is required">
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <button class="w-100 btn btn-success" style='margin-top: 30px'>Add brand</button>
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
        <button type="button" class="btn-close btn-close-white me-2 m-auto"data-bs-dismiss="toast"></button>
    </div>
    </div>
</body>

</html>