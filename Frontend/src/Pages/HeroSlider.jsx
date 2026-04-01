import { useEffect, useState, useRef } from "react";
import { useNavigate } from "react-router-dom";
import gsap from "gsap";
import { ScrollTrigger } from "gsap/ScrollTrigger";
import { FaArrowRightLong } from "react-icons/fa6";
import { MdOutlineKeyboardArrowRight } from "react-icons/md";
import ProductItem from "@/Pages/Products/ProductItem.jsx";

import banner from "../assets/images/HeroBanner(1).png";
import banner1 from "../assets/images/banner4.png";
import banner2 from "../assets/images/banner3.png";

import "../assets/styles/plugins/HeroSlider.css";
import "../../src/assets/styles/plugins/ProductCards.css";

const HeroSlider = () => {
  const [products, setProducts] = useState([]);
  const [activeCategory, setActiveCategory] = useState("All");
  const navigate = useNavigate();
  
  const heroRef = useRef(null);
  const brandTickerRef = useRef(null);
  const underlineRef = useRef(null);
  const categoryRefs = useRef([]);

  gsap.registerPlugin(ScrollTrigger);

  useEffect(() => {
    fetch("http://localhost/Inventory_management/Backend/src/Pages/APIs/productListAPI.php")
      .then((res) => res.json())
      .then(setProducts)
      .catch((err) => console.error("API Error:", err));
  }, []);

  useEffect(() => {
    const ctx = gsap.context(() => {
      const tl = gsap.timeline();
      tl.from(".reveal-text", {
        y: 100,
        opacity: 0,
        stagger: 0.2,
        duration: 1.2,
        ease: "power4.out"
      })
      .from(".hero-btn-container", {
        scale: 0.9,
        opacity: 0,
        duration: 0.8,
        ease: "expo.out"
      }, "-=0.6");

      gsap.to(".hero-bg", {
        scale: 1.2,
        scrollTrigger: {
          trigger: ".heroSection",
          start: "top top",
          end: "bottom top",
          scrub: true
        }
      });

      gsap.to(".ticker-track", {
        xPercent: -50,
        repeat: -1,
        duration: 30,
        ease: "none"
      });
    }, heroRef);

    return () => ctx.revert();
  }, []);

  useEffect(() => {
    gsap.fromTo(".product-anim-wrap", 
      { opacity: 0, y: 30 }, 
      { opacity: 1, y: 0, stagger: 0.1, duration: 0.8, ease: "power3.out" }
    );
  }, [activeCategory, products]);

  const categories = [...new Set(products.map((p) => p.category))].slice(0, 6);
  const latestProducts = [...products].sort((a, b) => b.id - a.id).slice(0, 8);
  const filteredProducts = activeCategory === "All" 
    ? latestProducts 
    : latestProducts.filter((p) => p.category === activeCategory);

  const handleCategoryChange = (cat, index) => {
    setActiveCategory(cat);
    const el = categoryRefs.current[index];
    if (el) {
      gsap.to(underlineRef.current, {
        x: el.offsetLeft,
        width: el.offsetWidth,
        duration: 0.6,
        ease: "elastic.out(1, 0.6)"
      });
    }
  };

  return (
    <div ref={heroRef} className="main-wrapper-advance">

      <div className="position-relative heroSection overflow-hidden">
        <div className="container position-absolute hero-content-overlay" style={{ zIndex: 10 }}>
          <div className="overflow-hidden">
            <h1 className="hero-title reveal-text text-start">Cosmelina</h1>
          </div>
          <div className="overflow-hidden">
            <p className="subtitle reveal-text text-start">
              <span className="text-pink">Natural</span> Beauty & Beyond
            </p>
          </div>
          <div className="hero-btn-container mt-4">
            <button className="adv-btn" onClick={() => navigate('/shop')}>
              Explore Collection <FaArrowRightLong className="ms-2 arrow-icon" />
            </button>
          </div>
        </div>
        <img src={banner} alt="hero" className="hero-bg w-100 object-fit-cover" style={{ height: "100vh" }} />
        <div className="hero-vignette"></div>
      </div>

      <div className="brand-ticker-section py-5">
        <div className="ticker-track d-flex gap-5">
          {[...Array(2)].map((_, i) => (
            <div key={i} className="d-flex gap-5 align-items-center">
              {["Lakme", "Nykaa", "Derma", "Morphe", "Dove", "ILLIYOON", "NEXXUS", "Cetaphil"].map((brand) => (
                <span key={brand} className="brand-item">{brand}</span>
              ))}
            </div>
          ))}
        </div>
      </div>

      <section className="container my-5">
        <div className="row g-4">
          <div className="col-lg-8">
            <div className="offer-card-adv main-offer shadow-lg">
              <img src={banner1} alt="banner" className="img-fluid rounded-4" />
              <div className="offer-overlay rounded-4">
                <div className="glass-badge">SPECIAL OFFER</div>
                <h2 className="display-4 fw-bold text-white">UP TO 50% OFF</h2>
                <p className="text-white-50">On all premium skincare essentials</p>
                <button className="btn btn-light rounded-pill px-4 py-2 mt-2">Get Deal</button>
              </div>
            </div>
          </div>
          <div className="col-lg-4">
            <div className="offer-card-adv side-offer h-100 shadow-sm">
              <img src={banner2} alt="banner" className="img-fluid rounded-4 h-100 object-fit-cover" />
            </div>
          </div>
        </div>
      </section>

      <section className="container py-5">
        <div className="d-flex flex-column flex-md-row justify-content-between align-items-center mb-5">
          <div className="section-title-area">
            <h2 className="fw-bold display-6">Trending Collection</h2>
            <div className="title-underline"></div>
          </div>

          <div className="modern-tabs mt-4 mt-md-0">
            <ul className="d-flex list-unstyled m-0 position-relative">
              {["All", ...categories].map((cat, index) => (
                <li
                  key={index}
                  ref={(el) => (categoryRefs.current[index] = el)}
                  className={`tab-item ${activeCategory === cat ? "active" : ""}`}
                  onClick={() => handleCategoryChange(cat, index)}
                >
                  {cat}
                </li>
              ))}
              <div ref={underlineRef} className="tab-indicator"></div>
            </ul>
          </div>
        </div>

        <div className="row g-4">
          {filteredProducts.map((product) => (
            <div className="col-6 col-md-4 col-lg-3 product-anim-wrap" key={product.id}>
              <ProductItem product={product} />
            </div>
          ))}
        </div>

        <div className="text-center mt-5">
          <a href="/shop" className="view-all-btn">
            View All Products <MdOutlineKeyboardArrowRight className="fs-4" />
          </a>
        </div>
      </section>
    </div>
  );
};

export default HeroSlider;