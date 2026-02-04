let cart = [];

    function updateCartUI() {
        const tbody = document.getElementById('cartBody');
        tbody.innerHTML = '';
        let total = 0;

        cart.forEach((item, i) => {
            total += item.price * item.qty;
            tbody.innerHTML += `
        <tr>
            <td>
                <span class="cart-product-name"
                      data-bs-toggle="tooltip"
                      title="${item.name}">
                    ${item.name}
                </span>
            </td>
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

        document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
            new bootstrap.Tooltip(el);
        });
    }

    function removeItem(i) {
        cart.splice(i, 1);
        updateCartUI();
    }

    function increaseQty(i) {
        if (cart[i].qty < cart[i].stock) {
            cart[i].qty++;
            updateCartUI();
        }
    }

    function decreaseQty(i) {
        if (cart[i].qty > 1) {
            cart[i].qty--;
        } else {
            cart.splice(i, 1);
        }
        updateCartUI();
    }
    document.querySelectorAll('.addToCartBtn').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.dataset.id,
                name = btn.dataset.name,
                price = parseFloat(btn.dataset.price),
                stock = parseInt(btn.dataset.stock);
            let index = cart.findIndex(p => p.id == id);
            if (index !== -1) {
                if (cart[index].qty >= cart[index].stock) {
                    alert(`Only ${cart[index].stock} items available`);
                    return;
                }
                cart[index].qty++;
                updateCartUI();
                return;
            }
            if (stock <= 0) {
                alert("Out of stock");
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
    document.getElementById('checkoutBtn').addEventListener('click', () => {
        const customerId = document.getElementById('customerSelect').value;

        if (cart.length === 0) {
            new bootstrap.Toast(
                document.getElementById('cartEmptyToast'), {
                    delay: 3000
                }
            ).show();
            return;
        }

        if (!customerId) {
            new bootstrap.Toast(
                document.getElementById('customerToast'), {
                    delay: 3000
                }
            ).show();
            return;
        }
        fetch('/Backend/src/Pages/POS/checkout.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    transaction_id: "<?= $_SESSION['transaction_id']; ?>",
                    customer_id: customerId,
                    cart: cart
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: data.message,
                        timer: 1500,
                        showConfirmButton: false
                    });
                    cart = [];
                    updateCartUI();
                    setTimeout(() => location.reload(), 1200);
                }
            })
            .catch(() => {
                Swal.fire({
                    icon: 'error',
                    title: 'Checkout failed'
                });
            });
    });

    document.getElementById('clearCartBtn').addEventListener('click', () => {
        Swal.fire({
            title: 'Are you sure?',
            text: "All products in the cart will be removed!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, clear cart!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {

                cart = [];
                updateCartUI();

                Swal.fire({
                    icon: 'success',
                    title: 'Cart Cleared',
                    text: 'All products have been removed from the cart.',
                    timer: 1500,
                    showConfirmButton: false,
                    timerProgressBar: true
                });
            }
        });
    });
   


document.addEventListener("DOMContentLoaded", function () {

    const categoryCards = document.querySelectorAll(".category-card");
    const productCards = document.querySelectorAll(".product-card");
    const noProduct = document.getElementById("noProductFound");

    if (!categoryCards.length || !productCards.length) {
        console.warn("Category or Product cards not found");
        return;
    }

    categoryCards.forEach(card => {
        card.addEventListener("click", function () {

            const selectedCategory = this.getAttribute("data-category");
            let found = false;

            productCards.forEach(product => {
                const wrapper = product.closest(".product-wrapper");
                const productCategory = product.getAttribute("data-category");

                if (productCategory === selectedCategory) {
                    wrapper.style.display = "";
                    found = true;
                } else {
                    wrapper.style.display = "none";
                }
            });

            noProduct.style.display = found ? "none" : "block";

            // active UI
            categoryCards.forEach(c => c.classList.remove("border-primary"));
            this.classList.add("border-primary");
        });
    });

});

    
    $('#customerName').on('input', function() {
        let value = $(this).val();
        value = value.replace(/[^a-zA-Z\s]/g, '');
        $(this).val(value);
    });
    $('#customerPhone').on('input', function() {
        let inputValue = $(this).val();
        let filteredValue = inputValue.replace(/[^0-9]/g, '');
        $(this).val(filteredValue);
    });

    $('#customerEmail').on('input', function() {
        let value = $(this).val();

        value = value.replace(/[^a-zA-Z0-9@.]/g, '');

        if (value.length === 1 && !/^[A-Za-z]$/.test(value)) {
            value = '';
        }

        $(this).val(value);
    });
    document.getElementById('customerPhone').addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');

        if (value.length === 1 && !/^[6-9]$/.test(value)) {
            value = '';
        }

        e.target.value = value.slice(0, 10);
    });
   
    
    
    // document.getElementById('customerForm').addEventListener('submit', function(e) {

    //     const phoneEl = document.getElementById('phoneError');
    //     const emailEl = document.getElementById('emailError');
    //     phoneEl.innerText = '';
    //     phoneEl.classList.add('d-none');
    //     emailEl.innerText = '';
    //     emailEl.classList.add('d-none');

    //     const formData = new FormData(this);
    //     formData.append('ajax', 'addCustomer');

    //     fetch("", {
    //             method: 'POST',
    //             body: formData
    //         })
    //         .then(res => res.json())
    //         .then(data => {
    //             if (data.status === 'error') {
    //                 if (data.errors.phone) {
    //                     phoneEl.innerText = data.errors.phone;
    //                     phoneEl.classList.remove('d-none');
    //                 }
    //                 if (data.errors.email) {
    //                     emailEl.innerText = data.errors.email;
    //                     emailEl.classList.remove('d-none');
    //                 }
    //                 if (data.errors.general) {
    //                     alert(data.errors.general);
    //                 }
    //             } else if (data.status === 'success') {
    //                 const toastEl = document.getElementById('customerSuccessToast');
    //                 const toast = new bootstrap.Toast(toastEl, {
    //                     delay: 3000
    //                 });
    //                 toast.show();

    //                 this.reset();
    //                 $('#addCustomerModal').modal('hide');

    //                 const select = document.getElementById('customerSelect');
    //                 const option = document.createElement('option');
    //                 option.value = data.customer_id;
    //                 option.text = data.customer_name;
    //                 select.prepend(option);
    //                 select.value = data.customer_id;
    //             }
    //         })
    //         .catch(err => console.error(err));
    // });

    