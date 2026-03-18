import React, { useState, useEffect } from "react";
import { useNavigate, useParams } from "react-router-dom";
import { FaStar, FaChevronLeft, FaFilter, FaSortAmountDown, FaRegStar } from "react-icons/fa";
import Footer from "../../components/Footer";

const ReviewPage = () => {
  const navigate = useNavigate();
  const { id } = useParams();

  const [reviews, setReviews] = useState([]);
  const [displayReviews, setDisplayReviews] = useState([]);
  const [product, setProduct] = useState(null);
  const [loading, setLoading] = useState(true);

  const [starFilter, setStarFilter] = useState("All");
  const [sortType, setSortType] = useState("latest");
  const [keywordFilter, setKeywordFilter] = useState("All");

  useEffect(() => {
    if (id) {
      fetch(`http://localhost/Inventory_management/Backend/src/Pages/APIs/productDetailsAPI.php?id=${id}`)
        .then((res) => res.json())
        .then((data) => setProduct(data))
        .catch((err) => console.error("Product Error:", err));

      fetch(`http://localhost/Inventory_management/Backend/src/Pages/APIs/fetchReviewsAPI.php?product_id=${id}`)
        .then((res) => res.json())
        .then((data) => {
          if (Array.isArray(data) && data.length > 0) {
            setReviews(data);
            setDisplayReviews(data);
          } else {
            const dummyData = [
              { id: 1, customer_name: "Jane Doe", rating: 5, title: "Excellent", comment: "This is a very good product!", created_at: "2025-10-12" },
              { id: 2, customer_name: "John Smith", rating: 2, title: "Not happy", comment: "Bad quality, broke in two days.", created_at: "2025-08-05" }
            ];
            setReviews(dummyData);
            setDisplayReviews(dummyData);
          }
        })
        .catch((err) => console.error("Review Fetch Error:", err))
        .finally(() => setLoading(false));
    }
  }, [id]);

  useEffect(() => {
    let temp = [...reviews];

    if (starFilter !== "All") {
      temp = temp.filter(r => parseInt(r.rating) === parseInt(starFilter));
    }

    if (keywordFilter === "good") {
      temp = temp.filter(r => parseInt(r.rating) >= 4);
    } else if (keywordFilter === "bad") {
      temp = temp.filter(r => parseInt(r.rating) <= 2);
    }

    if (sortType === "latest") {
      temp.sort((a, b) => new Date(b.created_at || b.date) - new Date(a.created_at || a.date));
    } else if (sortType === "oldest") {
      temp.sort((a, b) => new Date(a.created_at || a.date) - new Date(b.created_at || b.date));
    }

    setDisplayReviews(temp);
  }, [starFilter, sortType, keywordFilter, reviews]);

  const avgRating = reviews.length > 0
    ? (reviews.reduce((acc, curr) => acc + parseInt(curr.rating), 0) / reviews.length).toFixed(1)
    : "0.0";

  if (loading) return <div className="text-center mt-5 py-5"><h4>Loading Reviews...</h4></div>;

  return (
    <main style={{ background: "#F9F8F6", minHeight: '100vh' }}>
      <div className="container pt-4" style={{ marginTop: '80px' }}>

        <button onClick={() => navigate(-1)} className="btn d-flex align-items-center gap-2 fw-bold border-0 p-0 mb-4 text-dark" style={{letterSpacing: 'initial'}}>
          <FaChevronLeft /> Back to {product ? product.name : "Product"}
        </button>

        <h2 className="fw-bold mb-4" style={{ fontFamily: 'serif' }}>All Customer Reviews</h2>

        <div className="row mb-4 g-3 bg-white p-3 rounded shadow-sm mx-0">
          <div className="col-md-3">
            <label className="small fw-bold text-muted mb-1"><FaStar className="me-1" /> Ratings</label>
            <select className="form-select border-light bg-light" value={starFilter} onChange={(e) => setStarFilter(e.target.value)}>
              <option value="All">All Stars</option>
              <option value="5">5 Stars</option>
              <option value="4">4 Stars</option>
              <option value="3">3 Stars</option>
              <option value="2">2 Stars</option>
              <option value="1">1 Star</option>
            </select>
          </div>

          <div className="col-md-3">
            <label className="small fw-bold text-muted mb-1"><FaSortAmountDown className="me-1" /> Sort By Date</label>
            <select className="form-select border-light bg-light" value={sortType} onChange={(e) => setSortType(e.target.value)}>
              <option value="latest">Newest First</option>
              <option value="oldest">Oldest First</option>
            </select>
          </div>

          <div className="col-md-3">
            <label className="small fw-bold text-muted mb-1"><FaFilter className="me-1" /> Feedback Type</label>
            <select className="form-select border-light bg-light" value={keywordFilter} onChange={(e) => setKeywordFilter(e.target.value)}>
              <option value="All">All Reviews</option>
              <option value="good">Good (4+ Stars)</option>
              <option value="bad">Critical (1-2 Stars)</option>
            </select>
          </div>

          <div className="col-md-3 d-flex align-items-end">
            <button className="btn w-100 fw-bold text-white shadow-sm"
              style={{ background: 'rgba(227, 39, 95, 1)', letterSpacing:'initial' }}
              onClick={() => { setStarFilter("All"); setSortType("latest"); setKeywordFilter("All"); }}>
              Reset All
            </button>
          </div>
        </div>

        <div className="row g-4 mt-2">
          <div className="col-lg-4">
            <div className="p-4 rounded shadow-sm bg-white sticky-top" style={{ top: '100px' }}>
              <h4 className="fw-bold mb-3">Rating Summary</h4>
              <div className="d-flex align-items-center mb-2">
                <h1 className="display-4 fw-bold mb-0">{avgRating}</h1>
                <div className="ms-3">
                  <div className="text-warning fs-5">
                    {[...Array(5)].map((_, i) => (
                      i < Math.floor(avgRating) ? <FaStar key={i} /> : <FaRegStar key={i} />
                    ))}
                  </div>
                  <p className="text-muted small mb-0">{reviews.length} Global Ratings</p>
                </div>
              </div>
              <hr />
              <p className="text-muted small">Showing <strong>{displayReviews.length}</strong> filtered reviews</p>
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
                          style={{ width: '45px', height: '45px', background: '#e85a8a', fontWeight: '600' }}>
                          {review.customer_name?.[0].toUpperCase()}
                        </div>
                        <div>
                          <h6 className="mb-0 fw-bold">{review.customer_name} <span className="ms-2 badge bg-success" style={{ fontSize: '9px' }}>Verified Buyer</span></h6>
                          <div className="text-warning small">
                            {[...Array(5)].map((_, i) => (
                              i < parseInt(review.rating) ? <FaStar key={i} /> : <FaRegStar key={i} />
                            ))}
                          </div>
                        </div>
                      </div>
                      <span className="text-muted small">
                        {new Date(review.created_at || review.date).toLocaleDateString()}
                      </span>
                    </div>
                    <h6 className="mt-3 fw-bold">{review.title}</h6>
                    <p className="text-muted" style={{ fontSize: '0.95rem', lineHeight: '1.6' }}>{review.comment}</p>
                  </div>
                ))
              ) : (
                <div className="text-center py-5">
                  <h5 className="text-muted">No reviews found matching your criteria.</h5>
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