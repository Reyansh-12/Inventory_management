import { useEffect, useState, useRef } from "react";
import { useNavigate } from "react-router-dom";

import "../assets/styles/plugins/HeroSlider.css";
import "../../src/assets/styles/plugins/ProductCards.css";

import banner from "../assets/images/HeroBanner(1).png";
import textLogo from "../assets/images/textLogo-removebg-preview.png";
import second from "../assets/images/secondSection.png";
import image2 from "../assets/images/18448-removebg-preview.png";

import { FaArrowRightLong } from "react-icons/fa6";
import ProductItem from "@/Pages/Products/ProductItem.jsx";

const HeroSlider = () => {
  const [products, setProducts] = useState([]);
  const navigate = useNavigate();
  const scrollRef = useRef(null);

  useEffect(() => {
    fetch(
      "http://localhost/Inventory_management/Backend/src/Pages/APIs/productListAPI.php"
    )
      .then((res) => res.json())
      .then(setProducts)
      .catch((err) => console.error("API Error:", err));
  }, []);

  const normalize = (value) =>
    value?.toLowerCase().replace(/\s+/g, "");

  const categories = [...new Set(products.map((p) => p.category))];

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

  const handleCategoryClick = (category) => {
    navigate(`/shop?category=${encodeURIComponent(category)}`);
  };

  return (
    <>
      <div className="position-relative">
        <div
          className="position-absolute text-black"
          style={{ marginTop: 170, marginLeft: 80, zIndex: 2 }}
        >
          <h5 style={{ fontSize: 60 }}>Discover Your</h5>
          <img src={textLogo} alt="logo" />
          <h6 style={{ fontSize: 30 }}>Premium Cosmetic Collection</h6>

          <button
            className="rounded-5 mt-3 text-white border-0 px-4 py-2"
            style={{
              background:
                "linear-gradient(90deg, rgba(227,39,95,1) 50%, rgba(245,137,164,1) 100%)",
            }}
          >
            Shop Now <FaArrowRightLong className="ms-2" />
          </button>
        </div>

        <img
          src={banner}
          alt="hero"
          style={{ width: "100%", height: 700 }}
        />
      </div>

      <div className=""
        style={{
          backgroundImage: `url(${second})`,
          backgroundSize: "cover",
          backgroundPosition: "center",
          
        }}
      >
        <section className="category-section">
          <div className="textcenter mb-5">
            <h3 className="text-center">
              <strong>Shop by Category</strong>
            </h3>
            {/* <div>
              <button
                onClick={handlePrev}
                className="btn btn-outline-danger me-2"
              >
                ‹
              </button>
              <button
                onClick={handleNext}
                className="btn btn-outline-danger"
              >
                ›
              </button>
            </div> */}
          </div>

          <div
            ref={scrollRef}
            className="d-flex gap-4"
            style={{
              overflowX: "auto",
              scrollBehavior: "smooth",
              paddingTop: '17px'
            }}
          >
            {categories.map((category) => (
              <div
                key={category}
                className="category-card overflow-hidden"
                onClick={() => handleCategoryClick(category)}
                style={{
                  cursor: "pointer",
                  minWidth: "200px",
                  flex: "0 0 auto",
                }}
              >
                <div style={{
                    background:
                      "radial-gradient(circle,rgba(228, 181, 235, 0.4) 15%, rgba(192, 96, 240, 0.4) 100%)",
                  }}>
                <img
                  src={image2}
                  alt={category}
                  className="w-100"
                  style={{marginTop: '0px'}}
                />
                </div>
                <h3 className="text-capitalize">{category}</h3>
              </div>
            ))}
          </div>
        </section>
      </div>

      <section className="section-space pb-5">
        <div className="container">
          <div className="row g-3 g-sm-6">
            {products.length > 0 ? (
              products.slice(0, 6).map((product) => (
                <div className="col-6 col-lg-4" key={product.id}>
                  <ProductItem product={product} />
                </div>
              ))
            ) : (
              <p className="text-center">No products found</p>
            )}
          </div>
        </div>
      </section>
    </>
  );
};

export default HeroSlider;