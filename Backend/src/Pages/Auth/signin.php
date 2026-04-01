<?php
ob_start(); 
session_start();

define("BASE_PATH", dirname(__DIR__, 3));
$dbPath = BASE_PATH . "/src/controllers/dbConnection.php";

if (file_exists($dbPath)) {
    include $dbPath;
} else {
    die("Database connection file not found at: " . $dbPath);
}

if (isset($_POST['submit'])) {
    $userEmail = trim($_POST['userEmail']);
    $userPassword = $_POST['userPassword'];

    $stmt = mysqli_prepare($con, "SELECT * FROM new_user WHERE user_email = ?");
    mysqli_stmt_bind_param($stmt, "s", $userEmail);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);

    if ($user && password_verify($userPassword, $user['user_password'])) {

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_role'] = $user['user_role'];

        $role = trim($user['user_role']);

        if (strcasecmp($role, "Admin") == 0) {
            header("Location: /Backend/src/Pages/Dashboard.php");
            exit();
        } else {
            $userData = [
                "id" => $user['id'],
                "email" => $user['user_email'],
                "role" => $user['user_role'],
                "name" => $user['user_name']
            ];
            $encodedUser = urlencode(json_encode($userData));

            header("Location: http://localhost:5173/home?auth_user=" . $encodedUser);
            exit();
        }
    } else {
        $_SESSION['field_error'] = ['type' => 'password', 'message' => 'Invalid email or password'];
        header("Location: signin.php");
        exit();
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
        content="admin, estimates, bootstrap, business, corporate, creative, invoice, html5, responsive, Projects">
    <meta name="author" content="Dreamguys - Bootstrap Admin Template">
    <meta name="robots" content="noindex, nofollow">
    <title>Login</title>
    <link rel="shortcut icon" type="image/x-icon" href="/Backend/src/assets/images/favicon.jpg">
    <link rel="stylesheet" href="/Backend/src/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="/Backend/src/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
        integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        .parsley-required,
        .parsley-minlength,
        .parsley-type {
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
                            <h3>Sign In</h3>
                            <h4>Please login to your account</h4>
                        </div>
                        <form action="signin.php" method="POST" data-parsley-validate>
                            <div class="form-login">
                                <label for='email'>Email</label>
                                <div class="form-addons">
                                    <input type="email" id='email' name='userEmail'
                                        value="<?= htmlspecialchars($_SESSION['keep_email'] ?? '') ?>"
                                        placeholder="Enter your email address" required
                                        data-parsley-required-message="Email is required">
                                    <img src="/Backend/src/assets/images/icons/mail.svg" alt="img">
                                </div>
                            </div>

                            <div class="form-login">
                                <div class="pass-group">
                                    <label for="password">Password</label>
                                    <input type="password" id='password' name='userPassword' class="pass-input"
                                        placeholder="........." required data-parsley-minlength="6">
                                    <span class="fas toggle-password fa-eye-slash position-absolute"
                                        style="top: 53px; cursor: pointer;"></span>
                                </div>
                            </div>

                            <div class="form-login">
                                <button class="btn btn-login" name="submit" type="submit">Sign In</button>
                            </div>
                        </form>
                        <?php unset($_SESSION['field_error']); ?>
                        <div class="signinform text-center">
                            <h4>Don’t have an account? <a href="/Backend/src/Pages/Auth/signup.php" class="hover-a">Sign
                                    Up</a></h4>
                        </div>
                        <div class="form-setlogin">
                            <h4>Or sign up with</h4>
                        </div>
                        <div class="form-sociallink">
                            <ul>
                                <li>
                                    <a href="javascript:void(0);">
                                        <img src="/Backend/src/assets/images/icons/google.png" class="me-2"
                                            alt="google">
                                        Sign Up using Google
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);">
                                        <img src="/Backend/src/assets/images/icons/facebook.png" class="me-2"
                                            alt="google">
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
    <?php if (isset($_SESSION['toast_error'])): ?>
        <div class="toast-container position-fixed top-0 end-0 p-3">
            <div class="toast align-items-center text-bg-danger show" data-bs-delay="3000" data-bs-autohide="true">
                <div class="d-flex">
                    <div class="toast-body">
                        <?= $_SESSION['toast_error']; ?>
                    </div>
                    <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        </div>
        <?php unset($_SESSION['toast_error']);
    endif; ?>

    <script src="/Backend/src/assets/js/jquery-3.6.0.min.js"></script>
    <script src="/Backend/src/assets/js/feather.min.js"></script>
    <script src="/Backend/src/assets/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/parsley.js/2.9.2/parsley.min.js"></script>
    <script src="/Backend/src/assets/js/script.js"></script>
    <script>
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
        $('#email').on('input', function () {
            let value = $(this).val();

            value = value.replace(/[^a-zA-Z0-9@.]/g, '');

            if (value.length === 1 && !/^[A-Za-z]$/.test(value)) {
                value = '';
            }

            $(this).val(value);
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toastElList = document.querySelectorAll('.toast');
            toastElList.forEach(function (toastEl) {
                const toast = new bootstrap.Toast(toastEl);
                toast.show();
            });
        });
    </script>
    <script>
        $('#email').on('input', function () {
            $('.email-error').fadeOut();
        });

        $('#password').on('input', function () {
            $('.password-error').fadeOut();
        });
    </script>

</body>

</html>