import React, { useState, useEffect } from "react";
import "../../assets/styles/plugins/cart.css";
import { useNavigate } from "react-router-dom";
import { FaRegTrashAlt } from "react-icons/fa";
import { toast } from "react-toastify";
import Stepper from "../../components/Stepper";
import { FaChevronLeft } from "react-icons/fa";

const Cart = () => {
    const navigate = useNavigate();
    const [cartItems, setCartItems] = useState([]);

    const loadCartData = () => {
        const savedCart = JSON.parse(localStorage.getItem("cart")) || [];
        setCartItems(savedCart);
    };

    useEffect(() => {
        loadCartData();
        window.addEventListener("cartUpdated", loadCartData);
        return () => window.removeEventListener("cartUpdated", loadCartData);
    }, []);

    const handleQuantityChange = (id, action) => {
        const updatedCart = cartItems.map((item) => {
            if (item.id === id) {
                let newQty = item.qty || 1;
                if (action === "inc") newQty += 1;
                if (action === "dec") newQty = Math.max(1, newQty - 1);
                return { ...item, qty: newQty };
            }
            return item;
        });

        setCartItems(updatedCart);
        localStorage.setItem("cart", JSON.stringify(updatedCart));
        window.dispatchEvent(new Event("cartUpdated"));
    };

    const handleRemoveItem = (id) => {
        const updatedCart = cartItems.filter((item) => item.id !== id);
        setCartItems(updatedCart);
        localStorage.setItem("cart", JSON.stringify(updatedCart));
        window.dispatchEvent(new Event("cartUpdated"));
        toast.error("Item removed from cart");
    };

    const totalPrice = cartItems.reduce(
        (total, item) => total + item.price * (item.qty || 1),
        0
    );

    return (
        <div className="cart-container" style={{marginTop:'130px'}}>
            {/* <div className="mb-0">
                <Stepper currentStep={1} />
            </div> */}
            {/* <button className="btn btn-link text-dark p-0 mb-4 text-decoration-none" onClick={() => navigate(-1)}>
                <FaChevronLeft /> Back to Address
            </button> */}
            <header className="cart-header mb-3" style={{ marginTop: '80px' }}>
                <h1 className="cart-title me-5">Shopping Cart</h1>
                <span className="item-count border shadow-sm p-2 rounded-4">
                    <span className="text-success">{cartItems.length}</span> Items
                </span>
            </header>

            {cartItems.length === 0 ? (
                <div className="empty-state text-center p-5">
                    <h2>Your cart is looking a bit light.</h2>
                    <p>Go add some awesome products!</p>
                    <button className="btn btn-primary mt-3" onClick={() => navigate('/shop')}>Shop Now</button>
                </div>
            ) : (
                <div className="cart-grid d-flex ">
                    <div className="cart-items-list col-lg-8 shadow-lg p-5">
                        {cartItems.map((item) => (
                            <div key={item.id} className="cart-item border-bottom mb-3 pb-3">
                                <img src={item.image} alt={item.name} className="cart-item-image" style={{ width: '80px', height: '80px', objectFit: 'cover' }} />

                                <div className="cart-item-info flex-grow-1 ms-3">
                                    <h2 className="cart-item-name fs-5 mb-1">{item.name}</h2>
                                    <p className="cart-item-desc text-muted small">{item.description}</p>

                                    <div className="quantity-controls d-flex align-items-center">
                                        <button className="qty-btn" onClick={() => handleQuantityChange(item.id, "dec")}>−</button>
                                        <input type="text" className="qty-input text-dark text-center" style={{ width: '40px' }} value={item.qty || 1} readOnly />
                                        <button className="qty-btn" onClick={() => handleQuantityChange(item.id, "inc")}>+</button>
                                    </div>
                                </div>

                                <div className="cart-item-price-section text-end">
                                    <span className="item-total-price d-block fw-bold fs-5">
                                        ₹{(item.price * (item.qty || 1)).toFixed(2)}
                                    </span>
                                    <button className="remove-btn text-danger border-0 bg-transparent" onClick={() => handleRemoveItem(item.id)}>
                                        <FaRegTrashAlt />
                                    </button>
                                </div>
                            </div>
                        ))}
                    </div>

                    <aside className="cart-summary col-lg-5 p-4 card shadow-lg h-100 ms-lg-auto mt-4 mt-lg-0">
                        <h2 className="summary-title mb-4">Order Summary</h2>
                        <div className="summary-row d-flex justify-content-between mb-2">
                            <span>Subtotal</span>
                            <span>₹{totalPrice.toFixed(2)}</span>
                        </div>
                        <div className="summary-row d-flex justify-content-between mb-2">
                            <span>Shipping</span>
                            <span className="text-success">FREE</span>
                        </div>
                        <div className="summary-total d-flex justify-content-between mb-4 fw-bold fs-4">
                            <span>Total</span>
                            <span className="text-danger">₹{totalPrice.toFixed(2)}</span>
                        </div>
                        <button className="checkout-button btn py-2 w-100 text-white fw-bold rounded-pill"
                            style={{ background: 'rgb(232, 90, 138)', letterSpacing: 'initial' }}
                            onClick={() => navigate('/checkout')}>
                            Go to Checkout
                        </button>
                    </aside>
                </div>
            )}
        </div>
    );
};

export default Cart;