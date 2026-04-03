<?php
define("BASE_PATH", dirname(__DIR__, 3));
include BASE_PATH . "/src/Layouts/Links.php";
include BASE_PATH . "/src/controllers/dbConnection.php";

$order_list_query = "SELECT `id`, `order_id`, `customer`, `product`, `category`, `brand`, `quantity`, `status`, `total_amount`, `price`, `image_path`, `created`, `seen` FROM `order_list` ORDER BY `id` DESC";
$resut = mysqli_query($con, $order_list_query);

mysqli_query($con, "UPDATE order_list SET seen = 1 WHERE seen = 0");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <title>All Notifications</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="/Backend/src/assets/css/history.css">
    <link rel="stylesheet" href="/Backend/src/assets/css/notification.css">

    <style>
        .status-dropdown {
            font-size: 11px;
            padding: 4px 8px;
            border-radius: 6px;
            border: 1px solid #ddd;
            outline: none;
            cursor: pointer;
            background: #f8f9fa;
        }
        .status-dropdown:focus { border-color: #2874f0; }
        .swal2-icon{
            width: 100%;
        }
    </style>
</head>

<body>
    <div class="main-wrapper">
        <div class="d-flx row">
            <div class="col-md-3"><?php include BASE_PATH . "/src/Layouts/Sidebar.php"; ?></div>
            <div class="col-md-9"><?php include BASE_PATH . "/src/Layouts/Header.php"; ?></div>
        </div>
    </div>

    <div class="page-wrapper">
        <div class="content">
            <div class="page-header">
                <div class="page-title">
                    <h4>All Notifications</h4>
                    <h6>View your all activities</h6>
                </div>
            </div>

            <div class="filter-wrapper mb-4 d-flex align-items-center gap-4 flex-wrap">
                <div class="custom-filter-box">
                    <label class="filter-label">Payment Mode</label>
                    <select class="form-select-custom" id="paymentFilter">
                        <option value="all">All Transactions</option>
                        <option value="cash">Cash Payment</option>
                        <option value="online">Online Payment</option>
                    </select>
                </div>
                <div class="ms-auto">
                    <span class="text-muted small fw-bold">TOTAL ORDERS</span>
                    <h5 class="mb-0 fw-bold"><?= mysqli_num_rows($resut) ?></h5>
                </div>
            </div>

            <div class="activity">
                <ul class="activity-list" style="list-style: none; padding: 0;">
                    <?php
                    while ($row = mysqli_fetch_assoc($resut)) {
                        $paymentMode = strtolower($row['status']);
                        $names = explode(", ", $row['product']);
                        $imgs = explode(", ", $row['image_path']);
                        $is_multiple = count($names) > 1;
                        ?>
                        <div class='order-card <?= ($row['seen'] == 0) ? 'unseen-card' : '' ?>'
                            data-payment='<?= $paymentMode ?>'>

                            <div class='order-header'>
                                <div class='order-left'>
                                    <span class='order-id'>Order #<?= $row['order_id'] ?></span>
                                    <span class='badge paid ms-2'>Paid</span>
                                </div>
                                <div class='order-total'>₹ <?= number_format($row['total_amount'], 2) ?></div>
                            </div>

                            <div class='order-meta'>
                                <span>Customer: <strong><?= htmlspecialchars($row['customer']) ?></strong></span>
                                <span class='mx-2'>|</span>
                                <span>Time: <?= date('M d, Y h:i A', strtotime($row['created'])) ?></span>
                            </div>

                            <div class='product'>
                                <img src='<?= htmlspecialchars($imgs[0]) ?>' class='product-img' />
                                <div class='product-info w-100'>
                                    <div class='product-name'>
                                        <?= htmlspecialchars($names[0]) ?>
                                        <?php if ($is_multiple): ?>
                                            <span class="more-badge">+<?= count($names) - 1 ?> more items</span>
                                        <?php endif; ?>
                                    </div>

                                    <div class='product-meta d-flex justify-content-between align-items-center'>
                                        <div>
                                            <span>Brand: <strong><?= $row['brand'] ?></strong></span>
                                            <span>Qty: <strong><?= $row['quantity'] ?></strong></span>
                                        </div>
                                        <div class="d-flex align-items-center gap-3">
                                            <select class="status-dropdown" onchange="updateStatus('<?= $row['order_id'] ?>', this.value)">
                                                <option value="Pending" <?= $row['status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
                                                <option value="Delivered" <?= $row['status'] == 'Delivered' ? 'selected' : '' ?>>Delivered</option>
                                                <option value="Canceled" <?= $row['status'] == 'Canceled' ? 'selected' : '' ?>>Canceled</option>
                                            </select>
                                            <a href='/Backend/src/Pages/notification/viewAllOrder.php?order_id=<?= $row['order_id'] ?>'
                                                class="btn btn-sm btn-outline-primary" style="font-size: 11px; border-radius: 6px;">
                                                View Details
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </div>

    <script>
        function updateStatus(orderId, newStatus) {
            Swal.fire({
                title: 'Are you sure?',
                text: `Do you want to change order status to ${newStatus}?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, update it!'
            })
            // .then((result) => {
            //     if (result.isConfirmed) {
            //         Swal.fire({
            //             title: 'Updating...',
            //             allowOutsideClick: false,
            //             didOpen: () => { Swal.showLoading() }
            //         });

            //         fetch('/Backend/src/controllers/updateOrderStatus.php', {
            //             method: 'POST',
            //             headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            //             body: `order_id=${orderId}&status=${newStatus}`
            //         })
            //         .then(response => response.json())
            //         .then(data => {
            //             if (data.success) {
            //                 Swal.fire({
            //                     icon: 'success',
            //                     title: 'Updated!',
            //                     text: 'Status has been updated successfully.',
            //                     timer: 1500,
            //                     showConfirmButton: false
            //                 });
            //             } else {
            //                 Swal.fire('Error!', data.message, 'error');
            //             }
            //         })
            //         .catch(err => {
            //             console.error(err);
            //             Swal.fire('Oops!', 'Something went wrong!', 'error');
            //         });
            //     } else {
            //     }
            // });
        }

        const paymentFilter = document.getElementById('paymentFilter');
        paymentFilter.addEventListener('change', function () {
            const val = this.value.toLowerCase();
            document.querySelectorAll('.order-card').forEach(card => {
                const method = card.getAttribute('data-payment');
                card.style.display = (val === 'all' || method === val) ? 'block' : 'none';
            });
        });
    </script>
</body>
</html>