import logo from "../assets/images/brand-logo/logo.webp";
import { FaRegUser } from "react-icons/fa";
import { TbLogout2 } from "react-icons/tb";
import { IoSettingsOutline } from "react-icons/io5";
import { HiOutlineShoppingCart } from "react-icons/hi";
import Dropdown from "react-bootstrap/Dropdown";
import { NavLink } from "react-router-dom";
import { FaRegHeart } from "react-icons/fa";


const Navbar = () => {
  const handleLogout = () => {
    window.location.href =
      "http://localhost:3000/Backend/src/Pages/Auth/signin.php";
  };
  return (
    <>
      <header className="header-area sticky-header header-transparent ms-2 me-2">
        <div className="container">
          <div className="row align-items-center">
            <div className="col-5 col-lg-2 col-xl-1">
              <div className="header-logo">
                <NavLink to="/">
                  <img
                    className="logo-main"
                    src={logo}
                    width="95"
                    height="68"
                    alt="Logo"
                  />
                </NavLink>
              </div>
            </div>
            <div className="col-lg-5 col-xl-7 d-none d-lg-block">
              <div className="header-navigation ps-7">
                <ul className="main-nav justify-content-start">
                  <li className="has-submenu">
                    <NavLink to="/" className="text-decoration-none nav-link">
                      <span className="pb-2">Home</span>
                    </NavLink>
                  </li>
                  <li className="has-submenu position-static">
                    <NavLink
                      to="/shop"
                      className="text-decoration-none nav-link"
                    >
                      <span className="pb-2">Shop</span>
                    </NavLink>
                  </li>
                  <li className="has-submenu position-static">
                    <NavLink
                      to="/shop"
                      className="text-decoration-none nav-link"
                    >
                      <span className="pb-2">Categories</span>
                    </NavLink>
                  </li>
                  <li className="has-submenu position-static">
                    <NavLink
                      to="/shop"
                      className="text-decoration-none nav-link"
                    >
                      <span className="pb-2">Offer</span>
                    </NavLink>
                  </li>
                  <li>
                    <NavLink
                      to="/about"
                      className="text-decoration-none nav-link"
                    >
                      <span className="pb-2">About Us</span>
                    </NavLink>
                  </li>
                  
                  <li>
                    <NavLink
                      to="/contact"
                      className="text-decoration-none nav-link"
                    >
                      <span className="pb-2">Contact</span>
                    </NavLink>
                  </li>
                </ul>
              </div>
            </div>

            <div className="col-7 col-lg-5 col-xl-4 justify-content-end d-flex align-items-center">
              <div className="header-action me-4">
                <FaRegHeart className="fs-4"/>
              </div>
              <div className="header-action me-4"> 
                <HiOutlineShoppingCart className="fs-3" />
              </div>
              <div className="header-action justify-content-end">
                <button className="btn me-3" style={{width: '100px', height:'40px', background: 'linear-gradient(90deg,rgba(227, 39, 95, 1) 50%, rgba(245, 137, 164, 1) 100%)' }}>Login</button>
                {/* <Dropdown>
                  <Dropdown.Toggle className="bg-transparent me-2 text-black border-0 no-caret" id="dropdown-basic">
                    <span className="icon">
                      <HiOutlineShoppingCart className="fs-4" />
                    </span>
                    <span className="cartCounter"></span>
                  </Dropdown.Toggle>

                  <Dropdown.Menu>
                    <Dropdown.Item href="#/action-1"></Dropdown.Item>
                  </Dropdown.Menu>
                </Dropdown> */}

                {/* <Dropdown>
                  <Dropdown.Toggle className="bg-transparent me-2 text-black border-0 no-caret" id="dropdown-basic">
                    <span className="icon">
                      <FaRegHeart className="fs-5" />
                    </span>
                    <span className="cartCounter"></span>
                  </Dropdown.Toggle>

                  <Dropdown.Menu>
                    <Dropdown.Item href="#/action-1"></Dropdown.Item>
                  </Dropdown.Menu>
                </Dropdown> */}

                {/* <Dropdown>
                  <Dropdown.Toggle className="bg-transparent text-black border-0 no-caret" id="dropdown-basic">
                    <FaRegUser className="fs-5" />
                  </Dropdown.Toggle>

                  <Dropdown.Menu>
                    <Dropdown.Item href="#/action-1"></Dropdown.Item>
                    <Dropdown.Item href="#/action-2">
                      <IoSettingsOutline className="me-2" />
                      Setting
                    </Dropdown.Item>
                    <Dropdown.Item onClick={handleLogout}>
                      <TbLogout2 className="me-2" />
                      Logout
                    </Dropdown.Item>
                  </Dropdown.Menu>
                </Dropdown> */}

                <button className="header-menu-btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#AsideOffcanvasMenu" aria-controls="AsideOffcanvasMenu">
                  <span></span>
                  <span></span>
                  <span></span>
                </button>
              </div>
            </div>
          </div>
        </div>
      </header>
    </>
  );
};
export default Navbar;
