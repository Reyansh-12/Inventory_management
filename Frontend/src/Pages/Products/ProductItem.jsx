import React from "react";
import { useNavigate } from "react-router-dom";
import { CgArrowsExpandRight } from "react-icons/cg";
import { FaRegHeart, FaRegStar, FaStarHalfAlt } from "react-icons/fa";
import shop4 from "../../assets/images/shop/category/4.webp";

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
        console.log("updateQuantity response:", data);
        if (data.success) {
          navigate('/ProductDetailsNormal');
        } else {
          alert(data.error || "Failed to update quantity");
        }
      })
      .catch((err) => {
        console.error("API Error:", err);
        alert("Network error when updating quantity");
      });
  };

  return (
    <div className="product-item text-start">
      <div className="product-thumb">
        <a className="d-block" href="product-details.html">
          <img src={`http://localhost/Inventory_management/Backend${product.image}`} width="370" height="450" alt={product?.name || 'product'} />
        </a>
        <span className="flag-new">new</span>

        <div className="product-action">
          <button type="button" className="product-action-btn action-btn-quick-view">
            <CgArrowsExpandRight style={{ marginRight: "10px" }} />
          </button>

          <button
            type="button"
            className="product-action-btn action-btn-cart"
            onClick={() => addToCart(product?.id)}
          >
            <span>Add to cart</span>
          </button>

          <button type="button" className="product-action-btn action-btn-wishlist">
            <FaRegHeart className="me-5" />
          </button>
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
