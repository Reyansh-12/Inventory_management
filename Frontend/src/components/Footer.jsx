import { FaTwitter } from "react-icons/fa";
import { FaFacebookF } from "react-icons/fa";
import { FaPinterestP } from "react-icons/fa";
import { FaPaperPlane } from "react-icons/fa";
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

      <footer className="footer-area">
        <div className="footer-main">
          <div className="container">
            <div className="row">
              <div className="col-md-6 col-lg-4">
                <div className="widget-item">
                  <div className="widget-about">
                    <a className="widget-logo" href="index.html">
                      <img src={Logo} width="95" height="68" alt="Logo" />
                    </a>
                    <p className="desc">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been.</p>
                  </div>
                </div>
              </div>

              <div className="col-md-6 col-lg-5 mt-md-0 mt-9">
                <div className="widget-item">
                  <h4 className="widget-title">Information</h4>
                  <ul className="widget-nav">
                    <li><a href="blog.html" className="text-decoration-none">Blog</a></li>
                    <li><Link to='/about' className="text-decoration-none">About us</Link></li>
                    <li><Link to='/contact' className="text-decoration-none">Contact</Link></li>
                    <li><a href="faq.html" className="text-decoration-none">Privacy</a></li>
                    <li><a href="account-login.html" className="text-decoration-none">Login</a></li>
                    <li><a href="product.html" className="text-decoration-none">Shop</a></li>
                    <li><a href="my-account.html" className="text-decoration-none">My Account</a></li>
                    <li><a href="faq.html" className="text-decoration-none">FAQs</a></li>
                  </ul>
                </div>
              </div>

              <div className="col-md-6 col-lg-3 mt-lg-0 mt-6">
                <div className="widget-item">
                  <h4 className="widget-title">Social Info</h4>
                  <div className="widget-social">
                    <a href="https://twitter.com/" target="_blank" rel="noopener noreferrer"><FaTwitter className="fa fa-twitter"/></a>
                    <a href="https://www.facebook.com/" target="_blank" rel="noopener noreferrer"><FaFacebookF className="fa fa-facebook"/></a>
                    <a href="https://www.pinterest.com/" target="_blank" rel="noopener noreferrer"><FaPinterestP className="fa fa-pinterest-p"/></a>
                  </div>
                </div>
              </div>

            </div>
          </div>
        </div>

        <div className="footer-bottom">
          <div className="container pt-0 pb-0">
            <div className="footer-bottom-content">
              <p className="copyright">© 2025 Reyansh. Made with <i className="fa fa-heart"></i> by <a target="_blank" rel="noopener noreferrer" href="#" className="text-decoration-none">Reyansh.</a></p>
            </div>
          </div>
        </div>
      </footer>
    </>
  );
}     
export default Footer;