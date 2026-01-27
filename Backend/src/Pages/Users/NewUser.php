<?php
session_start();
define("BASE_PATH", dirname(__DIR__, 3));
include BASE_PATH . "/src/Layouts/Links.php";
include BASE_PATH . "/src/controllers/dbConnection.php";

$currentPage = basename($_SERVER['PHP_SELF']);
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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $userName = trim($_POST['userName']);
    $contact = trim($_POST['phoneNumber']);
    $userEmail = trim($_POST['userEmail']);
    $userRole = $_POST['userRole'] ?? 'User';
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirmPassword'] ?? '';
    $status = $_POST['status'];

    $emailCheck = mysqli_query(
        $con,
        "SELECT id FROM new_user WHERE user_email='$userEmail' AND id != '$userId'"
    );

    if (mysqli_num_rows($emailCheck) > 0) {
        $_SESSION['toast'] = ['type' => 'danger', 'msg' => 'Email already exists'];
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit;
    }

    $phoneCheck = mysqli_query(
        $con,"SELECT id FROM new_user WHERE user_contact='$contact' AND id != '$userId'"
    );

    if (mysqli_num_rows($phoneCheck) > 0) {
        $_SESSION['toast'] = ['type' => 'danger', 'msg' => 'Phone number already exists'];
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit;
    }
    if (!$isEdit && $password !== $confirmPassword) {
        $_SESSION['toast'] = ['type' => 'danger', 'msg' => 'Passwords do not match'];
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit;
    }
    if ($userId) {
        mysqli_query($con, "UPDATE new_user SET user_name='$userName', user_email='$userEmail', user_contact='$contact', user_role='$userRole', status='$status'WHERE id='$userId'");
        $_SESSION['toast'] = ['type' => 'success', 'msg' => 'User updated successfully'];
        header("Location: UsersList.php?updated=1");
        exit;

    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        mysqli_query($con, " INSERT INTO new_user (user_name,user_email,user_contact,user_password,user_role,status) VALUES ('$userName','$userEmail','$contact','$hash','$userRole','$status')");
        $_SESSION['toast'] = ['type' => 'success', 'msg' => 'User added successfully'];
        header("Location: UsersList.php?added=1");
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
                        <a href="/Backend/src/Pages/Users/UsersList.php" class="fw-bold text-secondary fs-6"><i class="bi bi-arrow-left me-1 fw-bold"></i>Back to user list</a>
                    </div>
                    <div class="page-title">
                        <h6>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="UsersList.php">User List</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Add User</li>
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
                                <div class="col-lg-4 col-12">
                                    <div class="form-group">
                                        <label for='userName'>User Name <span class="text-danger">*</span></label>
                                        <input type="text" id="userName" name="userName" value="<?php echo htmlspecialchars($editData['user_name'] ?? '') ?>" placeholder="User name" data-parsley-required-message="User name is required" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="userEmail">Email <span class="text-danger">*</span></label>
                                        <input type="text" id="userEmail" name="userEmail" value="<?php echo htmlspecialchars($editData['user_email'] ?? ''); ?>" placeholder="User email" data-parsley-type="email" data-parsley-pattern="^[A-Za-z][A-Za-z0-9]*@[A-Za-z0-9]+\.[A-Za-z]{2,}$" data-parsley-required-message="Email is required" data-parsley-required data-parsley-pattern-message="Email must start with a letter and contain only letters & numbers">
                                        <span class="parsley-required" id="emailError"><?php echo $emailError ?? ""; ?></span>
                                    </div>
                                    <div class="form-group">
                                        <div class="pass-group">
                                            <label for="password">Password <?php if (!$isEdit) echo '<span class="text-danger">*</span>'; ?></label>
                                            <input type="password" class="pass-input" id="password" name="password" placeholder=".........." <?php echo !$isEdit ? 'required' : 'disabled'; ?>>
                                            <span class="fas toggle-password fa-eye-slash position-absolute" style="top: 50px; color:rgba(138, 135, 135, 0.93); font-size: 13px"></span>
                                            <span class="parsley-required ms-1" id="passwordError"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-12">
                                    <div class="form-group">
                                        <label for="contact">Phone number <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text">+91</span>
                                            <input type="text" class="form-control" pattern="^[6-9][0-9]{9}$" data-parsley-pattern="^[6-9][0-9]{9}$" id="contact" name="phoneNumber" value="<?php echo htmlspecialchars($editData['user_contact'] ?? ''); ?>" placeholder="Phone number" maxlength="10" data-parsley-minlength="10" data-parsley-required="true" data-parsley-required-message="Phone number is required" data-parsley-errors-container="#contactError" />
                                        </div>
                                        <div id="contactError" class="parsley-required"><?php echo $contactError ?? ""; ?></div>
                                    </div>
                                    <div class="form-group d-none">
                                        <label for='userRole'>Role</label>
                                        <select class="form-select" id="userRole" name="userRole">
                                            <option disabled selected>Select role</option>
                                            <option <?php if (($editData['user_role'] ?? '') == 'Admin') echo 'selected'; ?>>Admin</option>
                                            <option <?php if (($editData['user_role'] ?? '') == 'User') echo 'selected'; ?>>User</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for='userStatus'>Status</label>
                                        <select class="form-select" id="userStatus" name="status" data-parsley-required="true" data-parsley-required-message="Status is required">
                                            <option disabled selected>Status</option>
                                            <option selected <?php if (($editData['status'] ?? '') == 'Active') echo 'selected'; ?>>Active</option>
                                            <option <?php if (($editData['status'] ?? '') == 'Inactive') echo 'selected'; ?>>Inactive</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for='confirmPassword'>Confirm Password <?php if (!$isEdit) echo '<span class="text-danger">*</span>'; ?></label>
                                        <div class="pass-group">
                                            <input type="password" class="pass-input" id="confirmPassword" name="confirmPassword" placeholder=".........." <?php echo !$isEdit ? 'required data-parsley-required-message="Confirm password"' : 'disabled'; ?>>
                                            <span class="fas toggle-password fa-eye-slash position-absolute" style="top: 20px; color:rgba(138, 135, 135, 0.93); font-size: 13px"></span>
                                            <span class="parsley-required ms-1" id="confirmPasswordError"><?php echo $confirmPasswordError ?></span>
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
                                    <button class="btn btn-cancel me-2" type="<?= $userId ? 'button' : 'reset' ?>" name="reset" id="resetButton"><?= $userId ? 'Back' : 'Reset' ?></button>
                                    <button class="btn btn-submit" name="submit" type="submit"><?= $userId ? 'Update' : 'Submit' ?></button>
                                </div>
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
<?php if (!empty($_SESSION['toast'])): ?>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const toastEl = document.getElementById("actionToast");
        const toastMsg = document.getElementById("toastMessage");

        toastEl.classList.add("bg-<?= $_SESSION['toast']['type'] ?>");
        toastMsg.innerText = "<?= $_SESSION['toast']['msg'] ?>";

        new bootstrap.Toast(toastEl, { delay: 3000 }).show();
    });
