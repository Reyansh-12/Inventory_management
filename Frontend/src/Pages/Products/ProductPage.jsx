import React, { useState } from "react";
import "../../assets/styles/plugins/cartDrawer.css";
import product from "../../assets/images/1256-removebg-preview.png";

const ProductPage = () => {

  const [openCart, setOpenCart] = useState(false);

  const [cart, setCart] = useState([
    {
      id: 1,
      name: "Love Beauty & Planet Shampoo",
      price: 559,
      qty: 1,
      image: product
    }
  ]);

  const increase = (id) => {
    setCart(cart.map(item =>
      item.id === id ? { ...item, qty: item.qty + 1 } : item
    ));
  };

  const decrease = (id) => {
    setCart(cart.map(item =>
      item.id === id && item.qty > 1
        ? { ...item, qty: item.qty - 1 }
        : item
    ));
  };

  const remove = (id) => {
    setCart(cart.filter(item => item.id !== id));
  };

  const subtotal = cart.reduce((acc, item) => acc + item.price * item.qty, 0);

  return (
    <div className="container mt-5">

      <button
        className="btn btn-danger"
        onClick={() => setOpenCart(true)}
      >
        Add To Cart
      </button>

      <div className={`cart-drawer ${openCart ? "open" : ""}`}>

        <div className="drawer-header">
          <h5>Shopping Cart</h5>
          <button onClick={() => setOpenCart(false)}>✕</button>
        </div>

        {/* Cart Items */}
        <div className="drawer-body">

          {cart.map(item => (

            <div className="cart-item" key={item.id}>

              <img src={item.image} alt=""/>

              <div className="item-info">

                <h6>{item.name}</h6>

                <p>₹{item.price}</p>

                <div className="qty-box">

                  <button onClick={() => decrease(item.id)}>-</button>

                  <span>{item.qty}</span>

                  <button onClick={() => increase(item.id)}>+</button>

                </div>

                <button
                  className="remove-btn"
                  onClick={() => remove(item.id)}
                >
                  Remove
                </button>

              </div>

            </div>

          ))}

        </div>

        {/* Footer */}
        <div className="drawer-footer">

          <div className="subtotal">
            <span>Subtotal</span>
            <strong>₹{subtotal}</strong>
          </div>

          <button className="btn btn-danger w-100">
            Checkout
          </button>

        </div>

      </div>

      {/* Overlay */}
      {openCart && (
        <div
          className="drawer-overlay"
          onClick={() => setOpenCart(false)}
        ></div>
      )}

    </div>
  );
};

export default ProductPage;