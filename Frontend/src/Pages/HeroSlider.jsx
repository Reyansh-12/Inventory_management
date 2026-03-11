import { useEffect, useState, useRef } from "react";
import { useNavigate } from "react-router-dom";

import "../assets/styles/plugins/HeroSlider.css";
import "../../src/assets/styles/plugins/ProductCards.css";

import banner from "../assets/images/HeroBanner(1).png";
import textLogo from "../assets/images/textLogo-removebg-preview.png";
import second from "../assets/images/secondSection.png";
import image2 from "../assets/images/18448-removebg-preview.png";
import banner1 from "../assets/images/banner4.png";
import banner2 from "../assets/images/banner3.png";
import { FaArrowRightLong } from "react-icons/fa6";
import ProductItem from "@/Pages/Products/ProductItem.jsx";
import gsap from "gsap";
import { ScrollTrigger } from "gsap/ScrollTrigger";
import { MdOutlineKeyboardArrowRight } from "react-icons/md";

const HeroSlider = () => {
  const [products, setProducts] = useState([]);
  const [activeCategory, setActiveCategory] = useState("All");
  const navigate = useNavigate();
  const scrollRef = useRef(null);
  const cardRefs = useRef([]);
  const categoryRefs = useRef([]);
  const underlineRef = useRef(null);
  gsap.registerPlugin(ScrollTrigger);
  const logoRef = useRef(null);

  useEffect(() => {
    fetch(
      "http://localhost/Inventory_management/Backend/src/Pages/APIs/productListAPI.php",
    )
      .then((res) => res.json())
      .then(setProducts)
      .catch((err) => console.error("API Error:", err));
  }, []);

  const normalize = (value) => value?.toLowerCase().replace(/\s+/g, "");

  const categories = [...new Set(products.map((p) => p.category))].slice(0, 6);

  const scrollAmount = 240;

  const handleNext = () => {
    scrollRef.current?.scrollBy({
      left: scrollAmount,
      behavior: "smooth",
    });
  };

  const handlePrev = () => {
    scrollRef.current?.scrollBy({
      left: -scrollAmount,
      behavior: "smooth",
    });
  };

  const handleCategoryChange = (category, index) => {
    setActiveCategory(category);

    const el = categoryRefs.current[index];

    gsap.to(underlineRef.current, {
      x: el.offsetLeft,
      width: el.offsetWidth,
      duration: 0.4,
      ease: "power3.out",
    });
  };
  
const buttonRef = useRef(null);
  useEffect(() => {
    const buttons = document.querySelectorAll(".button--stroke");
  
    buttons.forEach((button) => {
      const flair = button.querySelector(".button__flair");
  
      const xSet = gsap.quickSetter(flair, "xPercent");
      const ySet = gsap.quickSetter(flair, "yPercent");
  
      const getXY = (e) => {
        const { left, top, width, height } = button.getBoundingClientRect();
  
        const x = gsap.utils.clamp(
          0,
          100,
          gsap.utils.mapRange(0, width, 0, 100, e.clientX - left)
        );
  
        const y = gsap.utils.clamp(
          0,
          100,
          gsap.utils.mapRange(0, height, 0, 100, e.clientY - top)
        );
  
        return { x, y };
      };
  
      const onEnter = (e) => {
        const { x, y } = getXY(e);
        xSet(x);
        ySet(y);
  
        gsap.to(flair, {
          scale: 1,
          duration: 0.4,
          ease: "power2.out",
        });
      };
  
      const onLeave = (e) => {
        const { x, y } = getXY(e);
  
        gsap.killTweensOf(flair);
  
        gsap.to(flair, {
          scale: 0,
          duration: 0.3,
          ease: "power2.out",
        });
      };
  
      const onMove = (e) => {
        const { x, y } = getXY(e);
  
        gsap.to(flair, {
          xPercent: x,
          yPercent: y,
          duration: 0.4,
          ease: "power2",
        });
      };
  
      button.addEventListener("mouseenter", onEnter);
      button.addEventListener("mouseleave", onLeave);
      button.addEventListener("mousemove", onMove);
    });
  }, []);
  useEffect(() => {
    cardRefs.current.forEach((card) => {
      if (!card) return;

      const glare = card.querySelector(".card-glare");
      let bounds;
      let lastShadow = { x: 0, y: 0, blur: 20 };

      const move = (e) => {
        const mouseX = e.clientX;
        const mouseY = e.clientY;

        const leftX = mouseX - bounds.left;
        const topY = mouseY - bounds.top;

        const center = {
          x: leftX - bounds.width / 2,
          y: topY - bounds.height / 2,
        };

        const distance = Math.sqrt(center.x ** 2 + center.y ** 2);

        const rotX = center.y / 40;
        const rotY = -center.x / 40;

        const shadowX = -rotY * 5;
        const shadowY = rotX * 5;
        const shadowBlur = 20 + distance / 120;

        lastShadow = { x: shadowX, y: shadowY, blur: shadowBlur };

        gsap.to(card, {
          rotationX: rotX,
          rotationY: rotY,
          scale: 1.08,
          transformPerspective: 800,
          boxShadow: `${shadowX}px ${shadowY}px ${shadowBlur}px rgba(232,90,138,0.35)`,
          ease: "power2.out",
          duration: 0.3,
        });

        gsap.to(glare, {
          autoAlpha: 1,
          backgroundImage: `radial-gradient(circle at ${center.x * 2 + bounds.width / 2
            }px ${center.y * 2 + bounds.height / 2}px,
            rgba(255,255,255,0.4),
            rgba(255,255,255,0)
          )`,
        });
      };

      const enter = () => {
        bounds = card.getBoundingClientRect();
        document.addEventListener("mousemove", move);
      };

      const leave = () => {
        document.removeEventListener("mousemove", move);

        gsap.to(card, {
          rotationX: 0,
          rotationY: 0,
          scale: 1,
          boxShadow: `0px 0px ${lastShadow.blur}px rgba(0,0,0,0)`,
          duration: 0.6,
          ease: "power2.out",
        });

        gsap.to(glare, {
          autoAlpha: 0,
          duration: 0.6,
        });
      };

      card.addEventListener("mouseenter", enter);
      card.addEventListener("mouseleave", leave);

      return () => {
        card.removeEventListener("mouseenter", enter);
        card.removeEventListener("mouseleave", leave);
        document.removeEventListener("mousemove", move);
      };
    });
  }, [categories]);
  const latestProducts = [...products].sort((a, b) => b.id - a.id).slice(0, 4);
  const filteredProducts =
    activeCategory === "All"
      ? latestProducts
      : latestProducts.filter((p) => p.category === activeCategory);


  useEffect(() => {
    if (categoryRefs.current[0]) {
      const el = categoryRefs.current[0];

      gsap.set(underlineRef.current, {
        x: el.offsetLeft,
        width: el.offsetWidth,
      });
    }
  }, [categories]);
  useEffect(() => {
    categoryRefs.current.forEach((el) => {
      if (!el) return;

      el.addEventListener("mouseenter", () => {
        gsap.to(el, {
          scale: 1.1,
          duration: 0.2,
        });
      });

      el.addEventListener("mouseleave", () => {
        gsap.to(el, {
          scale: 1,
          duration: 0.2,
        });
      });
    });
  }, [categories]);
  useEffect(() => {
    gsap.fromTo(
      ".col-6",
      { opacity: 0, y: 20 },
      {
        opacity: 1,
        y: 0,
        duration: 0.5,
        stagger: 0.05,
        ease: "power2.out",
      }
    );
  }, [activeCategory]);
  return (
    <>
      <div className="position-relative heroSection">
        <div
          className="position-absolute text-black"
          style={{ marginTop: 170, marginLeft: 80, zIndex: 2 }}
        >
          <h1 className="hero-title" style={{ fontSize: '100px' }}>
            Cosmelina
          </h1>

          {/* <img src={textLogo} alt="logo" className="hero-logo" /> */}
          <span><h3><i className="subtitle"><span className="text-danger">Natural </span>Beauty</i></h3></span>

          <h3 className="hero-subtitle">Premium Cosmetic Collection</h3>

          <button ref={buttonRef} className="button button--stroke mt-3">
            <span className="button__flair"></span>

            <span className="button__label hero-subtitle">
              Shop Now <FaArrowRightLong className="ms-2" />
            </span>
          </button>
        </div>

        <img src={banner} alt="hero" style={{ width: "100%", height: 700 }} />
      </div>

      <section className="offer-banner my-5">
        <div className="container">
          <div
            className="row"
          >
            <div className="col-lg-8 col-md-6 p-0">
              <div className="position-relative">
                <div className="position-absolute">
                <div className="ms-4 pt-1 pb-1 text-center rounded-5 text-white" style={{background:'radial-gradient(circle,rgba(14, 69, 196, 1) 7%, rgba(3, 25, 168, 1) 100%)'}}>
                  <div className="d-flex row ps-4" style={{width: '220px'}}>
                    <div className="col-lg-4 text-nowrap" style={{marginTop: '11px'}}><strong>UP TO</strong></div>
                    <div className="col-lg-4 fs-1 p-0" style={{width: '31px'}}>50</div>
                    <div className="col-lg-4">
                      <div style={{marginTop: '10px', textAlign: 'left', marginLeft: '5px'}}>%</div>
                      <div>OFF</div>
                    </div>
                  </div>
                </div>
                  <button className="button button--stroke mt-3 ms-5">
                    <span className="button__flair"></span>

                    <span className="button__label hero-subtitle">
                      Shop Now <FaArrowRightLong className="ms-2" />
                    </span>
                  </button>
                </div>
                <img src={banner1} alt="" />
              </div>

            </div>

            <div className="col-lg-4 col-md-6 p-0 text-center">
              <img
                src={banner2}
                alt="Offer Product"
                className=" offer-img"
              />
            </div>
          </div>
        </div>
      </section>
      <section className="section-space">
        <div className="container">
          <div className="d-flex justify-content-between align-items-center mb-4">
            <div className="d-flex align-items-center">
              <h3 className="fw-bold me-4 hero-subtitle">Trending Products</h3>
              <div className="categorySelector position-relative">
                <ul className="d-flex gap-3 position-relative">

                  {["All", ...categories].map((cat, index) => (
                    <li
                      key={index}
                      ref={(el) => (categoryRefs.current[index] = el)}
                      className={`ps-3 pe-3 p-1 rounded category-item ${activeCategory === cat ? "active" : ""
                        }`}
                      onClick={() => handleCategoryChange(cat, index)}
                    >
                      {cat}
                    </li>
                  ))}
                </ul>
              </div>
            </div>
            <a href='/shop' className="text-muted small text-decoration-none">View All <MdOutlineKeyboardArrowRight className="fs-4" /></a>
          </div>

          <div className="row g-1 g-sm-2">
            {latestProducts.length > 0 ? (
              filteredProducts.map((product) => (
                <div className="col-6 col-lg-3" key={product.id}>
                  <ProductItem product={product} />
                </div>
              ))
            ) : (
              <p className="text-center">No products found</p>
            )}
          </div>
          {/* <div className="d-flex justify-content-center mt-5">
            <a href="/shop" className="btn btn-outline-primary text-center">
              View More Products
            </a>
          </div> */}
        </div>
      </section>
    </>
  );
};

export default HeroSlider;
