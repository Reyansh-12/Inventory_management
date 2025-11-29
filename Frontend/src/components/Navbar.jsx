import logo from "../assets/images/brand-logo/logo.webp";
import { FaUser } from "react-icons/fa";
import { FaSearch } from "react-icons/fa";
import { HiOutlineShoppingCart } from "react-icons/hi";
import Dropdown from 'react-bootstrap/Dropdown';
import {Link} from 'react-router-dom';

const Navbar = () => {

const handleLogout=()=>{
 window.location.href = "http://localhost:3000/Backend/src/Pages/Auth/signin.php";
}
  return(
    <>
      <header className="header-area sticky-header header-transparent">
        <div className="container">
          <div className="row align-items-center">
            <div className="col-5 col-lg-2 col-xl-1">
              <div className="header-logo">
                <Link to="/home"><img className="logo-main" src={logo} width="95" height="68" alt="Logo"/></Link>
              </div>
            </div>
            <div className="col-lg-7 col-xl-7 d-none d-lg-block">
              <div className="header-navigation ps-7">
                <ul className="main-nav justify-content-start">
                  <li className="has-submenu">
                    <Link to='/home' className="text-decoration-none">Home</Link>
                  </li>
                  <li>
                    <Link to='/about' className="text-decoration-none">About</Link>
                  </li>
                  <li className="has-submenu position-static">
                    <Link to='/shop' className="text-decoration-none">Shop</Link>
                  </li>
                  <li className="has-submenu">
                    <a href="blog.html" className="text-decoration-none">Brands</a>
                    <ul className="submenu-nav">
                      <li className="has-submenu">
                        <a href="#/">Blog Layout</a>
                        <ul className="submenu-nav">
                          <li>
                            <a href="blog.html">Blog Grid</a>
                          </li>
                          <li>
                            <a href="blog-left-sidebar.html">
                              Blog Left Sidebar
                            </a>
                          </li>
                          <li>
                            <a href="blog-right-sidebar.html">
                              Blog Right Sidebar
                            </a>
                          </li>
                        </ul>
                      </li>
                      <li>
                        <a href="blog-details.html">Blog Details</a>
                      </li>
                    </ul>
                  </li>
                  <li className="has-submenu">
                    <Link to='/category' className="text-decoration-none">Categories</Link>
                  </li>
                  <li>
                    <Link to='/contact' className="text-decoration-none">Contact</Link>
                  </li>
                </ul>
              </div>
            </div>
            <div className="col-7 col-lg-3 col-xl-4">
              <div className="header-action justify-content-end">
                <button
                  className="header-action-btn ms-0"
                  type="button"
                  data-bs-toggle="offcanvas"
                  data-bs-target="#AsideOffcanvasSearch"
                  aria-controls="AsideOffcanvasSearch"
                >
                  <FaSearch />
                </button>

                <button className="header-action-btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#AsideOffcanvasCart" aria-controls="AsideOffcanvasCart">
                  <span className="icon">
                    <HiOutlineShoppingCart />
                  </span>
                </button>
                <Dropdown>
      <Dropdown.Toggle  bsPrefix="" className="bg-transparent border-0" id="dropdown-basic">
        <FaUser />
      </Dropdown.Toggle>

      <Dropdown.Menu>
        <Dropdown.Item href="#/action-1"></Dropdown.Item>
        <Dropdown.Item href="#/action-2">Setting</Dropdown.Item>
        <Dropdown.Item onClick={handleLogout}>Logout</Dropdown.Item>
      </Dropdown.Menu>
    </Dropdown>

                <button
                  className="header-menu-btn"
                  type="button"
                  data-bs-toggle="offcanvas"
                  data-bs-target="#AsideOffcanvasMenu"
                  aria-controls="AsideOffcanvasMenu"
                >
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
