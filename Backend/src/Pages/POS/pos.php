<?php
define("BASE_PATH", dirname(__DIR__, 3));
include BASE_PATH . "/src/Layouts/Links.php";
include BASE_PATH . "/src/controllers/dbConnection.php";

session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
if (!isset($_SESSION['transaction_id'])) {
    $_SESSION['transaction_id'] = 'ID' . date('YmdHis') . rand(100, 999);
}

$sql = "SELECT `id`,`product_name`, `category`, `brand_name`, `price`, `quantity`, `image_path` FROM `product_list`";
$result = $con->query($sql);

if (isset($_POST['addCustomerSubmitButton'])) {

    $customerId = 'CUST' . time();
    $name       = $_POST['customername'];
    $phone      = $_POST['customerphone'];
    $email      = $_POST['customeremail'];
    $address    = $_POST['customeraddress'];

    $sql = "INSERT INTO customers (customer_id, name, phone, email, address)
            VALUES ('$customerId', '$name', '$phone', '$email', '$address')";

    if (mysqli_query($con, $sql)) {
        $_SESSION['customer_added'] = true;
        $_SESSION['selected_customer_id'] = mysqli_insert_id($con);
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dreams POS Admin Template</title>
    <link rel="stylesheet" href="/Backend/src/assets/css/pos.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <style>
        .parsley-minlength {
            color: red !important;
        }

        .cart-product-name {
            max-width: 140px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            display: inline-block;
            vertical-align: middle;
        }

        #posCartTable td {
            vertical-align: middle;
        }

        #posCartTable td:nth-child(2),
        #posCartTable td:nth-child(3) {
            white-space: nowrap;
        }

        #posCartTable td {
            vertical-align: middle;
            white-space: nowrap;
        }

        .qty-box {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .qty-box span {
            min-width: 20px;
            text-align: center;
            font-weight: 600;
        }
    </style>
</head>

<body>
    <div id="global-loader">
        <div class="whirly-loader"></div>
    </div>

    <div class="d-flex row">
        <div class="col-md-3">
            <?php include BASE_PATH . "/src/Layouts/Sidebar.php"; ?>
        </div>
        <div class="col-md-9">
            <?php include BASE_PATH . "/src/Layouts/Header.php"; ?>
        </div>
    </div>

    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="row">
                <div class="col-lg-12 mb-3">
                    <div class="page-header d-flex justify-content-between align-items-center">
                        <div class="page-title">
                            <h4>Categories</h4>
                            <h6 class="text-muted">Manage your purchases</h6>
                        </div>
                        <div class="page-btn">
                            <button class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#addCustomerModal">
                                <i class="bi bi-person-plus"></i> Add Customer
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card w-100">
                    <div class="card-body">
                        <div class="row gx-4">
                            <div class="col-lg-8 pos-column">
                                <div id="categorySlider" class="carousel slide slider-container mb-3" data-bs-ride="false">
                                    <button class="carousel-control-prev slider-btn" type="button" data-bs-target="#categorySlider" data-bs-slide="prev" id="prevBtn">
                                        <span class="carousel-control-prev-icon"></span>
                                    </button>
                                    <button class="carousel-control-next slider-btn" type="button" data-bs-target="#categorySlider" data-bs-slide="next" id="nextBtn">
                                        <span class="carousel-control-next-icon"></span>
                                    </button>
                                    <div class="carousel-inner">
                                        <div class="carousel-item active">
                                            <div class="card m-2 category-card">
                                                <img src="/Backend/assets/images/product/product62.png" class="card-img-top" alt="Hair care">
                                                <div class="card-body text-center"><span>Hair care</span></div>
                                            </div>
                                        </div>
                                        <div class="carousel-item">
                                            <div class="card m-2 category-card">
                                                <img src="/Backend/assets/images/product/product63.png" class="card-img-top" alt="Face skin">
                                                <div class="card-body text-center"><span>Face skin</span></div>
                                            </div>
                                        </div>
                                        <div class="carousel-item">
                                            <div class="card m-2 category-card">
                                                <img src="/Backend/assets/images/product/product64.png" class="card-img-top" alt="Skin Care">
                                                <div class="card-body text-center"><span>Skin Care</span></div>
                                            </div>
                                        </div>
                                        <div class="carousel-item">
                                            <div class="card m-2 category-card">
                                                <img src="/Backend/assets/images/product/product65.png" class="card-img-top" alt="Blusher">
                                                <div class="card-body text-center"><span>Blusher</span></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tabs_container">
                                    <div class="tab_content active" data-tab="fruits">
                                        <!-- <div class="col"> -->
                                        <div class="row row-cols-1 row-cols-md-3">
                                            <?php
                                            if ($result && $result->num_rows > 0) {
                                                while ($row = $result->fetch_assoc()) {
                                                    $id = (int)$row['id'];
                                                    $name = htmlspecialchars($row['product_name']);
                                                    $category = htmlspecialchars($row['category']);
                                                    $price = number_format((float)$row['price'], 2, '.', '');
                                                    $image = !empty($row['image_path']) ? $row['image_path']  : "/Inventory_managment/Backend/assets/images/favicon1.png"; ?>
                                                    <div class="col-12 col-md-4 col-lg-4">
                                                        <div class="product-card p-3 shadow-sm rounded h-100">
                                                            <div class="text-center">
                                                                <img src="<?php echo $image; ?>" alt="<?php echo $name; ?>" class="img-fluid mb-2" alt="Product image" />
                                                            </div>

                                                            <div>
                                                                <h6 class="text-muted mb-1 small text-truncate w-100" data-bs-toggle='tooltip' style="width: 100px" data-bs-title="<?php echo htmlspecialchars($category); ?>"><?php echo $category; ?></h6>
                                                                <h5 class="mb-1 text-truncate w-100" data-bs-toggle='tooltip' data-bs-title="<?php echo htmlspecialchars($name); ?>" style="width: 150px"><?php echo $name; ?></h5>
                                                                <h6 class="text-primary">₹<?php echo $price; ?></h6>
                                                            </div>

                                                            <div class="d-grid">
                                                                <button class="btn btn-primary addToCartBtn" data-id="<?php echo $id; ?>" data-name="<?php echo $name; ?>" data-price="<?= (float)$price; ?>" data-stock="<?= (int)$row['quantity'] ?>" aria-label="Add to cart">Add to Cart</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php
                                                }
                                            } else {
                                                ?>
                                                <div class="col">
                                                    <div class="p-3">No products found.</div>
                                                </div>
                                            <?php } ?>
                                        </div>
                                        <!-- </div> -->
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="mb-0">Order List</h5>
                                        <small class="text-muted">
                                            Transaction ID: #<?= $_SESSION['transaction_id']; ?>
                                        </small>
                                    </div>

                                    <div class="card-body">
                                        <!-- <div class="mb-3">
                                            <button class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#addCustomerModal">
                                                <i class="bibi-person-plus"></i> Add Customer
                                            </button>
                                        </div> -->

                                        <div class="mb-3 d-flex gap-2">
                                            <select class="form-select" id="customerSelect">
                                                <option disabled <?= empty($_SESSION['selected_customer_id']) ? 'selected' : '' ?>>
                                                    Walk-in Customer
                                                </option>

                                                <?php
                                                $selectedCustomerId = $_SESSION['selected_customer_id'] ?? null;

                                                $customers = mysqli_query($con, "SELECT id, name FROM customers ORDER BY id DESC");
                                                while ($c = mysqli_fetch_assoc($customers)) {
                                                    $selected = ($c['id'] == $selectedCustomerId) ? 'selected' : '';
                                                    echo "<option value='{$c['id']}' $selected>{$c['name']}</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>

                                        <!-- Dynamic POS Cart Table -->
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
                                                <tbody id="cartBody">

                                                </tbody>
                                            </table>
                                        </div>

                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <div>Total Items: <span id="totalItems">0</span></div>
                                            <div id="cartTotals">Total: ₹<span id="cartTotal">0.00</span></div>
                                        </div>

                                        <div class="d-grid">
                                            <button class="btn btn-success" id="checkoutBtn"><i class="bi bi-cart-check"></i> Checkout</button>
                                        </div>
                                    </div>

                                    <div class="card-footer text-end">
                                        <button class="btn btn-danger btn-sm" id="clearCartBtn">Clear All</button>
                                    </div>
                                </div>
                                <div class="mt-4">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="addCustomerModal" tabindex="-1" aria-labelledby="addCustomerModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCustomerModalLabel">Add Customer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i class="bi bi-x-lg"></i></button>
                </div>
                <div class="modal-body">
                    <form method="POST" id="customerForm" data-parsley-validate>
                        <div class="row mb-3">
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <label for="customerName" class="form-label">Customer Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="customername" id="customerName" placeholder="Enter customer name" data-parsley-required-message="Customer name is required" data-parsley-required>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <label for="customerPhone" class="form-label">Phone <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="customerphone" id="customerPhone" placeholder="Enter phone number" maxlength="10" data-parsley-minlength="10" data-parsley-required-message="Phone number is required" data-parsley-required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="customerEmail" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" name="customeremail" id="customerEmail" placeholder="Enter customer email" data-parsley-required-message="Email is required" data-parsley-required>
                        </div>
                        <div class="mb-3">
                            <label for="customerAddress" class="form-label">Address</label>
                            <input type="text" class="form-control" name="customeraddress" id="customerAddress" placeholder="Enter customer address">
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <label for="customerCity" class="form-label">City</label>
                                <input type="text" class="form-control" name="customercity" id="customerCity" placeholder="Enter city">
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <label for="customerCountry" class="form-label">Country</label>
                                <input type="text" class="form-control" name="customercountry" id="customerCountry" placeholder="Enter country">
                            </div>
                        </div>
                        <button type="submit" name="addCustomerSubmitButton" class="btn btn-primary">Add Customer</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="toast-container position-fixed top-0 end-0 p-3">
        <div id="checkoutToast" class="toast align-items-center text-bg-primary border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body" id="checkoutToastMessage">
                    Checkout pressed — check console for payload.
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>

    <div class="toast-container position-fixed top-0 end-0 p-3">
        <div id="cartToast" class="toast align-items-center text-bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    Cart is empty.
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>
    <div class="toast-container position-fixed top-0 end-0 p-3">
        <div id="clearCartToast" class="toast align-items-center text-bg-warning border-0"
            role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    Cart cleared successfully
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto"
                    data-bs-dismiss="toast"></button>
            </div>
        </div>
    </div>
    <div class="toast-container position-fixed top-0 end-0 p-3">
        <div id="customerToast" class="toast text-bg-success border-0">
            <div class="d-flex">
                <div class="toast-body">
                    Customer added successfully
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto"
                    data-bs-dismiss="toast"></button>
            </div>
        </div>
    </div>
    <?php unset($_SESSION['selected_customer_id']); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/Backend/src/assets/js/pos.js"></script>
    <script>
        let cart = [];

        function showCartToast(msg) {
            document.querySelector('#cartToast .toast-body').innerText = msg;
            new bootstrap.Toast(document.getElementById('cartToast')).show();
        }
        document.addEventListener('DOMContentLoaded', function() {
            const checkoutBtn = document.getElementById('checkoutBtn');

            function showCheckoutToast(msg) {
                document.getElementById('checkoutToastMessage').innerText = msg;
                new bootstrap.Toast(document.getElementById('checkoutToast')).show();
            }
document.querySelectorAll('.addToCartBtn').forEach(btn => {
    btn.addEventListener('click', () => {

        const id    = btn.dataset.id;
        const name  = btn.dataset.name;
        const price = parseFloat(btn.dataset.price);
        const stock = parseInt(btn.dataset.stock);

        let index = cart.findIndex(p => p.id == id);

        // 🟢 Product already in cart
        if (index !== -1) {

            // ❗ qty already equal to stock
            if (cart[index].qty >= cart[index].stock) {
                showCartToast(`Only ${cart[index].stock} items available in stock`);
                return; // 🔥 NO UI UPDATE
            }

            // 🟢 safe increase
            cart[index].qty++;
            updateCartUI();
            return;
        }

        // 🟢 First time add
        if (stock <= 0) {
            showCartToast("Out of stock");
            return;
        }

        cart.push({
            id,
            name,
            price,
            qty: 1,
            stock
        });

        updateCartUI();
    });
});




            function updateCartUI() {
                const tbody = document.getElementById('cartBody');
                tbody.innerHTML = '';
                let total = 0;
                let items = 0;
                cart.forEach((item, i) => {
                    total += item.price * item.qty;
                    // let items = cart.length;
                    tbody.innerHTML += `
                <tr>
                    <td>${item.name}</td>
                    <td class="text-center">
                        <div class="qty-box">
                            <button class="btn btn-sm btn-outline-secondary" onclick="decreaseQty(${i})">−</button>
                            <span>${item.qty}</span>
                            <button class="btn btn-sm btn-outline-secondary" onclick="increaseQty(${i})">+</button>
                        </div>
                    </td>
                    <td>₹${(item.price * item.qty).toFixed(2)}</td>
                    <td>
                        <button class="btn btn-sm btn-danger" onclick="removeItem(${i})">✕</button>
                    </td>
                </tr>`;
                });
                document.getElementById('totalItems').innerText = cart.length;
                document.getElementById('cartTotal').innerText = total.toFixed(2);
                checkoutBtn.disabled = cart.length === 0;

            }
            window.removeItem = function(index) {
                cart.splice(index, 1);
                updateCartUI();
            };
            checkoutBtn.addEventListener('click', () => {
                if (cart.length === 0) {
                    showCartToast("Cart is empty");
                    return;
                }
                fetch('/Backend/src/Pages/POS/checkout.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            transaction_id: "<?= $_SESSION['transaction_id']; ?>",
                            customer_id: document.getElementById('customerSelect').value || null,
                            cart: cart
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.status === 'success') {
                            showCheckoutToast(data.message);
                            cart = [];
                            updateCartUI();
                            setTimeout(() => location.reload(), 1200);
                        } else {
                            showCartToast(data.message);
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        showCartToast("Server error");
                    });
            });
            window.increaseQty = function(index) {
                let item = cart[index];
                if (item.qty >= item.stock) {
                    showCartToast(`Only ${item.stock} items available in stock`);
                    return;
                }
                item.qty++;
                updateCartUI();
            };
            window.decreaseQty = function(index) {
                if (cart[index].qty > 1) {
                    cart[index].qty--;
                } else {
                    cart.splice(index, 1);
                }
                updateCartUI();
            };
        });
    </script>
</body>

</html>