import React, { useState, useEffect } from "react";
import { useLocation } from "react-router-dom";
import Footer from "../../components/Footer";

import ProductItem from "@/Pages/Products/ProductItem.jsx";
import FilterBlock from "@/components/FilterBlock";
import image from "../../assets/images/secondSection.png";
import "../../assets/styles/plugins/ProductCards.css";

const ProductFourColumns = () => {
  const [products, setProducts] = useState([]);
  const [categories, setCategories] = useState([]);
  const [brands, setBrands] = useState([]);

  const [selectedCategory, setSelectedCategory] = useState("All");
  const [selectedBrand, setSelectedBrand] = useState("All");
  const [search, setSearch] = useState("");
  const [price, setPrice] = useState(5000);
  const [rating, setRating] = useState(0);

  const location = useLocation();

  useEffect(() => {
    window.scrollTo({ top: 0, behavior: "smooth" });
  }, [location.search]);

  useEffect(() => {
    fetch(
      "http://localhost/Inventory_management/Backend/src/Pages/APIs/categoryListAPI.php"
    )
      .then((res) => res.json())
      .then(setCategories)
      .catch((err) => console.log("Category API Error:", err));
  }, []);

  useEffect(() => {
    fetch(
      "http://localhost/Inventory_management/Backend/src/Pages/APIs/productListAPI.php"
    )
      .then((res) => res.json())
      .then((data) => {
        setProducts(data);

        const uniqueBrands = [...new Set(data.map((p) => p.brand))];
        setBrands(uniqueBrands);
      })
      .catch((err) => console.log("Product API Error:", err));
  }, []);

  const filteredProducts = products.filter((p) => {
    const categoryMatch =
      selectedCategory === "All" || p.category === selectedCategory;
    const brandMatch = selectedBrand === "All" || p.brand === selectedBrand;
    const searchMatch = p.name
      ?.toLowerCase()
      .includes(search.toLowerCase());
    const priceMatch = p.price <= price;
    const ratingMatch = rating === 0 || p.rating >= rating;

    return categoryMatch && brandMatch && searchMatch && priceMatch && ratingMatch;
  });

  return (
    <main
      className="main-content pt-4"
      style={{
        marginTop: "100px",
        background: `url(${image})`,
        backgroundSize: "cover",
        backgroundPosition: "center",
        height: "calc(100vh - 100px)",
        overflow: "hidden",
      }}
    >
      <div className="d-flex h-100">
        <div className="col-lg-3">
          <div className="filter-sidebar p-4 ms-4">
            <div className="d-flex justify-content-between align-items-center mb-3">
              <div>
                <h6 className="fw-bold mb-0">FILTERS</h6>
                <small className="text-muted">{filteredProducts.length}+ Products</small>
              </div>
              <button
                className="btn btn-link text-decoration-none p-0 text-danger small"
                style={{ letterSpacing: "1px" }}
                onClick={() => {
                  setSelectedCategory("All");
                  setSelectedBrand("All");
                  setSearch("");
                  setPrice(5000);
                  setRating(0);
                }}
              >
                Clear All
              </button>
            </div>

            <FilterBlock
              title="Category"
              items={["All", ...categories.map((c) => c.name)]}
              onChange={setSelectedCategory}
            />

            <FilterBlock
              title="Skin Type"
              items={["Dry Skin", "Oily Skin", "Sensitive", "Combination"]}
            />

            <FilterBlock
              title="Brand"
              items={["All", ...brands]}
              onChange={setSelectedBrand}
            />

            <div className="filter-block">
              <h6>Price</h6>
              <input
                type="range"
                className="form-range"
                min="0"
                max="5000"
                value={price}
                onChange={(e) => setPrice(Number(e.target.value))}
              />
              <div className="d-flex justify-content-between small text-muted">
                <span>₹0</span>
                <span>₹{price}</span>
              </div>
            </div>

            <div className="filter-block">
              <h6>Rating</h6>
              {[4, 3, 2].map((r) => (
                <label className="filter-option" key={r}>
                  <input
                    type="radio"
                    name="rating"
                    checked={rating === r}
                    onChange={() => setRating(r)}
                  />{" "}
                  {r}★ & above
                </label>
              ))}
            </div>
          </div>
        </div>

        <div className="col-lg-9 product-scroll">
          <div className="shop-topbar d-flex justify-content-between align-items-center mb-4 ms-4">
            <span className="product-count" style={{ width: "150px" }}>
              Total Products <b>{filteredProducts.length}</b>
            </span>

            <input
              type="search"
              className="form-control search-input w-25"
              placeholder="Search Products"
              value={search}
              onChange={(e) => setSearch(e.target.value)}
            />
          </div>

          <section className="section-space pb-5 mt-5">
            <div className="container-fluid px-3 px-lg-4">
              <div className="row g-4">
                {filteredProducts.length > 0 ? (
                  filteredProducts.map((product) => (
                    <div className="col-6 col-md-4 col-lg-3" key={product.id}>
                      <ProductItem product={product} />
                    </div>
                  ))
                ) : (
                  <p className="text-center w-100">No products found</p>
                )}
              </div>
            </div>
          </section>
        </div>
      </div>

      <Footer />
    </main>
  );
};

export default ProductFourColumns;