</script>
<?php unset($_SESSION['toast']); endif; ?>

    <script>
        $('#userName').on('input', function() {
            let value = $(this).val();
            value = value.replace(/[^a-zA-Z\s]/g, '');
            $(this).val(value);
        });
$('#contact').on('input', function () {
    let value = $(this).val();
    value = value.replace(/[^0-9]/g, '');
    if (value.length === 1 && !/^[6-9]$/.test(value)) {
        value = '';
    }
    if (value.length > 10) {
        value = value.substring(0, 10);
    }

    $(this).val(value);
});
        $('#userEmail').on('input', function() {
            let value = $(this).val();

            value = value.replace(/[^a-zA-Z0-9@.]/g, '');

            if (value.length === 1 && !/^[A-Za-z]$/.test(value)) {
                value = '';
            }

            $(this).val(value);
        });
        let parsleyForm = $('#myForm').parsley();
        $('#resetButton').on('click', function() {
            parsleyForm.reset();
            $('#confirmPasswordError').text('');
        });
        
        $(document).on('click', '.toggle-password', function () {
    const input = $(this).prev('input'); 

    if (input.attr('type') === 'password') {
        input.attr('type', 'text');
        $(this)
            .removeClass('fa-eye-slash')
            .addClass('fa-eye');
    } else {
        input.attr('type', 'password');
        $(this)
            .removeClass('fa-eye')
            .addClass('fa-eye-slash');
    }
});

        $('#resetButton').on('click', function() {
            if (<?= $userId ? 'true' : 'false' ?>) {
                window.location.href = "UsersList.php";
            }
        });
        const passwordRules = {
    upper: /[A-Z]/,
    lower: /[a-z]/,
    number: /[0-9]/,
    special: /[^A-Za-z0-9]/,
    length: /^.{6,16}$/
};

$('#password').on('input', function () {
    let val = $(this).val();

    const isValid =
        passwordRules.length.test(val) &&
        passwordRules.upper.test(val) &&
        passwordRules.lower.test(val) &&
        passwordRules.number.test(val) &&
        passwordRules.special.test(val);

    if (!isValid) {
        $('#passwordError').text(
            'Password must be 6–16 characters with uppercase, lowercase, number & special character'
        );
    } else {
        $('#passwordError').text('');
    }
});
        $('#confirmPassword').on('input', function () {
    if ($('#password').val() !== $(this).val()) {
        $('#confirmPasswordError').text('Passwords do not match');
    } else {
        $('#confirmPasswordError').text('');
    }
});

    </script>
</body>

</html>