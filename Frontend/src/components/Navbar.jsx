import { useEffect, useState } from "react";
import { HiOutlineShoppingCart } from "react-icons/hi";
import { NavLink } from "react-router-dom";
import { FaRegHeart } from "react-icons/fa";
import "../assets/styles/plugins/navbar.css";

export const Navbar = () => {

  const [scrolled, setScrolled] = useState(false);
  useEffect(() => {
    const handleScroll = () => {
      setScrolled(window.scrollY > 50);
    };
    window.addEventListener("scroll", handleScroll);
    return () => window.removeEventListener("scroll", handleScroll);
  }, []);
  return (
    <header className={`header-area ${scrolled ? "navbar-scrolled" : ""}`}>

      <div className="container">
        <div className="row align-items-center">

          <div className="col-lg-6 d-none d-lg-block">
            <ul className="main-nav">
              <li>
                <NavLink to="/" className="nav-link hero-subtitle nav-hover">
                  <span className="nav-flair"></span>
                  <span className="nav-label">Home</span>
                </NavLink>
              </li>

              <li>
                <NavLink to="/shop" className="nav-link hero-subtitle nav-hover">
                  <span className="nav-flair"></span>
                  <span className="nav-label">Shop</span>
                </NavLink>
              </li>

              <li>
                <NavLink to="/about" className="nav-link hero-subtitle nav-hover">
                  <span className="nav-flair"></span>
                  <span className="nav-label">About</span>
                </NavLink>
              </li>

              <li>
                <NavLink to="/contact" className="nav-link hero-subtitle nav-hover">
                  <span className="nav-flair"></span>
                  <span className="nav-label">Contact</span>
                </NavLink>
              </li>
            </ul>
          </div>

          {/* <div className="navbar-logo-slot" id="navbarLogo"></div> */}

          <div className="col-7 col-lg-6 d-flex justify-content-end align-items-center">
            <FaRegHeart className="fs-4 me-4" />
            <HiOutlineShoppingCart className="fs-3 me-4" />
            <button className="btn login-btn">Login</button>
          </div>

        </div>
      </div>

    </header>
  );
};

export default Navbar;