import React, { useState, useEffect } from "react";
import axios from "axios"; // API call ke liye
import { FaBoxOpen, FaChevronRight } from "react-icons/fa";
import { useNavigate } from "react-router-dom";

const MyOrders = () => {
  const [orders, setOrders] = useState([]);
  const navigate = useNavigate();
  const user = JSON.parse(localStorage.getItem("user"));

  useEffect(() => {
    if (user) {
      // Laravel API se user ke orders mangwayein
      axios.get(`http://localhost:8000/api/orders/${user.id}`)
        .then(res => setOrders(res.data))
        .catch(err => console.log(err));
    }
  }, []);

  return (
    <div className="container py-5 mt-5">
      <h3 className="fw-bold mb-4">My Orders History</h3>
      {orders.length === 0 ? (
        <div className="text-center py-5 shadow-sm rounded-4 bg-light">
          <FaBoxOpen size={60} className="text-muted mb-3" />
          <p className="text-muted">Aapne abhi tak koi order nahi kiya hai.</p>
        </div>
      ) : (
        <div className="row g-3">
          {orders.map((order) => (
            <div key={order.id} className="col-12">
              <div className="card border-0 shadow-sm p-3 rounded-4" style={{ cursor: 'pointer' }}>
                <div className="d-flex justify-content-between align-items-center">
                  <div>
                    <h6 className="fw-bold m-0 text-uppercase">Order #{order.id}</h6>
                    <small className="text-muted">Placed on: {new Date(order.created_at).toLocaleDateString()}</small>
                  </div>
                  <div className="text-end">
                    <span className={`badge rounded-pill mb-1 ${order.status === 'Delivered' ? 'bg-success' : 'bg-warning text-dark'}`}>
                      {order.status}
                    </span>
                    <p className="fw-bold m-0">₹{order.total_amount}</p>
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