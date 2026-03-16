import { useEffect, useState } from "react";
import { HiOutlineShoppingCart } from "react-icons/hi";
import { FaRegHeart, FaTrashAlt } from "react-icons/fa";
import { NavLink } from "react-router-dom";
import { toast } from "react-toastify";
import "../assets/styles/plugins/navbar.css";

export const Navbar = () => {
  const [scrolled, setScrolled] = useState(false);
  const [openCart, setOpenCart] = useState(false);
  const [openWishlist, setOpenWishlist] = useState(false);
  const [cart, setCart] = useState([]);
  const [wishlist, setWishlist] = useState([]);

  const loadData = () => {
    setCart(JSON.parse(localStorage.getItem("cart")) || []);
    setWishlist(JSON.parse(localStorage.getItem("wishlist")) || []);
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

  const removeItem = (id, type) => {
    if (type === "cart") {
      const updated = cart.filter(item => item.id !== id);
      localStorage.setItem("cart", JSON.stringify(updated));
      setCart(updated);
    } else {
      const updated = wishlist.filter(item => item.id !== id);
      localStorage.setItem("wishlist", JSON.stringify(updated));
      setWishlist(updated);
    }
    toast.info("Item removed");
  };

  const calculateTotal = () => cart.reduce((total, item) => total + item.price * item.qty, 0);

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
              {wishlist.length > 0 && <span className="badge bg-danger position-absolute top-0 start-100 translate-middle rounded-pill" style={{fontSize: '10px'}}>{wishlist.length}</span>}
            </div>

            <div className="position-relative cursor-pointer me-4" onClick={() => setOpenCart(true)}>
              <HiOutlineShoppingCart className="fs-3" />
              {cart.length > 0 && <span className="badge bg-danger position-absolute top-0 start-100 translate-middle rounded-pill" style={{fontSize: '10px'}}>{cart.length}</span>}
            </div>
            <button className="btn login-btn border border-dark"><a href="http://localhost:3000/Backend/src/Pages/Auth/signin.php" className="text-decoration-none text-black">Login</a></button>
          </div>
        </div>
      </div>

      <div className={`cart-drawer ${openWishlist ? "open" : ""}`} style={{
        position: 'fixed', right: openWishlist ? '0' : '-400px', top: '0', width: '350px', 
        height: '100vh', background: '#fff', zIndex: '2000', transition: '0.4s ease'
      }}>
        <div className="p-3 border-bottom d-flex justify-content-between align-items-center bg-light">
          <h5 className="m-0">My Wishlist ({wishlist.length})</h5>
          <button className="btn btn-close" onClick={() => setOpenWishlist(false)}></button>
        </div>
        <div className="p-3 overflow-auto" style={{ height: 'calc(100vh - 100px)' }}>
          {wishlist.length === 0 ? <p className="text-center mt-5">Wishlist is empty</p> : 
            wishlist.map(item => (
              <div className="d-flex align-items-center mb-3 border-bottom pb-2" key={item.id}>
                <img src={item.image} width="50" height="50" className="rounded" alt="" />
                <div className="ms-3 flex-grow-1">
                  <h6 className="mb-0 small">{item.name}</h6>
                  <p className="mb-0 fw-bold">₹{item.price}</p>
                </div>
                <button className="btn text-danger btn-sm" onClick={() => removeItem(item.id, "wishlist")}><FaTrashAlt /></button>
              </div>
            ))
          }
        </div>
      </div>

      <div className={`cart-drawer ${openCart ? "open" : ""}`} style={{
        position: 'fixed', right: openCart ? '0' : '-400px', top: '0', width: '350px', 
        height: '100vh', background: '#fff', zIndex: '2000', transition: '0.4s ease'
      }}>
        <div className="p-3 border-bottom d-flex justify-content-between align-items-center bg-light">
          <h5 className="m-0">Shopping Cart</h5>
          <button className="btn btn-close" onClick={() => setOpenCart(false)}></button>
        </div>
        <div className="p-3 overflow-auto" style={{ height: 'calc(100vh - 200px)' }}>
          {cart.length === 0 ? <p className="text-center mt-5">Cart is empty</p> : 
            cart.map(item => (
              <div className="d-flex align-items-center mb-3 border-bottom pb-2" key={item.id}>
                <img src={item.image} width="50" height="50" className="rounded" alt="" />
                <div className="ms-3 flex-grow-1">
                  <h6 className="mb-0 small">{item.name}</h6>
                  <p className="mb-0">₹{item.price} x {item.qty}</p>
                </div>
                <button className="btn btn-sm" onClick={() => removeItem(item.id, "cart")}>✕</button>
              </div>
            ))
          }
        </div>
        <div className="p-3 border-top position-absolute bottom-0 w-100 bg-white">
          <div className="d-flex justify-content-between mb-3">
            <span>Total:</span>
            <strong className="text-danger">₹{calculateTotal()}</strong>
          </div>
          <button className="btn btn-danger w-100" style={{letterSpacing: 'initial'}}>PROCEED TO CHECKOUT</button>
        </div>
      </div>

      {(openCart || openWishlist) && (
        <div className="drawer-overlay" onClick={() => {setOpenCart(false); setOpenWishlist(false);}} 
        style={{ position: 'fixed', inset: 0, background: 'rgba(0,0,0,0.5)', zIndex: '1500' }}></div>
      )}
    </header>
  );
};

export default Navbar;