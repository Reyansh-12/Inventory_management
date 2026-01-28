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

function getCount($table) {
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

$expiredProducts = "SELECT `id`, `product_name`, `category`, `brand_name`, `minQuantity`, `price`, `quantity`, `description`, `discount`, `status`, `image_path`, `gallery_images`, `expired_date`, `created_at` FROM `product_list` WHERE 1 ORDER BY `id` DESC LIMIT 3";
$result = $con->query($expiredProducts);
$orderList = "SELECT `id`, `order_id`, `customer`, `product`, `category`, `brand`, `quantity`, `status`, `created` FROM `order_list` WHERE 1 ORDER BY `id` DESC LIMIT 3";
$orderListResult = $con->query($orderList);
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
    <title>BRANCY – Cosmetic Store</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.5/css/dataTables.dataTables.css" />
    <style>
        .pie-card {
  width: 360px;
  padding: 25px;
  background: #fff;
  border-radius: 20px;
  box-shadow: 0 10px 25px rgba(0,0,0,0.08);
}

.pie-card h3 {
  text-align: center;
  margin-bottom: 20px;
  color: #24284a;
}

.pie-chart {
  width: 220px;
  height: 220px;
  margin: auto;
  border-radius: 50%;
  background: conic-gradient(
    #6792ff 0deg 72deg,
    #ff6b6b 72deg 90deg,
    #ffa94d 90deg 126deg,
    #4dabf7 126deg 198deg,
    #845ef7 198deg 241deg,
    #9c36b5 241deg 295deg,
    #20c997 295deg 324deg,
    #fcc419 324deg 360deg
  );
  display: flex;
  align-items: center;
  justify-content: center;
}

.pie-center {
  width: 140px;
  height: 140px;
  background: #fff;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: inset 0 5px 10px rgba(0,0,0,0.08);
}

.pie-center p {
  text-align: center;
  font-weight: 600;
  color: #24284a;
}

.pie-center span {
  font-size: 14px;
  font-weight: 400;
  color: #666;
}

.legend {
  list-style: none;
  margin-top: 20px;
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 10px;
  font-size: 14px;
}

.legend li {
  display: flex;
  align-items: center;
  gap: 8px;
}

.legend span {
  width: 12px;
  height: 12px;
  border-radius: 50%;
}

.c1 { background:#6792ff; }
.c2 { background:#ff6b6b; }
.c3 { background:#ffa94d; }
.c4 { background:#4dabf7; }
.c5 { background:#845ef7; }
.c6 { background:#9c36b5; }
.c7 { background:#20c997; }
.c8 { background:#fcc419; }






.legend li {
  opacity: 0;
  transform: translateY(10px);
  animation: legendFade 0.6s ease forwards;
}

.legend li:nth-child(1) { animation-delay: 0.2s; }
.legend li:nth-child(2) { animation-delay: 0.3s; }
.legend li:nth-child(3) { animation-delay: 0.4s; }
.legend li:nth-child(4) { animation-delay: 0.5s; }
.legend li:nth-child(5) { animation-delay: 0.6s; }
.legend li:nth-child(6) { animation-delay: 0.7s; }
.legend li:nth-child(7) { animation-delay: 0.8s; }
.legend li:nth-child(8) { animation-delay: 0.9s; }

@keyframes legendFade {
  to {
    opacity: 1;
    transform: translateY(0);
  }
}
.pie-chart {
  animation: pieRotate 1.5s ease-out forwards;
  transform: scale(0.8) rotate(-90deg);
}

@keyframes pieRotate {
  0% {
    transform: scale(0.6) rotate(-90deg);
    opacity: 0;
  }
  100% {
    transform: scale(1) rotate(0deg);
    opacity: 1;
  }
}


.pie-chart-wrapper {
  position: relative;
  display: inline-block;
}

.tooltip {
  position: absolute;
  padding: 6px 10px;
  background: #24284a;
  color: #fff;
  font-size: 13px;
  border-radius: 6px;
  pointer-events: none;
  opacity: 0;
  transform: translate(-50%, -120%);
  transition: opacity 0.2s ease;
  white-space: nowrap;
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
                        <div class="dash-widget">
                            <div class="dash-widgetimg">
                                <span><img src="/Backend/src/assets/images/box.png" alt="img" style="100%"></span>
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
                                <span><img src="/Backend/src/assets/images/expired.png" alt="img" style="width: 100%"></span>
                            </div>
                            <div class="dash-widgetcontent">
                                <h5>$<span class="counters" data-count="4385.00">$4,385.00</span></h5>
                                <h6>Expired Products</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="dash-widget dash2">
                            <div class="dash-widgetimg">
                                <span><img src="/Backend/src/assets/images/lowStock.svg" alt="img" style="width: 22px"></span>
                            </div>
                            <div class="dash-widgetcontent">
                                <h5>$<span class="counters" data-count="385656.50">385,656.50</span></h5>
                                <h6>Low stocks</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="dash-widget dash3">
                            <div class="dash-widgetimg">
                                <span><img src="/Backend/src/assets/images/sale.svg" alt="img" style="width: 100%"></span>
                            </div>
                            <div class="dash-widgetcontent">
                                <h5>$<span class="counters" data-count="40000.00">400.00</span></h5>
                                <h6>Total Sale Amount</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="dash-widget">
                            <div class="dash-widgetimg">
                                <span><img src="/Backend/src/assets/images/onlineCutomer.svg" alt="img" style="width: 100%"></span>
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
                                <span><img src="/Backend/src/assets/images/offlineCutomer.svg" alt="img" style="width: 100%"></span>
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
                                <span><img src="/Backend/src/assets/images/supplier.svg" alt="img" style="width: 100%"></span>
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



                <div class="pie-card w-100 mb-4">
                    <h3>Inventory Overview</h3>
                    <div class="row">
                        <div class="col-lg-6">
                    <div class="pie-chart">
                        <div class="pie-center">
                            <p>Inventory<br><span>Status</span></p>
                        </div>
                        <div class="tooltip" id="tooltip"></div>
                    </div>
                    </div>
                    <div class="col-lg-6">
      
        <ul class="legend">
          <li><span class="c1"></span>Total Products<b>20%</b></li>
          <li><span class="c2"></span>Expired Products<b>4%</b></li>
          <li><span class="c3"></span>Low Stock<b>8%</b></li>
          <li><span class="c4"></span>Total Sales<b>20%</b></li>
          <li><span class="c5"></span>Online Customers<b>12%</b></li>
          <li><span class="c6"></span>Walk-in Customers<b>15%</b></li>
          <li><span class="c7"></span>Total Suppliers<b>5%</b></li>
          <li><span class="c8"></span>Total Orders<b>16%</b></li>
        </ul>
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
                                            if ($result->num_rows > 0) {
                                                while ($row = $result->fetch_assoc()) {
                                                    echo "<tr>";
                                                    echo "<td class='text-truncate' style='max-width: 95px' data-bs-toggle='tooltip' data-bs-title='".$row['product_name']."'>" . htmlspecialchars($row['product_name']) . "</td>";
                                                    echo "<td class='text-truncate' style='max-width: 95px' data-bs-toggle='tooltip' data-bs-title='".$row['category']."'>" . htmlspecialchars($row['category']) . "</td>";
                                                    echo "<td class='text-truncate' style='max-width: 95px' data-bs-toggle='tooltip' data-bs-title='".$row['brand_name']."'>" . htmlspecialchars($row['brand_name']) . "</td>";
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
                                                    echo "<td>". htmlspecialchars($row['order_id']) . "</td>";
                                                    echo "<td class='text-truncate' style='max-width: 95px' data-bs-toggle='tooltip' data-bs-title='".$row['customer']."'>" . htmlspecialchars($row['customer']) . "</td>";
                                                    echo "<td class='text-truncate' style='max-width: 95px' data-bs-toggle='tooltip' data-bs-title='".$row['product']."'>" . htmlspecialchars($row['product']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row['quantity']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row['status']) . "</td>";
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

        <script>
const pie = document.getElementById("pieChart");
const tooltip = document.getElementById("tooltip");

const slices = [
  { label: "Total Products", percent: 20 },
  { label: "Expired Products", percent: 4 },
  { label: "Low Stock", percent: 8 },
  { label: "Total Sales", percent: 20 },
  { label: "Online Customers", percent: 12 },
  { label: "Walk-in Customers", percent: 15 },
  { label: "Total Suppliers", percent: 5 },
  { label: "Total Orders", percent: 16 }
];

pie.addEventListener("mousemove", (e) => {
  const rect = pie.getBoundingClientRect();
  const cx = rect.left + rect.width / 2;
  const cy = rect.top + rect.height / 2;

  const x = e.clientX - cx;
  const y = e.clientY - cy;

  let angle = Math.atan2(y, x) * (180 / Math.PI);
  angle = (angle + 360 + 90) % 360; // normalize

  let currentAngle = 0;
  for (let slice of slices) {
    const sliceAngle = slice.percent * 3.6;
    if (angle >= currentAngle && angle < currentAngle + sliceAngle) {
      tooltip.innerHTML = `${slice.label} – ${slice.percent}%`;
      break;
    }
    currentAngle += sliceAngle;
  }

  tooltip.style.left = e.offsetX + "px";
  tooltip.style.top = e.offsetY + "px";
  tooltip.style.opacity = 1;
});

pie.addEventListener("mouseleave", () => {
  tooltip.style.opacity = 0;
});
</script>
</body>
</html>