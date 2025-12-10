<?php
define("BASE_PATH", dirname(__DIR__, 3));
include BASE_PATH . "/src/Layouts/Links.php";
include BASE_PATH . "/src/controllers/dbConnection.php";

$sql = "SELECT `id`,`product_name`, `category`, `brand_name`, `price`, `quantity`, `image_path` FROM `product_list`";
$result = $con->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Dreams POS Admin Template</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <style>
.mySwiper {
    padding: 20px 0;
}

.product-card {
    background: rgba(255, 255, 255, 0.7);
    backdrop-filter: blur(8px);
    border-radius: 18px;
    padding: 18px;
    transition: 0.3s;
    border: 1px solid rgba(0,0,0,0.08);
    cursor: pointer;
}

.product-card:hover {
    transform: translateY(-6px);
    box-shadow: 0px 6px 18px rgba(0,0,0,0.12);
}

.product-card img {
    height: 130px;
    object-fit: contain;
    transition: 0.3s ease-in-out;
}

.product-card:hover img {
    transform: scale(1.07);
}

.product-card h4 {
    margin: 10px 0 6px;
    font-size: 18px;
    font-weight: 600;
    color: #333;
}

.product-card h6 {
    font-size: 16px;
    font-weight: bold;
    color: #ff5722;
}

.product-card button {
    margin-top: 10px;
    width: 100%;
    border-radius: 12px;
    font-weight: 600;
}

.swiper-button-next,
.swiper-button-prev {
    color: #000 !important;
    background: #fff;
    width: 42px;
    height: 42px;
    border-radius: 50%;
    box-shadow: 0px 3px 8px rgba(0,0,0,0.12);
}

.swiper-button-next:after,
.swiper-button-prev:after {
    font-size: 18px !important;
    font-weight: bold;
}

