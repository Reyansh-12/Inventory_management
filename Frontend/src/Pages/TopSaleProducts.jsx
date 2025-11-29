
import React from "react";
import { FaRegHeart } from "react-icons/fa";
import {Link} from 'react-router-dom';
import banner1 from "../assets/images/shop/banner/1.webp";
import banner2 from "../assets/images/shop/banner/2.webp";
import banner3 from "../assets/images/shop/banner/3.webp";
import { FaRegStar } from "react-icons/fa";
import { FaStarHalfAlt } from "react-icons/fa";
import { CgArrowsExpandRight } from "react-icons/cg";
import { useEffect, useState } from "react";
import { FaArrowRightLong } from "react-icons/fa6";
import ProductItem from "/xampp/htdocs/Inventory_management/Frontend/src/Pages/Products/ProductItem.jsx";

const TopSaleProducts = () => {

  const [products, setProducts] = useState([]);

  useEffect(() => {
    fetch("http://localhost:3000/Backend/src/Pages/APIs/productListAPI.php")
      .then((res) => res.json())
      .then((data) => setProducts(data))
      .catch((err) => console.log("API Error:", err));
  }, []);
const firstSix = products.slice(0, 6);
  const nextThree = products.slice(6, 9);
  return (
    <section className="section-space">
      <div className="container">
        <div className="row">
          <div className="col-12">
            <div className="section-title text-center">
              <h2 className="title">Top sale</h2>
              <p>
                Lorem ipsum dolor sit amet, consectetur adipiscing elit ut
                aliquam, purus sit amet luctus venenatis
              </p>
            </div>
          </div>
        </div>
      </div>
      <main className="main-content">
        <section className="section-space pb-5">
          <div className="container">
            <div className="row mb-n4 mb-sm-n10 g-3 g-sm-6">
              {firstSix.map((product) => (
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
        <section className="section-space pb-5">
          <div className="container">
            <div className="row">
              <div className="col-sm-6 col-lg-4">
                <a href="product.html" className="product-banner-item">
                  <img
                    src={banner1}
                    width="370"
                    height="370"
                    alt="Image-HasTech"
                  />
                </a>
              </div>
              <div className="col-sm-6 col-lg-4 mt-sm-0 mt-6">
                <a href="product.html" className="product-banner-item">
                  <img
                    src={banner2}
                    width="370"
                    height="370"
                    alt="Image-HasTech"
                  />
                </a>
              </div>
              <div className="col-sm-6 col-lg-4 mt-lg-0 mt-6">
                <a href="product.html" className="product-banner-item">
                  <img
                    src={banner3}
                    width="370"
                    height="370"
                    alt="Image-HasTech"
                  />
                </a>
              </div>
            </div>
          </div>
        </section>
        <section className="section-space pb-5">
          <div className="container">
            <div className="row mb-n4 mb-sm-n10 g-3 g-sm-6">
              {nextThree.map((product) => (
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
        <div className="d-flex justify-content-center text-white">
          <Link to="/shop" className="rounded-3 p-2 ps-3 pe-3 text-decoration-none text-wite" id="sow_all_products">See All Product <FaArrowRightLong className="ms-2" /></Link>
        </div>
      </main>
    </section>
  );
};
export default TopSaleProducts;