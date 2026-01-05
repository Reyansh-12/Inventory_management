import { FaTwitter } from "react-icons/fa";
import { FaFacebookF } from "react-icons/fa";
import { FaPinterestP } from "react-icons/fa";
import Logo from "../assets/images/brand-logo/logo.webp";
import {Link} from "react-router-dom";
const Footer = () => {
  return (
    <>
        {/* <section className="section-space pt-0">
          <div className="container">
            <div
              className="newsletter-content-wrap"
              data-bg-img="/assets/images/photos/bg1.webp"
            >
              <div className="newsletter-content">
                <div className="section-title mb-0">
                  <h2 className="title">Join with us</h2>
                  <p>
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit ut
                    aliquam.
                  </p>
                </div>
              </div>
              <div className="newsletter-form">
                <form>
                  <input
                    type="email"
                    className="form-control"
                    placeholder="enter your email"
                  />
                  <button className="btn-submit" type="submit">
                    
                    <FaPaperPlane className="fa fa-paper-plane"/>
                  </button>
                </form>
              </div>
            </div>
          </div>
        </section> */}

      <footer className="footer-area mt-5" style={{background: 'linear-gradient(180deg,rgb(254, 254, 255),rgb(78, 80, 78))'}}>
        <div className="footer-main pb-5">
          <div className="container">
            <div className="row">
              <div className="col-md-6 col-lg-3 col-sm-12">
                <div className="widget-item">
                  <h4 className="widget-title">Brand Info</h4>
                  <div className="widget-about">
                    <a className="widget-logo" href="index.html">
                      <img src={Logo} width="95" height="68" alt="Logo" />
                    </a>
                    <p className="desc text-black">Brancy offers premium cosmetic products made with safe, natural, and skin-friendly ingredients.</p>
                  </div>
                </div>
              </div>
              <div className="col-md-6 col-lg-3 col-sm-12">
                <div className="widget-item">
                  <h4 className="widget-title">Quick Links</h4>
                  <div className="widget-about">
                    <ul>
                      <li className="mb-3"><Link to='/' className="text-decoration-none text-black">Home</Link></li>
                      <li className="mb-3"><Link to='/about' className="text-decoration-none text-black">About</Link></li>
                      <li className="mb-3"><Link to='/shop' className="text-decoration-none text-black">Shop</Link></li>
                      <li><Link to='/contact' className="text-decoration-none text-black">Contct</Link></li>
                    </ul>
                  </div>
                </div>
              </div>

              <div className="col-md-6 col-lg-3 col-sm-12">
                <div className="widget-item">
                  <h4 className="widget-title">Customer Support</h4>
                  <ul>
                    <li className="mb-3"><Link to='/about' className="text-decoration-none text-black">About us</Link></li>
                    <li className="mb-3"><Link to='/contact' className="text-decoration-none text-black">Contact</Link></li>
                    <li className="mb-3"><Link to='/contact' className="text-decoration-none text-black">FAQs</Link></li>
                    <li><Link to='/contact' className="text-decoration-none text-black">Privacy & Policy</Link></li>
                  </ul>
                </div>
              </div>

              <div className="col-md-6 col-lg-3 col-sm-12">
                <div className="widget-item">
                  <h4 className="widget-title">Social Info</h4>
                  <div className="widget-social">
                    <a href="https://twitter.com/" target="_blank" rel="noopener noreferrer"><FaTwitter className="fa fa-twitter text-black"/></a>
                    <a href="https://www.facebook.com/" target="_blank" rel="noopener noreferrer"><FaFacebookF className="fa fa-facebook text-black"/></a>
                    <a href="https://www.pinterest.com/" target="_blank" rel="noopener noreferrer"><FaPinterestP className="fa fa-pinterest-p text-black"/></a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div className="footer-bottom">
          <div className="container-fluid">
            <div className="footer-bottom-content">
              <p className="copyright text-white">© 2025 Brancy. All rights reserved. Designed & Developed by Reyansh.</p>
            </div>
          </div>
        </div>
      </footer>
    </>
  );
}     
export default Footer;