import React from "react";
import { useNavigate } from "react-router-dom";
import { CgArrowsExpandRight } from "react-icons/cg";
import { IoBagAddOutline } from "react-icons/io5";
import { FaRegHeart, FaRegStar, FaStarHalfAlt } from "react-icons/fa";
import shop4 from "../../assets/images/shop/category/4-removebg-preview.png";
import { Link } from "react-router-dom";
import { ToastContainer, toast } from 'react-toastify';
import '../../assets/styles/plugins/ProductCards.css';
import image from '../../assets/images/secondSection.png';

const ProductItem = ({ product }) => {
  const navigate = useNavigate();

  const discountPercent = product?.discount;
  const originalPrice = product?.price;
  const discountedPrice = originalPrice - (originalPrice * discountPercent) / 100;

  const addToCart = (id) => {
    if (!id) {
      console.error("No product id. Can't update quantity.");
      return;
    }

    fetch("http://localhost/Inventory_management/Backend/src/Pages/APIs/updateQuantityAPI.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ id })
    })
      .then((res) => res.json())
      .then((data) => {
        toast.success("Product added to cart!");
      })
      .catch((error) => {
        toast.error("Error updating quantity.");
      });

  };

  return (
    <>
      <div className="product-card-wrapper">
        <div className="product-card mb-3 p-0 overflow-hidden shadow border border-1 position-relative">
          <div style={{background: `url(${image})`, backgroundSize: 'cover', backgroundPosition: 'center'}} className="position-relative">
          {product.quantity === 0 && (
            <span className="sale-badge">SALE OUT</span>
          )}
          {/* <button className="wishlist-btn" onClick={() => toast.success("Added to wishlist!")}>
            <FaRegHeart />
          </button> */}
          <Link to={`/product/${product.id}`}>
            <img
              src={product?.image || shop4}
              onError={(e) => (e.target.src = shop4)}
              alt={product?.name}
              className="product-img w-100" 
            />
          </Link>
          </div>
          <div className="product-body">
            <h4 className="product-title" title={product?.name}>
              {product?.name}
            </h4>
            <div className="price-row">
              <span className="price">
                ₹{Math.round(discountedPrice)}
              </span>
              {discountPercent > 0 && (
                <>
                  <del className="old-price">₹{Math.round(product.price)}</del>
                  <span className="discount">{discountPercent}% off</span>
                </>
              )}
            </div>
            <div className="rating">
              ★★★★☆ <span>(245)</span>
            </div>
          </div>
          <ToastContainer />
        </div>
      </div>
    </>
  );
};

export default ProductItem;
