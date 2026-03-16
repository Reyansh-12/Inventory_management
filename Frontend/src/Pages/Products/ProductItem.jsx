import React from "react";
import { useNavigate } from "react-router-dom";
import { FaRegHeart } from "react-icons/fa";
import shop4 from "../../assets/images/shop/category/4-removebg-preview.png";
import { Link } from "react-router-dom";
import { toast } from 'react-toastify';
import '../../assets/styles/plugins/ProductCards.css';
import { CiHeart } from "react-icons/ci";

const ProductItem = ({ product }) => {
  const navigate = useNavigate();

  const discountPercent = product?.discount || 0;
  const originalPrice = product?.price || 0;
  const discountedPrice = originalPrice - (originalPrice * discountPercent) / 100;

  const addToWishlist = (e) => {
    e.preventDefault();
    
    const user = localStorage.getItem("user");
    if (!user) {
      toast.info("Please login to add items to wishlist");
      setTimeout(() => {
        window.location.href = "http://localhost:3000/Backend/src/Pages/Auth/signin.php";
      }, 1000);
      return;
    }

    const existingWishlist = JSON.parse(localStorage.getItem("wishlist")) || [];
    const isExist = existingWishlist.find(item => item.id === product.id);
    
    if (!isExist) {
      existingWishlist.push({
        id: product.id,
        name: product.name,
        price: Math.round(discountedPrice),
        image: product.image || shop4
      });
      localStorage.setItem("wishlist", JSON.stringify(existingWishlist));
      window.dispatchEvent(new Event("wishlistUpdated"));
      toast.success("Added to wishlist!");
    } else {
      toast.info("Item already in wishlist");
    }
  };

  return (
    <div className="product-card-wrapper">
      <div className="product-card mb-3 p-0 overflow-hidden shadow border border-1 position-relative">
        <div 
          style={{
            background: 'radial-gradient(circle,rgba(238, 174, 202, 0.2) 0%, rgba(223, 93, 232, 0.2) 100%)',
            backgroundSize: 'cover', 
            backgroundPosition: 'center'
          }} 
          className="position-relative"
        >
          {product.quantity === 0 && (
            <span className="sale-badge">SALE OUT</span>
          )}
          
          <button 
            className="wishlist-btn" 
            style={{ width: '35px', height: '35px', display: 'flex', alignItems: 'center', justifyContent: 'center' }} 
            onClick={addToWishlist}
          >
            <FaRegHeart />❤
          </button>

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
          <div className="price-row" style={{ justifyContent: 'left' }}>
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
          <div className="d-flex row justify-content-between">
            <div className="col-lg-6 col-md-6 col-sm-6"></div>
            <div className="col-lg-6 col-md-6 col-sm-6">
              <span style={{ fontSize: '12px' }}>Free Delivery</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default ProductItem;