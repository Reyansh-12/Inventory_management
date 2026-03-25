import React, { useEffect, useState } from "react";
import { HiOutlineShoppingCart, HiOutlineUserCircle } from "react-icons/hi"; 
import { FaRegHeart, FaTrashAlt, FaSignOutAlt, FaPlus, FaMinus } from "react-icons/fa"; 
import { NavLink, useNavigate } from "react-router-dom";
import { toast } from "react-toastify";
import "../assets/styles/plugins/navbar.css";
import { ImCross } from "react-icons/im";
import { FaHeart } from "react-icons/fa";
import logo from "../assets/images/logo-removebg-preview.png";

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

  const notifyUpdates = () => {
    window.dispatchEvent(new Event("cartUpdated"));
    window.dispatchEvent(new Event("wishlistUpdated"));
  };

  const removeItem = (id, type) => {
    const key = type === "cart" ? "cart" : "wishlist";
    const data = type === "cart" ? cart : wishlist;
    const updated = data.filter(item => item.id !== id);
    localStorage.setItem(key, JSON.stringify(updated));
    
    notifyUpdates(); 
    loadData();
    toast.info(`Item removed from ${type}`);
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
    notifyUpdates();
  };

  const moveToCart = (product) => {
    const existingCart = JSON.parse(localStorage.getItem("cart")) || [];
    const idx = existingCart.findIndex(item => item.id === product.id);

    if (idx > -1) {
      existingCart[idx].qty = (existingCart[idx].qty || 1) + 1;
    } else {
      existingCart.push({ ...product, qty: 1 });
    }

    localStorage.setItem("cart", JSON.stringify(existingCart));
    
    const updatedWishlist = wishlist.filter(item => item.id !== product.id);
    localStorage.setItem("wishlist", JSON.stringify(updatedWishlist));

    notifyUpdates(); 
    loadData();
    toast.success("Moved to cart!");
    
    setOpenWishlist(false);
    setTimeout(() => setOpenCart(true), 400);
  };

  const moveAllToCart = () => {
    const existingCart = JSON.parse(localStorage.getItem("cart")) || [];
    let updatedCart = [...existingCart];

    wishlist.forEach(wItem => {
      const idx = updatedCart.findIndex(cItem => cItem.id === wItem.id);
      if (idx > -1) {
        updatedCart[idx].qty = (updatedCart[idx].qty || 1) + 1;
      } else {
        updatedCart.push({ ...wItem, qty: 1 });
      }
    });

    localStorage.setItem("cart", JSON.stringify(updatedCart));
    localStorage.setItem("wishlist", JSON.stringify([])); 
    
    notifyUpdates(); 
    loadData();
    toast.success("All items moved to cart!");
    
    setOpenWishlist(false);
    setTimeout(() => setOpenCart(true), 400);
  };

  const saveForLater = (product) => {
    const existingWishlist = JSON.parse(localStorage.getItem("wishlist")) || [];
    if (!existingWishlist.find(item => item.id === product.id)) {
      existingWishlist.push(product);
      localStorage.setItem("wishlist", JSON.stringify(existingWishlist));
    }

    const updatedCart = cart.filter(item => item.id !== product.id);
    localStorage.setItem("cart", JSON.stringify(updatedCart));

    notifyUpdates(); 
    loadData();
    toast.info("Saved to wishlist!");
    
    setOpenCart(false);
    setTimeout(() => setOpenWishlist(true), 400);
  };

  const calculateTotal = () => cart.reduce((total, item) => total + (item.price * (item.qty || 1)), 0);

  const handleLogout = () => {
    localStorage.removeItem("user");
    setUser(null);
    setOpenUserMenu(false);
    notifyUpdates();
    toast.success("Logged out successfully");
    window.location.reload();
  };

  return (
    <header className={`header-area ${scrolled ? "navbar-scrolled" : ""}`}>
      <div className="container">
        <div className="row align-items-center pt-3 pb-3">
          <div className="col-lg-3">
          <img src={logo} alt="" style={{width: '140px'}}/>
          </div>
          <div className="col-lg-4 d-none d-lg-block">
            <ul className="main-nav d-flex list-unstyled m-0">
              <li className="me-4"><NavLink to="/" className="nav-link text-black fw-bold">Home</NavLink></li>
              <li className="me-4"><NavLink to="/shop" className="nav-link text-black fw-bold">Shop</NavLink></li>
              <li className="me-4"><NavLink to="/about" className="nav-link text-black fw-bold">About</NavLink></li>
              <li><NavLink to="/contact" className="nav-link text-black fw-bold">Contact</NavLink></li>
            </ul>
          </div>

          <div className="col-7 col-lg-5 d-flex justify-content-end align-items-center">
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
                  <span className="ms-1 d-none d-md-inline small fw-bold">{user.name}</span>
                </div>
                {openUserMenu && (
                  <ul className="dropdown-menu show dropdown-menu-end shadow border-0 mt-2 position-absolute" style={{ right: 0, zIndex: 3000 }}>
                    <li className="px-3 py-2 border-bottom text-muted small">{user.email}</li>
                    <li><NavLink className="dropdown-item py-2" to="/profile">My Profile</NavLink></li>
                    <li><button className="dropdown-item py-2 text-danger" onClick={handleLogout}><FaSignOutAlt className="me-2" /> Logout</button></li>
                  </ul>
                )}
              </div>
            ) : (
              <NavLink to="/login" className="btn login-btn border border-dark px-3 py-1 fw-bold small">Login</NavLink>
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
        <div className="p-3 overflow-auto" style={{ height: wishlist.length > 1 ? 'calc(100vh - 140px)' : 'calc(100vh - 60px)' }}>
          {wishlist.length === 0 ? <p className="text-center mt-5 text-muted">Your wishlist is empty</p> : 
            wishlist.map(item => (
              <div className="d-flex align-items-center mb-3 border-bottom pb-2" key={item.id}>
                <img src={item.image} width="50" height="50" className="rounded object-fit-cover shadow-sm" alt="" />
                <div className="ms-3 flex-grow-1">
                  <h6 className="mb-0 small fw-bold">{item.name}</h6>
                  <p className="mb-1 small text-muted">₹{item.price}</p>
                  <button onClick={() => moveToCart(item)} className="btn border-0 btn-sm p-0 text-primary fw-bold" style={{fontSize: '11px', letterSpacing: 'initial'}}>MOVE TO CART →</button>
                </div>
                <button className="btn border-0 text-danger btn-sm" onClick={() => removeItem(item.id, "wishlist")}><ImCross style={{fontSize: '10px'}}/></button>
              </div>
            ))
          }
        </div>
        {wishlist.length > 1 && (
          <div className="p-3 border-top position-absolute bottom-0 w-100 bg-white shadow">
            <button onClick={moveAllToCart} className="btn w-100 fw-bold rounded-pill text-white" style={{background: 'rgb(232, 90, 138)', letterSpacing: '0.5px'}}>
              MOVE ALL TO CART ({wishlist.length})
            </button>
          </div>
        )}
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
          {cart.length === 0 ? <p className="text-center mt-5 text-muted">Your cart is empty</p> : 
            cart.map(item => (
              <div className="d-flex align-items-center mb-4 border-bottom pb-3" key={item.id}>
                <img src={item.image} width="60" height="60" className="rounded shadow-sm col-lg-2" alt="" />
                <div className="ms-3 flex-grow-1 col-lg-5">
                  <h6 className="mb-1 small fw-bold cursor-pointer">{item.name}</h6>
                  <p className="mb-2 small text-muted fw-bold">₹{item.price}</p>
                  {/* <div className="d-flex align-items-center border rounded-pill" style={{ width: 'fit-content', background: '#f9f9f9' }}>
                    <button className="btn btn-sm px-2 border-0" onClick={() => updateQuantity(item.id, "dec")}><FaMinus style={{fontSize: '10px'}}/></button>
                    <span className="px-2 small fw-bold">{item.qty || 1}</span>
                    <button className="btn btn-sm px-2 border-0" onClick={() => updateQuantity(item.id, "inc")}><FaPlus style={{fontSize: '10px'}}/></button>
                  </div> */}
                </div>
                <div className="text-end col-lg-4">
                    <button className="btn btn-sm fs-5 border-0 text-danger mb-2 me-1" onClick={() => saveForLater(item)} title="Save for Later"><FaHeart /></button>
                    <button className="btn btn-sm border-0 fs-5 text-danger mb-2" onClick={() => removeItem(item.id, "cart")}>✕</button>
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
            <button onClick={() => {setOpenCart(false); navigate("/cart");}} className="btn w-100 fw-bold rounded-pill text-white shadow-sm" style={{background: 'rgb(232, 90, 138)', letterSpacing:'0.5px'}}>
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