import React, { useState, useEffect } from "react";
import { useParams, NavLink, useNavigate } from "react-router-dom"; 
import Footer from "../../components/Footer";
import shop2 from "../../assets/images/shop/product-details/2.webp";
import shop4 from "../../assets/images/shop/category/4.webp";
import "bootstrap/dist/css/bootstrap.min.css";
import "../../assets/styles/plugins/cartDrawer.css";
import { toast } from "react-toastify";
import { FaStar, FaStarHalfAlt, FaRegStar, FaChevronLeft, FaTimes } from "react-icons/fa"; 
import { RiArrowRightDoubleLine } from "react-icons/ri";
import { TfiArrowCircleRight } from "react-icons/tfi";

const ProductDetailsNormal = () => {
  const { id } = useParams();
  const navigate = useNavigate(); 
  const [product, setProduct] = useState(null);
  const [qty, setQty] = useState(1);
  const [showReviewForm, setShowReviewForm] = useState(false); 

  useEffect(() => {
    fetch(`http://localhost/Inventory_management/Backend/src/Pages/APIs/productDetailsAPI.php?id=${id}`)
      .then((res) => res.json())
      .then((data) => setProduct(data))
      .catch((err) => console.error(err));
  }, [id]);

  const checkLogin = (actionName) => {
    const user = localStorage.getItem("user");
    if (!user) {
      toast.warning(`Please login to ${actionName}`);
      setTimeout(() => {
        window.location.href = "http://localhost:3000/Backend/src/Pages/Auth/signin.php";
      }, 1500);
      return false;
    }
    return true;
  };

  const increment = () => setQty((q) => q + 1);
  const decrement = () => setQty((q) => Math.max(1, q - 1));

  const addToCart = () => {
    if (!checkLogin("add items to cart")) return;

    const existingCart = JSON.parse(localStorage.getItem("cart")) || [];
    const existingItemIndex = existingCart.findIndex(item => item.id === product.id);

    if (existingItemIndex > -1) {
      existingCart[existingItemIndex].qty += qty;
    } else {
      existingCart.push({
        id: product.id,
        name: product.name,
        price: product.price,
        image: product.image || shop2,
        qty: qty
      });
    }

    localStorage.setItem("cart", JSON.stringify(existingCart));
    window.dispatchEvent(new Event("cartUpdated"));
    toast.success("Added to cart!");
  };

  const handleBuyNow = () => {
    if (!checkLogin("buy this product")) return;
  };

  const handleReviewClick = () => {
    if (checkLogin("write a review")) {
      setShowReviewForm(true);
    }
  };

  if (!product) return <h3 className="text-center mt-5">Loading...</h3>;

  return (
    <main style={{ background: "#F9F8F6", position: 'relative' }}>
      
      <div className="container pt-4" style={{marginTop: '80px'}}>
        <button 
          onClick={() => navigate(-1)} 
          className="btn d-flex align-items-center gap-2 text-dark fw-bold border-0 p-0"
          style={{ transition: '0.3s', letterSpacing:'initial' }}
          onMouseOver={(e) => e.target.style.color = 'rgba(227, 39, 95, 1)'}
          onMouseOut={(e) => e.target.style.color = '#000'}
        >
          <FaChevronLeft /> Back to Products
        </button>
      </div>

      <section style={{ padding: "40px 0 80px 0" }}>
        <div className="container">
          <div className="row align-items-center">
            <div className="col-lg-7">
              <div className="row g-3">
                <div className="col-2 d-none d-lg-block">
                  <img src={shop4} className="thumbMini mb-2 w-100" alt="" />
                  <img src={shop4} className="thumbMini mb-2 w-100" alt="" />
                </div>
                <div className="col-lg-10">
                  <div className="product-image-box">
                    <img src={product.image || shop2} className="img-fluid w-100 shadow-sm rounded" alt={product.name} />
                    <span className="badge newBadge">NEW</span>
                  </div>
                </div>
              </div>
            </div>

            <div className="col-lg-5 ps-lg-5">
              <h1 className="productTitle" style={{fontSize: '2.5rem', fontWeight: '700'}}>{product.name}</h1>
              <div className="d-flex align-items-center mb-3">
                <div className="ratingStars text-warning">
                  <FaStar /><FaStar /><FaStar /><FaStarHalfAlt /><FaRegStar />
                </div>
                <span className="ms-2 text-muted">(150 Reviews)</span>
              </div>
              <p className="text-muted">High-performance formula designed to brighten the under-eye area.</p>
              <h2 className="productPrice" style={{color: 'rgba(227, 39, 95, 1)', fontWeight: '700'}}>₹{product.price}</h2>
              
              <div className="quantityBox d-flex align-items-center mb-4 mt-4">
                <button className="btn btn-outline-dark px-3 rounded-circle" onClick={decrement} style={{width: '40px', height: '40px', padding: '0'}}>-</button>
                <span className="mx-4 fw-bold fs-5">{qty}</span>
                <button className="btn btn-outline-dark px-3 rounded-circle" onClick={increment} style={{width: '40px', height: '40px', padding: '0'}}>+</button>
              </div>

              <div className="d-grid gap-3">
                <button className="btn addBtn py-3 text-white fw-bold" style={{background: '#000', borderRadius: '5px', letterSpacing:'initial'}} onClick={addToCart}>
                  Add To Cart
                </button>
                <button className="btn buyBtn py-3 fw-bold border-dark" style={{borderRadius: '5px', letterSpacing:'initial'}} onClick={handleBuyNow}>
                  Buy Now <RiArrowRightDoubleLine />
                </button>
              </div>
            </div>
          </div>
        </div>
      </section>

      <section className="py-5 shadow-sm" style={{ backgroundColor: '#fff', borderTop: '1px solid #eee' }}>
        <div className="container">
          <div className="row g-5">
            <div className="col-lg-4 pt-4">
              <h3 style={{ fontFamily: 'serif', fontWeight: '600', marginBottom: '20px' }}>Customer Reviews</h3>
              <div className="d-flex align-items-center mb-2">
                <h2 className="mb-0 me-3" style={{ fontSize: '3rem', fontWeight: '700' }}>4.5</h2>
                <div>
                  <div style={{ fontSize: '1.2rem', color: 'rgba(227, 39, 95, 1)' }}>
                    <FaStar /><FaStar /><FaStar /><FaStar /><FaStarHalfAlt />
                  </div>
                  <p className="text-muted mb-0">Based on 150 reviews</p>
                </div>
              </div>

              {[5, 4, 3, 2, 1].map((star) => (
                <div key={star} className="d-flex align-items-center mb-1" style={{ fontSize: '14px' }}>
                  <span style={{ width: '50px' }}>{star} Stars</span>
                  <div className="progress flex-grow-1 mx-2" style={{ height: '6px', borderRadius: '10px' }}>
                    <div className="progress-bar" style={{ width: star === 5 ? '80%' : star === 4 ? '15%' : '5%', backgroundColor: 'rgba(227, 39, 95, 1)' }}></div>
                  </div>
                  <span className="text-muted" style={{ width: '30px' }}>{star === 5 ? '120' : '20'}</span>
                </div>
              ))}

              <button 
                className="btn mt-4 w-100 fw-bold" 
                style={{ 
                  backgroundColor: 'rgba(227, 39, 95, 0.1)', 
                  color: 'rgba(227, 39, 95, 1)', 
                  border: '1.5px dashed rgba(227, 39, 95, 1)',
                  letterSpacing:'initial'
                }}
                onClick={handleReviewClick}
              >
                Write a Review
              </button>
            </div>

            <div className="col-lg-8">
              <div className="d-flex justify-content-between align-items-center mb-4 pt-4">
                <h4 style={{ fontWeight: '600' }}>Most Relevant Reviews</h4>
                <select className="form-select-sm border-0 bg-light p-2 rounded">
                  <option>Newest First</option>
                  <option>Highest Rating</option>
                </select>
              </div>

              {[1, 5].map((i) => (
                <div key={i} className="pb-4 mb-4 border-bottom">
                  <div className="d-flex justify-content-between mb-2">
                    <div className="d-flex align-items-center">
                      <div className="rounded-circle d-flex align-items-center justify-content-center text-white me-3" style={{ width: '45px', height: '45px', background: '#d4c5b9', fontWeight: '600' }}>
                        {i === 1 ? 'JD' : 'AS'}
                      </div>
                      <div>
                        <h6 className="mb-0 fw-bold">Jane Doe <span className="ms-2 badge bg-success" style={{ fontSize: '10px' }}>Verified Buyer</span></h6>
                        <div className="small" style={{ color: 'rgba(227, 39, 95, 1)' }}>
                          <FaStar /><FaStar /><FaStar /><FaStar /><FaStar />
                        </div>
                      </div>
                    </div>
                    <span className="text-muted small">Oct 12, 2025</span>
                  </div>
                  <h6 className="mt-3 fw-bold">Life Changer for Dark Circles!</h6>
                  <p className="text-muted" style={{ fontSize: '15px', lineHeight: '1.6' }}>
                    I've tried dozens of creams, but this one actually works...
                  </p>
                </div>
              ))}
              
              <NavLink to="/rating">
                <button className="btn text-white text-decoration-none fw-bold shadow-sm" style={{background:'rgba(227, 39, 95, 1)', borderRadius: '30px', padding: '10px 25px', letterSpacing: 'initial'}}>
                  View All Reviews <TfiArrowCircleRight className='ms-1 fs-5'/>
                </button>
              </NavLink>
            </div>
          </div>
        </div>
      </section>

      {showReviewForm && (
        <div style={{
          position: 'fixed', top: 0, left: 0, width: '100%', height: '100%',
          backgroundColor: 'rgba(0,0,0,0.6)', zIndex: 9999, display: 'flex',
          alignItems: 'center', justifyContent: 'center', padding: '20px'
        }}>
          <div className="bg-white p-4 rounded shadow-lg" style={{ width: '100%', maxWidth: '500px', position: 'relative' }}>
            <button 
              onClick={() => setShowReviewForm(false)}
              style={{ position: 'absolute', top: '15px', right: '15px', border: 'none', background: 'none', fontSize: '1.2rem' }}
            >
              <FaTimes className="text-muted" />
            </button>
            <h4 className="fw-bold mb-3">Write a Review</h4>
            <div className="mb-3">
              <label className="small fw-bold text-muted d-block mb-2">Rating</label>
              <div className="fs-3 text-warning">
                <FaRegStar /><FaRegStar /><FaRegStar /><FaRegStar /><FaRegStar />
              </div>
            </div>
            <div className="mb-3">
              <label className="small fw-bold text-muted">Review Title</label>
              <input type="text" className="form-control" placeholder="Example: Great Product!" />
            </div>
            <div className="mb-3">
              <label className="small fw-bold text-muted">Your Experience</label>
              <textarea className="form-control" rows="4" placeholder="What did you like or dislike?"></textarea>
            </div>
            <button 
              className="btn w-100 text-white fw-bold py-2" 
              style={{ background: 'rgba(227, 39, 95, 1)', letterSpacing:'initial' }}
              onClick={() => {
                toast.success("Review submitted for moderation!");
                setShowReviewForm(false);
              }}
            >
              Submit Review
            </button>
          </div>
        </div>
      )}

      <Footer />
    </main>
  );
};

export default ProductDetailsNormal;