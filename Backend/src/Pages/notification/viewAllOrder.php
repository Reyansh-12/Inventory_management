<?php
define("BASE_PATH", dirname(__DIR__, 3));
include BASE_PATH . "/src/Layouts/Links.php";
include BASE_PATH . "/src/controllers/dbConnection.php";

$order_id = $_GET['order_id'] ?? null;

if (!$order_id) {
    echo "<script>window.location.href='history.php';</script>";
    exit;
}

$query = "SELECT * FROM `order_list` WHERE `order_id` = '$order_id' LIMIT 1";
$result = mysqli_query($con, $query);
$order = mysqli_fetch_assoc($result);

if (!$order) {
    echo "Order not found!";
    exit;
}

$names  = explode(", ", $order['product']);
$imgs   = explode(", ", $order['image_path']);
$prices = explode(", ", $order['price']);
$qtys   = explode(", ", $order['quantity']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details | #<?= $order['order_id'] ?></title>
    <style>
        :root {
            --primary-blue: #6792ff;
            --success-green: #20c997;
            --bg-light: #f8fafc;
            --border-color: #edf2f9;
        }
        .order-container { max-width: 1000px; margin: 0 auto; }
        .detail-card {
            background: #fff;
            border-radius: 16px;
            border: 1px solid var(--border-color);
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
            margin-bottom: 25px;
            overflow: hidden;
        }
        .card-header-custom {
            padding: 20px 25px;
            background: var(--bg-light);
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .status-pill {
            padding: 6px 15px;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
        }
        .status-online { background: rgba(103, 146, 255, 0.1); color: var(--primary-blue); }
        .status-cash { background: rgba(32, 201, 151, 0.1); color: var(--success-green); }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            padding: 25px;
        }
        .info-label { font-size: 0.75rem; color: #94a3b8; font-weight: 700; text-transform: uppercase; margin-bottom: 5px; }
        .info-value { font-size: 1rem; color: #2d3748; font-weight: 600; }

        /* Product Row (Exactly as per your design) */
        .product-row {
            display: flex;
            align-items: center;
            padding: 20px 25px;
            border-bottom: 1px solid var(--border-color);
        }
        .product-img-large {
            width: 80px;
            height: 80px;
            border-radius: 12px;
            object-fit: cover;
            margin-right: 20px;
            border: 1px solid var(--border-color);
        }
        .grand-total-box {
            background: #1e293b;
            color: white;
            padding: 25px;
            text-align: right;
            border-radius: 0 0 16px 16px;
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
            <div class="order-container">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <button onclick="window.history.back()" class="btn btn-outline-secondary btn-sm rounded-pill">
                        <i class="bi bi-arrow-left"></i> Back to History
                    </button>
                    <div class="d-flex gap-2">
                        <button class="btn btn-light btn-sm rounded-3 border" onclick="window.print()"><i class="bi bi-printer"></i> Print</button>
                    </div>
                </div>

                <div class="detail-card">
                    <div class="card-header-custom">
                        <div>
                            <h5 class="mb-0 fw-bold">Order Details</h5>
                            <span class="text-muted small">Placed on <?= date('M d, Y h:i A', strtotime($order['created'])) ?></span>
                        </div>
                        <span class="status-pill status-<?= (strtolower($order['payment_method']) == 'cod' ? 'cash' : 'online') ?>">
                            <?= strtoupper($order['payment_method']) ?> PAYMENT
                        </span>
                    </div>

                    <div class="info-grid">
                        <div>
                            <div class="info-label">Order ID</div>
                            <div class="info-value text-primary">#<?= $order['order_id'] ?></div>
                        </div>
                        <div>
                            <div class="info-label">Customer Name</div>
                            <div class="info-value"><?= htmlspecialchars($order['customer']) ?></div>
                        </div>
                        <div>
                            <div class="info-label">Payment Method</div>
                            <div class="info-value text-capitalize"><?= $order['payment_method'] ?></div>
                        </div>
                    </div>
                </div>

                <div class="detail-card">
                    <div class="card-header-custom">
                        <h5 class="mb-0 fw-bold">Items Ordered</h5>
                    </div>
                    
                    <?php for ($i = 0; $i < count($names); $i++): ?>
                    <div class="product-row">
                        <img src="<?= htmlspecialchars($imgs[$i]) ?>" class="product-img-large" onerror="this.src='/assets/img/placeholder.png'"/>
                        <div class="flex-grow-1" style="width: 80%">
                            <h6 class="mb-1 fw-bold"><?= htmlspecialchars($names[$i]) ?></h6>
                            <div class="text-muted small">
                                Category: <?= $order['category'] ?> | Brand: <?= $order['brand'] ?>
                            </div>
                        </div>
                        <div class="text-end">
                            <div class="text-muted small">Price × Qty</div>
                            <div class="fw-bold">₹ <?= number_format($prices[$i], 2) ?> × <?= $qtys[$i] ?></div>
                        </div>
                    </div>
                    <?php endfor; ?>
                    <div class="grand-total-box">
                        <div class="small opacity-75">Amount Paid</div>
                        <div class="display-6 fw-bold">₹ <?= number_format($order['total_amount'], 2) ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>