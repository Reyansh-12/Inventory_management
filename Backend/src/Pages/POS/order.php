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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = 'ORD-' . strtoupper(uniqid());
    $customer = mysqli_real_escape_string($con, $_POST['customer']);
    $status = mysqli_real_escape_string($con, $_POST['status']); // Payment Mode
    $total = (float)$_POST['total_amount'];
    
    // Optional fields (UTR, Card details)
    $utr = $_POST['utr'] ?? '';
    $card_name = $_POST['card_name'] ?? '';

    // Cart Items Loop (Example: storing multiple items as a string or in a sub-table)
    // Yahan hum man kar chal rahe hain ki aap single entry kar rahe hain summary ke liye
    $sql = "INSERT INTO order_list (order_id, customer, status, total_amount, created, seen) 
            VALUES ('$order_id', '$customer', '$status', '$total', NOW(), 0)";

    if (mysqli_query($con, $sql)) {
        // Order success hone par session clear karein
        unset($_SESSION['order_cart']);
        echo json_encode(['success' => true, 'order_id' => $order_id]);
    } else {
        echo json_encode(['success' => false, 'message' => mysqli_error($con)]);
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

        .table tbody tr td {
            max-width: 100px;
            overflow: hidden;
        }

        /* --- Professional Invoice & Checkout Overhaul --- */
        :root {
            --primary-blue: #6792ff;
            --border-color: #e2e8f0;
            --bg-light: #f8fafc;
        }

        .card {
            border: none !important;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            border-radius: 12px !important;
        }

        /* Invoice Header Styling */
        .invoice-branding h1 {
            font-weight: 800;
            letter-spacing: -1px;
            color: var(--primary-blue);
            margin-bottom: 5px;
        }

        .info-label {
            color: #718096;
            font-size: 0.8rem;
            text-transform: uppercase;
            font-weight: 600;
            display: block;
        }

        .info-value {
            color: #2d3748;
            font-weight: 700;
            font-size: 1rem;
        }

        /* Table Polish */
        .table thead th {
            background-color: #f8f9fa;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 0.5px;
            color: #6c757d;
            border-bottom: 1px solid #edf2f9;
        }

        .table td {
            vertical-align: middle !important;
            padding: 12px 15px !important;
        }

        /* Payment Options Styling */
        .btn-check:checked+.payment-card {
            border: 2px solid var(--primary-blue) !important;
            background-color: rgba(103, 146, 255, 0.05);
            transform: translateY(-5px);
        }

        .payment-card {
            border: 1px solid var(--border-color) !important;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: #fff;
            cursor: pointer;
            overflow: hidden;
        }

        .payment-card i {
            font-size: 2.5rem;
            transition: transform 0.3s;
        }

        .payment-card:hover i {
            transform: scale(1.1);
        }

        /* Sticky Grand Total Bar */
        .grand-total-section {
            background: #1a1d21;
            color: white;
            padding: 20px 40px;
            border-radius: 12px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .total-amount-large {
            font-size: 1.8rem;
            font-weight: 800;
            color: #fff;
        }

        /* --- Dynamic Payment Fields Polish --- */
        .payment-details-box {
            background: rgba(103, 146, 255, 0.05);
            border-radius: 8px;
            padding: 15px;
            margin-top: 15px;
            border: 1px solid rgba(103, 146, 255, 0.1);
            display: none;
            /* Hidden by default */
        }

        /* Show box only when parent radio is checked */
        #qrRadio:checked~.payment-card .payment-details-box,
        #cardRadio:checked~.payment-card .payment-details-box {
            display: block;
            animation: fadeInDown 0.3s ease;
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .payment-card i {
            transition: color 0.3s ease;
        }

        .btn-check:checked+.payment-card i {
            color: var(--primary-blue) !important;
        }

        /* --- Table Layout & Overflow Fix --- */
        .table-responsive {
            max-width: 800px;
            /* Table ki total width fix kar di */
            margin: 0 auto;
            /* Table ko center mein align kiya */
            overflow-x: hidden !important;
            /* Horizontal scroll remove kiya */
            border: 1px solid #edf2f9;
            border-radius: 8px;
        }

        .table {
            table-layout: fixed;
            /* Columns ki width fix karne ke liye */
            width: 100% !important;
            margin-bottom: 0 !important;
        }

        /* Specific Column Widths (In pixels) */
        .col-id {
            width: 40px;
        }

        .col-desc {
            width: 320px;
        }

        /* Description ko space di */
        .col-rate {
            width: 100px;
        }

        .col-qty {
            width: 70px;
        }

        .col-amount {
            width: 120px;
        }

        /* Product Name Truncation with Tooltip support */
        .product-name-cell {
            display: block;
            width: 100%;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            cursor: help;
        }

        .table td,
        .table th {
            padding: 12px 15px !important;
            vertical-align: middle !important;
        }
        /* --- Professional Pagination Styling --- */
.dataTables_wrapper .dataTables_paginate {
    margin-top: 20px;
    padding-top: 15px;
    border-top: 1px solid #edf2f9;
}

.dataTables_wrapper .dataTables_paginate .paginate_button {
    padding: 6px 14px !important;
    margin: 0 3px !important;
    border: 1px solid #e2e8f0 !important;
    border-radius: 8px !important;
    background: #fff !important;
    color: #64748b !important;
    font-weight: 600;
    font-size: 0.85rem;
    transition: all 0.2s ease;
}

.dataTables_wrapper .dataTables_paginate .paginate_button.current,
.dataTables_wrapper .dataTables_paginate .paginate_button:hover {
    background: var(--primary-blue) !important;
    color: white !important;
    border-color: var(--primary-blue) !important;
    box-shadow: 0 4px 10px rgba(103, 146, 255, 0.25);
}

.dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
    opacity: 0.5;
    cursor: not-allowed;
    background: #f8fafc !important;
}

/* Table Footer Symmetry */
.table-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px 0;
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
        <div class="page-wrapper">
            <div class="content container-fluid">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="fw-bold">Review Order</h4>
                    <a href="pos.php" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                        <i class="bi bi-arrow-left"></i> Modify Cart
                    </a>
                </div>

                <div class="card p-4">
                    <div class="invoice-branding text-center mb-5 border-bottom pb-4">
                        <h1>BRANCY</h1>
                        <p class="text-muted small mb-0">Plot no 66, Kharabi, Nagpur, Maharashtra</p>
                        <p class="text-muted small">GSTIN: 27AAAAA0000A1Z5</p>
                    </div>

                    <div class="row mb-5 g-4">
                        <div class="col-md-6 border-end">
                            <span class="info-label">Billed To:</span>
                            <div class="info-value"><?= htmlspecialchars($customerData['name']) ?></div>
                            <div class="text-muted small"><?= $customerData['phone'] ?> | <?= $customerData['email'] ?>
                            </div>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <span class="info-label">Invoice Details:</span>
                            <div class="info-value">Date: <?= $invoiceDate ?></div>
                            <div class="text-muted small">ID: #<?= $_SESSION['transaction_id'] ?? 'TRX-' . time() ?>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive mb-4">
    <table class="table" id="orderItemsTable">
        <thead>
            <tr>
                <th class="col-id">#</th>
                <th class="col-desc">Product Description</th>
                <th class="col-rate text-center">Rate</th>
                <th class="col-qty text-center">Qty</th>
                <th class="col-amount">Amount</th>
            </tr>
        </thead>
        <tbody>
            <?php $i = 1; foreach ($cartItems as $item): ?>
                <tr>
                    <td><?= $i++ ?></td>
                    <td>
                        <span class="product-name-cell" data-bs-toggle="tooltip" title="<?= htmlspecialchars($item['name']) ?>">
                            <?= htmlspecialchars($item['name']) ?>
                        </span>
                    </td>
                    <td class="text-center">₹<?= number_format($item['price'], 2) ?></td>
                    <td class="text-center"><?= $item['quantity'] ?></td>
                    <td class="text-end fw-bold">₹<?= number_format($item['price'] * $item['quantity'], 2) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

                    <div class="grand-total-section mb-5">
                        <span class="fs-5 fw-bold">Amount Payable</span>
                        <span class="total-amount-large">₹<?= number_format($grandTotal, 2) ?></span>
                    </div>

                    <h5 class="mb-4 text-primary fs-6 fw-bold">Select Payment Method</h5>
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <input type="radio" name="paymentGroup" id="cashRadio" class="btn-check" checked>
                            <label class="card h-100 p-4 payment-card" for="cashRadio">
                                <i class="bi bi-cash text-success mb-3"></i>
                                <h6 class="fw-bold">Cash Payment</h6>
                                <p class="text-muted small mb-0">Direct cash collection</p>
                            </label>
                        </div>
                        <div class="col-md-4 mb-3">
                            <input type="radio" name="paymentGroup" id="qrRadio" class="btn-check"
                                onclick="generateDynamicQR()">
                            <label class="card h-100 p-4 payment-card" for="qrRadio">
                                <div class="text-center">
                                    <i class="bi bi-qr-code-scan text-muted mb-2"></i>
                                    <h6 class="fw-bold">UPI / QR Code</h6>
                                </div>

                                <div class="payment-details-box text-center">
                                    <img id="qrImage"
                                        src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=SelectQR"
                                        alt="QR Code" class="img-fluid border p-2 mb-3 bg-white"
                                        style="max-height: 100px;">
                                    <div class="form-group text-start">
                                        <label class="small fw-bold">Transaction ID / UTR <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="utr_no" class="form-control form-control-sm"
                                            placeholder="Enter 12 digit No.">
                                    </div>
                                </div>
                            </label>
                        </div>

                        <div class="col-md-4 mb-3">
                            <input type="radio" name="paymentGroup" id="cardRadio" class="btn-check">
                            <label class="card h-100 p-4 payment-card" for="cardRadio">
                                <div class="text-center">
                                    <i class="bi bi-credit-card-2-back text-muted mb-2"></i>
                                    <h6 class="fw-bold">Card Terminal</h6>
                                </div>

                                <div class="payment-details-box">
                                    <div class="form-group mb-2">
                                        <label class="small fw-bold">Card Holder Name</label>
                                        <input type="text" name="card_name" class="form-control form-control-sm"
                                            placeholder="As on card">
                                    </div>
                                    <div class="row g-2">
                                        <div class="col-7">
                                            <div class="form-group">
                                                <label class="small fw-bold">Last 4 Digits</label>
                                                <input type="text" name="card_no" class="form-control form-control-sm"
                                                    placeholder="0000" maxlength="4">
                                            </div>
                                        </div>
                                        <div class="col-5">
                                            <div class="form-group">
                                                <label class="small fw-bold">Auth Code</label>
                                                <input type="text" name="auth_code" class="form-control form-control-sm"
                                                    placeholder="Appr. ID">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 border-top pt-4">
                        <button class="btn btn-outline-danger px-4">Print Thermal</button>
                        <button class="btn btn-outline-warning px-4">Download PDF</button>
                        <button id="processOrder" class="btn btn-success px-5 fw-bold shadow-sm">COMPLETE ORDER</button>
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
        $('#processOrder').on('click', function () {
    let $btn = $(this);
    let status = "Cash";
    let paymentDetails = {};

    // Payment Type detect karein
    if ($('#qrRadio').is(':checked')) {
        status = "Online";
        paymentDetails.utr = $('input[name="utr_no"]').val();
        if(!paymentDetails.utr) {
            Swal.fire("Required", "Please enter Transaction ID / UTR", "warning");
            return;
        }
    } else if ($('#cardRadio').is(':checked')) {
        status = "Card";
        paymentDetails.card_name = $('input[name="card_name"]').val();
    }

    // Button ko disable karein taaki double click na ho
    $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Processing...');

    $.ajax({
        url: 'save_order.php',
        type: 'POST',
        data: {
            status: status,
            total_amount: "<?= $grandTotal ?>",
            customer: "<?= $customerData['name'] ?>",
            utr: paymentDetails.utr || '',
            card_name: paymentDetails.card_name || ''
        },
        success: function (response) {
            try {
                let res = JSON.parse(response);
                if (res.success) {
                    Swal.fire({
                        title: "Success!",
                        text: "Order #" + res.order_id + " placed successfully",
                        icon: "success",
                        confirmButtonText: "View History"
                    }).then(() => {
                        window.location.href = "history.php"; // Redirect to history
                    });
                } else {
                    Swal.fire("Error", res.message, "error");
                    $btn.prop('disabled', false).text('COMPLETE ORDER');
                }
            } catch (e) {
                console.error("Invalid JSON:", response);
                Swal.fire("Server Error", "Data saved but response was invalid.", "error");
            }
        },
        error: function () {
            Swal.fire("Error", "Could not connect to server", "error");
            $btn.prop('disabled', false).text('COMPLETE ORDER');
        }
    });
});
    </script>
   
    <script>
        // $(document).ready(function () {
        //     if ($.fn.DataTable.isDataTable('#orderItemsTable')) {
        //         $('#orderItemsTable').DataTable().destroy();
        //     }

        //     $('#orderItemsTable').DataTable({
        //         "ordering": true,
        //         "searching": false,
        //         "paging": true,
        //         "info": false,
        //         "columnDefs": [
        //             { "orderable": false, "targets": [1, 2, 3, 4] } 
        //         ],
        //         "language": {
        //             "emptyTable": "No items available in the cart"
        //         },
        //         "dom": 'rt<"row mt-3"<"col-md-12 text-end"p>>'
        //     });
        // });
        var cartItems = <?php echo json_encode(array_map(function ($item, $i) {
            return [$i + 1, $item['name'], number_format($item['price'], 2), $item['quantity'], number_format($item['price'] * $item['quantity'], 2)];
        }, $cartItems, array_keys($cartItems))); ?>;

        $('#orderItemsTable').DataTable({
            data: cartItems,
            columns: [
                { title: "ID" },
                { title: "Product Name" },
                { title: "Price", className: "text-center" },
                { title: "Quantity", className: "text-center" },
                { title: "Total Price", className: "text-center" }
            ],
            "ordering": true,
            "searching": false,
        });
        $('#orderItemsTable').DataTable({
            data: cartItems,
            columns: [
                { title: "#" },
                { title: "Product Description", className: "fw-bold" },
                { title: "Rate", className: "text-center" },
                { title: "Qty", className: "text-center" },
                { title: "Amount", className: "text-end fw-bold" }
            ],
            "paging": false,
            "searching": false,
            "info": false,
            "ordering": false,
            "dom": 'rt' // Removes all clutter like search/length
        });
        function generateDynamicQR() {
            const amount = "<?= $grandTotal ?>"; // Real total from PHP
            const upiId = "reyanshraut@ybl"; // Your UPI ID
            const merchantName = "Brancy Cosmetic Store";

            // Standard UPI String
            const upiUrl = `upi://pay?pa=${upiId}&pn=${encodeURIComponent(merchantName)}&am=${amount}&cu=INR`;

            // Update QR Image
            const qrSource = `https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=${encodeURIComponent(upiUrl)}`;
            document.getElementById('qrImage').src = qrSource;
        }
        $(document).ready(function () {
            // Single optimized DataTable call
            $('#orderItemsTable').DataTable({
                "paging": false,
                "searching": false,
                "info": false,
                "ordering": false,
                "autoWidth": false, // Manual CSS width use karne ke liye ise false rakhein
                "dom": 'rt',
                "drawCallback": function () {
                    // Re-initialize tooltips every time the table is drawn
                    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
                    tooltipTriggerList.map(function (tooltipTriggerEl) {
                        return new bootstrap.Tooltip(tooltipTriggerEl)
                    });
                }
            });
        });
        $(document).ready(function () {
    // Destroy any existing instances to prevent conflicts
    if ($.fn.DataTable.isDataTable('#orderItemsTable')) {
        $('#orderItemsTable').DataTable().destroy();
    }

    $('#orderItemsTable').DataTable({
        "paging": true,          // Pagination enabled
        "pageLength": 5,        // Standard length for invoice review
        "searching": false,      // Clean invoice look
        "info": true,            // Show "Showing 1 to 5"
        "ordering": false,       // Keep order as added to cart
        "autoWidth": false,
        "language": {
            "paginate": {
                "next": '<i class="bi bi-chevron-right"></i>',
                "previous": '<i class="bi bi-chevron-left"></i>'
            },
            "info": "Showing _START_ to _END_ of _TOTAL_ items"
        },
        "dom": 'rt<"table-footer"ip>', // Positioning pagination at bottom
        "drawCallback": function () {
            // Re-initialize tooltips after pagination click
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });
        }
    });
});
    </script>
</body>

</html>