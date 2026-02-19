<?php
require_once __DIR__ . "/../config/path.php";
include(BASE_PATH . "/Backend/src/Layouts/Links.php");
include(BASE_PATH . '/Backend/src/controllers/dbConnection.php');
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'Admin') {
    header("Location: /Backend/src/Pages/Auth/signin.php");
    exit();
}

// header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
// header("Pragma: no-cache");

// if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'Admin') {
//     header("Location: /Backend/src/Pages/Auth/signin.php");
//     exit();
// }

function getCount($table)
{
    global $con;
    $allowedTables = [
        'new_user',
        'supplier',
        'product_list',
        'customers',
        'order_list'
    ];

    if (!in_array($table, $allowedTables)) {
        return 0;
    }
    $query = "SELECT COUNT(*) AS total FROM `$table`";
    $result = mysqli_query($con, $query);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        return $row['total'];
    }
    return 0;
}

$expiredProducts = "SELECT `id`, `product_name`, `category`, `brand_name`, `minQuantity`, `price`, `quantity`, `description`, `discount`, `status`, `image_path`, `gallery_images`, `expired_date`, `created_at` FROM `product_list` WHERE 1 ORDER BY `id` DESC LIMIT 5";
$expiredResult = $con->query($expiredProducts);
$orderList = "SELECT `id`, `order_id`, `customer`, `product`, `category`, `brand`, `quantity`, `status`, `created` FROM `order_list` WHERE 1 ORDER BY `id` DESC LIMIT 5";
$orderListResult = $con->query($orderList);

$sql = "SELECT COUNT(*) AS total_expired 
        FROM product_list 
        WHERE expired_date <= CURDATE()";

$result = mysqli_query($con, $sql);
$row = mysqli_fetch_assoc($result);

$expiredCount = (int) $row['total_expired'];

// $lowStocksql = mysqli_query($con, "
//     SELECT COUNT(*) AS total_low_stock 
//     FROM product_list 
//     WHERE quantity <= minQuantity AND quantity > 0
// ");
// $lowStockRow = mysqli_fetch_assoc($lowStocksql);
// $lowStockCount = (int) $lowStockRow['total_low_stock'];

$lowStock = "SELECT COUNT(*) AS total_low_stock 
            FROM product_list 
            WHERE quantity <= minQuantity OR quantity = 0";
$lowStockResult = mysqli_query($con, $lowStock);
$lowStockRow = mysqli_fetch_assoc($lowStockResult);
$lowStockCount = (int) $lowStockRow['total_low_stock'];


