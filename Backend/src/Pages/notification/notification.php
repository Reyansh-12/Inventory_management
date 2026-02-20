<?php
define("BASE_PATH", dirname(__DIR__, 3));
include BASE_PATH . "/src/Layouts/Links.php";
include BASE_PATH . "/src/controllers/dbConnection.php";
$order_list = "SELECT `id`, `order_id`, `customer`, `product`, `category`, `brand`, `quantity`, `status`, `total_amount`, `price`, `created` FROM `order_list` ORDER BY `id` DESC";
$resut = mysqli_query($con, $order_list);
mysqli_query($con, "UPDATE order_list SET seen = 1 WHERE seen = 0");

echo "success";
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
    <title>User List</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="/Backend/src/assets/css/history.css">
    <style>
        .order-card {
            transition: all 0.3s ease;
        }

        .unseen-card {
            background-color: #f8f9ff;
            border-left: 4px solid #0d6efd;
        }

        .seen-card {
            opacity: 0.85;
        }
        /* Professional Order History Overhaul */
:root {
    --primary-blue: #6792ff;
    --border-color: #edf2f9;
    --text-muted: #718096;
}

.order-card {
    background: #fff;
    border: 1px solid var(--border-color);
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 20px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.03);
}

.order-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08);
}

.order-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid var(--border-color);
    padding-bottom: 15px;
    margin-bottom: 15px;
}

.order-id {
    font-weight: 700;
    color: #2d3748;
    font-size: 1.1rem;
}

.order-total {
    font-weight: 800;
    color: var(--primary-blue);
    font-size: 1.2rem;
}

/* Metadata Styling */
.order-meta {
    font-size: 0.85rem;
    color: var(--text-muted);
    margin-bottom: 15px;
}

.meta-item a {
    color: #4a5568;
    text-decoration: none;
    font-weight: 600;
}

/* Product Section */
.product {
    display: flex;
    gap: 20px;
    align-items: center;
}

.product-img {
    width: 70px;
    height: 70px;
    border-radius: 10px;
    object-fit: cover;
    background: #f7fafc;
}

.product-name {
    font-weight: 700;
    color: #2d3748;
    font-size: 1rem;
    margin-bottom: 5px;
}

.product-meta {
    font-size: 0.8rem;
    color: var(--text-muted);
}

.product-meta span {
    margin-right: 15px;
}

/* Status Badges */
.badge.paid {
    background: rgba(32, 201, 151, 0.1);
    color: #198754;
    padding: 5px 12px;
    border-radius: 6px;
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
}

