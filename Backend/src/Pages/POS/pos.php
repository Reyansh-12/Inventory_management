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

// Fetch products
$sql = "SELECT `id`,`product_name`, `category`, `brand_name`, `price`, `quantity`, `image_path` FROM `product_list`";
$result = $con->query($sql);

// Add customer
if (isset($_POST['addCustomerSubmitButton'])) {
    $customerId = 'CUST' . time();
    $name = $_POST['customername'];
    $phone = $_POST['customerphone'];
    $email = $_POST['customeremail'];
    $address = $_POST['customeraddress'];

    $sql = "INSERT INTO customers (customer_id, name, phone, email, address)
            VALUES ('$customerId', '$name', '$phone', '$email', '$address')";

    if (mysqli_query($con, $sql)) {
        $_SESSION['customer_added'] = true;
        $_SESSION['selected_customer_id'] = mysqli_insert_id($con);
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
}

// Fetch categories
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .cart-product-name { max-width: 140px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; display: inline-block; vertical-align: middle; }
        #posCartTable td { vertical-align: middle; white-space: nowrap; }
        .qty-box { display: flex; align-items: center; justify-content: center; gap: 6px; }
        .category-track { display: flex; transition: transform 0.5s ease; }
        .category-slide { flex: 0 0 33.3333%; padding: 8px; }
        @media (max-width: 768px) { .category-slide { flex: 0 0 50%; } }
        @media (max-width: 576px) { .category-slide { flex: 0 0 100%; } }
    </style>
</head>
<body>
<div id="global-loader"><div class="whirly-loader"></div></div>

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
    <!-- LEFT: Categories & Products -->
    <div class="col-lg-8" style="max-height: 80vh; overflow-y: auto; padding-right:15px;">
        <div id="categorySlider" class="slider-container mb-3 position-relative">
            <button id="prevBtn" class="carousel-control-prev slider-btn" type="button">
                <span class="carousel-control-prev-icon"></span>
            </button>
            <button id="nextBtn" class="carousel-control-next slider-btn" type="button">
                <span class="carousel-control-next-icon"></span>
            </button>

            <div class="overflow-hidden">
                <div class="category-track">
                    <?php foreach ($categories as $cat) { ?>
                        <div class="category-slide">
                            <div class="card category-card text-center" data-category="<?= strtolower(trim($cat['category'])) ?>">
                                <img src="<?= !empty($cat['image_path']) ? htmlspecialchars($cat['image_path']) : '/Backend/assets/images/product/default-category.png'; ?>" class="card-img-top">
                                <div class="card-body"><?= htmlspecialchars($cat['category']) ?></div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>

        <!-- Products -->
        <div class="row row-cols-1 row-cols-md-3">
            <?php if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $id = (int)$row['id'];
                    $name = htmlspecialchars($row['product_name']);
                    $category = strtolower(trim($row['category']));
                    $price = number_format((float)$row['price'], 2);
                    $image = !empty($row['image_path']) ? $row['image_path'] : "/Inventory_managment/Backend/assets/images/favicon1.png";
            ?>
                <div class="col-12 col-md-4 col-lg-4">
                    <div class="product-card p-3 shadow-sm rounded h-100" data-category="<?= $category ?>">
                        <div class="text-center">
                            <img src="<?= $image ?>" class="img-fluid mb-2">
                        </div>
                        <h6 class="text-muted small"><?= $row['category'] ?></h6>
                        <h5 class="text-truncate"><?= $name ?></h5>
                        <h6 class="text-primary">₹<?= $price ?></h6>
                        <div class="d-grid">
                            <button class="btn btn-primary addToCartBtn"
                                data-id="<?= $id ?>" data-name="<?= $name ?>" data-price="<?= (float)$price ?>" data-stock="<?= (int)$row['quantity'] ?>">
                                Add to Cart
                            </button>
                        </div>
                    </div>
                </div>
            <?php }} ?>
        </div>

        <div id="noProductFound" class="text-center w-100 mt-4" style="display:none;">
            <img src="/Backend/src/assets/images/product_not_found2.png" class="img-fluid" style="max-width:300px;">
            <h6 class="text-muted mt-2">No products found in this category</h6>
        </div>
    </div>

    <!-- RIGHT: Order List (Sticky) -->
    <div class="col-lg-4">
        <div class="card position-sticky" style="top:20px;">
            <div class="card-header">
                <h5>Order List</h5>
                <small class="text-muted">Transaction ID: #<?= $_SESSION['transaction_id']; ?></small>
            </div>
            <div class="card-body">
                <div class="mb-3 d-flex gap-2">
                    <select class="form-select" id="customerSelect">
                        <option disabled <?= empty($_SESSION['selected_customer_id']) ? 'selected' : '' ?>>Walk-in Customer</option>
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

                <div class="d-grid mb-2">
                    <button class="btn btn-success" id="checkoutBtn"><i class="bi bi-cart-check"></i> Checkout</button>
                </div>
            </div>
            <div class="card-footer text-end">
                <button class="btn btn-danger btn-sm" id="clearCartBtn">Clear All</button>
            </div>
        </div>
    </div>
</div>

    </div>
</div>

