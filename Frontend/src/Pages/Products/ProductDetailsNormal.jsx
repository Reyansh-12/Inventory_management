import React, { useState, useEffect } from "react";
import { useParams, NavLink, useNavigate } from "react-router-dom";
import Footer from "../../components/Footer";
import shop2 from "../../assets/images/shop/product-details/2.webp";
import "bootstrap/dist/css/bootstrap.min.css";
import "../../assets/styles/plugins/cartDrawer.css";
import { toast } from "react-toastify";
import { FaStar, FaRegStar, FaChevronLeft, FaTimes } from "react-icons/fa";
import { TfiArrowCircleRight } from "react-icons/tfi";

const ProductDetailsNormal = () => {
  const { id } = useParams();
  const navigate = useNavigate();
  const [product, setProduct] = useState(null);
  const [reviews, setReviews] = useState([]);
  const [qty, setQty] = useState(1);
  const [showReviewForm, setShowReviewForm] = useState(false);

  const [userRating, setUserRating] = useState(0);
  const [hover, setHover] = useState(0);
  const [reviewTitle, setReviewTitle] = useState("");
  const [reviewComment, setReviewComment] = useState("");
  const [isSubmitting, setIsSubmitting] = useState(false);

  useEffect(() => {
    fetch(`http://localhost/Inventory_management/Backend/src/Pages/APIs/productDetailsAPI.php?id=${id}`)
      .then((res) => res.json())
      .then((data) => setProduct(data))
      .catch((err) => console.error(err));

    fetch(`http://localhost/Inventory_management/Backend/src/Pages/APIs/fetchReviewsAPI.php?product_id=${id}`)
      .then((res) => res.json())
      .then((data) => {
        if (Array.isArray(data)) setReviews(data);
        else setReviews([]);
      })
      .catch((err) => console.error("Review fetch error:", err));
  }, [id]);

  const avgRating = reviews.length > 0
    ? (reviews.reduce((acc, curr) => acc + parseInt(curr.rating), 0) / reviews.length).toFixed(1)
    : 0;

  const checkLogin = (actionName) => {
    const user = localStorage.getItem("user");
    if (!user) {
      toast.warning(`Please login to ${actionName}`);
      return false;
    }
    return true;
  };

  const increment = () => setQty((q) => q + 1);
  const decrement = () => setQty((q) => Math.max(1, q - 1));

  const handlePurchase = (targetPath) => {
    const isBuyNow = targetPath === "/checkout";

    if (!checkLogin(isBuyNow ? "buy now" : "add items to cart")) return;

    const productToSave = {
      ...product,
      image: product.image || shop2,
      qty: qty
    };

    if (isBuyNow) {
      localStorage.removeItem("buyNowItem");
      localStorage.setItem("buyNowItem", JSON.stringify([productToSave]));

      toast.success("Proceeding to checkout...");
      setTimeout(() => {
        navigate("/checkout");
      }, 400); 
    } else {
      const existingCart = JSON.parse(localStorage.getItem("cart")) || [];
      const existingItemIndex = existingCart.findIndex(item => item.id === product.id);

      if (existingItemIndex > -1) {
        existingCart[existingItemIndex].qty += qty;
      } else {
        existingCart.push(productToSave);
      }

      localStorage.setItem("cart", JSON.stringify(existingCart));
      window.dispatchEvent(new Event("cartUpdated"));
      toast.success("Added to cart!");
    }
  };

  const handleReviewClick = async () => {
    if (!checkLogin("write a review")) return;
    const user = JSON.parse(localStorage.getItem("user"));
    
    try {
      const response = await fetch(
        `http://localhost/Inventory_management/Backend/src/Pages/APIs/checkPurchaseAPI.php?email=${encodeURIComponent(user.email)}&product=${encodeURIComponent(product.name)}`
      );
      const data = await response.json();
      
      if (data.hasBought) {
        setShowReviewForm(true);
      } else {
        toast.info("Reviews are only allowed for orders that are successfully DELIVERED.");
      }
    } catch (err) { 
      toast.error("Security check failed. Please try again later."); 
    }
  };

  const submitReview = async () => {
    if (userRating === 0) return toast.error("Please select a rating!");
    if (!reviewTitle.trim() || !reviewComment.trim()) return toast.error("Please fill all fields!");

    const userStr = localStorage.getItem("user");
    if (!userStr) return toast.error("User session not found. Please login again.");

    const user = JSON.parse(userStr);
    setIsSubmitting(true);

    const reviewData = {
      product_id: id,
      customer_email: user.email,
      customer_name: user.name,
      rating: userRating,
      title: reviewTitle,
      comment: reviewComment
    };

    try {
      const res = await fetch("http://localhost/Inventory_management/Backend/src/Pages/APIs/submitReviewAPI.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(reviewData)
      });

      const result = await res.json();

      if (result.success) {
        toast.success("Review submitted!");
        setShowReviewForm(false);
        const newReview = { ...reviewData, created_at: "Just now" };
        setReviews([newReview, ...reviews]);
        setUserRating(0);
        setReviewTitle("");
        setReviewComment("");
      } else {
        toast.error(result.message || "Failed to submit review.");
      }
    } catch (err) {
      toast.error("Network error. Please try again.");
    } finally {
      setIsSubmitting(false);
    }
  };

  if (!product) return <h3 className="text-center mt-5">Loading...</h3>;

  return (
    <main style={{ background: "#F9F8F6", position: 'relative' }}>
      <div className="container pt-4" style={{ marginTop: '100px' }}>
        <button onClick={() => navigate(-1)} className="btn d-flex align-items-center gap-2 text-dark fw-bold border-0 p-0" style={{ letterSpacing: 'initial' }}>
          <FaChevronLeft /> Back to Products
        </button>
      </div>

      <section style={{ padding: "40px 0 80px 0" }}>
        <div className="container">
          <div className="row align-items-center">
            <div className="col-lg-6 text-center border border-0 border-end">
              <img src={product.image || shop2} className="img-fluid rounded-5 shadow ps-5 pe-5" style={{ maxHeight: '400px' }} alt={product.name} />
            </div>
            <div className="col-lg-6 ps-lg-5">
              <h1 className="fw-bold text-start ms-5" style={{ fontSize: '2.5rem' }}>{product.name}</h1>
              <div className="d-flex align-items-center mb-3 ms-5">
                <div className="text-warning">
                  {[...Array(5)].map((_, i) => (
                    i < Math.floor(avgRating) ? <FaStar key={i} style={{ color: 'rgb(232, 90, 138)' }} /> : <FaRegStar key={i} style={{ color: 'rgb(232, 90, 138)' }} />
                  ))}
                </div>
                <span className="ms-2 text-muted">({reviews.length} Reviews)</span>
              </div>
              <p className="text-muted text-start ms-5">{product.description || "Premium formula for your skin health."}</p>
              <h2 className="text-start ms-5" style={{ color: 'rgba(227, 39, 95, 1)', fontWeight: '700', fontFamily: 'none' }}>₹{product.price}</h2>

              <div className="quantityBox d-flex align-items-center mb-4 mt-4 ms-lg-5 ms-0 justify-content-start">
                <div className="d-flex align-items-center border border-danger rounded-pill bg-white shadow-sm px-2">
                  <button
                    className="btn border-0 d-flex align-items-center justify-content-center"
                    onClick={decrement}
                    style={{ width: '40px', height: '40px', fontSize: '1.5rem', color: '#e85a8a' }}
                  >
                    <strong>-</strong>
                  </button>

                  <span className="mx-3 fw-bold fs-5" style={{ minWidth: '20px', textAlign: 'center' }}>
                    {qty}
                  </span>

                  <button
                    className="btn border-0 d-flex align-items-center justify-content-center"
                    onClick={increment}
                    style={{ width: '40px', height: '40px', fontSize: '1.2rem', color: '#e85a8a' }}
                  >
                    <strong>+</strong>
                  </button>
                </div>
              </div>

              <div className="d-grid gap-3 ms-5 d-flex">
                <div className="col-lg-6">
                  <button
                    className="btn w-100 text-white fw-bold rounded-4"
                    style={{ background: '#e85a8a', letterSpacing: 'initial' }}
                    onClick={() => handlePurchase()}
                  >
                    Add To Cart
                  </button>
                </div>
                <div className="col-lg-6">
                  <button
                    className="btn w-100 text-white fw-bold rounded-4"
                    style={{ background: '#333', letterSpacing: 'initial' }}
                    onClick={() => handlePurchase("/checkout")}
                  >
                    Buy Now
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <section className="py-5 bg-white border-top">
        <div className="container">
          <div className="row g-5">
            <div className="col-lg-4">
              <h3 className="fw-bold mb-4">Customer Reviews</h3>
              <div className="d-flex align-items-center mb-4">
                <h2 className="display-4 fw-bold me-3">{avgRating}</h2>
                <div>
                  <div className="text-warning fs-5">
                    {[...Array(5)].map((_, i) => (
                      i < Math.floor(avgRating) ? <FaStar style={{ color: 'rgb(232, 90, 138)' }} key={i} /> : <FaRegStar key={i} style={{ color: 'rgb(232, 90, 138)' }} />
                    ))}
                  </div>
                  <p className="text-muted mb-0">Based on {reviews.length} reviews</p>
                </div>
              </div>
              <button className="btn w-100 fw-bold mb-3 text-dark border-dark rounded-5" style={{ border: '1.5px dashed black', letterSpacing: 'initial' }} onClick={handleReviewClick}>
                Write a Review
              </button>
            </div>

            <div className="col-lg-8">
              <h4 className="fw-bold mb-4">Most Relevant Reviews</h4>
              {reviews.length === 0 ? (
                <p className="text-muted">No reviews yet. Be the first to review!</p>
              ) : (
                <>
                  {reviews.slice(0, 2).map((rev, index) => (
                    <div key={index} className="pb-4 mb-4 border-bottom">
                      <div className="d-flex justify-content-between">
                        <div className="d-flex align-items-center">
                          <div className="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center me-3" style={{ width: '45px', height: '45px' }}>
                            {rev.customer_name?.charAt(0).toUpperCase() || 'U'}
                          </div>
                          <div>
                            <h6 className="mb-0 fw-bold">{rev.customer_name}</h6>
                            <div className="text-warning small">
                              {[...Array(5)].map((_, i) => (
                                i < rev.rating ? <FaStar key={i} style={{ color: 'rgb(232, 90, 138)' }} /> : <FaRegStar key={i} style={{ color: 'rgb(232, 90, 138)' }} />
                              ))}
                            </div>
                          </div>
                        </div>
                        <span className="text-muted small">
                          {rev.created_at === "Just now" ? "Just now" : new Date(rev.created_at).toLocaleDateString()}
                        </span>
                      </div>
                      <h6 className="mt-3 fw-bold text-start">{rev.title}</h6>
                      <p className="text-muted small text-start">{rev.comment}</p>
                    </div>
                  ))}

                  {reviews.length > 2 && (
                    <NavLink to={`/rating/${id}`}>
                      <button className="btn viewAll text-white fw-bold shadow-sm" style={{ background: 'rgba(227, 39, 95, 1)', borderRadius: '30px', padding: '0px 25px', letterSpacing: 'initial' }}>
                        View All Reviews ({reviews.length}) <TfiArrowCircleRight className='ms-1 fs-5' />
                      </button>
                    </NavLink>
                  )}
                </>
              )}
            </div>
          </div>
        </div>
      </section>

      {showReviewForm && (
        <div style={{ position: 'fixed', inset: 0, backgroundColor: 'rgba(0,0,0,0.6)', zIndex: 9999, display: 'flex', alignItems: 'center', justifyContent: 'center' }}>
          <div className="bg-white p-4 rounded shadow-lg w-100" style={{ maxWidth: '500px', position: 'relative' }}>
            <FaTimes onClick={() => setShowReviewForm(false)} className="position-absolute" style={{ top: 20, right: 20, cursor: 'pointer' }} />
            <h4 className="fw-bold mb-3">Write a Review</h4>
            <div className="mb-3 fs-3 text-center">
              {[1, 2, 3, 4, 5].map(s => (
                <FaStar key={s} color={s <= (hover || userRating) ? "#ffc107" : "#e4e5e9"}
                  onMouseEnter={() => setHover(s)} onMouseLeave={() => setHover(0)} onClick={() => setUserRating(s)} style={{ cursor: 'pointer' }} />
              ))}
            </div>
            <input type="text" className="form-control mb-3" placeholder="Review Title" value={reviewTitle} onChange={(e) => setReviewTitle(e.target.value)} />
            <textarea className="form-control mb-3" rows="4" placeholder="Your experience..." value={reviewComment} onChange={(e) => setReviewComment(e.target.value)}></textarea>
            <button className="btn w-100 text-white fw-bold" style={{ background: '#e85a8a', letterSpacing: 'initial' }} onClick={submitReview} disabled={isSubmitting}>
              {isSubmitting ? "Submitting..." : "Submit Review"}
            </button>
          </div>
        </div>
      )}
      <Footer />
    </main>
  );
};

export default ProductDetailsNormal;