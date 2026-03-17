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

  // States for filtering
  const [selectedCategory, setSelectedCategory] = useState("All");
  const [selectedBrand, setSelectedBrand] = useState("All");
  const [search, setSearch] = useState("");
  const [price, setPrice] = useState(5000);
  const [rating, setRating] = useState(0);

  const location = useLocation();

  // Scroll to top on route change
  useEffect(() => {
    window.scrollTo({ top: 0, behavior: "smooth" });
  }, [location.search]);

  useEffect(() => {
    fetch("http://localhost/Inventory_management/Backend/src/Pages/APIs/categoryListAPI.php")
      .then((res) => res.json())
      .then((data) => setCategories(data || []))
      .catch((err) => console.error("Category API Error:", err));
  }, []);

  useEffect(() => {
    fetch("http://localhost/Inventory_management/Backend/src/Pages/APIs/productListAPI.php")
      .then((res) => res.json())
      .then((data) => {
        setProducts(data || []);
        const uniqueBrands = [...new Set(data.map((p) => p.brand).filter(Boolean))];
        setBrands(uniqueBrands);
      })
      .catch((err) => console.error("Product API Error:", err));
  }, []);

  const filteredProducts = products.filter((p) => {
    const categoryMatch =
      selectedCategory === "All" || 
      (p.category && String(p.category).toLowerCase() === selectedCategory.toLowerCase());

    const brandMatch = 
      selectedBrand === "All" || 
      p.brand === selectedBrand;

    const searchMatch = !search || (p.name && p.name.toLowerCase().includes(search.toLowerCase()));

    const priceMatch = Number(p.price || 0) <= price;

    const ratingMatch = rating === 0 || Number(p.rating || 0) >= rating;

    return categoryMatch && brandMatch && searchMatch && priceMatch && ratingMatch;
  });

  const handleClearFilters = () => {
    setSelectedCategory("All");
    setSelectedBrand("All");
    setSearch("");
    setPrice(5000);
    setRating(0);
  };

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
          <div className="filter-sidebar p-4 ms-4 shadow-sm bg-white rounded" style={{ maxHeight: '90vh', overflowY: 'auto' }}>
            <div className="d-flex justify-content-between align-items-center mb-3">
              <div>
                <h6 className="fw-bold mb-0">FILTERS</h6>
                <small className="text-muted">{filteredProducts.length} Products Found</small>
              </div>
              <button
                className="btn btn-link text-decoration-none p-0 text-danger small"
                onClick={handleClearFilters}
                style={{ letterSpacing: 'initial' }}
              >
                Clear All
              </button>
            </div>

            <FilterBlock
              title="Category"
              items={["All", ...categories.map((c) => c.name)]}
              selectedValue={selectedCategory} 
              onChange={setSelectedCategory}
            />

            {/* <FilterBlock
              title="Brand"
              items={["All", ...brands]}
              selectedValue={selectedBrand} 
              onChange={setSelectedBrand}
            /> */}

            <div className="filter-block mb-4">
              <h6 className="fw-bold">Price (Max: ₹{price})</h6>
              <input
                type="range"
                className="form-range"
                min="0"
                max="10000" 
                step="100"
                value={price}
                onChange={(e) => setPrice(Number(e.target.value))}
              />
              <div className="d-flex justify-content-between small text-muted">
                <span>₹0</span>
                <span>₹10000</span>
              </div>
            </div>

            <div className="filter-block">
              <h6 className="fw-bold">Rating</h6>
              {[4, 3, 2].map((r) => (
                <label className="d-flex align-items-center gap-2 mb-1 cursor-pointer" key={r} style={{ cursor: 'pointer' }}>
                  <input
                    type="radio"
                    name="rating"
                    checked={rating === r}
                    onChange={() => setRating(r)}
                  />
                  {r}★ & above
                </label>
              ))}
              <label className="d-flex align-items-center gap-2 mb-1 cursor-pointer" style={{ cursor: 'pointer' }}>
                <input
                  type="radio"
                  name="rating"
                  checked={rating === 0}
                  onChange={() => setRating(0)}
                /> All Ratings
              </label>
            </div>
          </div>
        </div>

        <div className="col-lg-9 product-scroll" style={{ overflowY: 'auto' }}>
          <div className="shop-topbar d-flex justify-content-between align-items-center mb-5 px-4 w-100">
            <span className="product-count">
              Total Products <b>{filteredProducts.length}</b>
            </span>

            <input
              type="search"
              className="form-control search-input w-25 shadow-sm"
              placeholder="Search Products..."
              value={search}
              onChange={(e) => setSearch(e.target.value)}
            />
          </div>

          <section className="pb-5 pt-5">
            <div className="container-fluid px-3 px-lg-4">
              <div className="row g-4">
                {filteredProducts.length > 0 ? (
                  filteredProducts.map((product) => (
                    <div className="col-6 col-md-4 col-lg-3" key={product.id}>
                      <ProductItem product={product} />
                    </div>
                  ))
                ) : (
                  <div className="text-center w-100 mt-5">
                     <h4 className="text-muted">No products found matching your filters.</h4>
                  </div>
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