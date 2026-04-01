import React, { useState, useEffect } from "react";
import axios from "axios";
import { FaBox, FaTruck, FaCheckCircle, FaCalendarAlt, FaMapMarkerAlt, FaFilter } from "react-icons/fa";
import { toast } from "react-toastify";

const MyOrders = () => {
  const [orders, setOrders] = useState([]);
  const [filteredOrders, setFilteredOrders] = useState([]);
  const [loading, setLoading] = useState(true);
  const [selectedYear, setSelectedYear] = useState("All");

  const user = JSON.parse(localStorage.getItem("user"));

  useEffect(() => {
    if (user && (user.email || user.user_email)) {
      fetchOrders();
    } else {
      setLoading(false);
      toast.warn("Please login to see your orders");
    }
  }, []);

  const fetchOrders = async () => {
    try {
      const email = user.email || user.user_email;
      const response = await axios.get(`http://localhost/Inventory_management/Backend/src/Pages/APIs/get_orders.php?email=${email}`);
      setOrders(response.data);
      setFilteredOrders(response.data); // Initially show all
    } catch (error) {
      console.error("API Error:", error);
      toast.error("Failed to load orders");
    } finally {
      setLoading(false);
    }
  };

  // Yearly Filter Logic
  useEffect(() => {
    if (selectedYear === "All") {
      setFilteredOrders(orders);
    } else {
      const filtered = orders.filter((order) => {
        const orderYear = new Date(order.created).getFullYear().toString();
        return orderYear === selectedYear;
      });
      setFilteredOrders(filtered);
    }
  }, [selectedYear, orders]);

  // Extract unique years from orders for the dropdown
  const years = ["All", ...new Set(orders.map(order => new Date(order.created).getFullYear().toString()))].sort((a, b) => b - a);

  if (loading) {
    return (
      <div className="d-flex justify-content-center align-items-center" style={{ minHeight: "80vh" }}>
        <div className="spinner-border text-primary" role="status"></div>
      </div>
    );
  }

  return (
    <div className="container" style={{ background: "#f8f9fa", minHeight: "100vh", marginTop: '120px', paddingBottom: '50px' }}>
      
      {/* Header & Filter Section */}
      <div className="row align-items-center mb-4">
        <div className="col-md-6">
          <h3 className="fw-bold m-0 text-dark">Order History</h3>
          <p className="text-muted small">Showing {filteredOrders.length} items</p>
        </div>
        <div className="col-md-6 text-md-end">
          <div className="d-inline-flex align-items-center bg-white p-2 rounded-3 shadow-sm border">
            <FaFilter className="text-muted me-2 ms-2" />
            <select 
              className="form-select border-0 shadow-none fw-bold" 
              style={{ width: '150px', cursor: 'pointer' }}
              value={selectedYear}
              onChange={(e) => setSelectedYear(e.target.value)}
            >
              {years.map(year => (
                <option key={year} value={year}>{year === "All" ? "All Years" : year}</option>
              ))}
            </select>
          </div>
        </div>
      </div>

      {filteredOrders.length === 0 ? (
        <div className="card border-0 shadow-sm p-5 text-center rounded-4">
          <FaBox size={60} className="text-muted mb-3 mx-auto" />
          <h4 className="fw-bold text-muted">No orders found for {selectedYear}!</h4>
          <button className="btn btn-primary mt-3 rounded-pill px-4" onClick={() => setSelectedYear("All")}>Clear Filter</button>
        </div>
      ) : (
        <div className="row g-4">
          {filteredOrders.map((order) => (
            <div key={order.id} className="col-12">
              <div className="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
                <div className="row g-0">
                  <div className="col-md-2 p-3 d-flex align-items-center justify-content-center bg-light">
                    <img 
                      src={`http://localhost/Inventory_management/Backend/assets/uploads/${order.image_path.split(',')[0]}`} // Handling multiple images by taking first one
                      alt="product"
                      className="img-fluid rounded-3"
                      style={{ maxHeight: "120px", objectFit: "contain" }}
                      onError={(e) => { e.target.src = "https://via.placeholder.com/150?text=Product"; }}
                    />
                  </div>

                  <div className="col-md-10 p-4">
                    <div className="d-flex justify-content-between align-items-start mb-2">
                      <div>
                        <h5 className="fw-bold mb-1 text-dark" style={{ fontSize: '1.1rem' }}>{order.product}</h5>
                        <p className="small text-muted mb-2">
                          <span className="fw-bold text-uppercase text-primary">ID: #{order.order_id}</span> | Brand: {order.brand}
                        </p>
                      </div>
                      <div className="text-end">
                        <h4 className="fw-bold m-0" style={{ color: 'rgb(232, 90, 138)' }}>₹{order.total_amount}</h4>
                        <small className="badge bg-light text-dark border">{order.payment_method}</small>
                      </div>
                    </div>

                    <div className="row align-items-center mt-3 border-top pt-3">
                      <div className="col-md-4">
                        <div className="d-flex align-items-center text-muted small">
                          <FaCalendarAlt className="me-2" />
                          <span>Ordered: {new Date(order.created).toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' })}</span>
                        </div>
                      </div>
                      <div className="col-md-4">
                        <div className="d-flex align-items-center text-muted small">
                          <FaMapMarkerAlt className="me-2 text-danger" />
                          <span className="text-truncate">{order.city} - {order.pincode}</span>
                        </div>
                      </div>
                      <div className="col-md-4 text-md-end mt-2 mt-md-0">
                        <span className={`badge rounded-pill px-4 py-2 ${
                          order.status === 'Delivered' ? 'bg-success text-white' : 'bg-warning text-dark'
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