$result = mysqli_query($con, "
    SELECT SUM(total_amount) AS grand_total
    FROM order_list
");

$row = mysqli_fetch_assoc($result);
$grandTotal = $row['grand_total'] ?? 0;
$total = (float) $grandTotal;

$inventorySql = "
SELECT
  SUM(CASE WHEN quantity > minQuantity AND expired_date >= CURDATE() THEN 1 ELSE 0 END) AS in_stock,
  SUM(CASE WHEN quantity <= minQuantity AND quantity > 0 AND expired_date >= CURDATE() THEN 1 ELSE 0 END) AS low_stock,
  SUM(CASE WHEN expired_date < CURDATE() THEN 1 ELSE 0 END) AS expired,
  SUM(CASE WHEN quantity = 0 THEN 1 ELSE 0 END) AS out_stock
FROM product_list

";

$inventoryResult = mysqli_query($con, $inventorySql);
$inventory = mysqli_fetch_assoc($inventoryResult);

$inventoryData = [
    "In Stock" => (int) $inventory['in_stock'],
    "Low Stock" => (int) $inventory['low_stock'],
    "Expired" => (int) $inventory['expired'],
    "Out of Stock" => (int) $inventory['out_stock'],
];


$dailySql = "
SELECT 
    sale_date AS label,
    SUM(total) AS total
FROM (
    SELECT 
        DATE(`created`) AS sale_date,
        price * quantity AS total
    FROM order_list
    WHERE `created` >= CURDATE() - INTERVAL 6 DAY
) t
GROUP BY sale_date
ORDER BY sale_date
";

$weeklySql = "
SELECT 
    sale_week AS label,
    SUM(total) AS total
FROM (
    SELECT 
        CONCAT('Week ', WEEK(`created`, 1)) AS sale_week,
        YEAR(`created`) AS sale_year,
        price * quantity AS total
    FROM order_list
    WHERE YEAR(`created`) = YEAR(CURDATE())
) t
GROUP BY sale_year, sale_week
ORDER BY sale_year, sale_week
";

$monthlySql = "
SELECT 
    sale_month AS label,
    SUM(total) AS total
FROM (
    SELECT 
        DATE_FORMAT(`created`, '%b') AS sale_month,
        YEAR(`created`) AS sale_year,
        MONTH(`created`) AS sale_month_num,
        price * quantity AS total
    FROM order_list
    WHERE YEAR(`created`) = YEAR(CURDATE())
) t
GROUP BY sale_year, sale_month_num, sale_month
ORDER BY sale_year, sale_month_num
";




$yearlySql = "
SELECT 
    sale_year AS label,
    SUM(total) AS total
FROM (
    SELECT 
        YEAR(`created`) AS sale_year,
        price * quantity AS total
    FROM order_list
) t
GROUP BY sale_year
ORDER BY sale_year
";



function fetchSales($con, $sql)
{
    $labels = [];
    $data = [];

    $result = mysqli_query($con, $sql);

    if (!$result) {
        die(
            "Sales Query Error: " .
            mysqli_error($con) .
            "<br><pre>$sql</pre>"
        );
    }

    while ($row = mysqli_fetch_assoc($result)) {
        $labels[] = $row['label'];
        $data[] = (float) $row['total'];
    }

    return [$labels, $data];
}

[$dailyLabels, $dailyData] = fetchSales($con, $dailySql);
[$weeklyLabels, $weeklyData] = fetchSales($con, $weeklySql);
[$monthlyLabels, $monthlyData] = fetchSales($con, $monthlySql);
[$yearlyLabels, $yearlyData] = fetchSales($con, $yearlySql);



$inventorySql = "
SELECT
  SUM(CASE 
        WHEN quantity > minQuantity AND expired_date >= CURDATE() 
        THEN 1 ELSE 0 
      END) AS in_stock,

  SUM(CASE 
        WHEN quantity <= minQuantity AND quantity > 0 AND expired_date >= CURDATE() 
        THEN 1 ELSE 0 
      END) AS low_stock,

  SUM(CASE 
        WHEN expired_date < CURDATE() 
        THEN 1 ELSE 0 
      END) AS expired,

  SUM(CASE 
        WHEN quantity = 0 
        THEN 1 ELSE 0 
      END) AS out_stock
FROM product_list
";

$inventoryResult = mysqli_query($con, $inventorySql);

if (!$inventoryResult) {
    die("Inventory Query Error: " . mysqli_error($con));
}

$inventory = mysqli_fetch_assoc($inventoryResult);


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
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.5/css/dataTables.dataTables.css" />
    <link rel="stylesheet" href="/Backend/src/assets/css/dashboard/dashboard.css">
    <style>
    .sales-filter .btn {
        min-width: 90px;
        font-weight: 500;
        transition: all 0.25s ease;
    }

    .sales-filter .btn.active {
        background-color: #0d6efd;
        color: #fff;
        box-shadow: 0 4px 10px rgba(13, 110, 253, 0.3);
    }

    .sales-filter .btn:not(.active):hover {
        background-color: rgba(13, 110, 253, 0.08);
        color: black;
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
                <?php include(BASE_PATH . "/Backend/src/Layouts/Sidebar.php"); ?>
            </div>
            <div class="col-md-9">
                <?php include BASE_PATH . "/Backend/src/Layouts/Header.php"; ?>
            </div>
        </div>
    </div>
    <div class="page-wrapper">
        <div class="content">
            <div class="row">
            <div class="col-lg-3 col-sm-6 col-12">
                    <div class="dash-widget dash3">
                        <div class="dash-widgetimg">
                            <span><img src="/Backend/src/assets/images/sale.svg" alt="img" style="width: 100%"></span>
                        </div>
                        <div class="dash-widgetcontent">
                            <h5><?= '₹ ' . $total ?></h5>
                            <h6>Total Sale Amount</h6>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6 col-12">
                    <div class="dash-widget">
                        <div class="dash-widgetimg">
                            <span><img src="/Backend/src/assets/images/box.png" alt="img" style="width:100%"></span>
                        </div>
                        <div class="dash-widgetcontent">
                            <h5><?= getCount('product_list') ?></h5>
                            <h6>Total Products</h6>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6 col-12">
                    <div class="dash-widget dash1">
                        <div class="dash-widgetimg">
                            <span><img src="/Backend/src/assets/images/expired.png" alt="img"
                                    style="width: 100%"></span>
                        </div>
                        <div class="dash-widgetcontent">
                            <h5><?= $expiredCount ?></h5>
                            <h6>Expired Products</h6>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6 col-12">
                    <div class="dash-widget dash2">
                        <div class="dash-widgetimg">
                            <span><img src="/Backend/src/assets/images/lowStock.svg" alt="img"
                                    style="width: 22px"></span>
                        </div>
                        <div class="dash-widgetcontent">
                            <h5><?= $lowStockCount ?></h5>
                            <h6>Low stocks</h6>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6 col-12">
                    <div class="dash-widget">
                        <div class="dash-widgetimg">
                            <span><img src="/Backend/src/assets/images/onlineCutomer.svg" alt="img"
                                    style="width: 100%"></span>
                        </div>
                        <div class="dash-widgetcontent">
                            <h5><?= getCount('new_user') ?></h5>
                            <h6>Online Cutomers</h6>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6 col-12">
                    <div class="dash-widget dash1">
                        <div class="dash-widgetimg">
                            <span><img src="/Backend/src/assets/images/offlineCutomer.svg" alt="img"
                                    style="width: 100%"></span>
                        </div>
                        <div class="dash-widgetcontent">
                            <h5><?= getCount('customers') ?></h5>
                            <h6>Walk In Cutomers</h6>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6 col-12">
                    <div class="dash-widget dash2">
                        <div class="dash-widgetimg">
                            <span><img src="/Backend/src/assets/images/supplier.svg" alt="img"
                                    style="width: 100%"></span>
                        </div>
                        <div class="dash-widgetcontent">
                            <h5><?= getCount('supplier') ?></h5>
                            <h6>Total Suppliers</h6>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6 col-12">
                    <div class="dash-widget dash3">
                        <div class="dash-widgetimg">
                            <span><img src="/Backend/src/assets/images/orders.jpg" alt="img" width="100%"></span>
                        </div>
                        <div class="dash-widgetcontent">
                            <h5><?= getCount('order_list') ?></h5>
                            <h6>Total Orders</h6>
                        </div>
                    </div>
                </div>

                <!-- <div class="col-lg-3 col-sm-6 col-12 d-flex">
                        <div class="dash-count">
                            <div class="dash-counts">
                                <h4><?= getCount('new_user') ?></h4>
                                <h5>Total Customers</h5>
                            </div>
                            <div class="dash-imgs">
                                <i data-feather="user"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12 d-flex">
                        <div class="dash-count das1">
                            <div class="dash-counts">
                                <h4><?= getCount('product_list') ?></h4>
                                <h5>Total Products</h5>
                            </div>
                            <div class="dash-imgs">
                                <i data-feather="user-check"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12 d-flex">
                        <div class="dash-count das2">
                            <div class="dash-counts">
                                <h4><?= getCount('supplier') ?></h4>
                                <h5>Total supplier</h5>
                            </div>
                            <div class="dash-imgs">
                                <i data-feather="file-text"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12 d-flex">
                        <div class="dash-count das3">
                            <div class="dash-counts">
                                <h4>105</h4>
                                <h5>Sales Orders</h5>
                            </div>
                            <div class="dash-imgs">
                                <i data-feather="file"></i>
                            </div>
                        </div>
                    </div> -->
            </div>

            <div class="row mb-4">
                <div class="col-lg-6">
                    <div class="pie-card w-100" style="height: 400px">
                        <h3>Inventory Status</h3>

                        <div class="pie-chart" id="inventoryPie">
                            <div class="pie-center">
                                <p>Inventory<br><span>Status</span></p>
                            </div>
                            <div class="tooltip" id="tooltip"></div>
                        </div>
                        <ul class="legend mt-3" id="inventoryLegend"></ul>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card shadow-lg p-3" style="height: 400px">
                        <h4 class="text-center mb-3">Sales Overview 2026</h4>
                        <canvas id="salesChart" height="250"></canvas>
                        <div class="mb-4 mt-3 d-flex justify-content-end">
                            <div class="btn-group sales-filter" role="group">
                                <button class="btn btn-outline-secondary rounded me-2" data-range="daily">Daily</button>
                                <button class="btn btn-outline-secondary me-2 rounded" data-range="weekly">Weekly
                                    </buttonsecondary>
                                    <button class="btn btn-outline-secondary me-2 rounded active"
                                        data-range="monthly">Monthly</button>
                                    <button class="btn btn-outline-secondary rounded"
                                        data-range="yearly">Yearly</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <h4>Recently added products</h4>
                    <div class="card shadow-lg">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-center mb-0">
                                    <thead>
                                        <tr>
                                            <th>Product Name</th>
                                            <th>Category</th>
                                            <th>Brand</th>
                                            <th>Expired Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if ($expiredResult->num_rows > 0) {
                                            while ($row = $expiredResult->fetch_assoc()) {
                                                echo "<tr>";
                                                echo "<td class='text-truncate' style='max-width: 95px' data-bs-toggle='tooltip' data-bs-title='" . $row['product_name'] . "'>" . htmlspecialchars($row['product_name']) . "</td>";
                                                echo "<td class='text-truncate' style='max-width: 95px' data-bs-toggle='tooltip' data-bs-title='" . $row['category'] . "'>" . htmlspecialchars($row['category']) . "</td>";
                                                echo "<td class='text-truncate' style='max-width: 95px' data-bs-toggle='tooltip' data-bs-title='" . $row['brand_name'] . "'>" . htmlspecialchars($row['brand_name']) . "</td>";
                                                echo "<td>" . htmlspecialchars($row['expired_date']) . "</td>";
                                                echo "</tr>";
                                            }
                                        } else {
                                            echo "<tr><td colspan='4'>No expired products found.</td></tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <h4>Recently order list</h4>
                    <div class="card shadow-lg">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-center mb-0">
                                    <thead>
                                        <tr>
                                            <th>Order ID</th>
                                            <th>Customer</th>
                                            <th>Product</th>
                                            <th>Quantity</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if ($orderListResult->num_rows > 0) {
                                            while ($row = $orderListResult->fetch_assoc()) {
                                                echo "<tr>";
                                                echo "<td>" . htmlspecialchars($row['order_id']) . "</td>";
                                                echo "<td class='text-truncate' style='max-width: 95px' data-bs-toggle='tooltip' data-bs-title='" . $row['customer'] . "'>" . htmlspecialchars($row['customer']) . "</td>";
                                                echo "<td class='text-truncate' style='max-width: 95px' data-bs-toggle='tooltip' data-bs-title='" . $row['product'] . "'>" . htmlspecialchars($row['product']) . "</td>";
                                                echo "<td>" . htmlspecialchars($row['quantity']) . "</td>";
                                                $statusClass = '';

                                                switch (strtolower($row['status'])) {
                                                    case 'cash':
                                                        $statusClass = 'bg-success';
                                                        break;
                                                    case 'online':
                                                        $statusClass = 'bg-primary';
                                                        break;
                                                    case 'card':
                                                        $statusClass = 'bg-danger';
                                                        break;
                                                    default:
                                                        $statusClass = 'bg-secondary';
                                                }

                                                echo "<td><span class='badge $statusClass'>" . htmlspecialchars($row['status']) . "</span></td>";
                                                echo "</tr>";
                                            }
                                        } else {
                                            echo "<tr><td colspan='5'>No orders found.</td></tr>";
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
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
    const inventoryData = <?php echo json_encode($inventoryData); ?>;

    const inventoryPie = document.getElementById("inventoryPie");
    const legend = document.getElementById("inventoryLegend");
    const tooltip = document.getElementById("tooltip");

    const colors = {
        "In Stock": "#20c997",
        "Low Stock": "#ffa94d",
        "Expired": "#ff6b6b",
        "Out of Stock": "#495057"
    };

    let total = Object.values(inventoryData).reduce((a, b) => a + b, 0) || 1;
    let startDeg = 0;
    let gradientParts = [];
    let slices = [];

    Object.entries(inventoryData).forEach(([label, value]) => {
        const percent = Math.round((value / total) * 100);
        const deg = percent * 3.6;

        slices.push({
            label,
            percent,
            start: startDeg,
            end: startDeg + deg
        });

        gradientParts.push(
            `${colors[label]} ${startDeg}deg ${startDeg + deg}deg`
        );

        legend.innerHTML += `
    <li>
      <span style="background:${colors[label]}"></span>
      ${label} <b>${percent}%</b>
    </li>
  `;

        startDeg += deg;
    });

    inventoryPie.style.background = `conic-gradient(${gradientParts.join(",")})`;

    inventoryPie.addEventListener("mousemove", (e) => {
        const rect = inventoryPie.getBoundingClientRect();
        const cx = rect.left + rect.width / 2;
        const cy = rect.top + rect.height / 2;

        const x = e.clientX - cx;
        const y = e.clientY - cy;

        let angle = Math.atan2(y, x) * (180 / Math.PI);
        angle = (angle + 360 + 90) % 360;

        for (let slice of slices) {
            if (angle >= slice.start && angle < slice.end) {
                tooltip.innerHTML = `${slice.label} – ${slice.percent}%`;
                break;
            }
        }

        tooltip.style.left = e.offsetX + "px";
        tooltip.style.top = e.offsetY + "px";
        tooltip.style.opacity = 1;
    });

    inventoryPie.addEventListener("mouseleave", () => {
        tooltip.style.opacity = 0;
    });
    </script>
    <script>
    const buttons = document.querySelectorAll('.sales-filter .btn');

    buttons.forEach(btn => {
        btn.addEventListener('click', () => {
            buttons.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            const range = btn.dataset.range;
            updateSalesChart(range);
        });
    });
    </script>
    <script>
    const salesDataSets = {
        daily: {
            labels: <?php echo json_encode($dailyLabels); ?>,
            data: <?php echo json_encode($dailyData); ?>
        },
        weekly: {
            labels: <?php echo json_encode($weeklyLabels); ?>,
            data: <?php echo json_encode($weeklyData); ?>
        },
        monthly: {
            labels: <?php echo json_encode($monthlyLabels); ?>,
            data: <?php echo json_encode($monthlyData); ?>
        },
        yearly: {
            labels: <?php echo json_encode($yearlyLabels); ?>,
            data: <?php echo json_encode($yearlyData); ?>
        }
    };
    </script>
    <script>
    const ctx = document.getElementById("salesChart");

    let salesChart = new Chart(ctx, {
        type: "bar",
        data: {
            labels: salesDataSets.monthly.labels,
            datasets: [{
                data: salesDataSets.monthly.data,
                backgroundColor: "#6792ff",
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    function updateSalesChart(range) {
        salesChart.data.labels = salesDataSets[range].labels;
        salesChart.data.datasets[0].data = salesDataSets[range].data;
        salesChart.update();
    }
    </script>



</body>

</html>