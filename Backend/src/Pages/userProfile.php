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
        /* --- User Profile Professional Polish --- */
:root {
    --primary-blue: #6792ff;
    --border-color: #e2e8f0;
    --text-dark: #2d3748;
}

.card {
    border: none !important;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    border-radius: 16px !important;
}

/* Profile Header Section */
.profile-content {
    display: flex;
    align-items: center;
    gap: 25px;
    padding-bottom: 30px;
    border-bottom: 1px solid var(--border-color);
    margin-bottom: 30px;
}

.profile-contentimg {
    position: relative;
    width: 120px;
    height: 120px;
}

.profile-contentimg img#blah {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid #fff;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}

.profileupload {
    position: absolute;
    bottom: 5px;
    right: 5px;
}

.profileupload a {
    background: var(--primary-blue);
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
}

.profile-contentname h2 {
    font-size: 1.5rem;
    font-weight: 800;
    color: var(--text-dark);
    margin-bottom: 4px;
}

.profile-contentname h4 {
    font-size: 0.9rem;
    color: #718096;
    font-weight: 400;
}

/* Form Styling */
.form-group label {
    font-weight: 600;
    color: #4a5568;
    font-size: 0.85rem;
    margin-bottom: 8px;
    display: block;
}

.form-group input {
    border: 1px solid var(--border-color) !important;
    border-radius: 8px !important;
    padding: 12px 15px !important;
    background-color: #f8fafc !important;
    font-size: 0.9rem !important;
    transition: all 0.2s ease;
}

.form-group input:focus {
    border-color: var(--primary-blue) !important;
    background-color: #fff !important;
    box-shadow: 0 0 0 3px rgba(103, 146, 255, 0.1) !important;
}

/* Button Styling */
.btn-submit {
    background: var(--primary-blue) !important;
    color: #fff !important;
    font-weight: 700;
    padding: 12px 30px;
    border-radius: 8px;
    border: none;
    box-shadow: 0 4px 12px rgba(103, 146, 255, 0.25);
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
                <h4 class="fw-bold">My Account</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb" style="font-size: 0.85rem;">
                        <li class="breadcrumb-item"><a href="Dashboard.php" class="text-primary">Dashboard</a></li>
                        <li class="breadcrumb-item active">Profile Settings</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="card">
            <div class="card-body p-4">
                <form method="POST" enctype="multipart/form-data" data-parsley-validate>
                    <div class="profile-content">
                        <div class="profile-contentimg">
                            <img src="<?= htmlspecialchars($profileImg) ?>" alt="Profile" id="blah">
                            <div class="profileupload">
                                <input type="file" name="profile_image" id="imgInp" accept="image/*" hidden>
                                <a href="javascript:void(0);" onclick="document.getElementById('imgInp').click()">
                                    <img src="/Backend/src/assets/images/icons/edit-set.svg" alt="edit" style="width: 16px;">
                                </a>
                            </div>
                        </div>
                        <div class="profile-contentname">
                            <h2><?= htmlspecialchars($user['user_name']) ?></h2>
                            <h4>Administrator • <?= htmlspecialchars($user['user_email']) ?></h4>
                        </div>
                    </div>

                    <h5 class="mb-4 text-primary fs-6 fw-bold">Personal Information</h5>
                    <div class="row g-4">
                        <div class="col-lg-6 col-sm-12">
                            <div class="form-group">
                                <label>Full Name</label>
                                <input type="text" id="userName" name="username" class="form-control" 
                                       value="<?= htmlspecialchars($user['user_name']); ?>" required>
                            </div>
                        </div>

                        <div class="col-lg-6 col-sm-12">
                            <div class="form-group">
                                <label>Email Address</label>
                                <input type="email" name="email" id="userEmail" class="form-control" 
                                       value="<?= htmlspecialchars($user['user_email']); ?>" required>
                            </div>
                        </div>

                        <div class="col-lg-6 col-sm-12">
                            <div class="form-group">
                                <label>Mobile Number</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0">+91</span>
                                    <input type="text" name="phone" id="contact" class="form-control border-start-0" 
                                           value="<?= htmlspecialchars($user['user_contact']); ?>" required>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6 col-sm-12">
                            <div class="form-group">
                                <label>Account Role</label>
                                <input type="text" class="form-control text-muted" 
                                       value="<?= htmlspecialchars($user['user_role']); ?>" readonly>
                            </div>
                        </div>

                        <div class="col-12 d-flex justify-content-end mt-5 pt-3 border-top">
                            <button type="submit" name="update_profile" class="btn btn-submit">Save Changes</button>
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