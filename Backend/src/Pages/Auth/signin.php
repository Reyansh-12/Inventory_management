<?php
session_start();
define("BASE_PATH", dirname(__DIR__, 3));
include BASE_PATH . "/src/Layouts/Links.php";
include BASE_PATH . "/src/controllers/dbConnection.php"; 

if (isset($_POST['submit'])) {

    $userName = mysqli_real_escape_string($con, $_POST['userEmail']);
    $userPassword = mysqli_real_escape_string($con, $_POST['userPassword']);

    $sql = "SELECT * FROM `new_user` WHERE user_email = '$userName'";
    $result = mysqli_query($con, $sql);
    $user = mysqli_fetch_assoc($result);

    if ($user && password_verify($userPassword, $user['user_password'])) {

        $_SESSION['userEmail'] = $user['user_email'];
        $_SESSION['role'] = $user['user_role'];

        if ($user['user_role'] === "Admin") {
            header("Location: http://localhost:3000/Backend/src/Pages/Dashboard.php");
            exit();
        }

        if ($user['user_role'] === "User") {
            header("Location: http://localhost:5173/");
            exit();
        }

        echo "<script>alert('Access denied. Invalid role detected.');</script>";
        
    } else {
        echo "<script>alert('Invalid Email or Password');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="description" content="POS - Bootstrap Admin Template">
    <meta name="keywords" content="admin, estimates, bootstrap, business, corporate, creative, invoice, html5, responsive, Projects">
    <meta name="author" content="Dreamguys - Bootstrap Admin Template">
    <meta name="robots" content="noindex, nofollow">
    <title>Login</title>
    <link rel="shortcut icon" type="image/x-icon" href="/Backend/src/assets/images/favicon.jpg">
    <link rel="stylesheet" href="/Backend/src/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="/Backend/src/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.13.1/font/bootstrap-icons.min.css" integrity="sha512-t7Few9xlddEmgd3oKZQahkNI4dS6l80+eGEzFQiqtyVYdvcSG2D3Iub77R20BdotfRPA9caaRkg1tyaJiPmO0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        .parsley-required, .parsley-minlength, .parsley-type{
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
                        <form action="#" method="POST" data-parsley-validate>
                            <div class="form-login">
                                <label for='email'>Email</label>
                                <div class="form-addons">
                                    <input type="text" id='email' name='userEmail' placeholder="Enter your email address" data-parsley-type="email" data-parsley-required-message="Email is required" data-parsley-required>
                                    <img src="/Backend/src/assets/images/icons/mail.svg" alt="img">
                                </div>
                            </div>
                            <div class="form-login">
                                <div class="pass-group">
                                    <label for="password">Password</label>
                                    <input type="password" id='password' name='userPassword' class="pass-input" placeholder="........." data-parsley-minlength="6" data-parsley-required-message="Password is required" data-parsley-required>
                                    <span class="bi bi-eye-slash toggle-password mt-3" style="color: #605d5d"></span>
                                </div>
                            </div>
                            <div class="form-login">
                                <div class="alreadyuser">
                                    <h4><a href="forgetpassword.html" class="hover-a">Forgot Password?</a></h4>
                                </div>
                            </div>
                            <div class="form-login">
                                <button class="btn btn-login" name='submit' type="submit">Sign In</button>
                            </div>
                        </form>
                        <div class="signinform text-center">
                            <h4>Don’t have an account? <a href="/Backend/src/Pages/Auth/signup.php" class="hover-a">Sign Up</a></h4>
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
    <div class=" align-items-center text-bg-primary border-0" role="alert" aria-live="assertive" aria-atomic="true">
  <div class="d-flex">
    <div class="toast-body">
      Hello, world! This is a toast message.
    </div>
    <button type="button" class="btn-close btn-close-red top-0 end-0 me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
  </div>
</div>
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
                .removeClass('bi-eye-slash')
                .addClass('bi-eye');
        } else {
            input.attr('type', 'password');
            $(this)
                .removeClass('bi-eye')
                .addClass('bi-eye-slash');
        }
    });
}
    </script>
</body>

</html>