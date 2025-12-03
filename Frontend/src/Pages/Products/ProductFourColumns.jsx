import React, { useState, useEffect } from 'react';
 
import Footer from '../../components/Footer';
import banner7 from '../../assets/images/shop/banner/7.webp';
import category1 from "../../assets/images/shop/category/category1.webp";
import category2 from "../../assets/images/shop/category/category2.webp";
import category3 from "../../assets/images/shop/category/category3.webp";
import category4 from "../../assets/images/shop/category/category4.webp";
import category5 from "../../assets/images/shop/category/category5.webp";
import category6 from "../../assets/images/shop/category/category6.webp";
import ProductItem from "@/Pages/Products/ProductItem.jsx";


const ProductFourColumns = () => {
    const [products, setProducts] = useState([]);

    useEffect(() => {
      fetch("http://localhost:3000/Backend/src/Pages/APIs/productListAPI.php")
        .then((res) => res.json())
        .then((data) => setProducts(data))
        .catch((err) => console.log("API Error:", err));
    }, []);

  const categories = [
    { id: 1, category:'haircare', title: 'Hair care', image: category1, bgColor: '#FFF3DA', badge: 'new'},
    { id: 2, category:'skincare', title: 'Skin care', image: category2, bgColor: '#FFEDB4',  },
    { id: 3, category:'lipstick', title: 'Lip stick', image: category3, bgColor: '#DFE4FF',  },
    { id: 4, category:'faceskin', title: 'Face skin', image: category4, bgColor: '#FFEACC', badge: 'sale', badgeBgColor: '#835BF4', },
    { id: 5, category:'blusher', title: 'Blusher', image: category5, bgColor: '#FFDAE0', },
    { id: 6, category:'natural', title: 'Natural', image: category6, bgColor: '#FFF3DA', },
  ];
    
    const firstNine = products.slice(0, 9);
    const firstThree = products.slice(0, 3);
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
                Showing 09 Results
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
              <a
                href="#"
                className="product-category-item"
                style={cat.bgColor ? { backgroundColor: cat.bgColor } : {}}
                onClick={(e) => { e.preventDefault(); cat.onClick && cat.onClick(); }}
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
              </a>
            </div>
          ))}
        </div>
      </div>
    </section>
    <section className="section-space pb-5">
          <div className="container">
            <div className="row mb-n4 mb-sm-n10 g-3 g-sm-6">
              {firstNine.map((product) => (
              <div className="col-6 col-lg-4 mb-4 mb-sm-8" key={product.id}>
                <div className="product-item text-start">
                  <div className="product-thumb">
                    <a className="d-block" href="product-details.html"></a>
                    <ProductItem product={product} />
                  </div>
                </div>
              </div>
              ))}
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

      <section className="section-space pb-0">
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
      <section className="section-space pb-5">
          <div className="container">
            <div className="row mb-n4 mb-sm-n10 g-3 g-sm-6">
              {firstThree.map((product) => (
              <div className="col-6 col-lg-4 mb-4 mb-sm-8" key={product.id}>
                <div className="product-item text-start">
                  <div className="product-thumb">
                    <a className="d-block" href="product-details.html"></a>
                    <ProductItem product={product} />
                  </div>
                </div>
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