<?php
define("BASE_PATH", dirname(__DIR__, 2));
include BASE_PATH . "/src/Layouts/Links.php";
include BASE_PATH . "/src/controllers/dbConnection.php";

$sql = "SELECT `id`, `product_name`, `expired_date`, `image_path` FROM `product_list` WHERE expired_date <= CURDATE()";
$result = $con->query($sql);

$result = $con->query($sql);
if (isset($_GET['deleteId'])) {
    $expiredProductId = $_GET['deleteId'];

    $stmt = $con->prepare("DELETE FROM product_list WHERE id = ?");
    $stmt->bind_param("i", $expiredProductId);
    $stmt->execute();
    $stmt->close();

    header("Location: expiredProduct.php?deleted=1");
    exit();
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

    .swal2-icon-content {
        margin-left: 537%;
        margin-top: 48%;
    }

    .productimgname img {
        min-width: 40px;
        width: 40px;
        height: 40px;
        border: 0;
        object-fit: contain;
    }

    .dataTables_info {
        display: none;
    }
    /* --- Expired Products Professional Overhaul --- */
:root {
    --primary-blue: #6792ff;
    --danger-soft: rgba(255, 107, 107, 0.15);
    --danger-text: #dc3545;
    --card-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
}

.card {
    border: none !important;
    box-shadow: var(--card-shadow);
    border-radius: 12px !important;
}

/* Image & Product Name Alignment */
.productimgname {
    display: flex;
    align-items: center;
    gap: 12px;
}

.productimgname img {
    min-width: 45px;
    width: 45px;
    height: 45px;
    border-radius: 8px;
    object-fit: cover;
    border: 1px solid #edf2f9;
}

.productimgname a {
    font-weight: 600;
    color: #2d3748;
    text-decoration: none;
    transition: color 0.2s;
}

.productimgname a:hover {
    color: var(--primary-blue);
}

/* Table Header & Rows */
.table thead th {
    background-color: #f8f9fa;
    text-transform: uppercase;
    font-size: 11px;
    letter-spacing: 0.5px;
    font-weight: 700;
    color: #6c757d;
    padding: 15px !important;
    border-bottom: 1px solid #edf2f9;
}

.table tbody td {
    padding: 15px !important;
    vertical-align: middle;
}

/* Expired Date Badge Look */
.expired-date {
    color: var(--danger-text);
    background: var(--danger-soft);
    padding: 5px 12px;
    border-radius: 6px;
    font-size: 0.85rem;
    font-weight: 600;
    display: inline-block;
}

/* DataTable Pagination Polish */
.dataTables_paginate .page-link {
    border: none !important;
    border-radius: 6px !important;
    margin: 0 2px;
    font-weight: 600;
}
/* --- Professional Pagination Overhaul --- */
.dataTables_wrapper .dataTables_paginate {
    margin-top: 20px;
    padding-top: 15px;
    border-top: 1px solid #edf2f9;
}

.dataTables_wrapper .dataTables_paginate .paginate_button {
    padding: 6px 14px !important;
    margin: 0 3px !important;
    border: 1px solid #e2e8f0 !important;
    border-radius: 8px !important;
    background: #fff !important;
    color: #64748b !important;
    font-weight: 600;
    font-size: 0.85rem;
    transition: all 0.2s ease;
}

.dataTables_wrapper .dataTables_paginate .paginate_button.current,
.dataTables_wrapper .dataTables_paginate .paginate_button:hover {
    background: var(--primary-blue) !important;
    color: white !important;
    border-color: var(--primary-blue) !important;
    box-shadow: 0 4px 10px rgba(103, 146, 255, 0.25);
}

.dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
    opacity: 0.5;
    cursor: not-allowed;
    background: #f8fafc !important;
}

/* Symmetry for Info & Pagination Row */
.table-footer-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px 0;
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
                        <h4>Expired Products</h4>
                        <h6>Manage your expired products</h6>
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
                                    <a class="btn btn-searchset"><img
                                            src="/Backend/src/assets/images/icons/search-white.svg" alt="img"></a>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table datanew">
                                <thead>
                                    <tr>
                                        <th>Product Name</th>
                                        <th>Expired date </th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        // Product Column with Image & Tooltip
        echo "    <td class='productimgname'>";
        echo "        <img src='" . $row['image_path'] . "' alt='product'>";
        echo "        <a href='javascript:void(0);' class='text-truncate' style='max-width: 350px;' data-bs-toggle='tooltip' title='" . htmlspecialchars($row['product_name']) . "'>" . htmlspecialchars($row['product_name']) . "</a>";
        echo "    </td>";

        // Expired Date with Red Alert Style
        echo "    <td><span class='expired-date'>" . date('M d, Y', strtotime($row['expired_date'])) . "</span></td>";

        // Action Column
        echo "    <td>";
        echo "        <a class='confirm-delete btn btn-sm btn-outline-danger border-0' href='/Backend/src/Pages/expiredProduct.php?deleteId=" . $row['id'] . "' data-bs-toggle='tooltip' title='Remove Expired Product'>";
        echo "            <img src='/Backend/src/assets/images/icons/delete.svg' alt='delete' width='18'>";
        echo "        </a>";
        echo "    </td>";
        echo "</tr>";
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
        <div id="actionToast" class="toast border-0 bg-danger" role="alert" aria-live="assertive" aria-atomic="true"
            data-bs-delay="5000" data-bs-autohide="true">
            <div class="d-flex">
                <div class="toast-body text-white" id="toastMessage">Delete Successfully!</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
            <!-- <div class="toast-timer"></div> -->
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
            const toastEl = document.getElementById('actionToast');
            const toast = new bootstrap.Toast(toastEl);
            toast.show();
            window.history.replaceState({}, document.title, window.location.pathname);
        }
    });
    $(document).ready(function() {
    // Prevent re-initialization error
    if ($.fn.DataTable.isDataTable('.datanew')) {
        $('.datanew').DataTable().destroy();
    }

    $('.datanew').DataTable({
        "order": [[1, "asc"]], // Sort by date
        "searching": true,
        "pageLength": 10,
        "autoWidth": false,
        "dom": 'rt<"row mt-3"<"col-md-6"l><"col-md-6 text-end"p>>',
        "drawCallback": function() {
            // Re-initialize tooltips after every table redraw
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });
        }
    });
});
$(document).ready(function() {
    // Prevent re-initialization error
    if ($.fn.DataTable.isDataTable('.datanew')) {
        $('.datanew').DataTable().destroy();
    }

    $('.datanew').DataTable({
        "order": [[1, "asc"]], // Sort by Expiry Date (Oldest first)
        "searching": true,
        "pageLength": 10,
        "autoWidth": false,
        "language": {
            "paginate": {
                "next": '<i class="bi bi-chevron-right"></i>',
                "previous": '<i class="bi bi-chevron-left"></i>'
            },
            "info": "Showing _START_ to _END_ of _TOTAL_ expired products"
        },
        // Layout: Table (rt), then Footer Row with Info (i) and Pagination (p)
        "dom": 'rt<"table-footer-row"ip>', 
        "drawCallback": function() {
            // Re-initialize tooltips after every table redraw (search/pagination)
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });
        }
    });
});
    </script>
</body>
</html>