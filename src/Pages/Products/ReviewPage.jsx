import React, { useState } from "react";
import { useNavigate } from "react-router-dom";
import { FaChevronDown, FaStar, FaArrowLeft, FaTimes, FaCamera } from "react-icons/fa";
import { toast } from "react-toastify";
import image from "../../assets/images/1256-removebg-preview.png";
import "../../assets/styles/plugins/review.css";

const ReviewPage = () => {
  const navigate = useNavigate();

  // --- STATES ---
  const [selectedStar, setSelectedStar] = useState(null);
  const [showModal, setShowModal] = useState(false);
  const [cartCount, setCartCount] = useState(0);

  // Mock Data (In real app, fetch this from API)
  const allReviews = [
    { id: 1, name: "Nutan Sangma", date: "19 Apr 2023", rating: 5, title: "Work Amazing", text: "This shampoo is wonderful. Helped me manage my hair.", images: [1, 2], verified: true },
    { id: 2, name: "Rahul K.", date: "10 May 2023", rating: 4, title: "Good product", text: "Smells great but a bit expensive.", images: [], verified: true },
    { id: 3, name: "Sana M.", date: "02 June 2023", rating: 3, title: "Average", text: "Didn't see much difference in hair fall.", images: [3], verified: false },
  ];

  // --- HANDLERS ---
  const handleAddToBag = () => {
    // Cart logic (localStorage)
    const cart = JSON.parse(localStorage.getItem("cart")) || [];
    cart.push({ id: "onion-shampoo", name: "Love Beauty & Planet Onion", price: 559, qty: 1, image });
    localStorage.setItem("cart", JSON.stringify(cart));
    
    window.dispatchEvent(new Event("cartUpdated")); // Update Navbar
    toast.success("Added to bag!");
  };

  const filteredReviews = selectedStar 
    ? allReviews.filter(r => r.rating === selectedStar) 
    : allReviews;

  return (
    <div className="container review-page" style={{ marginTop: '100px', minHeight: '100vh' }}>
      
      <div className="mb-3">
        <button onClick={() => navigate(-1)} className="btn d-flex align-items-center gap-2 p-0 text-muted back-link shadow-none" style={{letterSpacing: 'initial'}}>
          <FaArrowLeft /> Back to Product
        </button>
      </div>

      <h3 className="page-title mb-4 fw-bold" style={{ fontSize: '22px' }}>
        Love Beauty & Planet Onion, Black Seed & Patchouli Hair Fall Control Sulphate Free Shampoo - Reviews
      </h3>

      <div className="row">
        <div className="col-lg-8">
          <div className="verified-box mb-4 p-2 px-3 rounded" style={{ background: '#f0f8ff', border: '1px solid #cce5ff', fontSize: '14px' }}>
            📋 Nykaa is committed to showing genuine and verified reviews.
          </div>

          <div className="rating-section mb-4 d-flex justify-content-between align-items-center bg-white p-3 rounded shadow-sm">
            <div className="d-flex align-items-center">
              <div className="rating-number fw-bold fs-2 text-white bg-success px-3 py-1 rounded">4.4</div>
              <div className="ms-3">
                <div className="fw-bold">Overall Rating</div>
                <div className="text-muted small">102,508 verified ratings</div>
              </div>
            </div>
            <button className="btn btn-outline-danger fw-bold shadow-none" style={{letterSpacing: 'initial'}} onClick={() => setShowModal(true)}>
              Write Review
            </button>
          </div>

          <div className="filters mb-4">
            <h6 className="fw-bold">Refine Reviews By</h6>
            <div className="filter-buttons d-flex flex-wrap gap-2 mt-2">
              <button className={`btn btn-sm border ${!selectedStar ? 'btn-dark' : 'btn-light'}`} onClick={() => setSelectedStar(null)}>All</button>
              {[5, 4, 3, 2, 1].map((star) => (
                <button 
                  key={star} 
                  className={`btn btn-sm border ${selectedStar === star ? 'btn-dark' : 'btn-light'}`}
                  onClick={() => setSelectedStar(star)}
                >
                  {star} <FaStar className="mb-1" size={12}/>
                </button>
              ))}
            </div>
          </div>


          {filteredReviews.length > 0 ? filteredReviews.map((review) => (
            <div className="review-card p-3 mb-3 bg-white border rounded shadow-sm" key={review.id}>
              <div className="d-flex">
                <div className="avatar bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold" style={{ width: '40px', height: '40px' }}>
                  {review.name[0]}
                </div>
                <div className="ms-3 flex-grow-1">
                  <div className="d-flex justify-content-between align-items-start">
                    <div>
                      <div className="fw-bold">{review.name}</div>
                      {review.verified && <div className="text-success small fw-bold">✓ Verified Buyer</div>}
                    </div>
                    <div className="text-muted small">{review.date}</div>
                  </div>
                  <div className="badge bg-success my-2">{review.rating} ★</div>
                  <h6 className="fw-bold text-dark">{review.title}</h6>
                  <p className="review-text text-muted mb-2">{review.text}</p>
                  <div className="review-images d-flex gap-2">
                    {review.images.map((imgId) => (
                      <img key={imgId} src={`https://picsum.photos/seed/${imgId+10}/100`} alt="review" className="rounded border" style={{ width: '60px', height: '60px', objectFit: 'cover' }} />
                    ))}
                  </div>
                </div>
              </div>
            </div>
          )) : <p className="text-center text-muted py-5">No reviews found for this rating.</p>}
        </div>

        {/* Sidebar Sticky Card */}
        <div className="col-lg-4">
          <div className="product-card p-3 shadow-sm border rounded bg-white sticky-top" style={{ top: '120px' }}>
            <img src={image} alt="product" className="img-fluid mb-3 mx-auto d-block" style={{ maxHeight: '200px' }} />
            <h6 className="fw-bold text-dark mb-2" style={{ fontSize: '14px' }}>
              Love Beauty & Planet Onion, Black Seed & Patchouli
            </h6>
            <div className="price my-2 d-flex align-items-center">
              <span className="old text-muted text-decoration-line-through me-2">₹658</span>
              <span className="new fw-bold text-danger fs-5">₹559</span>
              <span className="off ms-2 badge bg-success" style={{ fontSize: '11px' }}>15% Off</span>
            </div>
            <button className="btn btn-dark w-100 fw-bold py-2 mt-2" style={{letterSpacing: 'initial'}} onClick={handleAddToBag}>
              Add to Bag
            </button>
          </div>
        </div>
      </div>

      {showModal && (
        <div className="modal-overlay" style={{ position: 'fixed', inset: 0, background: 'rgba(0,0,0,0.5)', zIndex: 2000, display: 'flex', alignItems: 'center', justifyContent: 'center' }}>
          <div className="modal-content bg-white p-4 rounded shadow-lg" style={{ width: '90%', maxWidth: '500px' }}>
            <div className="d-flex justify-content-between align-items-center mb-3">
              <h5 className="fw-bold m-0">Write a Review</h5>
              <FaTimes className="cursor-pointer" onClick={() => setShowModal(false)} />
            </div>
            <div className="mb-3">
              <label className="small fw-bold mb-1">Select Rating</label>
              <div className="d-flex gap-2 text-warning fs-4">
                {[1,2,3,4,5].map(s => <FaStar key={s} className="cursor-pointer" />)}
              </div>
            </div>
            <input type="text" className="form-control mb-3" placeholder="Review Title" />
            <textarea className="form-control mb-3" rows="4" placeholder="Share your experience..."></textarea>
            <div className="mb-3 border rounded p-3 text-center border-dashed cursor-pointer text-muted">
              <FaCamera className="me-2" /> Add Photos
            </div>
            <button className="btn btn-danger w-100 fw-bold" style={{letterSpacing: 'initial'}} onClick={() => { setShowModal(false); toast.success("Review submitted!"); }}>
              Submit Review
            </button>
          </div>
        </div>
      )}
    </div>
  );
};

export default ReviewPage;