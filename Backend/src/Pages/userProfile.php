<?php define("BASE_PATH", dirname(__DIR__, 2));
include BASE_PATH . "/src/Layouts/Links.php";
include BASE_PATH . "/src/controllers/dbConnection.php";
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
    <title>User Profile</title>
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
                        <h4>Profile</h4>
                        <h6>User Profile</h6>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="profile-set">
                            <div class="profile-head">
                            </div>
                            <div class="profile-top">
                                <div class="profile-content">
                                    <div class="profile-contentimg">
                                        <img src="/Backend/src/assets/images/customer/customer5.jpg" alt="img" id="blah">
                                        <div class="profileupload">
                                            <input type="file" id="imgInp">
                                            <a href="javascript:void(0);"><img src="/Backend/src/assets/images/icons/edit-set.svg" alt="img"></a>
                                        </div>
                                    </div>
                                    <div class="profile-contentname">
                                        <h2>William Castillo</h2>
                                        <h4>Updates Your Photo and Personal Details.</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-sm-12">
                                <div class="form-group">
                                    <label>First Name</label>
                                    <input type="text" placeholder="William">
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12">
                                <div class="form-group">
                                    <label>Last Name</label>
                                    <input type="text" placeholder="Castilo">
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12">
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="text" placeholder="william@example.com">
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12">
                                <div class="form-group">
                                    <label>Phone</label>
                                    <input type="text" placeholder="+1452 876 5432">
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12">
                                <div class="form-group">
                                    <label>User Name</label>
                                    <input type="text" placeholder="+1452 876 5432">
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12">
                                <div class="form-group">
                                    <label>Password</label>
                                    <div class="pass-group">
                                        <input type="password" class=" pass-input">
                                        <span class="fas toggle-password fa-eye-slash"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 d-flex justify-content-end">
                                <a href="javascript:void(0);" class="btn btn-cancel me-2">Cancel</a>
                                <a href="javascript:void(0);" class="btn btn-submit">Submit</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>