.payment-label {
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 0.75rem;
    font-weight: 700;
}
.payment-cash { background: #fff5f5; color: #e53e3e; }
.payment-online { background: #ebf8ff; color: #3182ce; }
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
                    <h4>All Notifications</h4>
                    <h6>View your all activities</h6>
                </div>
            </div>
            <?php
            $unseenQuery = mysqli_query($con, "SELECT COUNT(*) as total FROM order_list WHERE seen = 0");
            $unseenData = mysqli_fetch_assoc($unseenQuery);
            $unseenCount = $unseenData['total'];
            ?>
            <div class="mb-3 d-flex align-items-center gap-3 flex-wrap">
                <div style="width: 150px;">
                    <select class="form-select" id="paymentFilter">
                        <option value="all" selected>All Payment</option>
                        <option value="cash">Cash payment</option>
                        <option value="online">Online payment</option>
                        <option value="card">Card payment</option>
                    </select>
                </div>

                <div style="width: 150px;">
                    <select class="form-select" id="priceSort">
                        <option value="default" selected>Sort by Price</option>
                        <option value="high">High to Low</option>
                        <option value="low">Low to High</option>
                    </select>
                </div>

            </div>
            <div class="activity">
                <div class="activity-box">
                    <ul class="activity-list">
                    <?php
while ($row = mysqli_fetch_assoc($resut)) {
    $paymentMode = strtolower($row['status']);
    $paymentClass = ($paymentMode == 'online') ? 'payment-online' : 'payment-cash';
    ?>
    <div class='order-card' data-payment='<?= $paymentMode ?>' data-price='<?= $row['total_amount'] ?>'>
        <div class='order-header'>
            <div class='order-left'>
                <span class='order-id'>Order #<?= $row['order_id'] ?></span>
                <span class='badge paid ms-2'>Paid</span>
            </div>
            <div class='order-total'>₹ <?= number_format($row['total_amount'], 2) ?></div>
        </div>

        <div class='order-meta'>
            <span class='meta-item'>Customer: <a href='#'><?= htmlspecialchars($row['customer']) ?></a></span>
            <span class='mx-2'>|</span>
            <span class='meta-item'>Time: <?= date('M d, Y h:i A', strtotime($row['created'])) ?></span>
        </div>

        <div class='product'>
            <img src='/Backend/src/uploads/products/featured/product_6979a7b1479cf2.85124957.webp' class='product-img' />
            <div class='product-info w-100'>
                <div class='product-name'><?= htmlspecialchars($row['product']) ?></div>
                <div class='product-meta d-flex justify-content-between align-items-center'>
                    <div>
                        <span>Brand: <strong><?= $row['brand'] ?></strong></span>
                        <span>Qty: <strong><?= $row['quantity'] ?></strong></span>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <span class="payment-label <?= $paymentClass ?>"><?= strtoupper($row['status']) ?></span>
                        <a href='/Backend/src/Pages/POS/viewAllOrder.php' class="btn btn-sm btn-outline-primary" style="font-size: 11px; border-radius: 6px;">View Details</a>
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
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const paymentFilter = document.getElementById('paymentFilter');
        const priceSort = document.getElementById('priceSort');
        const container = document.querySelector('.activity-list');

        function applyFilters() {

            const paymentValue = paymentFilter.value;
            const sortValue = priceSort.value;

            let cards = Array.from(document.querySelectorAll('.order-card'));

            cards.forEach(card => {
                const paymentType = card.getAttribute('data-payment');

                if (paymentValue === 'all') {
                    card.style.display = 'block';
                } else {
                    card.style.display = (paymentType === paymentValue) ? 'block' : 'none';
                }
            });

            if (sortValue !== 'default') {

                cards.sort((a, b) => {
                    let priceA = parseFloat(a.getAttribute('data-price'));
                    let priceB = parseFloat(b.getAttribute('data-price'));

                    return sortValue === 'high'
                        ? priceB - priceA
                        : priceA - priceB;
                });

                container.innerHTML = '';
                cards.forEach(card => container.appendChild(card));
            }
        }

        paymentFilter.addEventListener('change', applyFilters);
        priceSort.addEventListener('change', applyFilters);
    </script>
    <script>

        function updateBadge(count) {
            document.getElementById('unseenBadge').innerText = count;
        }

        document.getElementById('markAllSeen').addEventListener('click', function () {

            fetch('mark_all_seen.php')
                .then(response => response.text())
                .then(data => {

                    if (data === "success") {

                        document.querySelectorAll('.order-card').forEach(card => {
                            card.classList.remove('unseen-card');
                            card.classList.add('seen-card');
                            card.setAttribute('data-seen', '1');
                        });

                        updateBadge(0);
                    }

                });

        });


        // Mark single card seen
        document.querySelectorAll('.order-card').forEach(card => {

            card.addEventListener('click', function () {

                if (this.getAttribute('data-seen') === '0') {

                    const id = this.getAttribute('data-id');

                    fetch('mark_seen.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: 'id=' + id
                    });

                    this.classList.remove('unseen-card');
                    this.classList.add('seen-card');
                    this.setAttribute('data-seen', '1');

                    let badge = document.getElementById('unseenBadge');
                    let count = parseInt(badge.innerText);

                    if (count > 0) {
                        updateBadge(count - 1);
                    }
                }

            });

        });
    </script>
</body>

</html>