<?php include('/xampp/htdocs/Inventory_management/Backend/src/Layouts/Links.php');
include('/xampp/htdocs/Inventory_management/Backend/src/controllers/dbConnection.php');
ini_set('display_errors', 0);
$userName = $_POST['userName'];
$contact = $_POST['phoneNumber'];
$userEmail = $_POST['userEmail'];   
$userRole = $_POST['userRole'];
$password = $_POST['password'];
$sql = "INSERT INTO `new_user`(`user_name`)
VALUES ('$userName')";
if (isset($_POST['submitButton'])) {
    $sql = "INSERT INTO `new_user`(`user_name`, `user_contact`, `user_email`, `user_role`, `password`) 
    VALUES ('$userName','$contact','$userEmail','$userRole','$password')";
    $result = mysqli_query($con, $sql);
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
        .parsley-required{
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
                <?php include('/xampp/htdocs/Inventory_management/Backend/src/Layouts/Sidebar.php') ?>
            </div>
        </div>
        <div class="col-md-9">
            <?php include('/xampp/htdocs/Inventory_management/Backend/src/Layouts/Header.php') ?>
        </div>
    </div>
    <div class="page-wrapper">
        <div class="content">
            <div class="page-header">
                <div class="page-title">
                    <h4>User Management</h4>
                    <h6>Add/Update User</h6>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <form action="#" method="POST" data-parsley-validate>
                        <div class="row">
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for='userName'>User Name <span class="text-danger">*</span></label>
                                    <input type="text" id="userName" name="userName" placeholder="Enter your name" data-parsley-required>
                                </div>
                                <div class="form-group">
                                    <label for="userEmail">Email <span class="text-danger">*</span></label>
                                    <input type="text" id="userEmail" name="userEmail" placeholder="Enter your email" data-parsley-type="email" data-parsley-required>
                                </div>
                                <div class="form-group">
                                    <div class="pass-group">
                                        <label for="password">Password <span class="text-danger">*</span></label>
                                        <input type="password" class="pass-input" id="password" name="password" placeholder="Enter your password" data-parsley-minlength="6" data-parsley-required>
                                        <span class="fas toggle-password fa-eye-slash"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="contact">Mobile <span class="text-danger">*</span></label>
                                    <input type="text" id="contact" name="phoneNumber" placeholder="Enter your mobile number" data-parsley-minlength="6" data-parsley-required>
                                </div>
                                <div class="form-group">
                                    <label for='userRole'>Role <span class="text-danger">*</span></label>
                                    <select class="form-select" id="userRole" name="userRole" data-parsley-required>
                                        <option disabled selected>Select</option>
                                        <option>Role</option>
                                        <option>Role1</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for='confirmPassword'>Confirm Password <span class="text-danger">*</span></label>
                                    <div class="pass-group">
                                        <input type="password" class="pass-inputs" id="confirmPassword" name="confirmPassword" placeholder="Confirm your password" data-parsley-minlength="6" data-parsley-required>
                                        <span class="fas toggle-passworda fa-eye-slash"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for='uploadImage'> Profile Picture (optional)</label>
                                    <div class="image-upload image-upload-new">
                                        <input type="file" id="uploadImage" name="uploadImage">
                                        <div class="image-uploads">
                                            <img src="/Backend/src/assets/images/icons/upload.svg" alt="img">
                                            <h4>Drag and drop a file to upload</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <button type='submit' name="submitButton" class="btn btn-submit me-2">Submit</button>
                                <button class="btn btn-cancle">Cancel</button>
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