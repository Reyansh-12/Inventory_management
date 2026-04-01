import React, { useEffect, useRef } from "react";
import { FaTwitter, FaFacebookF, FaPinterestP, FaInstagram, FaEnvelope, FaMapMarkerAlt, FaPhoneAlt } from "react-icons/fa";
import Logo from "../assets/images/brand-logo/logo.webp";
import { Link } from "react-router-dom";
import { gsap } from "gsap";
import { ScrollTrigger } from "gsap/ScrollTrigger";
import logo from "../assets/images/logo-removebg-preview.png";

gsap.registerPlugin(ScrollTrigger);

const Footer = () => {
  const brandColor = "rgba(227, 39, 95, 1)";
  const footerRef = useRef(null);

  useEffect(() => {
    const ctx = gsap.context(() => {
      gsap.from(".footer-column", {
        y: 50,
        opacity: 0,
        stagger: 0.2,
        duration: 1,
        ease: "power3.out",
        scrollTrigger: {
          trigger: footerRef.current,
          start: "top 85%", 
        },
      });

      gsap.to(".footer-logo", {
        scale: 1.05,
        duration: 2,
        repeat: -1,
        yoyo: true,
        ease: "sine.inOut"
      });
    }, footerRef);

    return () => ctx.revert();
  }, []);

  const handleMouseEnter = (e) => {
    gsap.to(e.currentTarget, {
      y: -5,
      color: brandColor,
      duration: 0.3,
      ease: "power2.out"
    });
  };

  const handleMouseLeave = (e, originalColor = "#666") => {
    gsap.to(e.currentTarget, {
      y: 0,
      color: originalColor,
      duration: 0.3,
      ease: "power2.in"
    });
  };

  return (
    <footer 
      ref={footerRef}
      className="footer-area mt-5" 
      style={{ 
        background: "linear-gradient(to bottom, #ffffff, #f9f9f9)", 
        borderTop: "1px solid #eee", 
        paddingTop: "80px",
        overflow: "hidden"
      }}
    >
      <div className="container">
        <div className="row g-5 pb-5">
          
          <div className="col-md-6 col-lg-4 footer-column">
            <Link to="/" className="footer-logo d-inline-block">
              <img src={logo} width="150" alt="Logo" />
            </Link>
            <p style={{ color: "#666", fontSize: "15px", lineHeight: "1.8" }}>
              Experience the fusion of nature and science. Brancy delivers premium skincare that honors your natural glow.
            </p>
            <div className="d-flex gap-3 mt-4">
              {[FaFacebookF, FaInstagram, FaTwitter, FaPinterestP].map((Icon, i) => (
                <div 
                  key={i}
                  onMouseEnter={(e) => gsap.to(e.currentTarget, { scale: 1.2, backgroundColor: brandColor, color: "#fff", duration: 0.3 })}
                  onMouseLeave={(e) => gsap.to(e.currentTarget, { scale: 1, backgroundColor: "transparent", color: "#444", duration: 0.3 })}
                  style={{ 
                    width: "35px", height: "35px", border: "1px solid #ddd", 
                    borderRadius: "50%", display: "flex", alignItems: "center", 
                    justifyContent: "center", cursor: "pointer", transition: "border-color 0.3s"
                  }}
                >
                  <Icon size={14} />
                </div>
              ))}
            </div>
          </div>

          <div className="col-6 col-md-3 col-lg-2 footer-column">
            <h6 className="mb-4 fw-bold" style={{ letterSpacing: "1px" }}>Company</h6>
            <ul className="list-unstyled">
              {['Home', 'Shop', 'About', 'Contact'].map((item) => (
                <li key={item} className="mb-3">
                  <Link 
                    to={`/${item.toLowerCase()}`} 
                    className="text-decoration-none d-inline-block" 
                    style={{ color: "#666", fontSize: "14px" }}
                    onMouseEnter={handleMouseEnter}
                    onMouseLeave={(e) => handleMouseLeave(e, "#666")}
                  >
                    {item}
                  </Link>
                </li>
              ))}
            </ul>
          </div>

          <div className="col-6 col-md-3 col-lg-2 footer-column">
            <h6 className="mb-4 fw-bold" style={{ letterSpacing: "1px" }}>Support</h6>
            <ul className="list-unstyled">
              {['FAQs', 'Privacy', 'Terms', 'Shipping'].map((item) => (
                <li key={item} className="mb-3">
                  <Link 
                    to="#" 
                    className="text-decoration-none d-inline-block" 
                    style={{ color: "#666", fontSize: "14px" }}
                    onMouseEnter={handleMouseEnter}
                    onMouseLeave={(e) => handleMouseLeave(e, "#666")}
                  >
                    {item}
                  </Link>
                </li>
              ))}
            </ul>
          </div>

          <div className="col-md-6 col-lg-4 footer-column">
            <h6 className="mb-4 fw-bold" style={{ letterSpacing: "1px" }}>Contact</h6>
            <div className="mb-3 d-flex align-items-center gap-3" style={{ color: "#666", fontSize: "14px" }}>
              <FaMapMarkerAlt style={{ color: brandColor }} /> 123 Beauty Lane, NY
            </div>
            <div className="mb-3 d-flex align-items-center gap-3" style={{ color: "#666", fontSize: "14px" }}>
              <FaPhoneAlt style={{ color: brandColor }} /> +1 234 567 890
            </div>
            <div className="d-flex align-items-center gap-3" style={{ color: "#666", fontSize: "14px" }}>
              <FaEnvelope style={{ color: brandColor }} /> hello@brancy.com
            </div>
          </div>

        </div>
      </div>

      <div style={{ background: "#f1f1f1", padding: "20px 0" }}>
        <div className="container">
          <div className="row">
            <div className="col-12 text-center">
              <p className="mb-0" style={{ fontSize: "13px", color: "#888" }}>
                © 2026 <span className="fw-bold">Brancy</span>. Crafted with ❤️ by <span style={{color: brandColor}}>Reyansh</span>
              </p>
            </div>
          </div>
        </div>
      </div>
    </footer>
  );
};

export default Footer;