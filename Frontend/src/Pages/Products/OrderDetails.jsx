import React, { useEffect } from "react";
import { useLocation, useNavigate } from "react-router-dom";
import { FaArrowLeft, FaPrint, FaCheckCircle, FaChevronRight, FaRegFileAlt, FaMapMarkerAlt, FaCircle } from "react-icons/fa";

const OrderDetails = () => {
  const location = useLocation();
  const navigate = useNavigate();
  const order = location.state?.order;

  useEffect(() => {
    window.scrollTo(0, 0);
  }, []);

  if (!order) {
    return (
      <div className="container text-center" style={{ marginTop: '150px' }}>
        <h3>Order Details Not Found</h3>
        <button className="btn btn-primary mt-3" onClick={() => navigate("/my-orders")}>Back to Orders</button>
      </div>
    );
  }

  const orderDate = new Date(order.created).toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' });
  
  const isDelivered = order.status === 'Delivered';

  return (
    <div className="container-fluid" style={{ marginTop: '100px', paddingBottom: '100px', backgroundColor: '#F1F3F6', minHeight: '100vh' }}>
      <div className="container px-0" style={{ maxWidth: '1100px', padding: '20px' }}>
        
        <div className="d-flex align-items-center p-3 mb-3 bg-white shadow-sm d-print-none">
          <button className="btn btn-link border-0 text-decoration-none p-0 d-flex align-items-center" onClick={() => navigate(-1)}>
            <FaArrowLeft className="me-2" /> 
            <span style={{ color: '#2874f0', fontWeight: '500', letterSpacing: 'initial' }}>My Orders</span>
          </button>
          <FaChevronRight className="mx-2 text-muted" size={10} />
          <span className="text-muted">ID: #{order.order_id}</span>
        </div>

        <div className="row g-3">
          <div className="col-lg-8">
            <div className="card border-0 rounded-0 shadow-sm mb-3">
              <div className="card-body p-4">
                <div className="row align-items-center">
                  <div className="col-auto">
                    <img 
                      src={order.image_path}
                      alt="product"
                      style={{ width: '80px', height: '80px', objectFit: 'contain' }}
                      onError={(e) => e.target.src = "https://via.placeholder.com/80"}
                    />
                  </div>
                  <div className="col">
                    <h6 className="fw-bold mb-1" style={{ fontSize: '16px' }}>{order.product}</h6>
                    <p className="text-muted small mb-2">Brand: {order.brand}</p>
                    <span className="fw-bold h5">₹{order.total_amount}</span>
                  </div>
                </div>

                <div className="mt-5 px-3">
                  <div className="position-relative d-flex justify-content-between">
                    
                    <div className="position-absolute" style={{ top: '13px', left: '39px', right: '54px', height: '3px', backgroundColor: '#e0e0e0', zIndex: 0 }}></div>
                    
                    <div className="position-absolute" style={{ 
                        top: '13px', 
                        left: '39px', 
                        width: isDelivered ? '84%' : '50%',
                        height: '3px', 
                        backgroundColor: '#26a541', 
                        zIndex: 1,
                        transition: 'width 0.5s ease'
                    }}></div>

                    <div className="text-center position-relative" style={{ zIndex: 2, width: '100px' }}>
                      <div className="bg-white rounded-circle d-inline-block mb-2">
                        <FaCheckCircle className="text-success" size={22} />
                      </div>
                      <p className="small fw-bold mb-0">Order Confirmed</p>
                      <p className="text-muted" style={{ fontSize: '11px' }}>{orderDate}</p>
                    </div>

                    <div className={`text-center position-relative ${!isDelivered ? 'opacity-50' : ''}`} style={{ zIndex: 2, width: '100px' }}>
                      <div className="bg-white rounded-circle d-inline-block mb-2">
                        {isDelivered ? (
                          <FaCheckCircle className="text-success" size={22} />
                        ) : (
                          <FaCircle className="text-muted" size={20} style={{ padding: '2px', border: '2px solid #fff' }} />
                        )}
                      </div>
                      <p className={`small fw-bold mb-0 ${!isDelivered ? 'text-muted' : ''}`}>Delivered</p>
                      <p className="text-muted" style={{ fontSize: '11px' }}>{isDelivered ? 'Completed' : 'Expected Soon'}</p>
                    </div>

                  </div>
                </div>

              </div>
            </div>
          </div>

          <div className="col-lg-4">
            <div className="card border-0 rounded-0 shadow-sm mb-3">
              <div className="card-body">
                <h6 className="fw-bold mb-3 d-flex align-items-center">
                  <FaMapMarkerAlt className="me-2 text-danger" /> Delivery Details
                </h6>
                <p className="mb-1 fw-bold">{order.customer_name || "Customer"}</p>
                <p className="text-muted small mb-0">{order.address} - {order.pincode}</p>
                <p className="text-muted small mb-0">{order.city}</p>
                <p className="text-muted small">{order.email || order.user_email}</p>
              </div>
            </div>

            <div className="card border-0 rounded-0 shadow-sm">
              <div className="card-body p-4">
                <h6 className="fw-bold mb-3">Price Details</h6>
                <div className="d-flex justify-content-between mb-2 small text-muted">
                  <span>Price (1 Item)</span>
                  <span>₹{order.total_amount}</span>
                </div>
                <div className="d-flex justify-content-between mb-2 text-success small">
                  <span>Delivery Charges</span>
                  <span>FREE</span>
                </div>
                <hr />
                <div className="d-flex justify-content-between mb-4">
                  <span className="fw-bold">Total Amount</span>
                  <span className="fw-bold text-dark h5">₹{order.total_amount}</span>
                </div>
                <div className="bg-light p-2 text-center rounded mb-3 small">
                   Payment Status: <strong>{order.payment_method}</strong>
                </div>
                
                <button className="btn btn-outline-dark w-100 rounded-pill d-print-none mb-2 shadow-sm" onClick={() => window.print()} style={{ letterSpacing: 'initial' }}>
                   <FaPrint className="me-2" /> Print Bill
                </button>
                <button className="btn btn-dark w-100 rounded-pill d-print-none shadow-sm" onClick={() => alert("Invoice downloaded!")} style={{ letterSpacing: 'initial' }}>
                  <FaRegFileAlt className="me-2" /> Download Invoice
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default OrderDetails;