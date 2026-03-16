import React, { useState } from "react";
import { useNavigate } from "react-router-dom";
import { FaChevronDown, FaStar, FaArrowLeft, FaTimes, FaCamera } from "react-icons/fa";
import { toast } from "react-toastify";
import image from "../../assets/images/1256-removebg-preview.png";
import "../../assets/styles/plugins/review.css";

const ReviewPage = () => {
  const navigate = useNavigate();
  const [selectedStar, setSelectedStar] = useState(null);
  const [showModal, setShowModal] = useState(false);

  const validateAccess = (message) => {
    const user = localStorage.getItem("user");
    if (!user) {
      toast.warning(message || "Please login to continue");
      setTimeout(() => {
        window.location.href = "http://localhost:3000/Backend/src/Pages/Auth/signin.php";
      }, 1200);
      return false;
    }
    return true;
  };

  const handleAddToBag = () => {
    if (!validateAccess("Login required to add items to bag")) return;

    const cart = JSON.parse(localStorage.getItem("cart")) || [];
    cart.push({ id: "onion-shampoo", name: "Love Beauty & Planet Onion", price: 559, qty: 1, image });
    localStorage.setItem("cart", JSON.stringify(cart));
    
    window.dispatchEvent(new Event("cartUpdated"));
    toast.success("Added to bag!");
  };

  const openReviewModal = () => {
    if (validateAccess("Login required to write a review")) setShowModal(true);
  };

  const allReviews = [
    { 
      id: 1, 
      name: "Nutan Sangma", 
      date: "19 Apr 2023", 
      rating: 5, 
      title: "Work Amazing", 
      text: "This shampoo is wonderful. Helped me manage my hair.", 
      images: ["https://picsum.photos/200", "https://picsum.photos/201"], 
      verified: true 
    },
    { 
      id: 2, 
      name: "Rahul K.", 
      date: "10 May 2023", 
      rating: 4, 
      title: "Good product", 
      text: "Smells great but a bit expensive.", 
      images: ["https://picsum.photos/202"], 
      verified: true 
    },
  ];

  const filteredReviews = selectedStar ? allReviews.filter(r => r.rating === selectedStar) : allReviews;

  return (
    <div className="container review-page" style={{ marginTop: '100px', minHeight: '100vh' }}>
      <div className="mb-3">
        <button onClick={() => navigate(-1)} className="btn d-flex align-items-center gap-2 p-0 text-muted back-link shadow-none" style={{letterSpacing: 'initial'}}>
          <FaArrowLeft /> Back to Product
        </button>
      </div>

      <div className="row">
        <div className="col-lg-8">
          <div className="rating-section mb-4 d-flex justify-content-between align-items-center bg-white p-3 rounded shadow-sm">
            <div className="d-flex align-items-center">
              <div className="rating-number fw-bold fs-2 text-white bg-success px-3 py-1 rounded">4.4</div>
              <div className="ms-3">
                <div className="fw-bold">Overall Rating</div>
                <div className="text-muted small">102,508 ratings</div>
              </div>
            </div>
            <button className="btn btn-outline-danger fw-bold shadow-none" style={{letterSpacing: 'initial'}} onClick={openReviewModal}>Write Review</button>
          </div>

          <div className="filters mb-4">
            <div className="filter-buttons d-flex flex-wrap gap-2 mt-2">
              <button className={`btn btn-sm border ${!selectedStar ? 'btn-dark' : 'btn-light'}`} onClick={() => setSelectedStar(null)}>All</button>
              {[5, 4, 3, 2, 1].map((star) => (
                <button key={star} className={`btn btn-sm border ${selectedStar === star ? 'btn-dark' : 'btn-light'}`} onClick={() => setSelectedStar(star)}>
                  {star} <FaStar className="mb-1" size={12}/>
                </button>
              ))}
            </div>
          </div>

          {filteredReviews.map((review) => (
            <div className="review-card p-3 mb-3 bg-white border rounded shadow-sm" key={review.id}>
              <div className="d-flex">
                <div className="avatar bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold" style={{ width: '40px', height: '40px', minWidth: '40px' }}>{review.name[0]}</div>
                <div className="ms-3 flex-grow-1">
                  <h6 className="fw-bold mb-0">{review.name}</h6>
                  <div className="badge bg-success my-2">{review.rating} ★</div>
                  <p className="review-text text-muted mb-2">{review.text}</p>
                  
                  {review.images && review.images.length > 0 && (
                    <div className="d-flex gap-2 flex-wrap mt-2">
                      {review.images.map((img, idx) => (
                        <img key={idx} src={img} alt="review" className="rounded border" style={{ width: '60px', height: '60px', objectFit: 'cover' }} />
                      ))}
                    </div>
                  )}
                </div>
              </div>
            </div>
          ))}
        </div>

        <div className="col-lg-4">
          <div className="product-card p-3 shadow-sm border rounded bg-white sticky-top" style={{ top: '120px' }}>
            <img src={image} alt="product" className="img-fluid mb-3 mx-auto d-block" style={{ maxHeight: '200px' }} />
            
            <h6 className="fw-bold text-center mb-1">Love Beauty & Planet Onion Shampoo</h6>
            <p className="small text-muted text-center mb-3">Hair Fall Control, 400ml</p>
            
            <div className="d-flex justify-content-between align-items-center mb-3 px-2">
              <span className="fw-bold fs-5">₹559</span>
              <span className="text-muted text-decoration-line-through small">₹650</span>
            </div>

            <button className="btn btn-dark w-100 fw-bold py-2 mt-2" style={{letterSpacing: 'initial'}} onClick={handleAddToBag}>Add to Bag</button>
          </div>
        </div>
      </div>

      {showModal && (
        <div className="modal-overlay" style={{ position: 'fixed', inset: 0, background: 'rgba(0,0,0,0.5)', zIndex: 2000, display: 'flex', alignItems: 'center', justifyContent: 'center' }}>
          <div className="modal-content bg-white p-4 rounded shadow-lg" style={{ width: '90%', maxWidth: '500px' }}>
            <div className="d-flex justify-content-between mb-3">
              <h5 className="fw-bold">Write a Review</h5>
              <FaTimes className="cursor-pointer" onClick={() => setShowModal(false)} />
            </div>
            <textarea className="form-control mb-3" rows="4" placeholder="Your experience..."></textarea>
            
            <div className="mb-3 p-3 border rounded text-center border-dashed text-muted cursor-pointer">
              <FaCamera className="me-2" /> Add Photos
            </div>

            <button className="btn btn-danger w-100" style={{letterSpacing: 'initial'}} onClick={() => { setShowModal(false); toast.success("Review submitted!"); }}>Submit</button>
          </div>
        </div>
      )}
    </div>
  );
};

export default ReviewPage;