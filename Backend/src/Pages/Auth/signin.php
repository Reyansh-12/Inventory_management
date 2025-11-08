<?php 
    include('/xampp/htdocs/Inventory_management/Backend/src/controllers/dbConnection.php');
if(isset($_POST['submit'])) {
    $userName = $_POST['userEmail'];
    $userPassword = $_POST['userPassword'];

    $sql = "SELECT * FROM `authentication` WHERE user_email = '$userName' && password = '$userPassword'";
    $result = mysqli_query($con, $sql);
    $num = mysqli_num_rows($result);
    if($num == 0){
        echo "Invalid Credentials";
    } else {
        session_start();
        $_SESSION['userEmail'] = $userName;
        $_SESSION['status'] = "Admin";
        header("Location: /Backend/src/Pages/Dashboard.php");
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
    <div id="global-loader">
        <div class="whirly-loader"> </div>
    </div>
    <div class="main-wrapper">
        <div class="account-content">
            <div class="login-wrapper">
                <div class="login-content">
                    <div class="login-userset">
                        <div class="login-logo">
                            <img src="/Backend/src/assets/images/logo.png" alt="img">
                        </div>
                        <div class="login-userheading">
                            <h3>Sign In</h3>
                            <h4>Please login to your account</h4>
                        </div>
                        <form action="#" method="POST" data-parsley-validate>
                            <div class="form-login">
                                <label for='email'>Email</label>
                                <div class="form-addons">
                                    <input type="text" id='email' name='userEmail' placeholder="Enter your email address" data-parsley-type="email" data-parsley-required>
                                    <img src="/Backend/src/assets/images/icons/mail.svg" alt="img">
                                </div>
                            </div>
                            <div class="form-login">
                                <div class="pass-group">
                                    <label for="password">Password</label>
                                    <input type="password" id='password' name='userPassword' class="pass-input" placeholder="Enter your password" data-parsley-minlength="6" data-parsley-required>
                                    <span class="fa toggle-password fa-eye-slash"></span>
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
    <script src="/Backend/src/assets/js/jquery-3.6.0.min.js"></script>
    <script src="/Backend/src/assets/js/feather.min.js"></script>
    <script src="/Backend/src/assets/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/parsley.js/2.9.2/parsley.min.js"></script>
    <script src="/Backend/src/assets/js/script.js"></script>
</body>

</html>