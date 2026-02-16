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
                <div class="col-lg-8" style="max-height: 80vh; overflow-y: auto; padding-right:15px;">
                    <div id="categorySlider" class="slider-container mb-3 position-relative"
                        style="background:rgba(40, 0, 84, 0.18)">
                        <!-- <button id="prevBtn" class="carousel-control-prev slider-btn" type="button">
                            <span class="carousel-control-prev-icon"></span>
                        </button>
                        <button id="nextBtn" class="carousel-control-next slider-btn" type="button">
                            <span class="carousel-control-next-icon"></span>
                        </button> -->

                        <div class="overflow-hidden">
                            <div class="category-track">
                                <div class="category-slide">
                                    <div class="card category-card text-center border-primary" data-category="all">

                                        <div class="card-body text-center">
                                            <img src="/Backend/src/assets/images/allProducts.png" alt="">
                                            All products
                                        </div>
                                    </div>
                                </div>

                                <?php foreach ($categories as $cat) { ?>
                                    <div class="category-slide">
                                        <div class="card category-card text-center"
                                            data-category="<?= htmlspecialchars($cat['category']) ?>"
                                            style="cursor:pointer">
                                            <img style="object-fit: contain; height: 115px; margin-top: 5px;"
                                                src="<?= !empty($cat['image_path']) ? htmlspecialchars($cat['image_path']) : '/Backend/assets/images/product/default-category.png'; ?>"
                                                class="card-img-top mb-3">
                                            <div class="card-body" style="padding: 2px;">
                                                <?= htmlspecialchars($cat['category']) ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>

                    <div class="row row-cols-1 row-cols-md-3">
                        <?php if ($result && $result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $id = (int) $row['id'];
                                $name = htmlspecialchars($row['product_name']);
                                $categoryId = (int) $row['category_id'];
                                $categoryName = $row['category_name'];
                                $price = number_format((float) $row['price'], 2);
                                $image = !empty($row['image_path']) ? $row['image_path'] : "/Inventory_managment/Backend/assets/images/favicon1.png";
                                ?>
                                <div class="col-12 col-md-4 col-lg-4 shadow-md product-wrapper" style="padding: 5px">
                                    <div class="product-card p-3 shadow-sm rounded h-100"
                                        data-category="<?= htmlspecialchars($categoryName) ?>">
                                        <div class="text-center">
                                            <img src="<?= $image ?>" class="img-fluid mb-2">
                                        </div>
                                        <h6 class="text-muted small"><?= $categoryName ?></h6>
                                        <h5 class="text-truncate" data-bs-toggle='tooltip' data-bs-title="<?php echo $name ?>">
                                            <?= $name ?>
                                        </h5>
                                        <h6 class="text-primary">₹<?= $price ?></h6>
                                        <div class="d-grid">
                                            <?php $outOfStock = ((int) $row['quantity'] <= 0); ?>
                                            <button
                                                class="btn <?= $outOfStock ? 'btn-secondary' : 'btn-primary' ?> addToCartBtn"
                                                <?= $outOfStock ? 'disabled' : '' ?> data-id="<?= $id ?>"
                                                data-name="<?= $name ?>" data-price="<?= (float) $price ?>"
                                                data-stock="<?= (int) $row['quantity'] ?>">
                                                <?= $outOfStock ? 'Out of Stock' : 'Add to Cart' ?>
                                            </button>

                                        </div>
                                    </div>
                                </div>
                            <?php }
                        } ?>
                    </div>

                    <div id="noProductFound" class="text-center w-100 mt-4" style="display:none;">
                        <img src="/Backend/src/assets/images/product_not_found2.png" class="img-fluid"
                            style="max-width:400px;">
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card position-sticky" style="top:20px;">
                        <div class="card-header">
                            <h5>Order List</h5>
                            <small class="text-muted">Transaction ID: #<?= $_SESSION['transaction_id']; ?></small>
                        </div>
                        <div class="card-body">
                            <div class="mb-3 d-flex gap-2">
                                <select class="form-select" id="customerSelect">
                                    <option value="" disabled <?= !isset($_SESSION['selected_customer_id']) ? 'selected' : ''; ?>>Walk-in Customer</option>
                                    <?php
                                    $selectedCustomerId = $_SESSION['selected_customer_id'] ?? null;
                                    $customers = mysqli_query($con, "SELECT id, name FROM customers ORDER BY id DESC");
                                    while ($c = mysqli_fetch_assoc($customers)) {
                                        // Variable name ek hi rakhein: $isSelected
                                        $isSelected = ($c['id'] == $selectedCustomerId) ? 'selected' : '';
                                        echo "<option value='{$c['id']}' $isSelected>{$c['name']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="table-responsive mb-2">
                                <table class="table table-bordered table-sm" id="posCartTable">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width:40%;">Product</th>
                                            <th style="width:20%;">Qty</th>
                                            <th style="width:20%;">Price</th>
                                            <th style="width:20%;">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="cartBody"></tbody>
                                </table>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div>Total Items: <span id="totalItems">0</span></div>
                                <div id="cartTotals">Total: ₹<span id="cartTotal">0.00</span></div>
                            </div>

                            <div class="d-grid">
                                <button class="btn btn-success mb-2" id="checkoutBtn"><i class="bi bi-cart-check"></i>
                                    Checkout</button>
                                <button class="btn btn-danger btn-sm w-100" id="clearCartBtn">Clear All</button>
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

            // Success Toast
            <?php if (isset($_SESSION['customer_success'])): ?>
                var sEl = document.getElementById('customerSuccessToast');
                if (sEl) {
                    new bootstrap.Toast(sEl, { delay: 3000 }).show();
                }
                <?php unset($_SESSION['customer_success']); // Toaster dikhne ke baad clear karein ?>
            <?php endif; ?>

            // Error Toast
            <?php if (isset($_SESSION['customer_error'])): ?>
                var eEl = document.getElementById('customerExistsToast');
                if (eEl) {
                    new bootstrap.Toast(eEl, { delay: 4000 }).show();
                }
                <?php unset($_SESSION['customer_error']); ?>
            <?php endif; ?>

            // Selection clear (Optionally checkout ke baad karne ke liye)
            // Hum yahan unset nahi karenge taaki dropdown mein dikhta rahe
        });
        document.getElementById('checkoutBtn').addEventListener('click', () => {
    const customerSelect = document.getElementById('customerSelect');
    const customerId = customerSelect ? customerSelect.value : '';

    if (cart.length === 0) {
        Swal.fire('Empty Cart', 'Please add products first!', 'warning');
        return;
    }

    if (!customerId) {
        Swal.fire('Select Customer', 'Please select a customer!', 'warning');
        return;
    }

    fetch("/Backend/src/controllers/checkout.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ cart: cart, customer_id: customerId })
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            Swal.fire('Success', data.message, 'success').then(() => {
                cart = [];
                localStorage.removeItem('pos_cart');
                location.reload();
            });
        } else {
            Swal.fire('Error', data.message, 'error');
        }
    })
    .catch(err => {
        Swal.fire('Server Error', err.message, 'error');
    });
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