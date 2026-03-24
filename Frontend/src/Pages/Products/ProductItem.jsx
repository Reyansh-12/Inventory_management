import React, { useState, useEffect, useRef } from "react";
import { useNavigate, Link } from "react-router-dom";
import { FaRegHeart, FaHeart, FaStar, FaStarHalfAlt, FaRegStar } from "react-icons/fa";
import { toast } from 'react-toastify';
import gsap from "gsap";
import shop4 from "../../assets/images/shop/category/4-removebg-preview.png";
import '../../assets/styles/plugins/ProductCards.css';

const ProductItem = ({ product }) => {
  const navigate = useNavigate();
  const [isWishlisted, setIsWishlisted] = useState(false);
  
  const cardRef = useRef(null);
  const contentRef = useRef(null);

  useEffect(() => {
    const checkWishlist = () => {
      const wishlist = JSON.parse(localStorage.getItem("wishlist")) || [];
      const found = wishlist.some(item => item.id === product.id);
      setIsWishlisted(found);
    };

    checkWishlist();
    window.addEventListener("wishlistUpdated", checkWishlist);
    return () => window.removeEventListener("wishlistUpdated", checkWishlist);
  }, [product.id]);

  const onMouseEnter = () => {
    gsap.to(cardRef.current, {
      y: -10,
      boxShadow: "0px 15px 30px rgba(0,0,0,0.1)",
      duration: 0.3,
      ease: "power2.out"
    });
    gsap.to(contentRef.current, {
      y: -2,
      duration: 0.3,
      ease: "power2.out"
    });
  };

  const onMouseLeave = () => {
    gsap.to(cardRef.current, {
      y: 0,
      boxShadow: "0px 4px 6px rgba(0,0,0,0.05)",
      duration: 0.3,
      ease: "power2.in"
    });
    gsap.to(contentRef.current, {
      y: 0,
      duration: 0.3,
      ease: "power2.in"
    });
  };

  const discountPercent = Number(product?.discount || 0);
  const originalPrice = Number(product?.price || 0);
  const discountedPrice = originalPrice - (originalPrice * discountPercent) / 100;

  const renderStars = (rating) => {
    const stars = [];
    const numericRating = Number(rating) || 0;
    for (let i = 1; i <= 5; i++) {
      if (i <= Math.floor(numericRating)) {
        stars.push(<FaStar key={i} className="text-warning" />);
      } else if (i === Math.ceil(numericRating) && numericRating % 1 !== 0) {
        stars.push(<FaStarHalfAlt key={i} className="text-warning" />);
      } else {
        stars.push(<FaRegStar key={i} className="text-muted opacity-50" />);
      }
    }
    return stars;
  };

  const toggleWishlist = (e) => {
    e.preventDefault();
    const user = localStorage.getItem("user");
    if (!user) {
      toast.info("Please login to add items to wishlist");
      return;
    }

    let existingWishlist = JSON.parse(localStorage.getItem("wishlist")) || [];
    const index = existingWishlist.findIndex(item => item.id === product.id);

    if (index === -1) {
      existingWishlist.push({
        id: product.id,
        name: product.name,
        price: Math.round(discountedPrice),
        image: product.image || shop4
      });
      toast.success("Added to wishlist!");
    } else {
      existingWishlist.splice(index, 1);
      toast.info("Removed from wishlist");
    }

    localStorage.setItem("wishlist", JSON.stringify(existingWishlist));
    window.dispatchEvent(new Event("wishlistUpdated"));
  };

  return (
    <div className="product-card-wrapper">
      <div 
        ref={cardRef}
        onMouseEnter={onMouseEnter}
        onMouseLeave={onMouseLeave}
        className="product-card mb-3 p-0 overflow-hidden shadow-sm m-3 border border-light position-relative bg-white rounded-4"
        style={{ cursor: 'pointer', transition: 'box-shadow 0.3s ease' }}
      >
        
        <div
          style={{
            background: 'radial-gradient(circle, rgba(238, 174, 202, 0.1) 0%, rgba(223, 93, 232, 0.05) 100%)',
            height: '200px',
            display: 'flex',
            alignItems: 'center',
            justifyContent: 'center',
            overflow: 'hidden'
          }}
          className="position-relative"
        >
          {Number(product?.quantity) <= 0 && (
            <span className="position-absolute top-0 start-0 m-2 badge bg-dark text-white shadow-sm" style={{ zIndex: 2, fontSize: '10px' }}>
              OUT OF STOCK
            </span>
          )}

          <button
            className={`wishlist-btn bg-transparent ${isWishlisted ? 'text-danger' : 'text-dark'}`}
            style={{ zIndex: 3 }}
            onClick={toggleWishlist}
            title={isWishlisted ? "Remove from Wishlist" : "Add to Wishlist"}
          >
            {isWishlisted ? <FaHeart /> : <FaRegHeart />}
          </button>

          <Link to={`/product/${product.id}`} className="w-100 h-100 d-flex align-items-center justify-content-center p-3">
            <img
              src={product?.image || shop4}
              onError={(e) => (e.target.src = shop4)}
              alt={product?.name}
              className="product-img-zoom" 
              style={{ 
                maxWidth: '100%', 
                maxHeight: '100%', 
                objectFit: 'contain',
                transition: 'transform 0.5s ease'
              }}
            />
          </Link>
        </div>

        <div className="product-body p-3" ref={contentRef}>
          <h6 className="product-title mb-1 text-truncate" title={product?.name} style={{ fontWeight: '600' }}>
            {product?.name}
          </h6>

          <div className="price-row d-flex align-items-center gap-2 mb-2">
            <span className="price fw-bold text-dark fs-5">
              ₹{Math.round(discountedPrice)}
            </span>
            {discountPercent > 0 && (
              <>
                <del className="old-price text-muted small">₹{Math.round(originalPrice)}</del>
                <span className="discount text-success fw-bold small" style={{ fontSize: '11px' }}>
                  {discountPercent}% OFF
                </span>
              </>
            )}
          </div>

          <div className="rating-row d-flex align-items-center mb-2">
            <div className="stars d-flex gap-1 me-2" style={{ fontSize: '14px' }}>
              {renderStars(product?.rating || 0)}
            </div>
            <span className="review-count text-muted small" style={{ fontSize: '12px' }}>
              ({product?.reviews_count || 0})
            </span>
          </div>

          <div className="d-flex justify-content-between align-items-center border-top pt-2 mt-1">
            <span className="text-muted" style={{ fontSize: '11px', fontWeight: '500' }}>
              <i className="fas fa-truck me-1"></i>
              {product?.shipping_cost === "0" || !product?.shipping_cost ? "Free Delivery" : "Fast Shipping"}
            </span>
            <span className="text-muted" style={{ fontSize: '11px' }}>
              Stock: <span className={product?.quantity > 0 ? "text-success" : "text-danger"}>
                {product?.quantity > 0 ? product.quantity : '0'}
              </span>
            </span>
          </div>
        </div>
      </div>
    </div>
  );
};

export default ProductItem;