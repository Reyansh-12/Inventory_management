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
                                        
                                        <span></span>
                                    </a>
                                </div>
                                <div class="search-input">
                                    <a class="btn btn-searchset"></a>
                                </div>
                            </div>
                            <div class="wordset">
                                <ul>
                                    <li>
                                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="pdf"></a>
                                    </li>
                                    <li>
                                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="excel"></a>
                                    </li>
                                    <li>
                                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="print"></a>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="card" id="filter_inputs">
                            <div class="card-body pb-0">
                                <div class="row">
                                    <div class="col-lg-2 col-sm-6 col-12">
                                        <div class="form-group">
                                            <input type="text" placeholder="Enter User Name">
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-sm-6 col-12">
                                        <div class="form-group">
                                            <input type="text" placeholder="Enter Phone">
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-sm-6 col-12">
                                        <div class="form-group">
                                            <input type="text" placeholder="Enter Email">
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-sm-6 col-12">
                                        <div class="form-group">
                                            <input type="text" class="datetimepicker cal-icon" placeholder="Choose Date">
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-sm-6 col-12">
                                        <div class="form-group">
                                            <select class="select">
                                                <option>Disable</option>
                                                <option>Enable</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-1 col-sm-6 col-12 ms-auto">
                                        <div class="form-group">
                                            <a class="btn btn-filters ms-auto"></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table  datanew">
                                <thead>
                                    <tr>
                                        <th>
                                            <label class="checkboxs">
                                                <input type="checkbox">
                                                <span class="checkmarks"></span>
                                            </label>
                                        </th>
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
                                            echo "  <td>";
                                            echo "        <label class='checkboxs'>";
                                            echo "            <input type='checkbox'>";
                                            echo "            <span class='checkmarks'></span>";
                                            echo "        </label>";
                                            echo "    </td>";
                                            echo "    <td>".$row['user_name']."</td>";
                                            echo "    <td>".$row['user_contact']."</td>";
                                            echo "    <td><a href='/cdn-cgi/l/email-protection' class='__cf_email__' data-cfemail='fb8f9394969a88bb9e839a968b979ed5989496'>".$row['user_email']."</a> </td>";
                                            echo "    <td>".$row['user_role']."</td>";
                                            echo "    <td>".$row['created_at']."</td>";
                                            echo "    <td><span class='bg-lightgreen badges'>Active</span></td>";
                                            echo "    <td>";
                                            echo "        <a class='me-3' href='/Backend/src/Pages/Users/NewUser.php?userId=".$row['id']."'>";
                                            echo "            <img src='/Backend/src/assets/images/icons/edit.svg' alt='img'>";
                                            echo "        </a>";
                                            echo "        <a class='me-3 confirm-text'  href='/Backend/src/Pages/Users/UsersList.php?deleteId=".$row['id']."'>";
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
</body>

</html>