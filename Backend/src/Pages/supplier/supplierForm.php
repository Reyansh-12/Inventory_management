<?php 
define("BASE_PATH", dirname(__DIR__, 3));
include BASE_PATH . "/src/Layouts/Links.php";
include BASE_PATH . "/src/controllers/dbConnection.php";

$supplierId = $_GET['supplierId'] ?? null;
$supplierName = $email = $contact = $contry = $city = $address = $description = "";

if($supplierId) {
    $query = "SELECT * FROM `supplier` WHERE id='$supplierId'";
    $result = mysqli_query($con, $query);

    if($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

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

        if($supplierId) {
            $sql = "UPDATE `supplier` SET `supplier_name`='$supplierName',`email`='$email',`phone_number`='$contact',`country`='$country',`city`='$city',`address`='$address',`description`='$description'";
        } else {
            $sql = "INSERT INTO `supplier`(`supplier_name`, `email`, `phone_number`, `country`, `city`, `address`, `description`) 
            VALUES ('$supplierName','$email','$contact','$country','$city','$address','$description')";
        }
        mysqli_query($con, $sql);
        
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
                    <div class="page-title">
                        <h4>Supplier Management</h4>
                        <h6>Add/Update Customer</h6>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <form action="#" id="myForm" method="POST" data-parsley-validate>
                            <div class="row">
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for='supplierName'>Supplier Name</label>
                                    <input type="text" id="supplierName" name="supplierName" placeholder="Enter supplier name" data-parsley-required-message="Supplier Name is required" data-parsley-required>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for='supplierMail'>Email</label>
                                    <input type="text" id='supplierMail' name="supplierEmail" placeholder="Enter supplier email" data-parsley-required-message="Email is required" data-parsley-required>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="poneNumber">Phone</label>
                                    <input type="text" id="poneNumber" name="contact" placeholder="Enter phone number" data-parsley-required-message="Mobile number is required" data-parsley-required>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="countrySelector">Choose Country</label>
                                    <select class="form-select" name="countrySelector" id="countrySelector" data-parsley-required-message="Country is required" data-parsley-required>
                                        <option disabled selected>Choose Country</option>
                                        <option value="india">India</option>
                                        <option value="usa">USA</option>
                                     </select>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="citySelector">City</label>
                                    <select class="form-select" name="citySelector" id="citySelector" data-parsley-required-message="City is required" data-parsley-required>
                                        <option disabled selected>Choose City</option>
                                        <option value="india">Nagpur</option>
                                        <option value="usa">Bhandara</option>
                                     </select>
                                </div>
                            </div>
                            <div class="col-lg-9 col-12">
                                <div class="form-group">
                                    <label for="address">Address</label>
                                    <input type="text" id="address" name="address" placeholder="Enter supplier address" data-parsley-required-message="Address is required" data-parsley-required>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea class="form-control" id="description" name="description" placeholder="Enter description" data-parsley-required-message="Description Field is required" data-parsley-required></textarea>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label> Avatar</label>
                                    <div class="image-upload">
                                        <input type="file">
                                        <div class="image-uploads">
                                            <img src="/Backend/src/assets/images/icons/upload.svg" alt="img">
                                            <h4>Drag and drop a file to upload</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 d-flex justify-content-end">
                                <button class="btn btn-cancel me-2" type="reset" name="reset" id="resetButton">Reset</button>
                                <button class="btn btn-submit" name="submit" type="submit">Submit</button>
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
    </script>
</body>

</html>