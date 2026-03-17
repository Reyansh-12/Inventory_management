import React, { useEffect, useState } from "react";
import { HiOutlineShoppingCart, HiOutlineUserCircle } from "react-icons/hi"; 
import { FaRegHeart, FaTrashAlt, FaSignOutAlt, FaPlus, FaMinus } from "react-icons/fa"; 
import { NavLink, useNavigate } from "react-router-dom";
import { toast } from "react-toastify";
import "../assets/styles/plugins/navbar.css";
import { ImCross } from "react-icons/im";

export const Navbar = () => {
  const navigate = useNavigate();
  const [scrolled, setScrolled] = useState(false);
  const [openCart, setOpenCart] = useState(false);
  const [openWishlist, setOpenWishlist] = useState(false);
  const [openUserMenu, setOpenUserMenu] = useState(false);
  const [cart, setCart] = useState([]);
  const [wishlist, setWishlist] = useState([]);
  const [user, setUser] = useState(null);

  const loadData = () => {
    setCart(JSON.parse(localStorage.getItem("cart")) || []);
    setWishlist(JSON.parse(localStorage.getItem("wishlist")) || []);
    const savedUser = localStorage.getItem("user");
    setUser(savedUser ? JSON.parse(savedUser) : null);
  };

  useEffect(() => {
    loadData();
    window.addEventListener("cartUpdated", loadData);
    window.addEventListener("wishlistUpdated", loadData); 
    const handleScroll = () => setScrolled(window.scrollY > 50);
    window.addEventListener("scroll", handleScroll);

    return () => {
      window.removeEventListener("cartUpdated", loadData);
      window.removeEventListener("wishlistUpdated", loadData);
      window.removeEventListener("scroll", handleScroll);
    };
  }, []);

  const handleLogout = () => {
    localStorage.removeItem("user");
    setUser(null);
    setOpenUserMenu(false);
    toast.success("Logged out successfully");
    window.location.reload();
  };

  const removeItem = (id, type) => {
    const key = type === "cart" ? "cart" : "wishlist";
    const data = type === "cart" ? cart : wishlist;
    const updated = data.filter(item => item.id !== id);
    localStorage.setItem(key, JSON.stringify(updated));
    loadData();
    toast.info("Item removed");
  };

  const updateQuantity = (id, action) => {
    const updatedCart = cart.map(item => {
      if (item.id === id) {
        let newQty = item.qty || 1;
        if (action === "inc") newQty += 1;
        if (action === "dec") newQty = Math.max(1, newQty - 1);
        return { ...item, qty: newQty };
      }
      return item;
    });
    setCart(updatedCart);
    localStorage.setItem("cart", JSON.stringify(updatedCart));
  };

  const calculateTotal = () => cart.reduce((total, item) => total + (item.price * (item.qty || 1)), 0);

  const handleCheckoutRedirection = () => {
    if (cart.length === 0) {
      toast.warning("Your cart is empty!");
      return;
    }
    localStorage.setItem("temp_checkout", JSON.stringify(cart));
    setOpenCart(false);
    navigate("/checkout");
  };

  return (
    <header className={`header-area ${scrolled ? "navbar-scrolled" : ""}`}>
      <div className="container">
        <div className="row align-items-center">
          <div className="col-lg-6 d-none d-lg-block">
            <ul className="main-nav d-flex list-unstyled m-0">
              <li className="me-4"><NavLink to="/" className="nav-link">Home</NavLink></li>
              <li className="me-4"><NavLink to="/shop" className="nav-link">Shop</NavLink></li>
              <li className="me-4"><NavLink to="/about" className="nav-link">About</NavLink></li>
              <li><NavLink to="/contact" className="nav-link">Contact</NavLink></li>
            </ul>
          </div>

          <div className="col-7 col-lg-6 d-flex justify-content-end align-items-center">
            <div className="position-relative cursor-pointer me-4" onClick={() => setOpenWishlist(true)}>
              <FaRegHeart className="fs-4" />
              {wishlist.length > 0 && <span className="badge bg-danger position-absolute top-0 start-100 translate-middle rounded-pill">{wishlist.length}</span>}
            </div>

            <div className="position-relative cursor-pointer me-4" onClick={() => setOpenCart(true)}>
              <HiOutlineShoppingCart className="fs-3" />
              {cart.length > 0 && <span className="badge bg-danger position-absolute top-0 start-100 translate-middle rounded-pill">{cart.length}</span>}
            </div>

            {user ? (
              <div className="position-relative">
                <div className="d-flex align-items-center cursor-pointer" onClick={() => setOpenUserMenu(!openUserMenu)}>
                  <HiOutlineUserCircle className="fs-2 text-dark" />
                  <span className="ms-1 d-none d-md-inline small fw-bold">{user.name || "My Account"}</span>
                </div>
                {openUserMenu && (
                  <ul className="dropdown-menu show dropdown-menu-end shadow border-0 mt-2 position-absolute" style={{ right: 0 }}>
                    <li className="px-3 py-2 border-bottom text-muted small">{user.email}</li>
                    <li><NavLink className="dropdown-item py-2" to="/profile" onClick={() => setOpenUserMenu(false)}>My Profile</NavLink></li>
                    <li><button className="dropdown-item py-2 text-danger d-flex align-items-center" onClick={handleLogout}><FaSignOutAlt className="me-2" /> Logout</button></li>
                  </ul>
                )}
              </div>
            ) : (
              <a href="http://localhost:3000/Backend/src/Pages/Auth/signin.php" className="btn login-btn border border-dark px-3 py-1 fw-bold small">Login</a>
            )}
          </div>
        </div>
      </div>

      <div className={`cart-drawer ${openWishlist ? "open" : ""}`} style={{
        position: 'fixed', right: openWishlist ? '0' : '-400px', top: '0', width: '350px', 
        height: '100vh', background: '#fff', zIndex: '2000', transition: '0.4s ease', boxShadow: '-5px 0 15px rgba(0,0,0,0.1)'
      }}>
        <div className="p-3 border-bottom d-flex justify-content-between align-items-center bg-light">
          <h5 className="m-0 fw-bold">Wishlist ({wishlist.length})</h5>
          <button className="btn-close" onClick={() => setOpenWishlist(false)}></button>
        </div>
        <div className="p-3 overflow-auto" style={{ height: 'calc(100vh - 60px)' }}>
          {wishlist.length === 0 ? <p className="text-center mt-5">Wishlist is empty</p> : 
            wishlist.map(item => (
              <div className="d-flex align-items-center mb-3 border-bottom pb-2" key={item.id}>
                <img src={item.image} width="50" height="50" className="rounded object-fit-cover" alt="" />
                <div className="ms-3 flex-grow-1">
                  <h6 className="mb-0 small fw-bold">{item.name}</h6>
                  <p className="mb-0 small">₹{item.price}</p>
                </div>
                <button className="btn text-danger btn-sm" onClick={() => removeItem(item.id, "wishlist")}><ImCross /></button>
              </div>
            ))
          }
        </div>
      </div>

      <div className={`cart-drawer ${openCart ? "open" : ""}`} style={{
        position: 'fixed', right: openCart ? '0' : '-400px', top: '0', width: '350px', 
        height: '100vh', background: '#fff', zIndex: '2000', transition: '0.4s ease', boxShadow: '-5px 0 15px rgba(0,0,0,0.1)'
      }}>
        <div className="p-3 border-bottom d-flex justify-content-between align-items-center bg-light">
          <h5 className="m-0 fw-bold">Cart ({cart.length})</h5>
          <button className="btn-close" onClick={() => setOpenCart(false)}></button>
        </div>

        <div className="p-3 overflow-auto" style={{ height: 'calc(100vh - 180px)' }}>
          {cart.length === 0 ? <p className="text-center mt-5">Cart is empty</p> : 
            cart.map(item => (
              <div className="d-flex align-items-center mb-4 border-bottom pb-3" key={item.id}>
                <img src={item.image} width="60" height="60" className="rounded shadow-sm" alt="" />
                <div className="ms-3 flex-grow-1">
                  <h6 className="mb-1 small fw-bold">{item.name}</h6>
                  <p className="mb-2 small text-muted fw-bold">₹{item.price}</p>
                  
                  <div className="d-flex align-items-center border rounded-pill" style={{ width: 'fit-content', background: '#f9f9f9' }}>
                    <button className="btn btn-sm px-2 border-0" onClick={() => updateQuantity(item.id, "dec")}><FaMinus style={{fontSize: '10px'}}/></button>
                    <span className="px-2 small fw-bold">{item.qty || 1}</span>
                    <button className="btn btn-sm px-2 border-0" onClick={() => updateQuantity(item.id, "inc")}><FaPlus style={{fontSize: '10px'}}/></button>
                  </div>
                </div>
                <div className="text-end">
                    <button className="btn btn-sm text-danger mb-2" onClick={() => removeItem(item.id, "cart")}>✕</button>
                    <p className="m-0 small fw-bold">₹{item.price * (item.qty || 1)}</p>
                </div>
              </div>
            ))
          }
        </div>

        {cart.length > 0 && (
          <div className="p-3 border-top position-absolute bottom-0 w-100 bg-white">
            <div className="d-flex justify-content-between mb-3 fw-bold fs-5">
              <span>Total:</span>
              <span className="text-danger">₹{calculateTotal()}</span>
            </div>
            <button 
                onClick={handleCheckoutRedirection} 
                className="btn btn-danger w-100 py-2 fw-bold rounded-pill shadow-sm"
                style={{letterSpacing: 'initial'}}
            >
                PROCEED TO CHECKOUT
            </button>
          </div>
        )}
      </div>

      {(openCart || openWishlist) && (
        <div className="drawer-overlay" onClick={() => {setOpenCart(false); setOpenWishlist(false);}} 
        style={{ position: 'fixed', inset: 0, background: 'rgba(0,0,0,0.5)', zIndex: '1500' }}></div>
      )}
    </header>
  );
};

export default Navbar;