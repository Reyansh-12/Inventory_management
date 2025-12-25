<?php
define("BASE_PATH", dirname(__DIR__, 3));
include BASE_PATH . "/src/Layouts/Links.php";
include BASE_PATH . "/src/controllers/dbConnection.php";

$currentPage = basename($_SERVER['PHP_SELF']);
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

if (isset($_POST['ajax']) && $_POST['ajax'] === '1') {
    $userName = trim($_POST['userName']);
    $contact = trim($_POST['phoneNumber']);
    $userEmail = trim($_POST['userEmail']);
    $userRole = $_POST['userRole'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    $emailCheck = mysqli_query(
        $con,
        "SELECT id FROM new_user WHERE user_email='$userEmail' AND id != '$userId'"
    );
    if (mysqli_num_rows($emailCheck) > 0) {
    echo json_encode([
        'status' => 'error',
        'field'  => 'email',
        'message'=> 'Email already exists'
    ]);
    exit;
}
    $phoneCheck = mysqli_query(
        $con,
        "SELECT id FROM new_user WHERE user_contact='$contact' AND id != '$userId'"
    );
    if (mysqli_num_rows($phoneCheck) > 0) {
        echo json_encode([
            'status' => 'error',
            'field' => 'phone',
            'message' => 'Phone number already exists'
        ]);
        exit;
    }
    if (!$isEdit && $password !== $confirmPassword) {
        echo json_encode([
            'status' => 'error',
            'field' => 'confirmPassword',
            'message' => 'Passwords do not match'
        ]);
        exit;
    }

    if ($isEdit) {
        mysqli_query($con, "UPDATE new_user SET
            user_name='$userName',
            user_email='$userEmail',
            user_contact='$contact',
            user_role='$userRole'
            WHERE id='$userId'");
        echo json_encode(['status' => 'success', 'type' => 'updated']);
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        mysqli_query($con, "INSERT INTO new_user
        (user_name,user_email,user_contact,user_password,user_role,status)
        VALUES('$userName','$userEmail','$contact','$hash','$userRole','Active')");
        echo json_encode(['status' => 'success', 'type' => 'added']);
    }
    exit;
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
                                    <li class="breadcrumb-item"><a href="NewUser.php">User List</a></li>
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
                                        <span class="text-danger" id="emailError"><?php echo $emailError ?? ""; ?></span>
                                    </div>
                                    <div class="form-group">
                                        <div class="pass-group">
                                            <label for="password">Password <?php if (!$isEdit) echo '<span class="text-danger">*</span>'; ?></label>
                                            <input type="password" class="pass-input" id="password" name="password" placeholder=".........." data-parsley-minlength="6" <?php echo !$isEdit ? 'required' : 'disabled'; ?>>
                                            <i class="bi bi-eye-slash toggle-password position-absolute" style="color: #605d5d; margin-top: 15px;"></i>
                                            <span class="text-danger ms-1" id="passwordError"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-12">
                                    <div class="form-group">
                                        <label for="contact">Phone number <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text">+91</span>
                                            <input type="text" class="form-control" id="contact" name="phoneNumber" value="<?php echo htmlspecialchars($editData['user_contact'] ?? ''); ?>" placeholder="Phone number" maxlength="10" data-parsley-minlength="10" data-parsley-required="true" data-parsley-required-message="Phone number is required" data-parsley-errors-container="#contactError" />
                                        </div>
                                        <div id="contactError" class="text-danger"><?php echo $contactError ?? ""; ?></div>
                                    </div>
                                    <div class="form-group">
                                        <label for='userRole'>Role</label>
                                        <select class="form-select" id="userRole" name="userRole">
                                            <option disabled selected>Select role</option>
                                            <option <?php if (($editData['user_role'] ?? '') == 'Admin') echo 'selected'; ?>>Admin</option>
                                            <option <?php if (($editData['user_role'] ?? '') == 'User') echo 'selected'; ?>>User</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for='confirmPassword'>Confirm Password <?php if (!$isEdit) echo '<span class="text-danger">*</span>'; ?></label>
                                        <div class="pass-group">
                                            <input type="password" class="pass-inputs" id="confirmPassword" name="confirmPassword" placeholder=".........." data-parsley-minlength="6" <?php echo !$isEdit ? 'required data-parsley-required-message="Confirm password"' : 'disabled'; ?>>
                                            <i class="bi bi-eye-slash toggle-password" style="color: #605d5d; margin-top: 3px;"></i>
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
    <script>
        $('#userName').on('input', function() {
            let value = $(this).val();
            value = value.replace(/[^a-zA-Z\s]/g, '');
            $(this).val(value);
        });
        $('#contact').on('input', function() {
            let inputValue = $(this).val();
            let filteredValue = inputValue.replace(/[^0-9]/g, '');
            $(this).val(filteredValue);
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
    const input = $(this).prev('input'); // ✅ nearest input only

    if (input.attr('type') === 'password') {
        input.attr('type', 'text');
        $(this)
            .removeClass('bi-eye-slash')
            .addClass('bi-eye');
    } else {
        input.attr('type', 'password');
        $(this)
            .removeClass('bi-eye')
            .addClass('bi-eye-slash');
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

        $('#myForm').on('submit', function(e) {
            e.preventDefault();

            let formData = $(this).serialize() + '&ajax=1';

            $.post('', formData, function(res) {
                let data = JSON.parse(res);

                if (data.status === 'error') {
                    showToast(data.message, 'danger');

                    if (data.field === 'email') $('#emailError').text(data.message);
                    if (data.field === 'phone') $('#contactError').text(data.message);
                    if (data.field === 'confirmPassword') $('#confirmPasswordError').text(data.message);
                    return;
                }

                showToast(
                    data.type === 'added' ? 'User added successfully' : 'User updated successfully',
                    'success'
                );

                setTimeout(() => {
                    window.location.href = 'UsersList.php?' + data.type + '=1';
                }, 1500);
            });
        });

        function showToast(msg, type) {
            const toastEl = document.getElementById("actionToast");
            const toastMsg = document.getElementById("toastMessage");
            toastEl.classList.remove("bg-success", "bg-danger");
            toastEl.classList.add(type === 'success' ? 'bg-success' : 'bg-danger');
            toastMsg.innerText = msg;
            new bootstrap.Toast(toastEl, {
                delay: 3000
            }).show();
        }
    </script>
</body>

</html>