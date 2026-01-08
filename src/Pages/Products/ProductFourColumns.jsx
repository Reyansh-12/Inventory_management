import React, { useState, useEffect } from 'react';
 
import Footer from '../../components/Footer';
import banner7 from '../../assets/images/shop/banner/7.webp';
import category1 from "../../assets/images/HairConditioner-removebg-preview.png";
import category2 from "../../assets/images/shop/category/category2.webp";
import category3 from "../../assets/images/lipbalm-removebg-preview.png";
import category4 from "../../assets/images/shop/category/category4.webp";
import category5 from "../../assets/images/Makeup-removebg-preview.png";
import category6 from "../../assets/images/shop/category/category6.webp";
import ProductItem from "@/Pages/Products/ProductItem.jsx";
import Image from "../../../src/assets/images/product_not_found2.png";

const ProductFourColumns = () => {
    const [products, setProducts] = useState([]);
    const [selectedCategory, setSelectedCategory] = useState("all");
    const normalize = (value) =>
      value?.toLowerCase().replace(/\s+/g, '');
   
      
    useEffect(() => {
      fetch("http://localhost/Inventory_management/Backend/src/Pages/APIs/productListAPI.php")
        .then((res) => res.json())
        .then((data) => setProducts(data))
        .catch((err) => console.log("API Error:", err));
    }, []);

  const categories = [
    
    { id: 2, category:'haircare', title: 'Hair care', image: category1, bgColor: '#FFF3DA', class: 'category'},
    { id: 3, category:'skincare', title: 'Skin care', image: category2, bgColor: '#FFEDB4'},
    { id: 4, category:'lipstick', title: 'Lip stick', image: category3, bgColor: '#DFE4FF'},
    { id: 5, category:'faceskin', title: 'Face skin', image: category4, bgColor: '#FFEACC'},
    { id: 6, category:'blusher', title: 'Blusher', image: category5, bgColor: '#FFDAE0'},
    { id: 7, category:'natural', title: 'Natural', image: category6, bgColor: '#FFF3DA'},
  ];
    
  const filteredProducts =
  selectedCategory === "all"
    ? products
    : products.filter(p =>
        normalize(p.category) === normalize(selectedCategory)
      );

const visibleProducts = filteredProducts.slice(0, 9);
const relatedProducts = products.slice(0, 3);
  return (
    
    <main className="main-content" style={{marginTop: '80px'}}>
      <section className="page-header-area pt-10 pb-9 mb-5" style={{ backgroundColor: '#FFF3DA' }}>
        <div className="container">
          <div className="row">
            <div className="col-md-5">
              <div className="page-header-st3-content text-center text-md-start">
                <h2 className="page-header-title">All Products</h2>
              </div>
            </div>
            <div className="col-md-7">
              <h5 className="showing-pagination-results text-center text-md-end">
              Showing {visibleProducts.length} Results
              </h5>
            </div>
          </div>
        </div>
      </section>
      <section className="section-space pb-0">
      <div className="container">
        <div className="row g-3 g-sm-6">
          {categories.map((cat) => (
            <div key={cat.id} className="col-6 col-lg-2">
              <button
                  className={`product-category-item w-100 ${selectedCategory === cat.category ? "active" : ""}`}
                  style={{ backgroundColor: cat.bgColor, border: "none" }}
                  onClick={() => setSelectedCategory(cat.category)}
                >
                <img className="icon" src={cat.image} width="80" height="80" alt={cat.title} />
                <h3 className="title">{cat.title}</h3>
                {cat.badge && (
                  <span
                    className="flag-new"
                    style={cat.badgeBgColor ? { backgroundColor: cat.badgeBgColor } : {}}
                  >
                    {cat.badge}
                  </span>
                )}
              </button>
            </div>
          ))}
        </div>
      </div>
    </section>
      <section className="section-space pb-5">
        <div className="container">
          <div className="row g-3 g-sm-6">
            {visibleProducts.length > 0 ? (
              visibleProducts.map(product => (
                <div className="col-6 col-lg-4" key={product.id}>
                  <ProductItem product={product} />
                </div>
              ))
            ) : (
              <p className="text-center"><img src={Image} alt="no product found" style={{width: '700px'}} /></p>
            )}
          </div>
        </div>
      </section>
      <section>
        <div className="container">
          <a href="/products" className="product-banner-item">
            <img 
              src={banner7} 
              width="1170" 
              height="240" 
              alt="Product Banner"
            />
          </a>
        </div>
      </section>

      <section className="section-space">
        <div className="container">
          <div className="row">
            <div className="col-12">
              <div className="section-title">
                <h2 className="title">Related Products</h2>
                <p className="m-0">
                  Lorem ipsum dolor sit amet, consectetur adipiscing elit ut aliquam, purus sit amet luctus venenatis
                </p>
              </div>
            </div>
          </div>
        </div>
      </section>
      <section className="section-space pb-5 pt-0">
        <div className="container">
          <h2>Related Products</h2>
          <div className="row g-3 g-sm-6">
            {relatedProducts.map(product => (
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