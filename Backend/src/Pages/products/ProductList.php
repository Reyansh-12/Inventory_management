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
$sql = "
SELECT 
    id,
    product_name,
    category,
    brand_name,
    price,
    quantity,
    image_path,
    status
FROM product_list
ORDER BY id DESC
";


$result = $con->query($sql);

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
    <title>BRANCY – Cosmetic Store</title>
    <!-- <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script> -->
    <!-- <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css"> -->
    <!-- <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script> -->
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

        .dataTables_paginate .pagination {
            justify-content: end;
        }

        .dataTables_paginate .page-item .page-link {
            border-radius: 6px;
            margin: 0 3px;
            border: 1px solid #dee2e6;
            color: #333;
        }

        .dataTables_paginate .page-item.active .page-link {
            background-color: #0d6efd;
            border-color: #0d6efd;
            color: #fff;
        }

        .dataTables_paginate .page-item.disabled .page-link {
            opacity: 0.5;
        }

        table.dataTable thead .sorting:before,
        table.dataTable thead .sorting:after,
        table.dataTable thead .sorting_asc:before,
        table.dataTable thead .sorting_asc:after,
        table.dataTable thead .sorting_desc:before,
        table.dataTable thead .sorting_desc:after {
            display: none !important;
        }

        .card {
            border: none !important;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            border-radius: 12px !important;
        }

        .productimgname {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .product-img img {
            width: 45px;
            height: 45px;
            border-radius: 8px;
            object-fit: cover;
            border: 1px solid #edf2f9;
            transition: transform 0.2s ease;
        }

        .product-img:hover img {
            transform: scale(1.1);
        }

        .productimgname a {
            font-weight: 600;
            color: #2d3748;
            text-decoration: none;
            font-size: 0.95rem;
        }

        .productimgname a:hover {
            color: #6792ff;
        }

        .table thead th {
            background-color: #f8f9fa;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 0.5px;
            font-weight: 700;
            color: #6c757d;
            border-bottom: 1px solid #edf2f9;
            padding: 15px !important;
        }

        .table tbody td {
            padding: 15px !important;
            vertical-align: middle;
            color: #4a5568;
            border-bottom: 1px solid #f1f5f9;
        }

        .badge {
            padding: 6px 12px;
            font-weight: 600;
            border-radius: 6px;
            font-size: 0.75rem;
        }

        .badge.bg-success {
            background-color: rgba(32, 201, 151, 0.15) !important;
            color: #198754;
        }

        .badge.bg-danger {
            background-color: rgba(255, 107, 107, 0.15) !important;
            color: #dc3545;
        }

        .action-icons a {
            display: inline-flex;
            padding: 8px;
            border-radius: 6px;
            transition: all 0.2s;
        }

        .action-icons a:hover {
            background: #f1f5f9;
        }

        .dataTables_paginate .page-link {
            border: none !important;
            padding: 8px 14px;
            margin: 0 2px;
            font-weight: 600;
            font-size: 0.85rem;
        }

        .table-responsive {
            overflow-x: hidden !important;
        }

        .table-responsive {
            overflow-x: hidden !important;
        }

        table {
            table-layout: fixed;
            width: 100%;
        }

        td,
        th {
            white-space: normal;
            overflow-wrap: break-word;
        }

        .table td {
            vertical-align: middle !important;
        }

        .text-truncate {
            display: inline-block;
            vertical-align: bottom;
            cursor: help;
        }

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
            background: #6792ff !important;
            color: white !important;
            border-color: #6792ff !important;
            box-shadow: 0 4px 10px rgba(103, 146, 255, 0.25);
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
            opacity: 0.5;
            cursor: not-allowed;
            background: #f8fafc !important;
        }

        .table-footer-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
        }

        div.dataTables_wrapper div.dataTables_length label {
            display: none;
        }

        .table-footer-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 15px;
            padding: 10px 0;
        }

        .dataTables_info {
            font-size: 0.85rem;
            color: #64748b;
            font-weight: 500;
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
                        <a href="/Backend/src/Pages/products/addProductForm.php" class="btn btn-added"><img
                                src="/Backend/src/assets/images/icons/plus.svg" alt="img" class="me-1">Add New
                            Product</a>
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
                                        <th style="display:none;">ID</th>
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
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<tr>";
                                        echo "<td style='display:none;'>" . $row['id'] . "</td>";

                                        echo "    <td class='productimgname'>";
                                        echo "        <a href='javascript:void(0)' class='product-img'>";
                                        echo "            <img src='". $row['image_path'] . "' alt='product'>";
                                        echo "        </a>";
                                        echo "        <a href='javascript:void(0);' class='text-truncate' style='max-width: 180px;' data-bs-toggle='tooltip' title='" . htmlspecialchars($row['product_name']) . "'>" . htmlspecialchars($row['product_name']) . "</a>";
                                        echo "    </td>";

                                        echo "    <td>";
                                        echo "        <div class='text-truncate' style='max-width: 120px;' data-bs-toggle='tooltip' title='" . htmlspecialchars($row['category']) . "'>";
                                        echo htmlspecialchars($row['category']);
                                        echo "        </div>";
                                        echo "    </td>";

                                        echo "    <td>";
                                        echo "        <div class='text-truncate' style='max-width: 120px;' data-bs-toggle='tooltip' title='" . htmlspecialchars($row['brand_name']) . "'>";
                                        echo htmlspecialchars($row['brand_name']);
                                        echo "        </div>";
                                        echo "    </td>";

                                        echo "    <td class='fw-bold'>₹ " . number_format($row['price'], 2) . "</td>";
                                        echo "    <td><span class='text-muted'>" . $row['quantity'] . " Units</span></td>";

                                        $statusClass = strtolower($row['status']) === 'active' ? 'bg-success' : 'bg-danger';
                                        echo "<td><span class='badge $statusClass'>" . strtoupper($row['status']) . "</span></td>";

                                        echo "    <td class='action-icons'>";
                                        echo "        <a class='me-2' href='/Backend/src/Pages/products/addProductForm.php?productId=" . $row['id'] . "' data-bs-toggle='tooltip' title='Edit'>";
                                        echo "            <img src='/Backend/src/assets/images/icons/edit.svg' alt='edit' width='18'>";
                                        echo "        </a>";
                                        echo "        <a class='confirm-delete' href='/Backend/src/Pages/products/ProductList.php?deleteId=" . $row['id'] . "' data-bs-toggle='tooltip' title='Delete'>";
                                        echo "            <img src='/Backend/src/assets/images/icons/delete.svg' alt='delete' width='18'>";
                                        echo "        </a>";
                                        echo "    </td>";
                                        echo "</tr>";
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

    <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999;">
        <div id="actionToast" class="toast align-items-center border-0" role="alert">
            <div class="d-flex">
                <div class="toast-body text-white" id="toastMessage"></div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
            <div class="toast-timer" style="height:4px;background:rgba(255,255,255,0.6);
             animation: shrink 3s linear forwards;"></div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    
    <script>
        document.querySelectorAll('.confirm-delete').forEach(btn => {
            btn.addEventListener('click', function (e) {
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


        document.addEventListener("DOMContentLoaded", function () {
            const params = new URLSearchParams(window.location.search);
            const toastEl = document.getElementById("actionToast");
            const toastMsg = document.getElementById("toastMessage");

            toastEl.classList.remove("bg-success", "bg-danger");

            if (params.get("added") === "1") {
                toastMsg.innerText = "Product added successfully!";
                toastEl.classList.add("bg-success");
            }
            else if (params.get("updated") === "1") {
                toastMsg.innerText = "Product updated successfully!";
                toastEl.classList.add("bg-success");
            }
            else if (params.get("deleted") === "1") {
                toastMsg.innerText = "Product deleted successfully!";
                toastEl.classList.add("bg-danger");
            }
            else {
                return;
            }

            const toast = new bootstrap.Toast(toastEl, { delay: 3000 });
            toast.show();

            setTimeout(() => {
                window.history.replaceState({}, document.title, window.location.pathname);
            }, 3500);
        });
    </script>
    <script>
        $(document).ready(function () {
            // Prevent re-initialization error
            if ($.fn.DataTable.isDataTable('.datanew')) {
                $('.datanew').DataTable().destroy();
            }

            $('.datanew').DataTable({
                "order": [[0, 'desc']],
                "columnDefs": [
                    { targets: 0, visible: false, searchable: false },
                    { targets: '_all', orderable: true }
                ],
                "autoWidth": false,
                "pageLength": 10,
                "searching": false,      // Top Right Search Hata diya
                "lengthChange": false,   // Top Left Entries Dropdown Hata diya
                "language": {
                    "paginate": {
                        "next": '<i class="bi bi-chevron-right"></i>',
                        "previous": '<i class="bi bi-chevron-left"></i>'
                    },
                    "info": "Showing _START_ to _END_ of _TOTAL_ products"
                },
                // 'rt' = Table only, 'i' = Info, 'p' = Pagination
                "dom": 'rt<"table-footer-row"ip>',
                "drawCallback": function () {
                    // Re-initialize tooltips after every table redraw
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