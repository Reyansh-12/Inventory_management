<?php
define("BASE_PATH", dirname(__DIR__, 3));
include BASE_PATH . "/src/Layouts/Links.php";
include BASE_PATH . "/src/controllers/dbConnection.php";

// Fetching orders
$order_list_query = "SELECT `id`, `order_id`, `customer`, `product`, `category`, `brand`, `quantity`, `status`, `total_amount`, `price`, `image_path`, `created`, `seen` FROM `order_list` ORDER BY `id` DESC";
$resut = mysqli_query($con, $order_list_query);

// Mark as seen logic
mysqli_query($con, "UPDATE order_list SET seen = 1 WHERE seen = 0");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <title>All Notifications</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="/Backend/src/assets/css/history.css">
    <style>
        /* Aapki original CSS */
        .order-card { transition: all 0.3s ease; background: #fff; border: 1px solid var(--border-color); border-radius: 12px; padding: 20px; margin-bottom: 20px; box-shadow: 0 2px 12px rgba(0, 0, 0, 0.03); }
        .order-card:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08); }
        .unseen-card { background-color: #f8f9ff; border-left: 4px solid #0d6efd; }
        .seen-card { opacity: 0.85; }
        :root { --primary-blue: #6792ff; --border-color: #edf2f9; --text-muted: #718096; }
        .order-header { display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border-color); padding-bottom: 15px; margin-bottom: 15px; }
        .order-id { font-weight: 700; color: #2d3748; font-size: 1.1rem; }
        .order-total { font-weight: 800; color: var(--primary-blue); font-size: 1.2rem; }
        .order-meta { font-size: 0.85rem; color: var(--text-muted); margin-bottom: 15px; }
        .meta-item a { color: #4a5568; text-decoration: none; font-weight: 600; }
        .product { display: flex; gap: 20px; align-items: center; }
        .product-img { width: 70px; height: 70px; border-radius: 10px; object-fit: cover; background: #f7fafc; }
        .product-name { font-weight: 700; color: #2d3748; font-size: 1rem; margin-bottom: 5px; }
        .product-meta { font-size: 0.8rem; color: var(--text-muted); }
        .badge.paid { background: rgba(32, 201, 151, 0.1); color: #198754; padding: 5px 12px; border-radius: 6px; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; }
        .payment-label { padding: 4px 8px; border-radius: 4px; font-size: 0.75rem; font-weight: 700; }
        .payment-cash { background: #fff5f5; color: #e53e3e; }
        .payment-online { background: #ebf8ff; color: #3182ce; }
        .filter-wrapper { background: #fff; padding: 15px 20px; border-radius: 12px; border: 1px solid var(--border-color); box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03); }
        .custom-filter-box { position: relative; width: 180px; }
        .filter-label { font-size: 0.75rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; margin-bottom: 5px; display: block; }
        .form-select-custom { appearance: none; background: var(--bg-light); border: 1px solid var(--border-color) !important; border-radius: 8px !important; padding: 10px 35px 10px 15px !important; font-size: 0.9rem !important; font-weight: 600; color: #4a5568; width: 100%; cursor: pointer; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%236792ff' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 12px center; background-size: 12px; }
        
        /* Multiple product ke liye extra style */
        .more-badge { background: #eef2ff; color: #6366f1; padding: 2px 8px; border-radius: 4px; font-size: 11px; margin-left: 8px; border: 1px solid #e0e7ff; }
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
                        $paymentClass = ($paymentMode == 'online') ? 'payment-online' : 'payment-cash';
                        
                        // MULTIPLE PRODUCTS LOGIC
                        $names  = explode(", ", $row['product']);
                        $imgs   = explode(", ", $row['image_path']);
                        $is_multiple = count($names) > 1;
                        ?>
                        <div class='order-card <?= ($row['seen'] == 0) ? 'unseen-card' : '' ?>' 
                             data-payment='<?= $paymentMode ?>' data-price='<?= $row['total_amount'] ?>'>
                            
                            <div class='order-header'>
                                <div class='order-left'>
                                    <span class='order-id'>Order #<?= $row['order_id'] ?></span>
                                    <span class='badge paid ms-2'>Paid</span>
                                </div>
                                <div class='order-total'>₹ <?= number_format($row['total_amount'], 2) ?></div>
                            </div>

                            <div class='order-meta'>
                                <span class='meta-item'>Customer: <strong><?= htmlspecialchars($row['customer']) ?></strong></span>
                                <span class='mx-2'>|</span>
                                <span class='meta-item'>Time: <?= date('M d, Y h:i A', strtotime($row['created'])) ?></span>
                            </div>

                            <div class='product'>
                                <img src='<?= htmlspecialchars($imgs[0]) ?>' class='product-img' onerror="this.src='/assets/img/placeholder.png'"/>
                                
                                <div class='product-info w-100'>
                                    <div class='product-name'>
                                        <?= htmlspecialchars($names[0]) ?>
                                        <?php if($is_multiple): ?>
                                            <span class="more-badge">+<?= count($names)-1 ?> more items</span>
                                        <?php endif; ?>
                                    </div>
                                    <div class='product-meta d-flex justify-content-between align-items-center'>
                                        <div>
                                            <span>Brand: <strong><?= $row['brand'] ?></strong></span>
                                            <span>Qty: <strong><?= $row['quantity'] ?></strong></span>
                                        </div>
                                        <div class="d-flex align-items-center gap-3">
                                            <span class="payment-label <?= $paymentClass ?>"><?= strtoupper($row['status']) ?></span>
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
        // Filters Logic
        const paymentFilter = document.getElementById('paymentFilter');
        paymentFilter.addEventListener('change', function() {
            const val = this.value.toLowerCase();
            document.querySelectorAll('.order-card').forEach(card => {
                const method = card.getAttribute('data-payment');
                card.style.display = (val === 'all' || method === val) ? 'block' : 'none';
            });
        });
    </script>
</body>
</html>