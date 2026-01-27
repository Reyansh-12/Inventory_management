<?php
session_start();
define("BASE_PATH", dirname(__DIR__, 2));
include BASE_PATH . "/src/controllers/dbConnection.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$query = "SELECT user_name, user_email, user_contact FROM new_user WHERE id = ?";
$stmt = $con->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {

    $username = trim($_POST['username']);
    $email    = trim($_POST['email']);
    $phone    = trim($_POST['phone']);
    $password = $_POST['password'];

    if (!empty($password)) {

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $sql = "UPDATE new_user 
                SET user_name=?, user_email=?, user_contact=?, user_password=? 
                WHERE id=?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("ssssi", $username, $email, $phone, $hashed_password, $user_id);
    } else {

        $sql = "UPDATE new_user 
                SET user_name=?, user_email=?, user_contact=? 
                WHERE id=?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("sssi", $username, $email, $phone, $user_id);
    }

    if ($stmt->execute()) {
        header("Location: userProfile.php?updated=1");
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
    <title>User Profile</title>
</head>

<body>
   
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
                                            <input type="file" id="imgInp" class="pe-auto">
                                            <a href="javascript:void(0);"><img src="/Backend/src/assets/images/icons/edit-set.svg" alt="img"></a>
                                        </div>
                                    </div>
                                    <div class="profile-contentname">
                                        <h2><?= htmlspecialchars ($user['user_name']) ?></h2>
                                        <h4>Updates Your Photo and Personal Details.</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <form method="POST" action="">
                            <div class="row">

                                <div class="col-lg-6 col-sm-12">
                                    <div class="form-group">
                                        <label>User Name</label>
                                        <input type="text" name="username" value="<?= htmlspecialchars($user['user_name']); ?>" required>
                                    </div>
                                </div>

                                <div class="col-lg-6 col-sm-12">
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input type="text" name="email" value="<?= htmlspecialchars($user['user_email']); ?>" required>
                                    </div>
                                </div>

                                <div class="col-lg-6 col-sm-12">
                                    <div class="form-group">
                                        <label>Phone</label>
                                        <input type="text" name="phone"
                                            value="<?= htmlspecialchars($user['user_contact']); ?>">
                                    </div>
                                </div>

                                <div class="col-lg-6 col-sm-12">
                                    <div class="form-group">
                                        <label>Password (optional)</label>
                                        <input type="password" name="password" class="pass-input" disabled>
                                    </div>
                                </div>

                                <div class="col-12 d-flex justify-content-end">
                                    <button type="submit" name="update_profile" class="btn btn-submit">Update Profile</button>
                                </div>

                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>