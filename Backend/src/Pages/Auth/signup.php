<?php
define("BASE_PATH", dirname(__DIR__, 3));
include BASE_PATH . "/src/controllers/dbConnection.php";
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();

if (isset($_POST['submit'])) {

    $userName  = trim($_POST['username']);
    $userEmail = trim($_POST['useremail']);
    $password  = $_POST['userpassword'];
    $contact = $_POST['contact'];
    $userRole = $_POST['userRole'];
    $status = $_POST['status'];
    $strongPasswordPattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#^()_+\-=\[\]{};\'":\\|,.<>\/?]).{8,16}$/';

    $emailCheck = mysqli_query(
        $con,
        "SELECT id FROM new_user WHERE user_email='$userEmail' AND id != '$userId'"
    );

    if (mysqli_num_rows($emailCheck) > 0) {
        $_SESSION['toast'] = ['type' => 'danger', 'msg' => 'Email already exists'];
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit;
    }
if (!preg_match($strongPasswordPattern, $password)) {
    die("Password must be 8–16 characters and include at least one uppercase letter, one lowercase letter, one number, and one special character.");
}
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $stmt = mysqli_prepare(
        $con,
        "INSERT INTO new_user (user_name, user_email, user_contact, user_role, status, user_password) VALUES (?, ?, ?, ?, ?, ?)"
    );

    mysqli_stmt_bind_param($stmt, "ssisss", $userName, $userEmail, $contact, $userRole, $status, $hashed_password);
    if (!mysqli_stmt_execute($stmt)) {
        die("Insert Failed: " . mysqli_error($con));
    }
    header("Location: http://localhost:5173/");
    exit();
}
?>
<?php include BASE_PATH . "/src/Layouts/Links.php"; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="description" content="POS - Bootstrap Admin Template">
    <meta name="keywords" content="admin, estimates, bootstrap, business, corporate, creative, invoice, html5, responsive, Projects">
    <meta name="author" content="Dreamguys - Bootstrap Admin Template">
    <meta name="robots" content="noindex, nofollow">
    <title>Sign up</title>
    <link rel="shortcut icon" type="image/x-icon" href="/Backend/src/assets/images/favicon.jpg">
    <link rel="stylesheet" href="/Backend/src/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="/Backend/src/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"/>
    <style>
        .parsley-required, .parsley-minlength, .parsley-type,.parsley-pattern{
            color: orangered;
        }
    </style>
</head>

