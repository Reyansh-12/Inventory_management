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

function showCartEmptyToast() {
var toastElement = document.getElementById('cartToast');
var toast = new bootstrap.Toast(toastElement);
toast.show();
}

function showCheckoutToast(message = "Checkout pressed — check console for payload.") {
document.getElementById("checkoutToastMessage").textContent = message;
var toastElement = document.getElementById('checkoutToast');
var toast = new bootstrap.Toast(toastElement);
toast.show();
}

document.getElementById("checkoutBtn").addEventListener("click", function () {
    if (cart.length === 0) {
        let cartToast = new bootstrap.Toast(document.getElementById("cartToast"));
        cartToast.show();
        return;
    }

    fetch("Backend/src/Pages/APIs/checkoutAPI.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ cart: cart })
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === "success") {

            // Clear cart UI
            cart = [];
            updateCartUI();

            // Show success toast
            document.getElementById("checkoutToastMessage").innerText = "Checkout successful!";
            let toast = new bootstrap.Toast(document.getElementById("checkoutToast"));
            toast.show();

            // Reload page to refresh quantities
            setTimeout(() => location.reload(), 1000);
        }
    })
    .catch(err => console.error("Checkout Error:", err));
});
$('#customerPhone').on('input', function() {
    let inputValue = $(this).val();
    let filteredValue = inputValue.replace(/[^0-9]/g, '');
    $(this).val(filteredValue);
});