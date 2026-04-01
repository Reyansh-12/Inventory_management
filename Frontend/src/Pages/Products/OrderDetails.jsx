import React from "react";
import { useLocation, useNavigate } from "react-router-dom";
import { FaArrowLeft, FaPrint, FaGem, FaCheckCircle, FaRegFileAlt } from "react-icons/fa";

const OrderDetails = () => {
  const location = useLocation();
  const navigate = useNavigate();
  const { order } = location.state || {}; // Getting data from navigation state

  if (!order) {
    return (
      <div className="container text-center mt-5 pt-5">
        <h3>Order not found!</h3>
        <button className="btn btn-primary mt-3" onClick={() => navigate("/my-orders")}>Go Back</button>
      </div>
    );
  }

  const handlePrint = () => window.print();

  return (
    <div className="container" style={{ marginTop: '120px', paddingBottom: '100px', maxWidth: '850px' }}>
      
      {/* Action Buttons */}
      <div className="d-flex justify-content-between align-items-center mb-4 d-print-none">
        <button className="btn btn-outline-dark rounded-pill px-4" onClick={() => navigate(-1)}>
          <FaArrowLeft className="me-2" /> Back
        </button>
        <button className="btn btn-dark rounded-pill px-4" onClick={handlePrint}>
          <FaPrint className="me-2" /> Print Bill
        </button>
      </div>

      {/* Invoice Card */}
      <div className="card border-0 shadow-lg rounded-4 overflow-hidden shadow-sm bg-white">
        
        {/* Invoice Header */}
        <div className="p-5 text-white d-flex justify-content-between align-items-center" 
             style={{ background: 'linear-gradient(135deg, #e85a8a 0%, #f7a8b8 100%)' }}>
          <div>
            <h1 className="fw-bold m-0" style={{ letterSpacing: '2px' }}>INVOICE</h1>
            <p className="mb-0 opacity-75">Thank you for your purchase!</p>
          </div>
          <FaGem size={50} className="opacity-50" />
        </div>

        <div className="card-body p-4 p-md-5">
          {/* Info Section */}
          <div className="row mb-5">
            <div className="col-md-6 mb-4 mb-md-0">
              <h6 className="text-muted text-uppercase fw-bold small mb-3">Order Information</h6>
              <p className="mb-1"><strong>Order ID:</strong> #{order.order_id}</p>
              <p className="mb-1"><strong>Date:</strong> {new Date(order.created).toLocaleDateString('en-GB', { day: '2-digit', month: 'long', year: 'numeric' })}</p>
              <p className="mb-1"><strong>Status:</strong> <span className="text-success fw-bold">{order.status}</span></p>
            </div>
            <div className="col-md-6 text-md-end">
              <h6 className="text-muted text-uppercase fw-bold small mb-3">Delivery Address</h6>
              <p className="mb-1 fw-bold">{order.customer_name || "Customer"}</p>
              <p className="mb-0 text-muted">{order.city} - {order.pincode}</p>
              <p className="text-muted">{order.user_email || order.email}</p>
            </div>
          </div>

          {/* Product Table */}
          <div className="table-responsive">
            <table className="table table-borderless border-top border-bottom">
              <thead>
                <tr className="text-muted small text-uppercase">
                  <th className="py-3">Product Item</th>
                  <th className="py-3 text-center">Brand</th>
                  <th className="py-3 text-end">Price</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td className="py-4">
                    <div className="d-flex align-items-center">
                      <img 
                        src={`http://localhost/Inventory_management/Backend/assets/uploads/${order.image_path.split(',')[0]}`} 
                        alt="" 
                        className="rounded-3 border me-3"
                        style={{ width: '70px', height: '70px', objectFit: 'cover' }}
                      />
                      <div>
                        <h6 className="fw-bold mb-1">{order.product}</h6>
                        <small className="text-muted">Cosmetic & Personal Care</small>
                      </div>
                    </div>
                  </td>
                  <td className="py-4 text-center text-muted">{order.brand}</td>
                  <td className="py-4 text-end fw-bold">₹{order.total_amount}</td>
                </tr>
              </tbody>
            </table>
          </div>

          {/* Calculations */}
          <div className="row justify-content-end mt-4">
            <div className="col-md-5">
              <div className="d-flex justify-content-between mb-2">
                <span className="text-muted">Subtotal:</span>
                <span>₹{order.total_amount}</span>
              </div>
              <div className="d-flex justify-content-between mb-2 text-success">
                <span>Shipping:</span>
                <span>FREE</span>
              </div>
              <hr />
              <div className="d-flex justify-content-between align-items-center bg-light p-3 rounded">
                <span className="fw-bold">Total Paid:</span>
                <h3 className="fw-bold m-0" style={{ color: '#e85a8a' }}>₹{order.total_amount}</h3>
              </div>
              <p className="small text-muted mt-2 text-end">Via {order.payment_method}</p>
            </div>
          </div>

          {/* Footer Note */}
          <div className="mt-5 pt-4 border-top text-center">
            <div className="d-flex justify-content-center align-items-center mb-2">
              <FaCheckCircle className="text-success me-2" />
              <span className="fw-bold text-uppercase small">Official Beauty Invoice</span>
            </div>
            <p className="text-muted small mb-0">For any returns or exchanges, please keep this invoice handy.</p>
            <p className="text-muted small">Support: care@cosmeticbrand.com</p>
          </div>
        </div>
      </div>
    </div>
  );
};

export default OrderDetails;