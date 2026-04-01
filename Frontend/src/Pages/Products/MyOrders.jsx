import React, { useState, useEffect } from "react";
import axios from "axios";
import { FaBox, FaTruck, FaCheckCircle, FaCalendarAlt, FaMapMarkerAlt } from "react-icons/fa";
import { toast } from "react-toastify";

const MyOrders = () => {
  const [orders, setOrders] = useState([]);
  const [loading, setLoading] = useState(true);
  
  // LocalStorage se user nikalna (Ensure user is logged in)
  const user = JSON.parse(localStorage.getItem("user"));

  useEffect(() => {
    if (user && user.email) {
      fetchOrders();
    } else {
      setLoading(false);
      toast.warn("Please login to see your orders");
    }
  }, []);

  const fetchOrders = async () => {
    try {
      // Apne PHP file ka sahi URL yahan daalein
      const response = await axios.get(`http://localhost/your_project_folder/get_orders.php?email=${user.email}`);
      setOrders(response.data);
    } catch (error) {
      console.error("API Error:", error);
      toast.error("Failed to load orders");
    } finally {
      setLoading(false);
    }
  };

  if (loading) {
    return (
      <div className="d-flex justify-content-center align-items-center" style={{ minHeight: "80vh" }}>
        <div className="spinner-border text-primary" role="status"></div>
      </div>
    );
  }

  return (
    <div className="container" style={{ background: "#f8f9fa", minHeight: "100vh", marginTop:'120px' }}>
      <div className="d-flex justify-content-between align-items-center mb-4">
        <h3 className="fw-bold m-0 text-dark">Order History</h3>
        <span className="badge bg-secondary rounded-pill px-3 py-2">{orders.length} Items Purchased</span>
      </div>

      {orders.length === 0 ? (
        <div className="card border-0 shadow-sm p-5 text-center rounded-4">
          <FaBox size={60} className="text-muted mb-3 mx-auto" />
          <h4 className="fw-bold text-muted">No orders yet!</h4>
        </div>
      ) : (
        <div className="row g-4">
          {orders.map((order) => (
            <div key={order.id} className="col-12">
              <div className="card border-0 shadow-sm rounded-4 overflow-hidden bg-white hover-shadow transition-all">
                <div className="row g-0">
                  <div className="col-md-2 p-3 d-flex align-items-center justify-content-center bg-light">
                    <img 
                      src={`http://localhost/your_project_folder/${order.image_path}`} 
                      alt={order.product}
                      className="img-fluid rounded-3"
                      style={{ maxHeight: "120px", objectFit: "contain" }}
                      onError={(e) => { e.target.src = "https://via.placeholder.com/150?text=Product"; }}
                    />
                  </div>

                  <div className="col-md-10 p-4">
                    <div className="d-flex justify-content-between align-items-start mb-2">
                      <div>
                        <h5 className="fw-bold mb-1 text-dark">{order.product}</h5>
                        <p className="small text-muted mb-2">
                          <span className="fw-bold text-uppercase">ID: #{order.order_id}</span> | Brand: {order.brand}
                        </p>
                      </div>
                      <div className="text-end">
                        <h4 className="fw-bold m-0" style={{ color: 'rgb(232, 90, 138)' }}>₹{order.total_amount}</h4>
                        <small className="text-muted">Method: {order.payment_method}</small>
                      </div>
                    </div>

                    <div className="row align-items-center mt-3">
                      <div className="col-md-4">
                        <div className="d-flex align-items-center text-muted small">
                          <FaCalendarAlt className="me-2" />
                          <span>Ordered: {new Date(order.created).toLocaleDateString()}</span>
                        </div>
                      </div>
                      <div className="col-md-4">
                        <div className="d-flex align-items-center text-muted small">
                          <FaMapMarkerAlt className="me-2" />
                          <span className="text-truncate">{order.city} - {order.pincode || ""}</span>
                        </div>
                      </div>
                      <div className="col-md-4 text-md-end mt-2 mt-md-0">
                        <span className={`badge rounded-pill px-4 py-2 ${
                          order.status === 'Delivered' ? 'bg-success-subtle text-success border border-success' : 'bg-warning-subtle text-warning-emphasis border border-warning'
                        }`}>
                          {order.status === 'Delivered' ? <FaCheckCircle className="me-1" /> : <FaTruck className="me-1" />}
                          {order.status}
                        </span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          ))}
        </div>
      )}
    </div>
  );
};

export default MyOrders;