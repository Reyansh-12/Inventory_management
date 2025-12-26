<?php
define("BASE_PATH", dirname(__DIR__, 3));
include BASE_PATH . "/src/Layouts/Links.php";
include BASE_PATH . "/src/controllers/dbConnection.php";

if (isset($_GET['deleteId'])) {
    $deleteId = $_GET['deleteId'];
    $deleteQuery = "DELETE FROM new_user WHERE id = $deleteId";
    $con->query($deleteQuery);

    header("Location: UsersList.php?deleted=1");
    exit;

}

$sql = "SELECT `id`, `user_name`, `user_contact`, `user_email`, `user_role`, `user_password`, `status`, `created_at` FROM `new_user`";
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
    <title>User List</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <style>
        /* .toast-timer {
            height: 4px;
            width: 100%;
            background: rgba(231, 10, 10, 0.6);
            position: absolute;
            bottom: 0;
            left: 0;
            z-index: 999;
            animation: shrink 4s linear forwards;
        } */
        .swal2-icon-content{
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
    </div>
    <div class="page-wrapper">
        <div class="content">
            <div class="page-header">
                <div class="page-title">
                    <h4>User List</h4>
                    <h6>Manage your User</h6>
                </div>
                <div class="page-btn">
                    <a href="/Backend/src/Pages/Users/NewUser.php" class="btn btn-added"><img src="/Backend/src/assets/images/icons/plus.svg" alt="img">Add User</a>
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
                                    <th style="display:none;">ID</th>
                                    <th>User name </th>
                                    <th>Phone</th>
                                    <th>email</th>
                                    <th>Role</th>
                                    <th>Created On</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<tr>";
                                        echo "<td style='display:none;'>" . $row['id'] . "</td>";
                                        echo "    <td style='width: 200px'><a class='d-inline-block text-truncate w-100' data-bs-toggle='tooltip' data-bs-title='".$row['user_name']."'>" . $row['user_name'] . "</a></td>";
                                        echo "    <td>" . $row['user_contact'] . "</td>";
                                        echo "    <td><a>" . $row['user_email'] . "</a> </td>";
                                        echo "    <td>" . $row['user_role'] . "</td>";
                                        echo "    <td>" . $row['created_at'] . "</td>";
                                        $statusClass = strtolower($row['status']) === 'active' ? 'bg-success' : 'bg-danger';
                                        echo "<td><span class='badge $statusClass shadow-sm'>" . htmlspecialchars($row['status']) . "</span></td>";
                                        echo "    <td>";
                                        echo "        <a class='me-3' href='/Backend/src/Pages/Users/NewUser.php?userId=" . $row['id'] . "' data-bs-toggle='tooltip' data-bs-title='Edit'>";
                                        echo "            <img src='/Backend/src/assets/images/icons/edit.svg' alt='img'>";
                                        echo "        </a>";
                                        echo "        <a class='me-3 confirm-delete'  href='/Backend/src/Pages/Users/UsersList.php?deleteId=" . $row['id'] . "' data-bs-toggle='tooltip' data-bs-title='Delete'>";
                                        echo "            <img src='/Backend/src/assets/images/icons/delete.svg' alt='img'>";
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
    
<div class="toast position-fixed top-0 end-0 m-3 text-white" style="z-index: 1100" id="actionToast" role="alert">
<div class="d-flex">
    <div class="toast-body" id="toastMessage"></div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto"data-bs-dismiss="toast"></button>
    </div>
</div>
<?php if (!empty($_SESSION['toast'])): ?>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const toastEl = document.getElementById("actionToast");
        const toastMsg = document.getElementById("toastMessage");

        toastEl.classList.add("bg-<?= $_SESSION['toast']['type'] ?>");
        toastMsg.innerText = "<?= $_SESSION['toast']['msg'] ?>";

        new bootstrap.Toast(toastEl, { delay: 3000 }).show();
    });
</script>
<?php unset($_SESSION['toast']); endif; ?>
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

document.addEventListener("DOMContentLoaded", function () {
    const params = new URLSearchParams(window.location.search);
    const toastEl = document.getElementById("actionToast");
    const toastMsg = document.getElementById("toastMessage");

    toastEl.classList.remove("bg-success", "bg-danger");

    if (params.get("added") === "1") {
        toastMsg.innerText = "User added successfully!";
        toastEl.classList.add("bg-success");
    }
    else if (params.get("updated") === "1") {
        toastMsg.innerText = "User updated successfully!";
        toastEl.classList.add("bg-success");
    }
    else if (params.get("deleted") === "1") {
        toastMsg.innerText = "User deleted successfully!";
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

    if ($.fn.DataTable.isDataTable('.datanew')) {
        $('.datanew').DataTable().destroy();
    }

    $('.datanew').DataTable({
        order: [[0, 'desc']],

        columnDefs: [
            { targets: 0, visible: false, searchable: false },
            { targets: '_all', orderable: true } 
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
</script>
</body>
</html>