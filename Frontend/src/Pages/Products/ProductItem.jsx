import React from "react";
import { useNavigate } from "react-router-dom";
import { CgArrowsExpandRight } from "react-icons/cg";
import { FaRegHeart, FaRegStar, FaStarHalfAlt } from "react-icons/fa";
import shop4 from "../../assets/images/shop/category/4.webp";
import { Link } from "react-router-dom";
import { ToastContainer, toast } from 'react-toastify';
import '../../assets/styles/plugins/ProductCards.css';
 
const ProductItem = ({ product }) => {
  const navigate = useNavigate();

  console.log("Product received in ProductItem:", product);

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
    <div className="product-item text-start" style={{position: 'relative'}}>
      <div className="product-thumb">
        <Link to={`/product/${product.id}`}>
          <img src={product?.image || shop4} style={{width: '100%', height: '350px', objectFit: 'contain'}} onError={(e) => (e.target.src = shop4)} />
        </Link>
        <span className="flag-new">new</span>
        {/* <div className="product-action">
          <button type="button" className="product-action-btn action-btn-quick-view">
            <CgArrowsExpandRight style={{ marginRight: "10px" }} />
          </button>

          <button type="button" className="product-action-btn action-btn-cart" onClick={() => addToCart(product?.id)}>
            <span>Add to cart</span>
          </button>
            <ToastContainer />

          <button type="button" className="product-action-btn action-btn-wishlist" onClick={() => toast.success("Added to wishlist!")}>
            <FaRegHeart className="me-5" />
          </button>
          <ToastContainer />
        </div> */}
      </div>

      <div className="product-info">
        <h4 className="title">
          <a href="product-details.html" className="text-decoration-none">
            {product?.name} 
          </a>
        </h4>

        <div className="prices">
          <span className="price">${product?.price}</span>
        </div>
      </div>
      
    </div>
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
  <div class="product-card position-relative text-white">
   
   

    <Link to={`/product/${product.id}`}>
          <img src={product?.image || shop4} style={{width: '100%', height: '200px', objectFit: 'contain'}} onError={(e) => (e.target.src = shop4)} />
        </Link>
    <div class="card-body mt-auto">
      <h4 class="fw-semibold">{product?.name}</h4>
      <p class="small opacity-75">
        Body mist Fragrance Spray is floral, with top notes of black tea,
        cyclamen, and monstera leaf blended with orchids.
      </p>

      <div class="d-flex justify-content-between align-items-center mt-3">
        <h4 class="fw-bold mb-0">$50</h4>

        <div className="qty-control">
          <button>-</button>
          <span>1</span>
          <button>+</button>
        </div>
      </div>

      <div className="d-flex align-items-center gap-3 mt-4">
        <button className="btn-like"><span className="position-absolute fs-4" style={{marginTop: '-15px', marginLeft: '-10px'}}>♡</span></button>
        <button className="btn btn-light rounded-pill fw-semibold">
          Add to basket
        </button>
        <button class="btn-cart"><span className="position-absolute" style={{marginTop: '-15px', marginLeft: '-10px'}}>👜</span></button>
      </div>
    </div>

  </div>
</div>
    </>
  );
};

export default ProductItem;
