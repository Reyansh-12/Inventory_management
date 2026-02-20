<?php
define("BASE_PATH", dirname(__DIR__, 3));
include BASE_PATH . "/src/Layouts/Links.php";
include BASE_PATH . "/src/controllers/dbConnection.php";

session_start();
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

if (!isset($_SESSION['transaction_id'])) {
    $_SESSION['transaction_id'] = 'ID' . date('YmdHis') . rand(100, 999);
}

$sql = "
SELECT 
    p.id,
    p.product_name,
    p.category AS category_id,
    c.category AS category_name,
    p.price,
    p.quantity,
    p.image_path
FROM product_list p
LEFT JOIN category c ON p.category = c.category
WHERE p.status = 'Active'
";


$result = $con->query($sql);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['customername'])) {
    $name = mysqli_real_escape_string($con, $_POST['customername']);
    $phone = mysqli_real_escape_string($con, $_POST['customerphone']);
    $email = mysqli_real_escape_string($con, $_POST['customeremail']);
    $address = mysqli_real_escape_string($con, $_POST['customeraddress']);

    $check = mysqli_query($con, "SELECT phone, email FROM customers WHERE phone='$phone' OR email='$email'");
    if (mysqli_num_rows($check) > 0) {
        $_SESSION['customer_error'] = true;
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }

    $customerId = 'CUST' . time();
    $insert = mysqli_query($con, "INSERT INTO customers (customer_id, name, phone, email, address)
                                  VALUES ('$customerId', '$name', '$phone', '$email', '$address')");

    if ($insert) {
        $_SESSION['selected_customer_id'] = mysqli_insert_id($con);
        $_SESSION['customer_success'] = true;
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

$categories = [];
$catQuery = mysqli_query($con, "SELECT id, category, image_path FROM category WHERE status='Active'");
while ($row = mysqli_fetch_assoc($catQuery)) {
    $categories[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dreams POS Admin Template</title>
    <link rel="stylesheet" href="/Backend/src/assets/css/pos.css" />
    <link rel="stylesheet" href="/Backend/src/assets/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .category-track {
            overflow-x: auto;
        }

        .category-slide {
            flex: 0 0 auto;
        }

        .category-card {
            cursor: pointer;
            user-select: none;
        }

        /* --- POS Premium Overhaul --- */
        .category-track {
            display: flex;
            gap: 15px;
            padding: 10px 5px;
            scroll-behavior: smooth;
        }

        .category-card {
            min-width: 100px;
            border: 1px solid #edf2f9;
            border-radius: 12px;
            transition: all 0.3s ease;
            background: #fff;
        }

        .category-card img {
            height: 60px !important;
            width: auto;
            margin: 10px auto;
        }

        .category-card.active {
            border-color: #6792ff !important;
            background: rgba(103, 146, 255, 0.05);
            box-shadow: 0 4px 12px rgba(103, 146, 255, 0.1);
        }

        /* Product Card Polish */
        .product-card {
            border: 1px solid #edf2f9;
            transition: transform 0.2s, box-shadow 0.2s;
            background: #fff;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05) !important;
        }

        .product-card img {
            height: 120px;
            object-fit: contain;
        }

        /* Cart Section Polish */
        .card.position-sticky {
            border-radius: 16px;
            border: none;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        }

        .table-sm th {
            font-size: 0.75rem;
            text-transform: uppercase;
            color: #94a3b8;
        }

        #cartTotal {
            font-size: 1.5rem;
            font-weight: 800;
            color: #1e293b;
        }

        .btn-added-custom {
            background: #6792ff;
            color: white;
            border-radius: 8px;
            font-weight: 600;
        }

        /* --- Professional Customer Selector --- */
        .customer-selection-group {
            background: #f8fafc;
            border-radius: 10px;
            border: 1px solid #e2e8f0;
            overflow: hidden;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            height: 45px;
            /* Fixed Height */
        }

        .customer-selection-group:focus-within {
            border-color: #6792ff;
            box-shadow: 0 0 0 3px rgba(103, 146, 255, 0.1);
            background: #fff;
        }

        .customer-icon-box {
            background: transparent;
            padding: 0 15px;
            color: #94a3b8;
            border-right: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            height: 100%;
        }

        #customerSelect {
            border: none !important;
            background: transparent !important;
            height: 100% !important;
            padding: 0 12px !important;
            font-size: 0.9rem;
            font-weight: 600;
            color: #334155;
            box-shadow: none !important;
            cursor: pointer;
            flex-grow: 1;
        }

        #customerSelect:focus {
            outline: none !important;
        }
    </style>
</head>

<body>
    <div id="global-loader">
        <div class="whirly-loader"></div>
    </div>

    <div class="d-flex row">
        <div class="col-md-3"><?php include BASE_PATH . "/src/Layouts/Sidebar.php"; ?></div>
        <div class="col-md-9"><?php include BASE_PATH . "/src/Layouts/Header.php"; ?></div>
    </div>

    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="row mb-3">
                <div class="col-lg-12 d-flex justify-content-between align-items-center">
                    <div>
                        <h4>Categories</h4>
                        <h6 class="text-muted">Manage your purchases</h6>
                    </div>
                    <div>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCustomerModal">
                            <i class="bi bi-person-plus"></i> Add Customer
                        </button>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-8" style="max-height: 85vh; overflow-y: auto;">
                    <div class="category-wrapper mb-4 p-3 rounded-4" style="background: #f8fafc;">
                        <h6 class="fw-bold mb-3">Filter by Category</h6>
                        <div class="category-track overflow-auto">
                            <div class="category-slide" style="height: 165px !important;">
                                <div class="card category-card text-center active" data-category="all"
                                    style="height: 150px !important;">
                                    <div class="card-body p-2">
                                        <img src="/Backend/src/assets/images/allProducts.png" class="img-fluid">
                                        <div class="small fw-bold">All</div>
                                    </div>
                                </div>
                            </div>

                            <?php foreach ($categories as $cat) { ?>
                                <div class="category-slide" style="height: 165px !important;">
                                    <div class="card category-card text-center" style="height: 150px !important;"
                                        data-category="<?= htmlspecialchars($cat['category']) ?>">
                                        <div class="card-body p-2">
                                            <img src="<?= !empty($cat['image_path']) ? htmlspecialchars($cat['image_path']) : '/Backend/assets/images/product/default-category.png'; ?>"
                                                class="img-fluid">
                                            <div class="small fw-bold"><?= htmlspecialchars($cat['category']) ?></div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>

                    <div class="row g-3">
                        <?php while ($row = $result->fetch_assoc()) {
                            $outOfStock = ((int) $row['quantity'] <= 0);
                            ?>
                            <div class="col-md-4 product-wrapper">
                                <div class="card product-card h-100 rounded-4 border-0 shadow-sm"
                                    data-category="<?= htmlspecialchars($row['category_name']) ?>">
                                    <div class="p-3 text-center">
                                        <img src="<?= $row['image_path'] ?>" class="rounded-3">
                                    </div>
                                    <div class="card-body pt-0">
                                        <span class="badge bg-light text-primary mb-1"><?= $row['category_name'] ?></span>
                                        <h6 class="text-truncate fw-bold mb-1"
                                            title="<?= htmlspecialchars($row['product_name']) ?>">
                                            <?= htmlspecialchars($row['product_name']) ?>
                                        </h6>
                                        <div class="d-flex justify-content-between align-items-center mt-3">
                                            <span class="fw-bold fs-5">₹<?= number_format($row['price'], 2) ?></span>
                                            <button
                                                class="btn btn-sm <?= $outOfStock ? 'btn-light disabled' : 'btn-primary' ?> addToCartBtn"
                                                data-id="<?= $row['id'] ?>" data-name="<?= $row['product_name'] ?>"
                                                data-price="<?= $row['price'] ?>" data-stock="<?= $row['quantity'] ?>">
                                                <i class="bi <?= $outOfStock ? 'bi-x-circle' : 'bi-plus-lg' ?>"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card border-0 shadow-lg position-sticky" style="top:20px; border-radius: 20px;">
                        <div class="card-header bg-white border-0 pt-4 px-4">
                            <h5 class="fw-bold mb-0">Current Order</h5>
                            <div class="badge bg-primary-light text-primary mt-2">ID:
                                #<?= $_SESSION['transaction_id']; ?></div>
                        </div>
                        <div class="card-body px-4">
                            <label class="small text-muted mb-2">Select Customer</label>
                            <div class="customer-selection-group mb-4">
                                <div class="customer-icon-box">
                                    <i class="bi bi-person-circle fs-5"></i>
                                </div>
                                <select class="form-select" id="customerSelect">
                                    <option value="0" <?= !isset($_SESSION['selected_customer_id']) ? 'selected' : ''; ?>>
                                        Walk-in Customer
                                    </option>

                                    <?php
                                    // Database se customers fetch karein
                                    $customers = mysqli_query($con, "SELECT id, name FROM customers ORDER BY id DESC");
                                    while ($c = mysqli_fetch_assoc($customers)) {
                                        $selected = (isset($_SESSION['selected_customer_id']) && $_SESSION['selected_customer_id'] == $c['id']) ? 'selected' : '';
                                        echo "<option value='{$c['id']}' $selected>{$c['name']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <div id="cart-items-container" style="max-height: 350px; overflow-y: auto;">
                                <table class="table table-borderless align-middle" id="posCartTable">
                                    <tbody id="cartBody">
                                    </tbody>
                                </table>
                            </div>

                            <div class="border-top pt-3 mt-3">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Subtotal</span>
                                    <span class="fw-bold">₹<span id="cartTotal">0.00</span></span>
                                </div>
                                <div class="d-flex justify-content-between mb-4">
                                    <span class="text-muted">Items Count</span>
                                    <span class="badge bg-info-light text-info" id="totalItems">0</span>
                                </div>

                                <button class="btn btn-success w-100 py-3 fw-bold rounded-3 shadow-sm mb-2"
                                    id="checkoutBtn">
                                    PROCEED TO PAYMENT <i class="bi bi-arrow-right ms-2"></i>
                                </button>
                                <button class="btn btn-link btn-sm text-danger w-100 text-decoration-none"
                                    id="clearCartBtn">Discard Order</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addCustomerModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Customer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i></button>
                </div>
                <div class="modal-body">
                    <form method="POST" id="customerForm" data-parsley-validate>
                        <div class="mb-3">
                            <label>Customer Name <span class="text-danger">*</span></label>
                            <input type="text" name="customername" id="customerName" class="form-control"
                                maxlength="100" data-parsley-required
                                data-parsley-error-message="Customer name is required">
                        </div>
                        <div class="mb-3">
                            <label>Phone <span class="text-danger">*</span></label>
                            <input type="text" name="customerphone" id="customerPhone" class="form-control"
                                maxlength="10" data-parsley-required
                                data-parsley-error-message="Phone number is required">
                            <small class="text-danger d-none" id="phoneError"></small>
                        </div>
                        <div class="mb-3">
                            <label>Email <span class="text-danger">*</span></label>
                            <input type="email" name="customeremail" id="customerEmail" class="form-control"
                                maxlength="200" data-parsley-required data-parsley-error-message="Email is required">
                            <small class="text-danger d-none" id="emailError"></small>
                        </div>
                        <div class="mb-3">
                            <label>Address</label>
                            <input type="text" name="customeraddress" class="form-control" maxlength="300">
                        </div>
                        <button type="submit" name="addCustomerSubmitButton" class="btn btn-primary">Add
                            Customer</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="toast-container position-fixed top-0 end-0 p-3">
        <div id="customerToast" class="toast align-items-center text-bg-danger border-0" role="alert">
            <div class="d-flex">
                <div class="toast-body">
                    Please select a customer before checkout
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    </div>
    <div class="toast-container position-fixed top-0 end-0 p-3">
        <div id="cartEmptyToast" class="toast align-items-center text-bg-warning border-0" role="alert">
            <div class="d-flex">
                <div class="toast-body">
                    Cart is empty. Please add products before checkout.
                </div>
                <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    </div>
    <div class="toast-container position-fixed top-0 end-0 p-3">
        <div id="customerExistsToast" class="toast align-items-center text-bg-danger border-0">
            <div class="d-flex">
                <div class="toast-body">
                    Phone or Email already exists!
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    </div>

    <div class="toast-container position-fixed top-0 end-0 p-3">
        <div id="customerSuccessToast" class="toast align-items-center text-bg-success border-0" role="alert"
            aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">Customer added successfully!</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="/Backend/src/assets/js/pos.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {

            <?php if (isset($_SESSION['customer_success'])): ?>
                var sEl = document.getElementById('customerSuccessToast');
                if (sEl) {
                    new bootstrap.Toast(sEl, {
                        delay: 3000
                    }).show();
                }
                <?php unset($_SESSION['customer_success']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['customer_error'])): ?>
                var eEl = document.getElementById('customerExistsToast');
                if (eEl) {
                    new bootstrap.Toast(eEl, {
                        delay: 4000
                    }).show();
                }
                <?php unset($_SESSION['customer_error']); ?>
            <?php endif; ?>
        });
        let checkoutBtn = document.getElementById("checkoutBtn");
        checkoutBtn.replaceWith(checkoutBtn.cloneNode(true));
        checkoutBtn = document.getElementById("checkoutBtn");

        checkoutBtn.addEventListener("click", () => {
            const customerId = document.getElementById("customerSelect").value;

            if (cart.length === 0) {
                Swal.fire("Empty Cart", "Please add products first!", "warning");
                return;
            }

            if (!customerId) {
                Swal.fire("Select Customer", "Please select a customer!", "warning");
                return;
            }

            fetch("store_checkout.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    cart: cart,
                    customer_id: customerId
                })
            })
                .then(res => res.json())
                .then(data => {
                    if (data.status === "success") {
                        window.location.href = "order.php";
                    }
                })
                .catch(err => console.log(err));
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const categoryCards = document.querySelectorAll(".category-card");
            const productWrappers = document.querySelectorAll(".product-wrapper");
            const noProduct = document.getElementById("noProductFound");

            categoryCards.forEach(card => {
                card.addEventListener("click", function () {
                    let selectedCategory = this.getAttribute("data-category").trim().toLowerCase();

                    let found = false;

                    productWrappers.forEach(wrapper => {
                        const productCard = wrapper.querySelector(".product-card");
                        let productCategory = productCard.getAttribute("data-category") ? productCard.getAttribute("data-category").trim().toLowerCase() : "";

                        if (selectedCategory === "all" || productCategory === selectedCategory) {
                            wrapper.style.setProperty('display', 'block', 'important');
                            found = true;
                        } else {
                            wrapper.style.setProperty('display', 'none', 'important');
                        }
                    });

                    noProduct.style.display = found ? "none" : "block";

                    categoryCards.forEach(c => c.classList.remove("border-primary", "shadow-sm"));
                    this.classList.add("border-primary", "shadow-sm");
                });
            });
        });
    </script>
</body>

</html>