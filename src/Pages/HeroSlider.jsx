import { useEffect, useState, useRef } from "react";
import { useNavigate } from "react-router-dom";

import "../assets/styles/plugins/HeroSlider.css";
import "../../src/assets/styles/plugins/ProductCards.css";

import banner from "../assets/images/HeroBanner(1).png";
import textLogo from "../assets/images/textLogo-removebg-preview.png";
import second from "../assets/images/secondSection.png";
import image2 from "../assets/images/18448-removebg-preview.png";
import banner1 from "../assets/images/banner1.png";
import banner2 from "../assets/images/banner3.png";
import { FaArrowRightLong } from "react-icons/fa6";
import ProductItem from "@/Pages/Products/ProductItem.jsx";
import gsap from "gsap";
import { MdOutlineKeyboardArrowRight } from "react-icons/md";

const HeroSlider = () => {
  const [products, setProducts] = useState([]);
  const [activeCategory, setActiveCategory] = useState("All");
  const navigate = useNavigate();
  const scrollRef = useRef(null);
  const cardRefs = useRef([]);
  const categoryRefs = useRef([]);
  const underlineRef = useRef(null);

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
    const button = buttonRef.current;
    const flair = button.querySelector(".button__flair");

    const xSet = gsap.quickSetter(flair, "xPercent");
    const ySet = gsap.quickSetter(flair, "yPercent");

    const getXY = (e) => {
      const { left, top, width, height } = button.getBoundingClientRect();

      const x = gsap.utils.clamp(
        0,
        100,
        gsap.utils.mapRange(0, width, 0, 100, e.clientX - left),
      );

      const y = gsap.utils.clamp(
        0,
        100,
        gsap.utils.mapRange(0, height, 0, 100, e.clientY - top),
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
        xPercent: x > 90 ? x + 20 : x < 10 ? x - 20 : x,
        yPercent: y > 90 ? y + 20 : y < 10 ? y - 20 : y,
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

    return () => {
      button.removeEventListener("mouseenter", onEnter);
      button.removeEventListener("mouseleave", onLeave);
      button.removeEventListener("mousemove", onMove);
    };
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
  const latestProducts = [...products].sort((a, b) => b.id - a.id).slice(0, 20);
  const filteredProducts =
    activeCategory === "All"
      ? latestProducts
      : latestProducts.filter((p) => p.category === activeCategory);

  useEffect(() => {

    const letters = document.querySelectorAll(".hero-letter");

    const animate = () => {
      gsap.to(letters, {
        y: -18,
        duration: 0.4,
        stagger: 0.05,
        ease: "power1.out",
        yoyo: true,
        repeat: 1
      });
    };

    letters.forEach((letter) => {

      letter.addEventListener("mouseenter", animate);

    });

  }, []);
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
      <div className="position-relative">
        <div
          className="position-absolute text-black"
          style={{ marginTop: 170, marginLeft: 80, zIndex: 2 }}
        >
          <h1 className="hero-title">
            {"Discover Your".split("").map((char, index) => (
              <span key={index} className="hero-letter">
                {char === " " ? "\u00A0" : char}
              </span>
            ))}
          </h1>

          <img src={textLogo} alt="logo" className="hero-logo" />
          <span><h3><i><span className="text-danger">Natural</span>Beauty</i></h3></span>

          <h3 className="hero-subtitle">Premium Cosmetic Collection</h3>

          <button ref={buttonRef} className="button button--stroke mt-3">
            <span className="button__flair"></span>

            <span className="button__label">
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
              <img src={banner1} alt="" />
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
              <h3 className="fw-bold me-4">Trending Products</h3>
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
          <div className="d-flex justify-content-center mt-5">
            <a href="/shop" className="btn btn-outline-primary text-center">
              View More Products
            </a>
          </div>
        </div>
      </section>
    </>
  );
};

export default HeroSlider;
