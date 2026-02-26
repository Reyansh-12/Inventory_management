import React, { useState, useEffect } from "react";
import { useLocation } from "react-router-dom";

import Footer from "../../components/Footer";
import CategorySlider from "@/components/CategorySlider";
import ProductItem from "@/Pages/Products/ProductItem.jsx";

import banner7 from "../../assets/images/shop/banner/7.webp";
import NoProductImage from "../../../src/assets/images/product_not_found2.png";

import "../../assets/styles/plugins/ProductCards.css";

const ProductFourColumns = () => {
  const [products, setProducts] = useState([]);

  const location = useLocation();
  useEffect(() => {
  window.scrollTo({
    top: 0,
    behavior: "smooth",
  });
}, [location.search]);
  const params = new URLSearchParams(location.search);
  const selectedCategory = params.get("category") || "all";

  const normalize = (value) =>
    value?.toLowerCase().replace(/\s+/g, "");

  const filteredProducts =
    selectedCategory === "all"
      ? products
      : products.filter(
          (p) => normalize(p.category) === normalize(selectedCategory)
        );

  const visibleProducts = filteredProducts.slice(0, 9);
  const relatedProducts = products.slice(0, 3);

  useEffect(() => {
    fetch(
      "http://localhost/Inventory_management/Backend/src/Pages/APIs/productListAPI.php"
    )
      .then((res) => res.json())
      .then(setProducts)
      .catch((err) => console.log("API Error:", err));
  }, []);

  return (
    <main className="main-content" style={{ marginTop: "80px" }}>
      <section
        className="page-header-area pt-10 pb-9 mb-5"
        style={{ backgroundColor: "#FFF3DA" }}
      >
        <div className="container">
          <div className="row">
            <div className="col-md-5">
              <h2 className="page-header-title">All Products</h2>
            </div>
            <div className="col-md-7 text-end">
              Showing {visibleProducts.length} Results
            </div>
          </div>
        </div>
      </section>

      <section className="section-space pb-0 pt-3">
        <div className="container">
          <CategorySlider />
        </div>
      </section>

      <section className="section-space pb-5">
        <div className="container">
          <div className="row g-3 g-sm-6">
            {visibleProducts.length > 0 ? (
              visibleProducts.map((product) => (
                <div className="col-6 col-lg-4" key={product.id}>
                  <ProductItem product={product} />
                </div>
              ))
            ) : (
              <div className="text-center">
                <img
                  src={NoProductImage}
                  alt="No products found"
                  style={{ width: "700px" }}
                />
              </div>
            )}
          </div>
        </div>
      </section>

      <section>
        <div className="container">
          <img
            src={banner7}
            width="1170"
            height="240"
            alt="Product Banner"
          />
        </div>
      </section>

      <section className="section-space pb-5">
        <div className="container">
          <div className="row g-3 g-sm-6">
            {relatedProducts.map((product) => (
              <div className="col-6 col-lg-4" key={product.id}>
                <ProductItem product={product} />
              </div>
            ))}
          </div>
        </div>
      </section>

      <Footer />
    </main>
  );
};

export default ProductFourColumns;