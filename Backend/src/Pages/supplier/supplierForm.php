<?php
define("BASE_PATH", dirname(__DIR__, 3));
include BASE_PATH . "/src/Layouts/Links.php";
include BASE_PATH . "/src/controllers/dbConnection.php";

$currentPage = basename($_SERVER['PHP_SELF']);
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

if (isset($_POST['submit'])) {
    $supplierName = $_POST['supplierName'];
    $email = $_POST['supplierEmail'];
    $contact = $_POST['phoneNumber'];
    $country = $_POST['countrySelector'];
    $city = $_POST['citySelector'];
    $address = $_POST['address'];
    $description = $_POST['description'];

    $emailError = $contactError = "";

    $stmt = $con->prepare("SELECT id FROM supplier WHERE email = ? " . ($supplierId ? "AND id != ?" : ""));
    if ($supplierId) {
        $stmt->bind_param("si", $email, $supplierId);
    } else {
        $stmt->bind_param("s", $email);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $emailError = "Email already exists!";
    }

    $stmt = $con->prepare("SELECT id FROM supplier WHERE phone_number = ? " . ($supplierId ? "AND id != ?" : ""));
    if ($supplierId) {
        $stmt->bind_param("si", $contact, $supplierId);
    } else {
        $stmt->bind_param("s", $contact);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $contactError = "Phone number already exists!";
    }

    if (!$emailError && !$contactError) {
        if ($supplierId) {
            $stmt = $con->prepare("UPDATE supplier SET supplier_name=?, email=?, phone_number=?, country=?, city=?, address=?, description=? WHERE id=?");
            $stmt->bind_param("sssssssi", $supplierName, $email, $contact, $country, $city, $address, $description, $supplierId);
            $stmt->execute();
            header("Location: /Backend/src/Pages/supplierlist.php?updated=1");
        } else {
            $stmt = $con->prepare("INSERT INTO supplier (supplier_name, email, phone_number, country, city, address, description) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssss", $supplierName, $email, $contact, $country, $city, $address, $description);
            $stmt->execute();
            header("Location: /Backend/src/Pages/supplierlist.php?added=1");
        }
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
        content="admin, estimates, bootstrap, business, corporate, creative, management, minimal, modern,  html5, responsive">
    <meta name="author" content="Dreamguys - Bootstrap Admin Template">
    <meta name="robots" content="noindex, nofollow">
    <title>Dreams Pos admin template</title>
    <style>
        .parsley-required,
        .parsley-pattern {
            color: orangered;
        }

        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* --- Add Supplier Professional Overhaul --- */
        :root {
            --primary-blue: #6792ff;
            --border-color: #e2e8f0;
            --bg-light: #f8fafc;
        }

        .card {
            border: none !important;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            border-radius: 16px !important;
        }

        /* Form Groups & Labels */
        .form-group label {
            font-weight: 600;
            color: #4a5568;
            font-size: 0.85rem;
            margin-bottom: 8px;
            display: block;
        }

        /* Refined Inputs & Selects */
        .form-control,
        .form-select,
        .input-group-text,
        input[type="text"] {
            border: 1px solid var(--border-color) !important;
            border-radius: 8px !important;
            padding: 10px 14px !important;
            font-size: 0.9rem !important;
            background-color: var(--bg-light);
            transition: all 0.2s ease;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--primary-blue) !important;
            background-color: #fff;
            box-shadow: 0 0 0 3px rgba(103, 146, 255, 0.1) !important;
            outline: none;
        }

        /* Custom Input Group for Phone */
        .input-group-text {
            background-color: #edf2f7 !important;
            color: #4a5568;
            font-weight: 600;
            border-right: none !important;
        }

        #contact {
            border-top-left-radius: 0 !important;
            border-bottom-left-radius: 0 !important;
        }

        /* Action Buttons */
        .btn-submit {
            background: var(--primary-blue) !important;
            border: none;
            padding: 10px 30px;
            font-weight: 700;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(103, 146, 255, 0.25);
            color: white !important;
        }

        .btn-cancel {
            background: #f1f5f9 !important;
            color: #64748b !important;
            border: none;
            padding: 10px 25px;
            font-weight: 600;
            border-radius: 8px;
        }

        /* Error Styling */
        .parsley-required,
        .parsley-pattern {
            font-size: 0.75rem;
            margin-top: 4px;
            font-weight: 500;
        }

        .parsley-custom-error-message {
            color: #DC3545 !important;
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
                        <h4 class="fw-bold"><?= $supplierId ? 'Edit Supplier Profile' : 'Register New Supplier' ?></h4>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb" style="font-size: 0.85rem;">
                                <li class="breadcrumb-item"><a href="/Backend/src/Pages/supplierlist.php"
                                        class="text-primary">Suppliers</a></li>
                                <li class="breadcrumb-item active"><?= $supplierId ? 'Update' : 'New Entry' ?></li>
                            </ol>
                        </nav>
                    </div>
                    <div class="page-btn">
                        <a href="/Backend/src/Pages/supplierlist.php"
                            class="btn btn-sm btn-outline-secondary rounded-pill">
                            <i class="bi bi-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <form id="myForm" method="POST" data-parsley-validate>
                            <div class="row g-4">
                                <div class="col-lg-12">
                                    <h5 class="mb-3 text-primary fs-6 border-bottom pb-2">Business Information</h5>
                                    <div class="row">
                                        <div class="col-lg-4 col-sm-6 mb-3">
                                            <div class="form-group">
                                                <label>Supplier Name <span class="text-danger">*</span></label>
                                                <input type="text" id="supplierName" name="supplierName"
                                                    class="form-control" value="<?= htmlspecialchars($supplierName) ?>"
                                                    placeholder="e.g. Brancy Skincare Hub" data-parsley-required="true"
                                                    data-parsley-error-message="Supplier name is required.">
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-sm-6 mb-3">
                                            <div class="form-group">
                                                <label>Email Address <span class="text-danger">*</span></label>

                                                <input type="text" id="supplierMail" name="supplierEmail"
                                                    class="form-control <?= $emailError ? 'is-invalid' : '' ?>"
                                                    value="<?= htmlspecialchars($email) ?>"
                                                    placeholder="contact@supplier.com" data-parsley-required="true"
                                                    data-parsley-type="email"
                                                    data-parsley-error-message="Please enter a valid email address."
                                                    data-parsley-error-container="#emailError">

                                                <?php if (!empty($emailError)): ?>
                                                    <div class="text-danger small mt-1">
                                                        <?= $emailError ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-sm-6 mb-3">
                                            <div class="form-group">
                                                <label>Phone Number <span class="text-danger">*</span></label>

                                                <div class="input-group">
                                                    <span class="input-group-text">+91</span>
                                                    <input type="text" id="contact" name="phoneNumber"
                                                        class="form-control <?= !empty($contactError) ? 'is-invalid' : '' ?>"
                                                        value="<?= htmlspecialchars($contact) ?>"
                                                        placeholder="9876543210" maxlength="10"
                                                        data-parsley-required="true" data-parsley-type="digits"
                                                        data-parsley-length="[10,10]"
                                                        data-parsley-error-message="Please enter a valid 10-digit phone number."
                                                        data-parsley-errors-container="#contactError">
                                                </div>

                                                <div id="contactError" class="text-danger small mt-1">
                                                    <?= !empty($contactError) ? $contactError : '' ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <h5 class="mb-3 text-primary fs-6 border-bottom pb-2">Location Details</h5>
                                    <div class="row">
                                        <div class="col-lg-4 col-sm-6 mb-3">
                                            <div class="form-group">
                                                <label>Country</label>
                                                <select class="form-select" name="countrySelector" id="countrySelector"
                                                    required>
                                                    <option value="india" <?= $country == 'india' ? 'selected' : '' ?>>
                                                        India</option>
                                                    <option value="usa" <?= $country == 'usa' ? 'selected' : '' ?>>USA
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-sm-6 mb-3">
                                            <div class="form-group">
                                                <label>City</label>
                                                <select class="form-select" name="citySelector" id="citySelector"
                                                    required>
                                                    <option value="Nagpur" <?= $city == 'Nagpur' ? 'selected' : '' ?>>
                                                        Nagpur</option>
                                                    <option value="Bhandara" <?= $city == 'Bhandara' ? 'selected' : '' ?>>
                                                        Bhandara</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-sm-12 mb-3">
                                            <div class="form-group">
                                                <label>Office Address <span class="text-danger">*</span></label>
                                                <input type="text" name="address" class="form-control"
                                                    value="<?= htmlspecialchars($address) ?>"
                                                    placeholder="Street name, Building no." data-parsley-required="true"
                                                    data-parsley-error-message="Office address is required.">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>Business Description / Notes</label>
                                        <textarea class="form-control" name="description" rows="3"
                                            placeholder="Enter any additional notes about this supplier..."><?= htmlspecialchars($description) ?></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-5 pt-4 border-top d-flex justify-content-end gap-2">
                                <button class="btn btn-cancel" type="button" id="resetButton">
                                    <?= $supplierId ? 'Cancel' : 'Reset' ?>
                                </button>
                                <button class="btn btn-submit" name="submit" type="submit">
                                    <?= $supplierId ? 'Save Changes' : 'Register Supplier' ?>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <script>
        let parsleyForm = $('#myForm').parsley();
        $('#resetButton').on('click', function () {
            parsleyForm.reset();
        });
        $('#supplierName').on('input', function () {
            let value = $(this).val();
            value = value.replace(/[^a-zA-Z\s]/g, '');
            $(this).val(value);
        });
        $('#contact').on('input', function () {
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
        $('#resetButton').on('click', function () {
            if (<?= $supplierId ? 'true' : 'false' ?>) {
                window.location.href = "/Backend/src/Pages/supplierlist.php";
            }
        });
        $('#supplierMail').on('input', function () {
            let value = $(this).val();

            value = value.replace(/[^a-zA-Z0-9@.]/g, '');

            if (value.length === 1 && !/^[A-Za-z]$/.test(value)) {
                value = '';
            }

            $(this).val(value);
        });
    </script>
    <script>
        $('#myForm').parsley({
            errorsContainer: function (parsleyField) {
                let el = parsleyField.$element;

                if (el.closest('.input-group').length) {
                    return el.closest('.form-group').find('.text-danger');
                }
            },
            errorsWrapper: '<div></div>',
            errorTemplate: '<div></div>'
        });
    </script>
</body>

</html>