<!-- Add Customer Modal -->
<div class="modal fade" id="addCustomerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Customer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form method="POST" id="customerForm" data-parsley-validate>
                    <div class="mb-3">
                        <label>Customer Name <span class="text-danger">*</span></label>
                        <input type="text" name="customername" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Phone <span class="text-danger">*</span></label>
                        <input type="text" name="customerphone" class="form-control" maxlength="10" required>
                    </div>
                    <div class="mb-3">
                        <label>Email <span class="text-danger">*</span></label>
                        <input type="email" name="customeremail" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Address</label>
                        <input type="text" name="customeraddress" class="form-control">
                    </div>
                    <button type="submit" name="addCustomerSubmitButton" class="btn btn-primary">Add Customer</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- CART JS -->
<script>
let cart = [];
function updateCartUI() {
    const tbody = document.getElementById('cartBody');
    tbody.innerHTML = '';
    let total = 0;
    cart.forEach((item, i) => {
        total += item.price * item.qty;
        tbody.innerHTML += `<tr>
            <td>${item.name}</td>
            <td class="text-center">
                <div class="qty-box">
                    <button class="btn btn-sm btn-outline-secondary" onclick="decreaseQty(${i})">−</button>
                    <span>${item.qty}</span>
                    <button class="btn btn-sm btn-outline-secondary" onclick="increaseQty(${i})">+</button>
                </div>
            </td>
            <td>₹${(item.price * item.qty).toFixed(2)}</td>
            <td><button class="btn btn-sm btn-danger" onclick="removeItem(${i})">✕</button></td>
        </tr>`;
    });
    document.getElementById('totalItems').innerText = cart.length;
    document.getElementById('cartTotal').innerText = total.toFixed(2);
    document.getElementById('checkoutBtn').disabled = cart.length === 0;
}
function removeItem(i){ cart.splice(i,1); updateCartUI(); }
function increaseQty(i){ if(cart[i].qty<cart[i].stock){ cart[i].qty++; updateCartUI(); } }
function decreaseQty(i){ if(cart[i].qty>1){ cart[i].qty--; } else{ cart.splice(i,1); } updateCartUI(); }
document.querySelectorAll('.addToCartBtn').forEach(btn=>{
    btn.addEventListener('click',()=>{
        const id=btn.dataset.id,name=btn.dataset.name,price=parseFloat(btn.dataset.price),stock=parseInt(btn.dataset.stock);
        let index=cart.findIndex(p=>p.id==id);
        if(index!==-1){ if(cart[index].qty>=cart[index].stock){ alert(`Only ${cart[index].stock} items available`); return; } cart[index].qty++; updateCartUI(); return; }
        if(stock<=0){ alert("Out of stock"); return; }
        cart.push({id,name,price,qty:1,stock}); updateCartUI();
    });
});
document.getElementById('checkoutBtn').addEventListener('click',()=>{
    if(cart.length===0){ alert("Cart is empty"); return; }
    fetch('/Backend/src/Pages/POS/checkout.php',{
        method:'POST',
        headers:{'Content-Type':'application/json'},
        body:JSON.stringify({
            transaction_id:"<?= $_SESSION['transaction_id']; ?>",
            customer_id: document.getElementById('customerSelect').value || null,
            cart:cart
        })
    }).then(res=>res.json()).then(data=>{
        if(data.status==='success'){ alert(data.message); cart=[]; updateCartUI(); setTimeout(()=>location.reload(),1200); }
        else{ alert(data.message); }
    }).catch(()=>alert("Server error"));
});
document.getElementById('clearCartBtn').addEventListener('click',()=>{ cart=[]; updateCartUI(); alert("Cart cleared"); });
</script>

<!-- CATEGORY SLIDER & FILTER JS -->
<script>
document.addEventListener("DOMContentLoaded", function () {
    const track = document.querySelector(".category-track");
    const slides = document.querySelectorAll(".category-slide");
    const products = document.querySelectorAll(".product-card");
    const noProduct = document.getElementById("noProductFound");
    const nextBtn = document.getElementById("nextBtn");
    const prevBtn = document.getElementById("prevBtn");
    const visibleCards = 3;
    let currentIndex = 0;

    function updateSlider() { const slideWidth = slides[0].offsetWidth; track.style.transform = `translateX(-${currentIndex * slideWidth}px)`; }

    nextBtn.addEventListener("click",()=>{ if(currentIndex<slides.length-visibleCards){ currentIndex++; updateSlider(); } });
    prevBtn.addEventListener("click",()=>{ if(currentIndex>0){ currentIndex--; updateSlider(); } });

    document.querySelectorAll(".category-card").forEach(card=>{
        card.addEventListener("click",function(){
            const selectedCategory=this.dataset.category.toLowerCase(); let found=false;
            products.forEach(product=>{
                const wrapper=product.closest(".col-12");
                if(product.dataset.category===selectedCategory){ wrapper.style.display=""; found=true; }
                else{ wrapper.style.display="none"; }
            });
            noProduct.style.display=found?"none":"block";
        });
    });

    window.addEventListener("resize", updateSlider);
});
</script>

<?php unset($_SESSION['selected_customer_id']); ?>
</body>
</html>