.swiper-button-next:hover,
.swiper-button-prev:hover {
    background: #000;
    color: #fff !important;
}
.category-card { 
    width: 10rem; 
}
.slider-container { 
    position: relative; 
}
.slider-btn { 
    background: red !important; 
    border-radius: 50%; 
    width: 10px; 
    height: 15px; 
    color: black !important; 
}
        .pos-column { max-height: calc(100vh - 0px); overflow-y: auto; }
        .product-card { background: #fff; border-radius: 10px; transition: 0.3s; height:100%; display:flex; flex-direction:column; justify-content:space-between; }
        .product-card:hover { transform: translateY(-5px); }
        .product-card img { max-height: 120px; object-fit: contain; }
        #cartTotals { font-size:1.05rem; font-weight:600; }
        .swiper { padding-bottom: 40px; }
        .swiper-slide { height: auto; }
</style>
    
</head>
<body>
    <div id="global-loader"><div class="whirly-loader"></div></div>

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
                                            <div class="row row-cols-1 row-cols-md-3 g-3">
                                                <?php
                                                if ($result && $result->num_rows > 0) {
                                                    while ($row = $result->fetch_assoc()) {
                                                        $id = (int)$row['id'];
                                                        $name = htmlspecialchars($row['product_name']);
                                                        $category = htmlspecialchars($row['category']);
                                                        $price = number_format((float)$row['price'], 2, '.', '');
                                                        $image = !empty($row['image_path']) ? $row['image_path']  : "/Inventory_managment/Backend/assets/images/favicon1.png";
                                                ?>
                                                <div class="col">
                                                    <div class="product-card p-3 shadow-sm rounded h-100">
                                                        <div class="text-center">
                                                            <img src="<?php echo $image; ?>" alt="<?php echo $name; ?>" class="img-fluid mb-2" />
                                                        </div>

                                                        <div>
                                                            <h6 class="text-muted mb-1 small"><?php echo $category; ?></h6>
                                                            <h5 class="mb-1"><?php echo $name; ?></h5>
                                                            <h6 class="text-primary mb-2">₹<?php echo $price; ?></h6>
                                                        </div>

                                                        <div class="d-grid mt-2">
                                                            <button
                                                                class="btn btn-primary addToCartBtn"
                                                                data-id="<?php echo $id; ?>"
                                                                data-name="<?php echo $name; ?>"
                                                                data-price="<?php echo $price; ?>"
                                                            >Add to Cart</button>
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
                                        <small class="text-muted">Transaction ID: #65565</small>
                                    </div>

                                    <div class="card-body">
                                        <!-- <div class="mb-3">
                                            <button class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#addCustomerModal">
                                                <i class="bibi-person-plus"></i> Add Customer
                                            </button>
                                        </div> -->

                                        <div class="mb-3 d-flex gap-2">
                                            <select class="form-select">
                                                <option>Walk-in Customer</option>
                                                <option>Chris Moris</option>
                                            </select>
                                            <select class="form-select">
                                                <option>Product</option>
                                                <option>Barcode</option>
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
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="customerForm">
                        <div class="mb-3">
                            <label for="customerName" class="form-label">Customer Name</label>
                            <input type="text" class="form-control" id="customerName">
                        </div>
                        <div class="mb-3">
                            <label for="customerEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="customerEmail">
                        </div>
                        <div class="mb-3">
                            <label for="customerPhone" class="form-label">Phone</label>
                            <input type="text" class="form-control" id="customerPhone">
                        </div>
                        <button type="submit" class="btn btn-primary">Add Customer</button>
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


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        var mySwiper = new Swiper(".mySwiper", {
            slidesPerView: 4,
            spaceBetween: 20,
            loop: false,
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
            slidesPerGroup: 1,
            breakpoints: {
                0: { slidesPerView: 1 },
                576: { slidesPerView: 2 },
                768: { slidesPerView: 3 },
                992: { slidesPerView: 4 }
            }
        });

    
        const cartBody = document.getElementById('cartBody');
        const cartTotalEl = document.getElementById('cartTotal');
        const totalItemsEl = document.getElementById('totalItems');
        const clearCartBtn = document.getElementById('clearCartBtn');
        const checkoutBtn = document.getElementById('checkoutBtn');

        
        let cart = {};
        function formatPrice(num) {
            return parseFloat(num).toFixed(2);
        }

        function renderCart() {
            cartBody.innerHTML = '';
            let total = 0;
            let itemCount = 0;
            for (const id in cart) {
                const item = cart[id];
                const row = document.createElement('tr');


                const productCell = document.createElement('td');
                productCell.innerHTML = `<div><strong>${escapeHtml(item.name)}</strong><div class="text-muted small">${escapeHtml(item.category || '')}</div></div>`;

    
                const qtyCell = document.createElement('td');
                qtyCell.classList.add('align-middle');
                qtyCell.innerHTML = `
                    <div class="input-group input-group-sm">
                        <button class="btn btn-outline-secondary btn-sm" data-action="decrease" data-id="${id}">-</button>
                        <input type="text" class="form-control text-center" value="${item.qty}" style="max-width:60px;" readonly>
                        <button class="btn btn-outline-secondary btn-sm" data-action="increase" data-id="${id}">+</button>
                    </div>
                `;

        
                const priceCell = document.createElement('td');
                priceCell.classList.add('align-middle');
                priceCell.innerText = '₹' + formatPrice(item.price * item.qty);

            
                const actionCell = document.createElement('td');
                actionCell.classList.add('align-middle');
                actionCell.innerHTML = `<button class="btn btn-sm btn-danger" data-action="remove" data-id="${id}"><i class="bi bi-trash"></i> Delete</button>`;

                row.appendChild(productCell);
                row.appendChild(qtyCell);
                row.appendChild(priceCell);
                row.appendChild(actionCell);

                cartBody.appendChild(row);

                total += item.price * item.qty;
                itemCount += item.qty;
            }

            cartTotalEl.innerText = formatPrice(total);
            totalItemsEl.innerText = itemCount;


            cartBody.querySelectorAll('button[data-action]').forEach(btn => {
                btn.addEventListener('click', function (e) {
                    const action = this.getAttribute('data-action');
                    const id = this.getAttribute('data-id');
                    if (action === 'increase') changeQty(id, +1);
                    if (action === 'decrease') changeQty(id, -1);
                    if (action === 'remove') removeItem(id);
                });
            });
        }


        function addToCart(id, name, price, category = '') {
            id = String(id);
            price = parseFloat(price);
            if (!cart[id]) {
                cart[id] = { id, name, price, qty: 1, category };
            } else {
                cart[id].qty += 1;
            }
            renderCart();
        }

    
        function changeQty(id, delta) {
            id = String(id);
            if (!cart[id]) return;
            cart[id].qty += delta;
            if (cart[id].qty <= 0) delete cart[id];
            renderCart();
        }

    
        function removeItem(id) {
            id = String(id);
            if (cart[id]) delete cart[id];
            renderCart();
        }


        clearCartBtn.addEventListener('click', function () {
            if (confirm('Clear all items from cart?')) {
                cart = {};
                renderCart();
            }
        });

        checkoutBtn.addEventListener('click', function () {
            if (Object.keys(cart).length === 0) {
                showCartEmptyToast();
                return;
            }

            console.log('Checkout payload:', cart);
            showCheckoutToast();
        });

        document.querySelectorAll('.addToCartBtn').forEach(btn => {
            btn.addEventListener('click', function () {
                const id = this.dataset.id;
                const name = this.dataset.name;
                const price = this.dataset.price;
    
                const card = this.closest('.product-card');
                const categoryEl = card ? card.querySelector('.text-muted') : null;
                const category = categoryEl ? categoryEl.innerText.trim() : '';
                addToCart(id, name, price, category);
            });
        });

    
        function escapeHtml(unsafe) {
            if (!unsafe) return '';
            return unsafe
                .replace(/&/g, "&amp;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;")
                .replace(/"/g, "&quot;")
                .replace(/'/g, "&#039;");
        }

        renderCart();
    });
    </script>
    <script>
function showCartEmptyToast() {
    var toastElement = document.getElementById('cartToast');
    var toast = new bootstrap.Toast(toastElement);
    toast.show();
}
</script>
<script>
function showCheckoutToast(message = "Checkout pressed — check console for payload.") {
    document.getElementById("checkoutToastMessage").textContent = message;
    var toastElement = document.getElementById('checkoutToast');
    var toast = new bootstrap.Toast(toastElement);
    toast.show();
}
</script>


</body>
</html>
