<?php
define("BASE_PATH", dirname(__DIR__, 2));
include BASE_PATH . "/src/Layouts/Links.php";
include BASE_PATH . "/src/controllers/dbConnection.php";

$sql = "SELECT * FROM `supplier`";
$result = $con->query($sql);

if (isset($_GET['supplierId'])) {
    $supplierId = intval($_GET['supplierId']);
    $deletesql = "DELETE FROM `supplier` WHERE `id` = ?";
    if ($stmt = $con->prepare($deletesql)) {
        $stmt->bind_param("i", $supplierId);
        if ($stmt->execute()) {
            header("Location: supplierlist.php");
            exit();
        }
        $stmt->close();
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
    <title>Dreams Pos admin template</title>
    <style>
        .toast-timer {
            height: 4px;
            width: 100%;
            background: rgba(231, 10, 10, 0.6);
            position: absolute;
            bottom: 0;
            left: 0;
            z-index: 999;
            animation: shrink 4s linear forwards;
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
                        <h4>Supplier List</h4>
                        <h6>Manage your Supplier</h6>
                    </div>
                    <div class="page-btn">
                        <a href="/Backend/src/Pages/supplier/supplierForm.php" class="btn btn-added"><img src="/Backend/src/assets/images/icons/plus.svg" alt="img">Add Supplier</a>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="table-top">
                            <div class="search-set">
                                <!-- <div class="search-path">
                                    <a class="btn btn-filter" id="filter_search">
                                        <img src="/Backend/src/assets/images/icons/filter.svg" alt="img">
                                        <span><img src="/Backend/src/assets/images/icons/closes.svg" alt="img"></span>
                                    </a>
                                </div> -->
                                <div class="search-input">
                                    <a class="btn btn-searchset"><img src="/Backend/src/assets/images/icons/search-white.svg" alt="img"></a>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table datanew">
                                <thead>
                                    <tr>
                                        <th>Supplier Name</th>
                                        <th>Email</th>
                                        <th>Phone Number</th>
                                        <th>City</th>
                                        <th>Country</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (!$result) {
                                        echo "Error: " . $con->error;
                                    } else {
                                        if ($result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                                echo "<tr>";
                                                echo "    <td class='productimgname'>";
                                                echo "        <a href='' class='text-truncate w-50' data-bs-toggle='tooltip' data-bs-title='" . $row['supplier_name'] . "'>" . $row['supplier_name'] . "</a>";
                                                echo "    </td>";
                                                echo "    <td><a href='/cdn-cgi/l/email-protection' class='__cf_email__' data-cfemail='b8ccd0d7d5d9cbf8ddc0d9d5c8d4dd96dbd7d5'>" . $row['email'] . "</a></td>";
                                                echo "    <td>" . $row['phone_number'] . "</td>";
                                                echo "    <td>" . $row['city'] . "</td>";
                                                echo "    <td>" . $row['country'] . "</td>";
                                                echo "    <td>";
                                                echo "        <a class='me-3 confirm-text' href='/Backend/src/Pages/supplier/supplierForm.php?supplierId=" . $row['id'] . "' data-bs-toggle='tooltip' data-bs-title='Edit'>";
                                                echo "            <img src='/Backend/src/assets/images/icons/edit.svg' alt='img'>";
                                                echo "        </a>";
                                                echo "        <a class='me-3 confirm-delete' href='/Backend/src/Pages/supplierlist.php?supplierId=" . $row['id'] . "' data-bs-toggle='tooltip' data-bs-title='Delete'>";
                                                echo "            <img src='/Backend/src/assets/images/icons/delete.svg' alt='img'>";
                                                echo "        </a>";
                                                echo "    </td>";
                                                echo "</tr>";
                                            }
                                        }
                                    } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index:1100;">
        <div id="actionToast" class="toast border-0 text-bg-success" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="5000" data-bs-autohide="true">
            <div class="d-flex">
                <div class="toast-body" id="toastMessage"></div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
            <div class="toast-timer"></div>
        </div>
    </div>
    <script>
        document.querySelectorAll('.confirm-delete').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();

                const deleteUrl = this.getAttribute('href');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "This product will be permanently deleted!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = deleteUrl;
                    }
                });
            });
        });

        document.addEventListener("DOMContentLoaded", function() {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('deleted') === '1') {
                const toastEl = document.getElementById('deleteToast');
                const toast = new bootstrap.Toast(toastEl);
                toast.show();
                window.history.replaceState({}, document.title, window.location.pathname);
            }
        });
    </script>
</body>
</html>