import React from "react";
import { useNavigate } from "react-router-dom";
import { CgArrowsExpandRight } from "react-icons/cg";
import { IoBagAddOutline } from "react-icons/io5";
import { FaRegHeart, FaRegStar, FaStarHalfAlt } from "react-icons/fa";
import shop4 from "../../assets/images/shop/category/4-removebg-preview.png";
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
    {/* <div className="product-item text-start" style={{position: 'relative'}}>
      <div className="product-thumb">
        <Link to={`/product/${product.id}`}>
          <img src={product?.image || shop4} style={{width: '100%', height: '350px', objectFit: 'contain'}} onError={(e) => (e.target.src = shop4)} />
        </Link>
        <span className="flag-new">new</span>
        <div className="product-action">
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
        </div>
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
      
    </div> */}

      <div className="">
    <div class="container d-flex justify-content-center align-items-center mt-3">
  <div class="product-card position-relative text-white">
   
    <Link to={`/product/${product.id}`}>
          <img src={product?.image || shop4} style={{width: '100%', height: '200px', objectFit: 'contain'}} onError={(e) => (e.target.src = shop4)} />
        </Link>
    <div class="card-body mt-auto card-title">
      <h4 className="fw-semibold mb-3" data-bs-toggle='tooltip' title={product?.name}>{product?.name}</h4>

      <div class="d-flex justify-content-between align-items-center">
          <h4 class="fw-bold mb-0" style={{fontSize: '20px'}}>₹ {product?.price}</h4>
          <button className="btn-like"><span className="position-absolute fs-" onClick={() => toast.success("Added to wishlist!")} style={{marginTop: '-15px', marginLeft: '-10px', fontSize: '20px'}}><FaRegHeart /></span></button>
      </div>
          <ToastContainer />

      <div className="row align-items-center mt-4">
        <button className="btn btn-light rounded-3" onClick={() => addToCart(product?.id)}>
          <span className="text-nowrap">Add to basket</span> <span className="fs-5"><IoBagAddOutline /></span>
          <ToastContainer />
        </button>
      </div>
      
    </div>

  </div>
</div>
</div>
    </>
  );
};

export default ProductItem;
