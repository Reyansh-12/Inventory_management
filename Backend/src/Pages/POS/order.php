<?php
define("BASE_PATH", dirname(__DIR__, 3));
include BASE_PATH . "/src/Layouts/Links.php";
include BASE_PATH . "/src/controllers/dbConnection.php";

session_start();

$cartItems = $_SESSION['order_cart'] ?? [];
$grandTotal = 0;
foreach ($cartItems as $item) {
    $grandTotal += $item['price'] * $item['quantity'];
}
if (isset($_SESSION['selected_customer_id'])) {
    $c_id = $_SESSION['selected_customer_id'];
    $c_query = mysqli_query($con, "SELECT name, phone, email FROM customers WHERE id = '$c_id'");
    if ($row = mysqli_fetch_assoc($c_query)) {
        $customerData = $row;
    }
}
$invoiceDate = date("d - M - y");

$customerData = [
    'name' => 'Walk-in Customer',
    'phone' => 'N/A',
    'email' => 'N/A'
];

if (!empty($_SESSION['selected_customer_id'])) {
    $c_id = (int) $_SESSION['selected_customer_id'];

    $c_query = mysqli_query($con, "SELECT name, phone, email FROM customers WHERE id = $c_id LIMIT 1");

    if ($row = mysqli_fetch_assoc($c_query)) {
        $customerData = $row;
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
    <title>Cosmetic product form</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
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

        div.dataTables_wrapper div.dataTables_length label,
        .text-end {
            display: none;
        }

        .btn-check:checked+.payment-card {
            border: 2px solid #0d6efd !important;
            background-color: #f8fbff;
        }

        .payment-card {
            cursor: pointer;
            transition: all 0.2s ease-in-out;
            border: 1px solid #dee2e6;
            border-radius: 10px;
        }

        .payment-card:hover {
            background-color: #fdfdfd;
        }

        .btn-check {
            position: absolute;
            clip: rect(0, 0, 0, 0);
            pointer-events: none;
        }

        .card {
            margin: 0px 0 0px !important;
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
        <div class="page-wrapper" style="padding-top: 60px;">
            <div class="content container-fluid">
                <div class="row mb-3">
                    <div class="col-lg-12 d-flex justify-content-between align-items-center">
                        <div>
                            <h4>Products</h4>
                            <!-- <h6 class="text-muted">Manage your purchases</h6> -->
                        </div>
                        <div>
                            <a href="pos.php"><button class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#addCustomerModal">
                                    <i class="bi bi-arrow-left"></i> Back
                                </button></a>
                        </div>
                    </div>
                </div>
                <div class="card p-3">
                    <div class="text-center mb-4">
                        <h1>Brancy</h1>
                        <span>Plot no 66, kharabi, nagpur, maharashtra</span>
                    </div>
                    <div class="row mb-5">
                        <div class="col-lg-6 ps-5">
                            <div class="mb-2">
                                <strong class="fs-4">Customer Details</strong>
                            </div>
                            <div>
                                <span>Customer Name :</span>
                                <span><?php echo $customerData['name']; ?></span>
                            </div>
                            <div>
                                <span>Mobile Number :</span>
                                <span><?php echo $customerData['phone']; ?></span>
                            </div>
                            <div>
                                <span>Customer Email :</span>
                                <span><?php echo $customerData['email']; ?></span>
                            </div>
                        </div>
                        <div class="col-lg-6 text-end pe-5 d-block">
                            <div class="mb-2">
                                <strong class="fs-4">Invoice Details</strong>
                            </div>
                            <div>
                                <span>Customer Name :</span>
                                <span>Reyansh Raut</span>
                            </div>
                            <div>
                                <span>Invoice Date :</span>
                                <span><?php echo $invoiceDate; ?></span>
                            </div>
                            <div>
                                <span>Transaction ID :</span>
                                <span>#<?php echo $_SESSION['transaction_id'] ?? 'N/A'; ?></span>
                            </div>
                        </div>
                    </div>
                    <table class="table datanew" id="orderItemsTable">
                        <thead>
                            <tr>
                            <th>Id</th>
                            <th>Product Name</th>
                            <th class="text-center">Price</th>
                            <th class="text-center">Quantity</th>
                            <th class="text-center">Total Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($cartItems)): ?>
                                <tr>
                                    <td colspan="6" class="text-center">No items in order</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($cartItems as $index => $item): ?>
                                    <tr>
                                        <td><?= $index + 1 ?></td>
                                        <td style="max-width: 200px;" class="text-truncate" data-bs-toggle="tooltip" data-bs-title="<?= htmlspecialchars($item['name']) ?>"><span><?= htmlspecialchars($item['name']) ?></span></td>
                                        <td class="text-center">₹<?= number_format($item['price'], 2) ?></td>
                                        <td class="text-center"><?= $item['quantity'] ?? 1 ?></td>
                                        <?php $qty = $item['quantity'] ?? 1; ?>
                                        <td class="text-center">₹<?= number_format($item['price'] * $qty, 2) ?></td>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>

                    </table>
                    <div class="d-flex justify-content-end me-5">
                        <span class="fs-5"><strong class="text-danger">Grand Total :</strong>
                            <span>₹<?= number_format($grandTotal, 2) ?></span></span>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-4 mb-3">
                            <input type="radio" name="paymentGroup" id="cashRadio" class="btn-check" checked>
                            <label class="card h-100 p-4 shadow-sm payment-card" for="cashRadio">
                                <div class="d-flex align-items-center mb-3">
                                    <i class="bi bi-cash-stack fs-2 text-success me-2"></i>
                                    <h5 class="mb-0">Collect Cash</h5>
                                </div>
                                <p class="text-muted small">No extra fields required. Please collect physical cash from
                                    the customer.</p>
                            </label>
                        </div>

                        <div class="col-md-4 mb-3">
                            <input type="radio" name="paymentGroup" id="qrRadio" class="btn-check"
                                onclick="generateDynamicQR()">
                            <label class="card h-100 p-4 shadow-sm payment-card" for="qrRadio">
                                <div class="d-flex align-items-center mb-3">
                                    <i class="bi bi-qr-code-scan fs-2 text-primary me-2"></i>
                                    <h5 class="mb-0">QR Code / UPI</h5>
                                </div>
                                <div class="text-center mb-2">
                                    <img id="qrImage"
                                        src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=SelectQR"
                                        alt="QR Code" class="img-fluid border p-2" style="max-height: 80px;">
                                </div>
                                <!-- <input type="text" class="form-control form-control-sm mb-1 mt-2"
                                    placeholder="Enter Transaction ID">
                                <small class="text-muted d-block text-center">Verify payment for <span
                                        id="displayAmount">₹0</span></small> -->
                            </label>
                        </div>

                        <div class="col-md-4 mb-3">
                            <input type="radio" name="paymentGroup" id="cardRadio" class="btn-check">
                            <label class="card h-100 p-4 shadow-sm payment-card" for="cardRadio">
                                <div class="d-flex align-items-center mb-3">
                                    <i class="bi bi-credit-card-2-front fs-2 text-danger me-2"></i>
                                    <h5 class="mb-0">Card Payment</h5>
                                </div>
                                <div class="payment-fields">
                                    <input type="text" class="form-control form-control-sm mb-2"
                                        placeholder="Card Number (Last 4 digits)">
                                    <div class="row g-2">
                                        <div class="col-6"><input type="text" class="form-control form-control-sm"
                                                placeholder="Expiry"></div>
                                        <div class="col-6"><input type="password" class="form-control form-control-sm"
                                                placeholder="CVV"></div>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end mt-4">
                        <button class="btn btn-success me-4" style="width: 150px;">Order</button>
                        <button class="btn btn-danger me-4" style="width: 150px;">Print</button>
                        <button class="btn btn-warning" style="width: 150px;">Download Pdf</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {

            if ($.fn.DataTable.isDataTable('.datanew')) {
                $('.datanew').DataTable().destroy();
            }

            $('.datanew').DataTable({
                order: [[0, 'desc']],

                // columnDefs: [
                //     { targets: 0, visible: false, searchable: false },
                //     { targets: '_all', orderable: true }
                // ],

                autoWidth: false,
                responsive: false,

                searching: false,
                lengthChange: true,
                pageLength: 10,

                pagingType: "simple_numbers",

                dom: 'rt<"row mt-3"<"col-md-6"l><"col-md-6 text-end"p>>'
            });
        });
        function generateDynamicQR() {
            const totalElement = document.querySelector('strong:contains("Grand Total")').parentElement;
            const amount = 20;

            const upiId = "reyanshraut@ybl";
            const merchantName = "Brancy Store";

            const upiUrl = `upi://pay?pa=${upiId}&pn=${merchantName}&am=${amount}&cu=INR`;

            const qrSource = `https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=${encodeURIComponent(upiUrl)}`;

            document.getElementById('qrImage').src = qrSource;
            document.getElementById('displayAmount').innerText = "₹" + amount;
        }
    </script>
    <script>
        
    </script>
</body>

</html>