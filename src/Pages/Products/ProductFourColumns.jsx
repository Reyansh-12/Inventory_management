import React, { useState, useEffect } from 'react';

import Footer from '../../components/Footer';
import banner7 from '../../assets/images/shop/banner/7.webp';
import ProductItem from "@/Pages/Products/ProductItem.jsx";
import Image from "../../../src/assets/images/product_not_found2.png";
import CategorySlider from "@/components/CategorySlider";
import '../../assets/styles/plugins/ProductCards.css';


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

  const filteredProducts =
    selectedCategory === "all"
      ? products
      : products.filter(p =>
          normalize(p.category) === normalize(selectedCategory)
        );

  const visibleProducts = filteredProducts.slice(0, 9);
  const relatedProducts = products.slice(0, 3);

  return (
    <main className="main-content" style={{ marginTop: '80px' }}>

      <section className="page-header-area pt-10 pb-9 mb-5" style={{ backgroundColor: '#FFF3DA' }}>
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
          <CategorySlider onSelectCategory={setSelectedCategory} />
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
              <p className="text-center">
                <img src={Image} alt="no product found" style={{ width: '700px' }} />
              </p>
            )}
          </div>
        </div>
      </section>

 
      <section>
        <div className="container">
          <img src={banner7} width="1170" height="240" alt="Product Banner" />
        </div>
      </section>

      <section className="section-space pb-5">
        <div className="container">
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
