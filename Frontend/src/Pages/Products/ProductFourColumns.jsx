import React, { useState, useEffect } from "react";
import { useLocation } from "react-router-dom";
import Form from 'react-bootstrap/Form';
import Footer from "../../components/Footer";
import CategorySlider from "@/components/CategorySlider";
import ProductItem from "@/Pages/Products/ProductItem.jsx";
import FilterBlock from "@/components/FilterBlock";

import banner7 from "../../assets/images/shop/banner/7.webp";
import NoProductImage from "../../../src/assets/images/product_not_found2.png";
import image from '../../assets/images/secondSection.png';
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
    <main className="main-content pt-4" style={{ marginTop: "100px", background: `url(${image})`, backgroundSize: 'cover', backgroundPosition: 'center' }}>
      <div className="d-flex row ">
        <div className="col-lg-3">
          <div className="filter-sidebar p-4 ms-4">

            <div className="d-flex justify-content-between align-items-center mb-3">
              <div>
                <h6 className="fw-bold mb-0">FILTERS</h6>
                <small className="text-muted">100+ Products</small>
              </div>
              <button className="btn btn-link text-decoration-none p-0 text-danger small" style={{ letterSpacing: '1px' }}>
                Clear All
              </button>
            </div>

            <FilterBlock
  title="Category"
  items={[
    "Skincare",
    "Makeup",
    "Hair Care",
    "Body Care",
    "Fragrance",
    "Tools",
    "Men Grooming"
  ]}
/>

<FilterBlock
  title="Skin Type"
  items={["Dry Skin", "Oily Skin", "Sensitive", "Combination"]}
/>

<FilterBlock
  title="Brand"
  items={[
    "Mamaearth",
    "Lakme",
    "Plum",
    "WOW",
    "Maybelline",
    "Minimalist",
    "Cetaphil"
  ]}
/>

            <div className="filter-block">
              <h6>Price</h6>
              <input type="range" className="form-range" />
              <div className="d-flex justify-content-between small text-muted">
                <span>₹0</span>
                <span>₹5000</span>
              </div>
            </div>

            <div className="filter-block">
              <h6>Rating</h6>
              {[4, 3].map(r => (
                <label className="filter-option" key={r}>
                  <input type="checkbox" /> {r}★ & above
                </label>
              ))}
            </div>

          </div>
        </div>
        <div className="col-lg-9">

          <div className="shop-topbar d-flex justify-content-between align-items-center mb-4">
            <span className="product-count" style={{ width: '150px' }}>
              Total Products <b>{visibleProducts.length}</b>
            </span>

            <input
              type="search"
              className="form-control search-input w-25"
              placeholder="Search Products"
            />
          </div>
          <div className="row g-4">
            {visibleProducts.map(product => (
              <div className="col-md-6 col-lg-4" key={product.id}>
                <ProductItem product={product} />
              </div>
            ))}
          </div>

        </div>
      </div>
      {/* <section
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
      </section> */}

      {/* <section className="section-space pb-0 pt-3">
        <div className="container">
          <CategorySlider />
        </div>
      </section> */}

      <Footer />
    </main>
  );
};

export default ProductFourColumns;