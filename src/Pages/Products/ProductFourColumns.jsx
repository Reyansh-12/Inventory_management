import React, { useState, useEffect } from "react";
import { useLocation } from "react-router-dom";
import Footer from "../../components/Footer";
import ProductItem from "@/Pages/Products/ProductItem.jsx";
import image from "../../assets/images/secondSection.png";
import "../../assets/styles/plugins/ProductCards.css";

const ProductFourColumns = () => {
  const [products, setProducts] = useState([]);
  const [categories, setCategories] = useState([]);
  const [selectedCategory, setSelectedCategory] = useState("All");
  const [search, setSearch] = useState("");
  const [price, setPrice] = useState(10000);
  const [rating, setRating] = useState(0);

  const location = useLocation();

  // --- FIX FOR SCROLL: Sirf isi page par scroll lock hoga ---
  useEffect(() => {
    // Page enter hote hi body scroll band
    document.body.style.overflow = "hidden";
    
    return () => {
      // Page se jate hi body scroll wapas chalu (Enable for other pages)
      document.body.style.overflow = "auto";
    };
  }, []);

  useEffect(() => {
    window.scrollTo({ top: 0, behavior: "smooth" });
  }, [location.search]);

  useEffect(() => {
    fetch("http://localhost/Inventory_management/Backend/src/Pages/APIs/categoryListAPI.php")
      .then((res) => res.json())
      .then((data) => setCategories(data || []))
      .catch((err) => console.error("Category Error:", err));

    fetch("http://localhost/Inventory_management/Backend/src/Pages/APIs/productListAPI.php")
      .then((res) => res.json())
      .then((data) => setProducts(data || []))
      .catch((err) => console.error("Product Error:", err));
  }, []);

  const filteredProducts = products.filter((p) => {
    const categoryMatch = selectedCategory === "All" || 
      (p.category_name && p.category_name.toLowerCase() === selectedCategory.toLowerCase()) ||
      (p.category && String(p.category).toLowerCase() === selectedCategory.toLowerCase());
    const searchMatch = !search || (p.name && p.name.toLowerCase().includes(search.toLowerCase()));
    const priceMatch = Number(p.price || 0) <= price;
    const ratingMatch = rating === 0 || Number(p.rating || 0) >= rating;
    return categoryMatch && searchMatch && priceMatch && ratingMatch;
  });

  const handleClearFilters = () => {
    setSelectedCategory("All");
    setSearch("");
    setPrice(10000);
    setRating(0);
  };

  return (
    <main
      className="main-content"
      style={{
        marginTop: "80px", 
        background: `url(${image})`,
        backgroundSize: "cover",
        backgroundPosition: "center",
        height: "calc(100vh - 80px)", 
        width: "100%",
        overflow: "hidden", 
        display: "flex",
        flexDirection: "column"
      }}
    >
      <div className="container-fluid h-100 px-0">
        <div className="row g-0 h-100 mx-0">
          
          <div className="col-lg-3 d-none d-lg-block h-100 bg-white shadow-sm border-end">
            <div className="filter-sidebar p-4 h-100 custom-scrollbar" style={{ overflowY: 'auto' }}>
              <div className="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                <h6 className="fw-bold m-0" style={{letterSpacing:'1px'}}>REFINE BY</h6>
                <button className="btn btn-link text-danger p-0 fw-bold text-decoration-none small" 
                        onClick={handleClearFilters} style={{fontSize: '12px', letterSpacing:'initial'}}>RESET</button>
              </div>

              <div className="mb-4 text-start">
                <p className="text-muted fw-bold small mb-3">CATEGORIES</p>
                <div className="list-group list-group-flush">
                  <button className={`list-group-item list-group-item-action border-0 px-0 py-1 small text-start ${selectedCategory === "All" ? "text-danger fw-bold" : ""}`}
                          onClick={() => setSelectedCategory("All")}>All Products</button>
                  {categories.map((cat, i) => (
                    <button key={i} className={`list-group-item list-group-item-action border-0 px-0 py-1 small text-start ${selectedCategory === cat.name ? "text-danger fw-bold" : ""}`}
                            onClick={() => setSelectedCategory(cat.name)}>{cat.name}</button>
                  ))}
                </div>
              </div>

              <div className="mb-4">
                <p className="text-muted fw-bold small mb-2">MAX PRICE: ₹{price}</p>
                <input type="range" className="form-range custom-range-input" min="0" max="10000" step="100" value={price} onChange={(e) => setPrice(Number(e.target.value))} />
              </div>

              <div className="mb-4">
                <p className="text-muted fw-bold small mb-2">RATINGS</p>
                {[4, 3, 2].map(r => (
                  <div className="form-check mb-2" key={r}>
                    <input className="form-check-input shadow-none" type="radio" name="rating" id={`r${r}`} checked={rating === r} onChange={() => setRating(r)} />
                    <label className="form-check-label small cursor-pointer" htmlFor={`r${r}`}>{r}★ & Above</label>
                  </div>
                ))}
              </div>
            </div>
          </div>

          <div className="col-lg-9 d-flex flex-column h-100 px-4">
            
            <div className="shop-topbar d-flex justify-content-between align-items-center p-3 mt-3 mb-3 bg-white rounded-4 shadow-sm border-start border-5 border-danger">
              <div>
                <span className="text-muted small text-uppercase fw-bold" style={{fontSize: '10px'}}>Browsing</span>
                <h6 className="m-0 fw-bold">Showing <span className="text-danger">{filteredProducts.length}</span> Results</h6>
              </div>
              <div className="search-box" style={{width: '280px'}}>
                <input type="search" className="form-control bg-light rounded-pill ps-4 shadow-none border-secondary" 
                       placeholder="Search products..." value={search} onChange={(e) => setSearch(e.target.value)} />
              </div>
            </div>

            <div className="product-container flex-grow-1 custom-scrollbar px-1" 
                 style={{ overflowY: 'auto', paddingBottom: '100px' }}>
              <div className="row g-3 mx-0">
                {filteredProducts.length > 0 ? (
                  filteredProducts.map((product) => (
                    <div className="col-6 col-md-4 col-xl-3 mb-2" key={product.id}>
                      <ProductItem product={product} />
                    </div>
                  ))
                ) : (
                  <div className="col-12 text-center py-5 bg-white rounded-4 shadow-sm mt-3">
                     <h5 className="text-muted m-0">No items match your filters.</h5>
                  </div>
                )}
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>
  );
};

export default ProductFourColumns;