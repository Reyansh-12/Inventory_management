import logo from "../assets/images/brand-logo/logo.webp";
import { HiOutlineShoppingCart } from "react-icons/hi";
import { NavLink } from "react-router-dom";
import { FaRegHeart } from "react-icons/fa";
import "../assets/styles/plugins/navbar.css";




export const Navbar = () => {
  
  return (
    <header className="header-area sticky-header header-transparent ms-2 me-2">
      <div className="container">
        <div className="row align-items-center">
          <div className="col-5 col-lg-2 col-xl-1">
            <NavLink to="/">
              <img src={logo} width="95" height="68" alt="Logo" />
            </NavLink>
          </div>

          <div className="col-lg-5 col-xl-7 d-none d-lg-block">
            <ul className="main-nav">
              <li>
                <NavLink to="/" className="nav-link text-decoration-none ">
                  Home
                </NavLink>
              </li>

              <li>
                <NavLink to="/shop" className="nav-link">
                  Shop
                </NavLink>
              </li>

              <li>
                <NavLink to="/shop" className="nav-link">
                  Categories
                </NavLink>
              </li>

              <li>
                <NavLink to="/shop" className="nav-link">
                  Offer
                </NavLink>
              </li>

              <li>
                <NavLink to="/about" className="nav-link">
                  About Us
                </NavLink>
              </li>

              <li>
                <NavLink to="/contact" className="nav-link">
                  Contact
                </NavLink>
              </li>
            </ul>
          </div>

          <div className="col-7 col-lg-5 col-xl-4 d-flex justify-content-end align-items-center">
            <FaRegHeart className="fs-4 me-4" />
            <HiOutlineShoppingCart className="fs-3 me-4" />
            <button className="btn me-3 login-btn">Login</button>
          </div>
        </div>
      </div>
    </header>
  );
};

export default Navbar;