<?php
define("BASE_PATH", dirname(__DIR__, 3));
include BASE_PATH . "/src/Layouts/Links.php";
include BASE_PATH . "/src/controllers/dbConnection.php";

if (isset($_GET['deleteId'])) {
    $deleteId = $_GET['deleteId'];
    $deleteQuery = "DELETE FROM new_user WHERE id = $deleteId";
    $con->query($deleteQuery);

    header("Location: UsersList.php");
    exit;
}

$sql = "SELECT `id`, `user_name`, `user_email`, `user_contact`, `user_role`, `user_password`, `status`, `created_at` FROM `new_user`";
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
                                <div class="search-path">
                                    <a class="btn btn-filter" id="filter_search">
                                        <img src="/Backend/src/assets/images/icons/filter.svg" alt="img">
                                        <span><img src="/Backend/src/assets/images/icons/closes.svg" alt="img"></span>
                                    </a>
                                </div>
                                <div class="search-input">
                                    <a class="btn btn-searchset"><img src="/Backend/src/assets/images/icons/search-white.svg" alt="img"></a>
                                </div>
                            </div>
                            <div class="wordset">
                                <ul>
                                    <li>
                                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="pdf"><img src="/Backend/src/assets/images/icons/pdf.svg" alt="img"></a>
                                    </li>
                                    <li>
                                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="excel"><img src="/Backend/src/assets/images/icons/excel.svg" alt="img"></a>
                                    </li>
                                    <li>
                                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="print"><img src="/Backend/src/assets/images/icons/printer.svg" alt="img"></a>
                                    </li>
                                </ul>
                            </div>
                        </div>

                    <div class="table-responsive">
                        <table class="table  datanew">
                            <thead>
                                <tr>
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
                                        echo "    <td>" . $row['user_name'] . "</td>";
                                        echo "    <td>" . $row['user_contact'] . "</td>";
                                        echo "    <td><a href='/cdn-cgi/l/email-protection' class='__cf_email__' data-cfemail='fb8f9394969a88bb9e839a968b979ed5989496'>" . $row['user_email'] . "</a> </td>";
                                        echo "    <td>" . $row['user_role'] . "</td>";
                                        echo "    <td>" . $row['created_at'] . "</td>";
                                        echo "    <td><span class='bg-lightgreen badges'>Active</span></td>";
                                        echo "    <td>";
                                        echo "        <a class='me-3' href='/Backend/src/Pages/Users/NewUser.php?userId=" . $row['id'] . "'>";
                                        echo "            <img src='/Backend/src/assets/images/icons/edit.svg' alt='img'>";
                                        echo "        </a>";
                                        echo "        <a class='me-3 confirm-delete'  href='/Backend/src/Pages/Users/UsersList.php?deleteId=" . $row['id'] . "'>";
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
    <div class="toast-container position-fixed end-0 p-3" style="z-index: 1100; top: 60px;">
    <div id="deleteToast" class="toast align-items-center text-bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body bg-danger" id="deleteToastMessage">
                Item deleted successfully.
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>
    <script>
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".confirm-delete").forEach(btn => {
        btn.addEventListener("click", function (e) {
            e.preventDefault();
            const deleteUrl = this.getAttribute("href");
            document.getElementById("deleteToastMessage").textContent = "User successfully deleted!";
            var toast = new bootstrap.Toast(document.getElementById('deleteToast'));
            toast.show();
            setTimeout(() => {
                window.location.href = deleteUrl;
            }, 3000); 
        });
    });
});
</script>
</body>
</html>