<body class="account-page">
    <div id="global-loader">
        <div class="whirly-loader"> </div>
    </div>
    <div class="main-wrapper">
        <div class="account-content">
            <div class="login-wrapper">

                <div class="login-content">
                    <div class="login-userset">
                        <div class="login-logo">
                            <img src="/Backend/src/assets/images/logo.webp" alt="img">
                        </div>
                        <div class="login-userheading">
                            <h3>Create an Account</h3>
                        </div>
                        <form method="POST" data-parsley-validate>
                            <input type="text" class="d-none" value="User" name="userRole">
                            <input type="text" class="d-none" value="Active" name="status">
                            <div class="form-login">
                                <label for='fullName'>Full Name</label>
                                <div class="form-addons">
                                    <input type="text" name='username' id='userName' placeholder="Enter your full name" maxlength="50" data-parsley-minlength="3" data-parsley-required-message="Name is required" data-parsley-required>
                                    <img src="/Backend/src/assets/images/icons/users1.svg" alt="img">
                                </div>
                            </div>
                            <div class="form-login">
                                <label for="contact">Phone Number</label>
                                <div class="form-addons">
                                    <input type="text" name='contact' id='contact' placeholder="Enter phone number" pattern="^[6-9][0-9]{9}$" maxlength="10" data-parsley-type="digits" data-parsley-pattern="^[6-9][0-9]{9}$" data-parsley-required-message="Phone number is required" data-parsley-pattern-message="Phone number must start with 6, 7, 8 or 9" data-parsley-required>
                                    <i class="fa-solid toggle-password pe-none fa-phone position-absolute" style="top: 20px; color:rgba(138, 135, 135, 0.93); font-size: 13px"></i>
                                </div>
                            </div>
                            
                            <div class="form-login">
                                <label for="email">Email</label>
                                <div class="form-addons">
                                    <input type="text" name='useremail' id='email' placeholder="Enter your email address" data-parsley-pattern="^[A-Za-z][A-Za-z0-9]*@[A-Za-z0-9]+\.[A-Za-z]{2,}$" data-parsley-required-message="Email is required" data-parsley-required data-parsley-pattern-message="Email must start with a letter and contain only letters & numbers">
                                    <img src="/Backend/src/assets/images/icons/mail.svg" alt="img">
                                </div>
                            </div>
                            <div class="form-login">
                                <label for="password">Password</label>
                                <div class="form-addons position-relative">
                                    <input type="password" name='userpassword' id='password' data-parsley-trigger="keyup" class="pass-input" placeholder="........." data-parsley-minlength="8" data-parsley-required-message="Password is required" data-parsley-required data-parsley-pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#^()_+\-=\[\]{};':\\|,.<>\/?]).{8,16}$" data-parsley-pattern-message="Password must be 8–16 characters with uppercase, lowercase, number & special character">
                                    <span class="fas toggle-password fa-eye-slash position-absolute" style="top: 20px; color:rgba(138, 135, 135, 0.93); font-size: 13px"></span>
                                </div>
                            </div>  
                            <div class="form-login">
                                <button type='submit' name='submit' class='btn btn-login'>Sign Up</button>
                            </div>
                        </form>
                        <div class="signinform text-center">
                            <h4>Already a user? <a href="/Backend/src/Pages/Auth/signin.php" class="hover-a">Sign In</a></h4>
                        </div>
                        <div class="form-setlogin">
                            <h4>Or sign up with</h4>
                        </div>
                        <div class="form-sociallink">
                            <ul>
                                <li>
                                    <a href="javascript:void(0);">
                                        <img src="/Backend/src/assets/images/icons/google.png" class="me-2" alt="google">
                                        Sign Up using Google
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);">
                                        <img src="/Backend/src/assets/images/icons/facebook.png" class="me-2" alt="google">
                                        Sign Up using Facebook
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="login-img">
                    <img src="/Backend/src/assets/images/login.jpg" alt="img">
                </div>
            </div>
        </div>
    </div>
    <div class="toast position-fixed bg-white top-0 end-0 m-3 text-white" style="z-index: 999" id="actionToast" role="alert">
    <div class="d-flex">
    <div class="toast-body text-black" id="toastMessage"></div>
        <button type="button" class="btn-close btn-close-black me-2 m-auto"data-bs-dismiss="toast"></button>
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
    <script src="/Backend/src/assets/js/jquery-3.6.0.min.js"></script>
    <script src="/Backend/src/assets/js/feather.min.js"></script>
    <script src="/Backend/src/assets/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/parsley.js/2.9.2/parsley.min.js"></script>
    <script src="/Backend/src/assets/js/script.js"></script>
    <script>
        $('#userName').on('input', function () {
    let value = $(this).val();
    value = value.replace(/[^a-zA-Z\s]/g, '');
    $(this).val(value);
});

        $('#contact').on('input', function() {
            let inputValue = $(this).val();
            let filteredValue = inputValue.replace(/[^0-9]/g, '');
            $(this).val(filteredValue);
        });

$('#email').on('input', function () {
    let value = $(this).val();

    value = value.replace(/[^a-zA-Z0-9@.]/g, '');

    if (value.length === 1 && !/^[A-Za-z]$/.test(value)) {
        value = '';
    }

    $(this).val(value);
});
if ($('.toggle-password').length > 0) {
    $(document).on('click', '.toggle-password', function () {
        const input = $('.pass-input');

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
}
$('#password').on('keyup', function () {
    $(this).parsley().validate();
});
$('#password').on('input', function () {
    if ($(this).val().length === 0) {
        $(this).parsley().reset();
    }
});

document.getElementById('contact').addEventListener('input', function (e) {
    let value = e.target.value.replace(/\D/g, '');

    if (value.length === 1 && !/^[6-9]$/.test(value)) {
        value = '';
    }

    e.target.value = value.slice(0, 10);
});
</script>

</body>

</html>