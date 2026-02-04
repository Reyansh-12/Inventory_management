<?php
session_start();
define("BASE_PATH", dirname(__DIR__, 2));
include BASE_PATH . "/src/Layouts/Links.php";
include BASE_PATH . "/src/controllers/dbConnection.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: /Backend/src/Pages/Auth/signin.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$query = "SELECT 
    id, user_name, user_contact, user_email, user_role, 
    user_password, status, image_path 
FROM new_user 
WHERE id = ?
";

$stmt = $con->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

$profileImg = !empty($user['image_path'])
    ? $user['image_path']
    : '/Backend/src/assets/images/customer/default-user.png';
$imagePath = $user['image_path'] ?? null;

if (!empty($_FILES['profile_image']['name'])) {

    $uploadDir = BASE_PATH . "/uploads/profile/";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $ext = pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION);
    $fileName = "user_" . $user_id . "." . $ext;

    if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $uploadDir . $fileName)) {
        $imagePath = "/Backend/uploads/profile/" . $fileName;
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {

    $username = trim($_POST['username']);
    $email    = trim($_POST['email']);
    $phone    = trim($_POST['phone']);
    $password = $_POST['password'];

    if (!empty($_FILES['profile_image']['name'])) {

        $uploadDir = BASE_PATH . "/uploads/profile/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $ext = pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION);
        $fileName = "user_" . $user_id . "." . $ext;

        if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $uploadDir . $fileName)) {
            $imagePath = "/Backend/uploads/profile/" . $fileName;
        }
    }

    $sql = "UPDATE new_user 
            SET user_name=?, user_contact=?, user_email=?, image_path=? 
            WHERE id=?";

    $stmt = $con->prepare($sql);
    $stmt->bind_param("ssssi", $username, $phone, $email, $imagePath, $user_id);

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
    <style>
        .parsley-custom-error-message {
            color: #f94a4a;
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
                        <h4>Profile</h4>
                        <h6>User Profile</h6>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <form method="POST" enctype="multipart/form-data" data-parsley-validate>
                            <div class="profile-set">
                                <div class="profile-head">
                                </div>
                                <div class="profile-top">
                                    <div class="profile-content">
                                        <div class="profile-contentimg">
                                            <img src="<?= htmlspecialchars($profileImg) ?>" alt="Profile Image" id="blah" style="height: 110px;">
                                            <div class="profileupload">
                                                <input type="file" name="profile_image" id="imgInp" accept="image/*">
                                                <a href="javascript:void(0);"><img src="/Backend/src/assets/images/icons/edit-set.svg" alt="img"></a>
                                            </div>
                                        </div>
                                        <div class="profile-contentname">
                                            <h2><?= htmlspecialchars($user['user_name']) ?></h2>
                                            <h4>Updates Your Photo and Personal Details.</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">

                                <div class="col-lg-6 col-sm-12">
                                    <div class="form-group">
                                        <label>User Name</label>
                                        <input type="text" id="userName" name="username" maxlength="100" value="<?= htmlspecialchars($user['user_name']); ?>" data-parsley-required data-parsley-error-message="Name is required">
                                    </div>
                                </div>

                                <div class="col-lg-6 col-sm-12">
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input type="text" name="email" id="userEmail" maxlength="200" value="<?= htmlspecialchars($user['user_email']); ?>" data-parsley-required data-parsley-error-message="Email is required">
                                    </div>
                                </div>

                                <div class="col-lg-6 col-sm-12">
                                    <div class="form-group">
                                        <label>Phone</label>
                                        <input type="text" name="phone" id="contact" value="<?= htmlspecialchars($user['user_contact']); ?>" data-parsley-required data-parsley-error-message="Phone number is required" data-parsley-minlength="10">
                                    </div>
                                </div>

                                <div class="col-lg-6 col-sm-12">
                                    <div class="form-group">
                                        <label>Password (optional)</label>
                                        <input type="password" name="password" maxlength="16" class="pass-input" disabled>
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
    </script>
</body>

</html>