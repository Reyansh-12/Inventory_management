import React from "react";
import { useNavigate } from "react-router-dom";
import { CgArrowsExpandRight } from "react-icons/cg";
import { FaRegHeart, FaRegStar, FaStarHalfAlt } from "react-icons/fa";
import shop4 from "../../assets/images/shop/category/4.webp";
import { Link } from "react-router-dom";
import { ToastContainer, toast } from 'react-toastify';
 
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
    <div className="product-item text-start">
      <div className="product-thumb">
        <Link to="/ProductDetailsNormal">
          <img src={product?.image ? product.image : shop4} width="370" height="450" alt={product?.name || 'product'} />
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
          <span className="price-old">300.00</span>
        </div>
      </div>
    </div>
  );
};

export default ProductItem;
