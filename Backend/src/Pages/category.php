<?php
define("BASE_PATH", dirname(__DIR__, 2));
include BASE_PATH . "/src/Layouts/Links.php";
include BASE_PATH . "/src/controllers/dbConnection.php";

$sql = "SELECT * FROM `category`";
$result = $con->query($sql);

if (isset($_GET['categoryId'])) {
    $categoryId = intval($_GET['categoryId']);

    $stmt = $con->prepare("DELETE FROM category WHERE id = ?");
    $stmt->bind_param("i", $categoryId);

    if ($stmt->execute()) {
        header("Location: category.php?deleted=1");
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
    <title>Supplier List</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <style>
        .toast-timer {
            height: 4px;
            width: 100%;
            background: rgba(10, 231, 39, 0.6);
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

        .productimgname img {
            min-width: 40px;
            width: 40px;
            height: 40px;
            border: 0;
            object-fit: contain;
        }

        /* --- Category List Professional Polish --- */
        .card {
            border: none !important;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            border-radius: 12px !important;
        }

        /* Category Image & Text Alignment */
        .productimgname {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .productimgname img {
            min-width: 40px;
            width: 40px;
            height: 40px;
            border-radius: 8px;
            /* Rounded corners for modern look */
            object-fit: cover;
            border: 1px solid #edf2f9;
        }

        .productimgname a {
            font-weight: 600;
            color: #2d3748;
            text-decoration: none;
            font-size: 0.95rem;
            transition: color 0.2s;
        }

        .productimgname a:hover {
            color: #6792ff;
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
        }

        .table tbody td {
            padding: 15px !important;
            vertical-align: middle;
            color: #4a5568;
            border-bottom: 1px solid #f1f5f9;
        }

        /* Modern Tinted Badges */
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

        /* Action Icon Styling */
        .confirm-text img,
        .confirm-delete img {
            width: 18px;
            transition: opacity 0.2s;
        }

        .confirm-text:hover img,
        .confirm-delete:hover img {
            opacity: 0.7;
        }

        /* Ensure smooth truncation for table cells */
        .text-truncate {
            display: inline-block;
            vertical-align: middle;
        }

        /* Tooltip hover pointer */
        [data-bs-toggle="tooltip"] {
            cursor: pointer;
        }
        /* --- Professional Pagination & Entries Styling --- */
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
    background: #6792ff !important; /* Brancy Blue Theme */
    color: white !important;
    border-color: #6792ff !important;
    box-shadow: 0 4px 10px rgba(103, 146, 255, 0.25);
}

/* Entries Dropdown Styling */
.dataTables_length select {
    border: 1px solid #e2e8f0 !important;
    border-radius: 6px !important;
    padding: 4px 8px !important;
    outline: none !important;
    color: #4a5568 !important;
}

/* Pagination Row Alignment */
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
        <div class="page-wrapper">
            <div class="content">
                <div class="page-header">
                    <div class="page-title">
                        <h4>Category List</h4>
                        <h6>Manage your Categories</h6>
                    </div>
                    <div class="page-btn">
                        <a href="/Backend/src/Pages/category/addcategory.php" class="btn btn-added"><img
                                src="/Backend/src/assets/images/icons/plus.svg" alt="img">Add Category</a>
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
                                        <th>Category</th>
                                        <th>Brands</th>
                                        <th>created</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<tr>";
                                        echo "<td style='display:none;'>" . $row['id'] . "</td>";

                                        // Category Column with Image and Tooltip
                                        echo "    <td class='productimgname'>";
                                        echo "        <img src='" . $row['image_path'] . "' alt='cat-img' class='rounded-2 shadow-sm'>";
                                        echo "        <a href='javascript:void(0);' class='text-truncate' style='max-width: 150px;' data-bs-toggle='tooltip' title='" . htmlspecialchars($row['category']) . "'>" . htmlspecialchars($row['category']) . "</a>";
                                        echo "    </td>";

                                        // Brands Column with Max-Width & Tooltip
                                        echo "    <td>";
                                        echo "        <div class='text-truncate' style='max-width: 180px; cursor: help;' data-bs-toggle='tooltip' title='" . htmlspecialchars($row['brands']) . "'>";
                                        echo htmlspecialchars($row['brands']);
                                        echo "        </div>";
                                        echo "    </td>";

                                        // Created Date Formatting
                                        echo "    <td class='text-muted' style='font-size: 0.85rem;'>" . date('M d, Y', strtotime($row['created_on'])) . "</td>";

                                        // Status Badge
                                        $statusClass = strtolower($row['status']) === 'active' ? 'bg-success' : 'bg-danger';
                                        echo "    <td><span class='badge $statusClass'>" . strtoupper($row['status']) . "</span></td>";

                                        // Actions
                                        echo "    <td>";
                                        echo "        <a class='me-3 confirm-text' href='/Backend/src/Pages/category/addcategory.php?categoryId=" . $row['id'] . "' data-bs-toggle='tooltip' title='Edit'>";
                                        echo "            <img src='/Backend/src/assets/images/icons/edit.svg' alt='edit' width='18'>";
                                        echo "        </a>";
                                        echo "        <a class='me-3 confirm-delete' href='/Backend/src/Pages/category.php?categoryId=" . $row['id'] . "' data-bs-toggle='tooltip' title='Delete'>";
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
        <div id="actionToast" class="toast border-0" role="alert">
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
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
                toastMsg.innerText = "Category added successfully!";
                toastEl.classList.add("bg-success");
            } else if (params.get("updated") === "1") {
                toastMsg.innerText = "Category updated successfully!";
                toastEl.classList.add("bg-success");
            } else if (params.get("deleted") === "1") {
                toastMsg.innerText = "Category deleted successfully!";
                toastEl.classList.add("bg-danger");
            } else {
                return;
            }
            const toast = new bootstrap.Toast(toastEl, {
                delay: 3000
            });
            toast.show();

            setTimeout(() => {
                window.history.replaceState({}, document.title, window.location.pathname);
            }, 3500);
        });
    </script>
    <script>
        $(document).ready(function () {
            if ($.fn.DataTable.isDataTable('.datanew')) {
                $('.datanew').DataTable().destroy();
            }
            $('.datanew').DataTable({
                order: [
                    [0, 'desc']
                ],
                columnDefs: [{
                    targets: 0,
                    visible: false,
                    searchable: false
                },
                {
                    targets: '_all',
                    orderable: true
                }
                ],
                autoWidth: false,
                responsive: false,
                searching: false,
                lengthChange: true,
                pageLength: 10,
                pagingType: "simple_numbers",
                dom: 'rt<"row mt-3"<"col-md-6"l><"col-md-6 text-end"p>>'
            });
        });
        $(document).ready(function () {
            // Check if table is already a DataTable, then destroy it cleanly
            if ($.fn.DataTable.isDataTable('.datanew')) {
                $('.datanew').DataTable().destroy();
            }

            var table = $('.datanew').DataTable({
                destroy: true, // Purani instance ko khatam karke nayi start karega
                order: [[0, 'desc']],
                columnDefs: [
                    { targets: 0, visible: false, searchable: false }
                ],
                autoWidth: false,
                searching: false,
                pageLength: 10,
                dom: 'rt<"row mt-3"<"col-md-6"l><"col-md-6 text-end"p>>',

                // Tooltip logic for pagination/search consistency
                drawCallback: function () {
                    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                    tooltipTriggerList.map(function (tooltipTriggerEl) {
                        return new bootstrap.Tooltip(tooltipTriggerEl);
                    });
                }
            });
        });
        $(document).ready(function () {
    // Prevent re-initialization error by checking if it exists
    if ($.fn.DataTable.isDataTable('.datanew')) {
        $('.datanew').DataTable().destroy();
    }

    $('.datanew').DataTable({
        "order": [[0, 'desc']],
        "columnDefs": [
            { targets: 0, visible: false, searchable: false }
        ],
        "autoWidth": false,
        "pageLength": 10,
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]], // "Show entries" option
        "language": {
            "paginate": {
                "next": '<i class="bi bi-chevron-right"></i>',
                "previous": '<i class="bi bi-chevron-left"></i>'
            },
            "lengthMenu": "Show _MENU_ entries",
            "info": "Showing _START_ to _END_ of _TOTAL_ categories"
        },
        // 'l' = length (show per page), 'r' = processing, 't' = table, 'i' = info, 'p' = pagination
        "dom": '<"row mb-3"<"col-md-6"l><"col-md-6 text-end"f>>rt<"table-footer-row"ip>', 
        "drawCallback": function() {
            // IMPORTANT: Re-initialize tooltips every time the table redraws (pagination/search)
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