import React, { useState, useEffect } from "react";
import { useNavigate, useParams } from "react-router-dom";
import { FaStar, FaChevronLeft, FaTimes, FaFilter, FaSortAmountDown } from "react-icons/fa"; 
import { toast } from "react-toastify";
import Footer from "../../components/Footer";

const ReviewPage = () => {
  const navigate = useNavigate();
  const { id } = useParams();
  
  const [reviews, setReviews] = useState([]); 
  const [displayReviews, setDisplayReviews] = useState([]); 
  const [showReviewForm, setShowReviewForm] = useState(false);
  const [product, setProduct] = useState(null);

  const [starFilter, setStarFilter] = useState("All");
  const [sortType, setSortType] = useState("latest");
  const [keywordFilter, setKeywordFilter] = useState("All");

  useEffect(() => {
    fetch(`http://localhost/Inventory_management/Backend/src/Pages/APIs/productDetailsAPI.php?id=${id}`)
      .then((res) => res.json())
      .then((data) => setProduct(data))
      .catch((err) => console.error(err));

    const dummyData = [
      { id: 1, customer_name: "Jane Doe", rating: 5, title: "Excellent", comment: "This is a very good product!", date: "2025-10-12" },
      { id: 2, customer_name: "John Smith", rating: 2, title: "Not happy", comment: "Bad quality, broke in two days.", date: "2025-08-05" },
      { id: 3, customer_name: "Alice W.", rating: 4, title: "Latest Edition", comment: "Good experience overall.", date: "2025-11-20" },
      { id: 4, customer_name: "Bob M.", rating: 1, title: "Worst", comment: "Very bad service.", date: "2025-01-15" }
    ];
    setReviews(dummyData);
    setDisplayReviews(dummyData);
  }, [id]);

  useEffect(() => {
    let temp = [...reviews];

    if (starFilter !== "All") {
      temp = temp.filter(r => r.rating === parseInt(starFilter));
    }

    if (keywordFilter === "good") {
      temp = temp.filter(r => r.comment.toLowerCase().includes("good") || r.rating >= 4);
    } else if (keywordFilter === "bad") {
      temp = temp.filter(r => r.comment.toLowerCase().includes("bad") || r.rating <= 2);
    }

    if (sortType === "latest") {
      temp.sort((a, b) => new Date(b.date) - new Date(a.date));
    } else if (sortType === "oldest") {
      temp.sort((a, b) => new Date(a.date) - new Date(b.date));
    }

    setDisplayReviews(temp);
  }, [starFilter, sortType, keywordFilter, reviews]);

  return (
    <main style={{ background: "#F9F8F6", minHeight: '100vh' }}>
      <div className="container pt-4" style={{marginTop: '80px'}}>
        <button onClick={() => navigate(-1)} className="btn d-flex align-items-center gap-2 fw-bold border-0 p-0 pe-2 ps-2 mb-4" style={{letterSpacing: 'initial'}}>
          <FaChevronLeft /> Back to Product
        </button>

        <div className="row mb-4 g-3 bg-white p-3 rounded shadow-sm mx-0">
          <div className="col-md-3">
            <label className="small fw-bold text-muted mb-1"><FaStar className="me-1"/> Ratings</label>
            <select className="form-select border-light bg-light" onChange={(e) => setStarFilter(e.target.value)}>
              <option value="All">All Stars</option>
              <option value="5">5 Stars</option>
              <option value="4">4 Stars</option>
              <option value="3">3 Stars</option>
              <option value="2">2 Stars</option>
              <option value="1">1 Star</option>
            </select>
          </div>

          <div className="col-md-3">
            <label className="small fw-bold text-muted mb-1"><FaSortAmountDown className="me-1"/> Sort By Date</label>
            <select className="form-select border-light bg-light" onChange={(e) => setSortType(e.target.value)}>
              <option value="latest">Newest First</option>
              <option value="oldest">Oldest First</option>
            </select>
          </div>

          <div className="col-md-3">
            <label className="small fw-bold text-muted mb-1"><FaFilter className="me-1"/> Feedback Type</label>
            <select className="form-select border-light bg-light" onChange={(e) => setKeywordFilter(e.target.value)}>
              <option value="All">All Reviews</option>
              <option value="good">Good Reviews</option>
              <option value="bad">Bad Reviews</option>
            </select>
          </div>

          <div className="col-md-3 d-flex align-items-end">
            <button className="btn w-100 fw-bold text-white" 
              style={{ background: 'rgba(227, 39, 95, 1)', letterSpacing: 'initial' }}
              onClick={() => {setStarFilter("All"); setSortType("latest"); setKeywordFilter("All");}}>
              Reset Filters
            </button>
          </div>
        </div>

        <div className="row g-5 mt-5">
          <div className="col-lg-4">
            <div className="p-4 rounded shadow-sm bg-white sticky-top" style={{top: '100px'}}>
              <h3 className="fw-bold mb-3" style={{fontFamily:'serif'}}>Summary</h3>
              <div className="d-flex align-items-center mb-3">
                <h1 className="display-4 fw-bold mb-0">4.5</h1>
                <div className="ms-3 text-warning fs-5">
                  <FaStar /><FaStar /><FaStar /><FaStar /><FaStar />
                </div>
              </div>
              <p className="text-muted small">Showing {displayReviews.length} out of {reviews.length} reviews</p>
            </div>
          </div>

          <div className="col-lg-8">
            <div className="p-4 rounded shadow-sm bg-white">
              {displayReviews.length > 0 ? (
                displayReviews.map((review) => (
                  <div key={review.id} className="pb-4 mb-4 border-bottom">
                    <div className="d-flex justify-content-between">
                      <div className="d-flex align-items-center">
                        <div className="rounded-circle d-flex align-items-center justify-content-center text-white me-3" 
                          style={{ width: '45px', height: '45px', background: '#d4c5b9', fontWeight: '600' }}>
                          {review.customer_name[0]}
                        </div>
                        <div>
                          <h6 className="mb-0 fw-bold">{review.customer_name} <span className="ms-2 badge bg-success" style={{fontSize:'9px'}}>Verified Buyer</span></h6>
                          <div className="text-warning small">
                            {[...Array(review.rating)].map((_, i) => <FaStar key={i} />)}
                          </div>
                        </div>
                      </div>
                      <span className="text-muted small">{review.date}</span>
                    </div>
                    <h6 className="mt-3 fw-bold">{review.title}</h6>
                    <p className="text-muted small">{review.comment}</p>
                  </div>
                ))
              ) : (
                <div className="text-center py-5">
                  <h5 className="text-muted">No reviews match your filter.</h5>
                </div>
              )}
            </div>
          </div>
        </div>
      </div>
      <Footer />
    </main>
  );
};

export default ReviewPage;