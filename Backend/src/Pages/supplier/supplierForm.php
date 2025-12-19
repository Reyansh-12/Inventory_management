<?php 
define("BASE_PATH", dirname(__DIR__, 3));
include BASE_PATH . "/src/Layouts/Links.php";
include BASE_PATH . "/src/controllers/dbConnection.php";

$supplierId = $_GET['supplierId'] ?? null;

$supplierName = $email = $contact = $country = $city = $address = $description = "";

if ($supplierId) {
    $stmt = $con->prepare("SELECT * FROM supplier WHERE id = ?");
    $stmt->bind_param("i", $supplierId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $supplierName = $row['supplier_name'];
        $email = $row['email'];
        $contact = $row['phone_number'];
        $country = $row['country'];
        $city = $row['city'];
        $address = $row['address'];
        $description = $row['description'];
    }
}

if(isset($_POST['submit'])) {
    $supplierName = $_POST['supplierName'];
    $email = $_POST['supplierEmail'];
    $contact = $_POST['contact'];
    $country = $_POST['countrySelector'];
    $city = $_POST['citySelector'];
    $address = $_POST['address'];
    $description = $_POST['description'];

    if ($supplierId) {
        $stmt = $con->prepare("UPDATE supplier SET supplier_name=?, email=?, phone_number=?, city=?, country=?, address=?, description=? WHERE id=?");
        $stmt->bind_param("sssssssi", $supplierName, $email, $contact, $city, $country, $address, $description, $supplierId);
    } else {
        $stmt = $con->prepare("INSERT INTO supplier (supplier_name, email, phone_number, city, country, address, description) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $supplierName, $email, $contact, $city, $country, $address, $description);
        $stmt->execute();
    }

    header("Location: /Backend/src/Pages/supplierlist.php");
    exit();
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
    <title>Dreams Pos admin template</title>
    <style>
        .parsley-required {
            color: orangered;
        }

        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
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
                    <div class="page-btn">
                        <a href="/Backend/src/Pages/supplierlist.php" class="text-secondary fw-bold fs-6"><i class="bi bi-arrow-left me-1 fw-bold"></i>Back to Product</a>
                    </div>
                    <div class="page-title">
                        <h6>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="/Backend/src/Pages/supplierlist.php">Supplier List</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Add Supplier</li>
                                </ol>
                            </nav>
                        </h6>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                    <form id="myForm" method="POST" data-parsley-validate>
    <div class="row">
        <div class="col-lg-3 col-sm-6 col-12">
            <div class="form-group">
                <label for='supplierName'>Supplier Name <span class="text-danger">*</span></label>
                <input type="text" id="supplierName" name="supplierName" value="<?= htmlspecialchars($supplierName) ?>" placeholder="Supplier name" data-parsley-required-message="Supplier Name is required" data-parsley-required>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6 col-12">
            <div class="form-group">
                <label for='supplierMail'>Email <span class="text-danger">*</span></label>
                <input type="text" id='supplierMail' name="supplierEmail" value="<?= htmlspecialchars($email) ?>" placeholder="Supplier email" data-parsley-required-message="Email is required" data-parsley-required>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6 col-12">
        <div class="form-group">
            <label for="contact" class="">Phone number <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text" id="addon-wrapping">+91</span>
                    <input type="text" id="contact" name="phoneNumber" value="<?= htmlspecialchars($contact) ?>" placeholder="Phone number" maxlength="10" data-parsley-minlength="10"data-parsley-required="true"data-parsley-required-message="Phone number is required"data-parsley-errors-container="#contactError" />
                </div>
            <div id="contactError" class="text-danger"><?php echo $contactError ?? ""; ?></div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6 col-12">
            <div class="form-group">
                <label for="countrySelector">Choose Country <span class="text-danger">*</span></label>
                <select class="form-select" name="countrySelector" id="countrySelector" data-parsley-required-message="Country is required" data-parsley-required>
                    <option disabled <?= !$country ? 'selected' : '' ?>>Choose Country</option>
                    <option value="india" <?= $country=='india' ? 'selected' : '' ?>>India</option>
                    <option value="usa" <?= $country=='usa' ? 'selected' : '' ?>>USA</option>
                </select>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6 col-12">
            <div class="form-group">
                <label for="citySelector">City <span class="text-danger">*</span></label>
                <select class="form-select" name="citySelector" id="citySelector" data-parsley-required-message="City is required" data-parsley-required>
                    <option disabled <?= !$city ? 'selected' : '' ?>>Choose City</option>
                    <option value="Nagpur" <?= $city=='Nagpur' ? 'selected' : '' ?>>Nagpur</option>
                    <option value="Bhandara" <?= $city=='Bhandara' ? 'selected' : '' ?>>Bhandara</option>
                </select>
            </div>
        </div>
        <div class="col-lg-9 col-12">
            <div class="form-group">
                <label for="address">Address <span class="text-danger">*</span></label>
                <input type="text" id="address" name="address" value="<?= htmlspecialchars($address) ?>" placeholder="Supplier address" data-parsley-required-message="Address is required" data-parsley-required>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" id="description" name="description" placeholder="Description"><?= htmlspecialchars($description) ?></textarea>
            </div>
        </div>
        <div class="col-lg-12 d-flex justify-content-end">
            <button class="btn btn-cancel me-2" type="reset" id="resetButton"><?= $supplierId ? 'Back' : 'Reset' ?></button>
            <button class="btn btn-submit" name="submit" type="submit"><?= $supplierId ? 'Update' : 'Submit' ?></button>
        </div>
    </div>
</form>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        let parsleyForm = $('#myForm').parsley();
        $('#resetButton').on('click', function() {
            parsleyForm.reset();
        });
        $('#contact').on('input', function() {
            let inputValue = $(this).val();
            let filteredValue = inputValue.replace(/[^0-9]/g, '');
            $(this).val(filteredValue);
        });
    </script>
</body>

</html>