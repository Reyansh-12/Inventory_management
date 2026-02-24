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
$imagePath = $editData['image_path'] ?? null;

if (!empty($_FILES['uploadImage']['name'])) {

    $allowedTypes = ['jpg', 'jpeg', 'png', 'webp'];
    $fileName = $_FILES['uploadImage']['name'];
    $tmpName  = $_FILES['uploadImage']['tmp_name'];

    $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    if (!in_array($ext, $allowedTypes)) {
        $_SESSION['toast'] = ['type' => 'danger', 'msg' => 'Invalid image type'];
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit;
    }

    $newName = 'user_' . time() . '_' . rand(1000,9999) . '.' . $ext;

    $uploadDir = BASE_PATH . '/src/Pages/Users/userImage/';
    $uploadPath = $uploadDir . $newName;

    if (move_uploaded_file($tmpName, $uploadPath)) {
        $imagePath = '/Backend/src/Pages/Users/userImage/' . $newName;
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
        $con,
        "SELECT id FROM new_user WHERE user_contact='$contact' AND id != '$userId'"
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
        mysqli_query($con," UPDATE new_user SET user_name='$userName', user_email='$userEmail', user_contact='$contact', user_role='$userRole', status='$status', image_path='$imagePath' WHERE id='$userId'");
        $_SESSION['toast'] = ['type' => 'success', 'msg' => 'User updated successfully'];
        header("Location: UsersList.php?updated=1");
        exit;
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        mysqli_query($con," INSERT INTO new_user (user_name,user_email,user_contact,user_password,user_role,status,image_path) VALUES ('$userName','$userEmail','$contact','$hash','$userRole','$status','$imagePath') ");
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
    <meta name="keywords"
        content="admin, estimates, bootstrap, business, corporate, creative, management, minimal, modern,  html5, responsive">
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
    /* --- Add User Professional Overhaul --- */
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

/* Refined Inputs */
.form-control, .form-select, .pass-input, .input-group-text {
    border: 1px solid var(--border-color) !important;
    border-radius: 8px !important;
    padding: 10px 14px !important;
    font-size: 0.9rem !important;
    background-color: var(--bg-light);
    transition: all 0.2s ease;
}

.form-control:focus, .form-select:focus, .pass-input:focus {
    border-color: var(--primary-blue) !important;
    background-color: #fff;
    box-shadow: 0 0 0 3px rgba(103, 146, 255, 0.1) !important;
    outline: none;
}

/* Password Group Positioning */
.pass-group {
    position: relative;
}

.toggle-password {
    position: absolute;
    right: 15px;
    top: 38px;
    cursor: pointer;
    z-index: 5;
    color: #a0aec0;
}

/* Profile Picture Upload Zone */
.image-upload-new {
    border: 2px dashed #cbd5e0;
    border-radius: 50%; /* Circle for user profiles */
    width: 150px;
    height: 150px;
    margin: 0 auto 20px auto;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    background: var(--bg-light);
    cursor: pointer;
    overflow: hidden;
    transition: all 0.3s;
}

.image-upload-new:hover {
    border-color: var(--primary-blue);
    background: #fff;
}

#previewImg {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* Button Styling */
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

.parsley-required, .parsley-pattern {
    font-size: 0.75rem;
    color: #e53e3e !important;
    margin-top: 4px;
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
                <h4 class="fw-bold"><?= $isEdit ? 'Update User Account' : 'Register New User' ?></h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb" style="font-size: 0.85rem;">
                        <li class="breadcrumb-item"><a href="UsersList.php" class="text-primary">Users</a></li>
                        <li class="breadcrumb-item active"><?= $isEdit ? 'Edit Mode' : 'New User' ?></li>
                    </ol>
                </nav>
            </div>
            <div class="page-btn">
                <a href="/Backend/src/Pages/Users/UsersList.php" class="btn btn-sm btn-outline-secondary rounded-pill">
                    <i class="bi bi-arrow-left"></i> Back to List
                </a>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <form id="myForm" method="POST" enctype="multipart/form-data" data-parsley-validate>
                    <div class="row g-4">
                        <div class="col-lg-12 text-center mb-2">
                            <div class="image-upload-new shadow-sm" onclick="document.getElementById('uploadImage').click()" style="width: 200px; height: 200px">
                                <input type="file" id="uploadImage" name="uploadImage" accept="image/*" hidden>
                                <img id="previewImg" src="<?= !empty($editData['image_path']) ? $editData['image_path'] : '/Backend/src/assets/images/icons/upload.svg' ?>">
                            </div>
                            <h6 id="imageName" class="text-muted small">Profile Picture (JPG/PNG/WEBP)</h6>
                        </div>

                        <div class="col-lg-6">
                            <h5 class="mb-3 text-primary fs-6 border-bottom pb-2">Account Information</h5>
                            <div class="form-group mb-3">
                                <label>Full Name <span class="text-danger">*</span></label>
                                <input type="text" id="userName" name="userName" class="form-control" 
                                       value="<?= htmlspecialchars($editData['user_name'] ?? '') ?>" placeholder="John Doe" required>
                            </div>
                            <div class="form-group mb-3">
                                <label>Email Address <span class="text-danger">*</span></label>
                                <input type="text" id="userEmail" name="userEmail" class="form-control" 
                                       value="<?= htmlspecialchars($editData['user_email'] ?? '') ?>" placeholder="john@example.com" required>
                                <small id="emailError" class="text-danger"></small>
                            </div>
                            <div class="form-group mb-3">
                                <label>Phone Number <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">+91</span>
                                    <input type="text" id="contact" name="phoneNumber" class="form-control" 
                                           value="<?= htmlspecialchars($editData['user_contact'] ?? '') ?>" placeholder="9876543210" required>
                                </div>
                                <small id="contactError" class="text-danger"></small>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <h5 class="mb-3 text-primary fs-6 border-bottom pb-2">Access & Security</h5>
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group mb-3">
                                        <label>Role</label>
                                        <select class="form-select" name="userRole">
                                            <option value="Admin" <?= ($editData['user_role'] ?? '') == 'Admin' ? 'selected' : '' ?>>Admin</option>
                                            <option value="User" <?= ($editData['user_role'] ?? '') == 'User' ? 'selected' : '' ?>>User</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group mb-3">
                                        <label>Status</label>
                                        <select class="form-select" name="status">
                                            <option value="Active" <?= ($editData['status'] ?? '') == 'Active' ? 'selected' : '' ?>>Active</option>
                                            <option value="Inactive" <?= ($editData['status'] ?? '') == 'Inactive' ? 'selected' : '' ?>>Inactive</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group mb-3 pass-group">
                                <label>Password <?= !$isEdit ? '<span class="text-danger">*</span>' : '(Leave blank to keep current)' ?></label>
                                <input type="password" id="password" name="password" class="form-control pass-input" 
                                       placeholder="••••••••" <?= !$isEdit ? 'required' : '' ?>>
                                <span class="fas toggle-password fa-eye-slash"></span>
                                <small id="passwordError" class="text-danger d-block mt-1"></small>
                            </div>

                            <div class="form-group mb-3 pass-group">
                                <label>Confirm Password</label>
                                <input type="password" id="confirmPassword" name="confirmPassword" class="form-control pass-input" 
                                       placeholder="••••••••" <?= !$isEdit ? 'required' : '' ?>>
                                <span class="fas toggle-password fa-eye-slash"></span>
                                <small id="confirmPasswordError" class="text-danger d-block mt-1"></small>
                            </div>
                        </div>
                    </div>

                    <div class="mt-5 pt-3 border-top d-flex justify-content-end gap-2">
                        <button class="btn btn-cancel" type="button" id="resetButton">
                            <?= $userId ? 'Cancel' : 'Reset' ?>
                        </button>
                        <button class="btn btn-submit" name="submit" type="submit">
                            <?= $userId ? 'Update User' : 'Register User' ?>
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
    <?php if (!empty($_SESSION['toast'])): ?>
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        const toastEl = document.getElementById("actionToast");
        const toastMsg = document.getElementById("toastMessage");

        toastEl.classList.add("bg-<?= $_SESSION['toast']['type'] ?>");
        toastMsg.innerText = "<?= $_SESSION['toast']['msg'] ?>";

        new bootstrap.Toast(toastEl, {
            delay: 3000
        }).show();
    });
    </script>
    <?php unset($_SESSION['toast']);
    endif; ?>

    <script>
    $('#userName').on('input', function() {
        let value = $(this).val();
        value = value.replace(/[^a-zA-Z\s]/g, '');
        $(this).val(value);
    });
    $('#contact').on('input', function() {
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

    $(document).on('click', '.toggle-password', function() {
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

    $('#password').on('input', function() {
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
    $('#confirmPassword').on('input', function() {
        if ($('#password').val() !== $(this).val()) {
            $('#confirmPasswordError').text('Passwords do not match');
        } else {
            $('#confirmPasswordError').text('');
        }
    });
    document.getElementById('uploadImage').addEventListener('change', function() {
        const file = this.files[0];

        if (!file) return;

        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('previewImg').src = e.target.result;
        };
        reader.readAsDataURL(file);

        document.getElementById('imageName').innerText = file.name;
    });

    $('#userEmail').on('blur', function() {
        let email = $(this).val().trim();
        let userId = $('input[name="user_id"]').val() || 0;

        if (email) {
            $.post('checkExists.php', {
                type: 'email',
                value: email,
                userId: userId
            }, function(data) {
                let res = JSON.parse(data);
                if (res.exists) {
                    $('#emailError').text('Email already exists');
                } else {
                    $('#emailError').text('');
                }
            });
        }
    });

    $('#contact').on('blur', function() {
        let phone = $(this).val().trim();
        let userId = $('input[name="user_id"]').val() || 0;

        if (phone) {
            $.post('checkExists.php', {
                type: 'phone',
                value: phone,
                userId: userId
            }, function(data) {
                let res = JSON.parse(data);
                if (res.exists) {
                    $('#contactError').text('Phone number already exists');
                } else {
                    $('#contactError').text('');
                }
            });
        }
    });
    </script>

</body>

</html>