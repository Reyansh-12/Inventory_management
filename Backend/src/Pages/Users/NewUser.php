<?php 
define("BASE_PATH", dirname(__DIR__, 3));
include BASE_PATH . "/src/Layouts/Links.php";
include BASE_PATH . "/src/controllers/dbConnection.php";

$status = 'Active';
$confirmPasswordError = '';
$userId = $_GET['userId'] ?? null;
$isEdit = false;
$editData = [];

if ($userId) {
    $isEdit = true;
    $result = mysqli_query($con, "SELECT * FROM `new_user` WHERE id = '$userId'");
    if ($result && mysqli_num_rows($result) > 0) {
        $editData = mysqli_fetch_assoc($result);
    }
}

if (isset($_POST['submit'])) {
    $userName = mysqli_real_escape_string($con, $_POST['userName']);
    $contact = mysqli_real_escape_string($con, $_POST['phoneNumber']);
    $userEmail = mysqli_real_escape_string($con, $_POST['userEmail']);
    $userRole = mysqli_real_escape_string($con, $_POST['userRole']);
    $password = mysqli_real_escape_string($con, $_POST['password']);
    $confirmPassword = mysqli_real_escape_string($con, $_POST['confirmPassword']);

    if ($isEdit) {
        $sql = "UPDATE `new_user` SET  `user_name` = '$userName', `user_email` = '$userEmail', `user_contact` = '$contact', `user_role` = '$userRole', `status` = '$status' WHERE id = '$userId'";
        mysqli_query($con, $sql);
        header("Location: UsersList.php");
        exit();
    } else {
        if ($password !== $confirmPassword) {
            $confirmPasswordError = "Passwords do not match.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $insertSql = "INSERT INTO `new_user`(`user_name`, `user_email`, `user_contact`, `user_password`, `user_role`, `status`)
                          VALUES ('$userName', '$userEmail', '$contact', '$hashed_password', '$userRole', '$status')";
            if (!mysqli_query($con, $insertSql)) {
                die("Insert Failed: " . mysqli_error($con));
            }

            header("Location: UsersList.php");
            exit();
        }
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
    <style>
        .parsley-required, .parsley-type, .parsley-minlength {
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
                <div class="page-title">
                    <h4>User Management</h4>
                    <h6>Add/Update User</h6>
                </div>
                <div class="page-btn">
                    <a href="/Backend/src/Pages/Users/UsersList.php" class="btn btn-added"><i class="bi bi-arrow-left me-1 fw-bold"></i>Back to user list</a>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <form action="#" id="myForm" method="POST" enctype="multipart/form-data" data-parsley-validate>
                    <input type="hidden" name="user_id" value="<?php echo $editData['id'] ?? ''; ?>">    
                    <div class="row">
                            <div class="col-lg-4 col-12">
                                <div class="form-group">
                                    <label for='userName'>User Name <span class="text-danger">*</span></label>
                                    <input type="text" id="userName" name="userName" value="<?php echo htmlspecialchars($editData['user_name'] ?? '') ?>" placeholder="Enter your name" data-parsley-required-message="User name is required" required>
                                </div>
                                <div class="form-group">
                                    <label for="userEmail">Email <span class="text-danger">*</span></label>
                                    <input type="text" id="userEmail" name="userEmail" value="<?php echo htmlspecialchars($editData['user_email'] ?? ''); ?>" placeholder="Enter your email" data-parsley-type="email" data-parsley-required-message="Enter your email address" data-parsley-required>
                                </div>
                                <div class="form-group">
                                    <div class="pass-group">
                                        <label for="password">Password <?php if(!$isEdit) echo '<span class="text-danger">*</span>'; ?></label>
                                        <input type="password" class="pass-input" id="password" name="password" placeholder="Enter your password" data-parsley-minlength="6" <?php echo !$isEdit ? 'required data-parsley-required-message="Password is required"' : 'disabled'; ?>>
                                        <span class="fas toggle-password fa-eye-slash"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-12">
                                <div class="form-group">
                                    <label for="contact" class="">Mobile <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text" id="addon-wrapping">+91</span>
                                        <input type="text" id="contact" name="phoneNumber" value="<?php echo htmlspecialchars($editData['user_contact'] ?? ''); ?>" placeholder="Enter your mobile number"
                                            maxlength="10"
                                            
                                            data-parsley-minlength="10"
                                            data-parsley-required="true"
                                            data-parsley-required-message="Phone number is required"
                                            data-parsley-errors-container="#contactError" />
                                    </div>
                                    <div id="contactError" class="text-danger"></div>
                                </div>
                                <div class="form-group">
                                    <label for='userRole'>Role</label>
                                    <select class="form-select" id="userRole" name="userRole" data-parsley-required-message="Select user role" data-parsley-required>
                                        <option disabled selected>Select</option>
                                        <option <?php if(($editData['user_role'] ?? '')=='Admin') echo 'selected'; ?>>Admin</option>
                                        <option <?php if(($editData['user_role'] ?? '')=='User') echo 'selected'; ?>>User</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for='confirmPassword'>Confirm Password <?php if(!$isEdit) echo '<span class="text-danger">*</span>'; ?></label>
                                    <div class="pass-group">
                                        <input type="password" class="pass-inputs" id="confirmPassword" name="confirmPassword" placeholder="Confirm your password" data-parsley-minlength="6" <?php echo !$isEdit ? 'required data-parsley-required-message="Confirm password"' : 'disabled'; ?>>
                                        <span class="fas toggle-passworda fa-eye-slash"></span>
                                        <span class="text-danger ms-1" id="confirmPasswordError"><?php echo $confirmPasswordError ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-12">
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
                            <div class="col-lg-12 d-flex justify-content-end">
                                <button class="btn btn-cancel me-2" type="reset" name="reset" id="resetButton">Reset</button>
                                <button class="btn btn-submit" name="submit" type="submit"><?= $userId ? 'Update' : 'Submit' ?></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
    <script>
        $('#userName').on('input', function() {
            let inputValue = $(this).val();
            let filteredValue = inputValue.replace(/[0-9]/g, '');
            $(this).val(filteredValue);
        });
        $('#contact').on('input', function() {
            let inputValue = $(this).val();
            let filteredValue = inputValue.replace(/[^0-9]/g, '');
            $(this).val(filteredValue);
        });
        let parsleyForm = $('#myForm').parsley();
        $('#resetButton').on('click', function() {
            parsleyForm.reset();
            $('#confirmPasswordError').text('');
        });
    </script>
</body>

</html>