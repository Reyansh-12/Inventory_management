import React from "react";
import { useNavigate } from "react-router-dom";
import { FaRegHeart, FaStar, FaStarHalfAlt, FaRegStar } from "react-icons/fa"; 
import shop4 from "../../assets/images/shop/category/4-removebg-preview.png";
import { Link } from "react-router-dom";
import { toast } from 'react-toastify';
import '../../assets/styles/plugins/ProductCards.css';

const ProductItem = ({ product }) => {
  const navigate = useNavigate();

  // Price Calculation Logic
  const discountPercent = Number(product?.discount || 0);
  const originalPrice = Number(product?.price || 0);
  const discountedPrice = originalPrice - (originalPrice * discountPercent) / 100;

  // Dynamic Stars Logic
  const renderStars = (rating) => {
    const stars = [];
    const numericRating = Number(rating) || 0; // Backend se aayi rating (e.g., 4.5)
    
    for (let i = 1; i <= 5; i++) {
      if (i <= Math.floor(numericRating)) {
        // Full Star
        stars.push(<FaStar key={i} className="text-warning" />);
      } else if (i === Math.ceil(numericRating) && numericRating % 1 !== 0) {
        // Half Star
        stars.push(<FaStarHalfAlt key={i} className="text-warning" />);
      } else {
        // Empty Star
        stars.push(<FaRegStar key={i} className="text-muted opacity-50" />);
      }
    }
    return stars;
  };

  const addToWishlist = (e) => {
    e.preventDefault();
    const user = localStorage.getItem("user");
    if (!user) {
      toast.info("Please login to add items to wishlist");
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
      <div className="product-card mb-3 p-0 overflow-hidden shadow-sm border border-light position-relative bg-white rounded-4 transition-hover">
        
        {/* IMAGE SECTION */}
        <div 
          style={{
            background: 'radial-gradient(circle, rgba(238, 174, 202, 0.1) 0%, rgba(223, 93, 232, 0.05) 100%)',
            height: '200px',
            display: 'flex',
            alignItems: 'center',
            justifyContent: 'center'
          }} 
          className="position-relative overflow-hidden"
        >
          {/* Out of Stock Badge */}
          {Number(product?.quantity) <= 0 && (
            <span className="position-absolute top-0 start-0 m-2 badge bg-dark text-white shadow-sm" style={{zIndex: 2, fontSize: '10px'}}>
              OUT OF STOCK
            </span>
          )}
          
          <button 
            className="wishlist-btn shadow-sm" 
            onClick={addToWishlist}
            title="Add to Wishlist"
          >
            <FaRegHeart />
          </button>

          <Link to={`/product/${product.id}`} className="w-100 h-100 d-flex align-items-center justify-content-center p-3">
            <img
              src={product?.image || shop4}
              onError={(e) => (e.target.src = shop4)}
              alt={product?.name}
              className="product-img"
              style={{ maxWidth: '100%', maxHeight: '100%', objectFit: 'contain' }}
            />
          </Link>
        </div>

        {/* CONTENT SECTION */}
        <div className="product-body p-3">
          <h6 className="product-title mb-1 text-truncate" title={product?.name}>
            {product?.name}
          </h6>

          <div className="price-row d-flex align-items-center gap-2 mb-2">
            <span className="price fw-bold text-dark fs-5">
              ₹{Math.round(discountedPrice)}
            </span>
            {discountPercent > 0 && (
              <>
                <del className="old-price text-muted small">₹{Math.round(originalPrice)}</del>
                <span className="discount text-success fw-bold small" style={{fontSize: '11px'}}>
                  {discountPercent}% OFF
                </span>
              </>
            )}
          </div>


<div className="rating-row d-flex align-items-center mb-2">
  <div className="stars d-flex gap-1 me-2" style={{fontSize: '14px'}}>
    {renderStars(product?.rating || 0)} 
  </div>
  <span className="review-count text-muted small" style={{fontSize: '12px'}}>
    ({product?.reviews_count || 0})
  </span>
</div>

          <div className="d-flex justify-content-between align-items-center border-top pt-2 mt-1">
            <span className="text-muted" style={{ fontSize: '11px', fontWeight: '500' }}>
              <i className="fas fa-truck me-1"></i>
              {product?.shipping_cost === "0" || !product?.shipping_cost ? "Free Delivery" : "Fast Shipping"}
            </span>
            <span className="text-muted" style={{ fontSize: '11px' }}>
              Stock: {product?.quantity > 0 ? product.quantity : '0'}
            </span>
          </div>
        </div>
      </div>
    </div>
  );
};

export default ProductItem;