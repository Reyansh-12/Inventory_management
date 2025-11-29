import React from "react";
import { CgArrowsExpandRight } from "react-icons/cg";
import { FaRegHeart, FaRegStar, FaStarHalfAlt } from "react-icons/fa";
import shop4 from "../../assets/images/shop/category/4.webp";
import { Navigate, useNavigate } from "react-router-dom";

const ProductItem = ({ product }) => {
  const navigate = useNavigate();
  const addToCart = () => {
    navigate('/ProductDetailsNormal');
  }
  return (
    <div className="product-item text-start">
      <div className="product-thumb">
        <a className="d-block" href="product-details.html">
          <img
            src={shop4}
            width="370"
            height="450"
            alt={product.name}
          />
        </a>
        <span className="flag-new">new</span>

        <div className="product-action">
          <button
            type="button"
            className="product-action-btn action-btn-quick-view justify-content-center"
            data-bs-toggle="modal"
            data-bs-target="#action-QuickViewModal"
          >
            <CgArrowsExpandRight style={{marginRight: '10px'}}/>
          </button>

          <button
            type="button"
            className="product-action-btn action-btn-cart"
            data-bs-toggle="modal"
            data-bs-target="#action-CartAddModal"
            onClick={addToCart}
          >
            <span>Add to cart</span>
          </button>

          <button
            type="button"
            className="product-action-btn action-btn-wishlist"
            data-bs-toggle="modal"
            data-bs-target="#action-WishlistModal"
          >
            <FaRegHeart className="me-5"/>
          </button>
        </div>
      </div>

      <div className="product-info">
        <div className="product-rating">
          <div className="rating">
            <FaRegStar />
            <FaRegStar />
            <FaRegStar />
            <FaRegStar />
            <FaStarHalfAlt />
          </div>
          <div className="reviews">150 reviews</div>
        </div>

        <h4 className="title">
          <a href="product-details.html" className="text-decoration-none">
            {product.name}
          </a>
        </h4>

        <div className="prices">
          <span className="price">${product.price}</span>
          <span className="price-old">300.00</span>
        </div>
      </div>

      <div className="product-action-bottom">
        <button type="button" className="product-action-btn action-btn-quick-view">
          <i className="fa fa-expand"></i>
        </button>
        <button type="button" className="product-action-btn action-btn-wishlist">
          <FaRegHeart />
        </button>
        <button type="button" className="product-action-btn action-btn-cart">
          <span>Add to cart</span>
        </button>
      </div>
    </div>
  );
};

export default ProductItem;