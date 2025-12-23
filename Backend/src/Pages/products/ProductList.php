<?php
define("BASE_PATH", dirname(__DIR__, 3));
include BASE_PATH . "/src/Layouts/Links.php";
include BASE_PATH . "/src/controllers/dbConnection.php";

if (isset($_GET['deleteId'])) {
    $productId = intval($_GET['deleteId']);
    $stmt = $con->prepare("DELETE FROM product_list WHERE id = ?");
    $stmt->bind_param("i", $productId);
    $stmt->execute();

    header("Location: ProductList.php?deleted=1");
    exit;
}
$sql = "SELECT `id`,`product_name`, `category`, `brand_name`, `price`, `quantity`, `image_path`, `status` FROM `product_list`";
$result = $con->query($sql);
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
    <title>Cosmetic product form</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

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
        .swal2-icon-content{
            margin-left: 537%;
            margin-top: 48%;
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
        <div class="page-wrapper" style="padding-top: 40px;">
            <div class="content">
                <div class="page-header mt-3">
                    <div class="page-title">
                        <h4>Cosmetic Product List</h4>
                        <h6>Manage your products</h6>
                    </div>
                    <div class="page-btn">
                        <a href="/Backend/src/Pages/products/addProductForm.php" class="btn btn-added"><img src="/Backend/src/assets/images/icons/plus.svg" alt="img" class="me-1">Add New Product</a>
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
                                        <th>Product Name</th>
                                        <th>Category </th>
                                        <th>Brand</th>
                                        <th>price</th>
                                        <th>Qty</th>
                                        <th>Status</th>
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
                                                echo "        <a href='javascript:void(0)' class='product-img'>";
                                                echo "            <img src='" . $row['image_path'] . "' alt='product' class=''>";
                                                echo "        </a>";
                                                echo "        <a href='javascript:void(0);' class='text-truncate w-50' data-bs-toggle='tooltip' data-bs-title='" . $row['product_name'] . "'>" . $row['product_name'] . "</a>";
                                                echo "    </td>";
                                                echo "    <td>" . $row['category'] . "</td>";
                                                echo "    <td>" . $row['brand_name'] . "</td>";
                                                echo "    <td>" . $row['price'] . "</td>";
                                                echo "    <td>" . $row['quantity'] . "</td>";
                                                $statusClass = strtolower($row['status']) === 'active' ? 'bg-success' : 'bg-danger';
                                                echo "<td><span class='badge $statusClass shadow-sm'>" . htmlspecialchars($row['status']) . "</span></td>";
                                                echo "    <td>";
                                                echo "        <a class='me-3' href='/Backend/src/Pages/products/addProductForm.php?productId=" . $row['id'] . "' data-bs-toggle='tooltip' data-bs-title='Edit'>";
                                                echo "            <img src='/Backend/src/assets/images/icons/edit.svg' alt='img'>";
                                                echo "        </a>";
                                                echo "        <a class='confirm-delete' href='/Backend/src/Pages/products/ProductList.php?deleteId=" . $row['id'] . "' data-bs-toggle='tooltip' data-bs-title='Delete'>";
                                                echo "            <img src='/Backend/src/assets/images/icons/delete.svg' alt='img'>";
                                                echo "        </a>";
                                                echo "    </td>";
                                                echo "</tr>";
                                            }
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index:1100;">
        <div id="actionToast"
            class="toast border-0 text-bg-success"
            role="alert"
            aria-live="assertive"
            aria-atomic="true"
            data-bs-delay="5000"
            data-bs-autohide="true">
            <div class="d-flex">
                <div class="toast-body" id="toastMessage"></div>
                <button type="button"
                    class="btn-close btn-close-white me-2 m-auto"
                    data-bs-dismiss="toast"></button>
            </div>
            <div class="toast-timer"></div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
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