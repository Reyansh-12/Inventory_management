<?php
include('/xampp/htdocs/Inventory_management/Backend/src/controllers/dbConnection.php');
ini_set('display_errors', 0);
session_start();
$_SESSION['username'] = '';
$_SESSIOn['user_email'] = '';
$_SESSION['userpassword'] = '';
$username = $_POST['username'];
$useremail = $_POST['useremail'];
$userPassword = $_POST['userpassword'];
$hashedPassword = password_hash($userPassword, PASSWORD_DEFAULT);
if (isset($_POST['submit'])) {
    $sql = "INSERT INTO `authentication`(`user_full_name`, `user_email`, `password`) 
    VALUES ('$username','$useremail','$hashedPassword')";
    $result = mysqli_query($con, $sql);
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
    <title>Login - Pos admin template</title>
    <link rel="shortcut icon" type="image/x-icon" href="/Backend/src/assets/images/favicon.jpg">
    <link rel="stylesheet" href="/Backend/src/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="/Backend/src/assets/css/style.css">
    <style>
        .parsley-required, .parsley-minlength, .parsley-type{
            color: orangered;
        }
    </style>
</head>

<body class="account-page">

    <div class="main-wrapper">
        <div class="account-content">
            <div class="login-wrapper">

                <div class="login-content">
                    <div class="login-userset">
                        <div class="login-logo">
                            <img src="/Backend/src/assets/images/logo.png" alt="img">
                        </div>
                        <div class="login-userheading">
                            <h3>Create an Account</h3>
                            <h4>Continue where you left off</h4>
                        </div>
                        <form action="#" method="POST" data-parsley-validate>
                            <div class="form-login">
                                <label for='fullName'>Full Name</label>
                                <div class="form-addons">
                                    <input type="text" name='username' id='fullName' placeholder="Enter your full name" data-parsley-required>
                                    <img src="/Backend/src/assets/images/icons/users1.svg" alt="img">
                                </div>
                            </div>
                            <div class="form-login">
                                <label for="email">Email</label>
                                <div class="form-addons">
                                    <input type="text" name='useremail' id='email' placeholder="Enter your email address" data-parsley-type="email" data-parsley-required>
                                    <img src="/Backend/src/assets/images/icons/mail.svg" alt="img">
                                </div>
                            </div>
                            <div class="form-login">
                                <label for="password">Password</label>
                                <div class="pass-group">
                                    <input type="password" name='userpassword' id='password' class="pass-input" placeholder="Enter your password" data-parsley-minlength="6" data-parsley-required>
                                    <span class="fas toggle-password fa-eye-slash"></span>
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
    <script src="/Backend/src/assets/js/jquery-3.6.0.min.js"></script>
    <script src="/Backend/src/assets/js/feather.min.js"></script>
    <script src="/Backend/src/assets/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/parsley.js/2.9.2/parsley.min.js"></script>
    <script src="/Backend/src/assets/js/script.js"></script>
</body>

</html>