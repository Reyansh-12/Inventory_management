import React, { useState, useEffect } from "react";
import { useNavigate } from "react-router-dom";
import { FaMoneyBillWave, FaGlobe, FaMobileAlt, FaQrcode, FaUniversity, FaChevronLeft, FaCheckCircle } from "react-icons/fa";
import Swal from "sweetalert2";

const Payment = () => {
  const navigate = useNavigate();
  const [method, setMethod] = useState("COD");
  const [onlineType, setOnlineType] = useState(""); 
  const [total, setTotal] = useState(0);
  
  const [upiId, setUpiId] = useState("");
  const [selectedBank, setSelectedBank] = useState("");

  useEffect(() => {
    const cart = JSON.parse(localStorage.getItem("cart")) || [];
    const subtotal = cart.reduce((acc, item) => acc + item.price * (item.qty || 1), 0);
    setTotal(subtotal);
  }, []);

  const handleFinalOrder = () => {
    if (method === "Online") {
      if (!onlineType) return Swal.fire("Error", "Please select an Online Payment method", "error");
    }

    Swal.fire({
      title: 'Processing Payment...',
      html: 'Please wait while we verify your transaction',
      timer: 2000,
      didOpen: () => { Swal.showLoading() }
    }).then(() => {
      Swal.fire('Order Placed!', `Successfully paid via ${onlineType || method}`, 'success')
      .then(() => {
        localStorage.removeItem("cart"); 
        navigate("/order-success");     
      });
    });
};
const handleNext = (addressData) => {
  localStorage.setItem("shippingAddress", JSON.stringify(addressData));
  navigate("/payment");
};

  return (
    <div className="container py-5" style={{ maxWidth: "800px" }}>
      <button className="btn btn-link text-dark border-0 p-0 mb-4 text-decoration-none d-flex align-items-center" onClick={() => navigate(-1)} style={{letterSpacing: 'initial'}}>
        <FaChevronLeft className="me-2" /> Back to Address
      </button>
      
      <h3 className="fw-bold mb-4">Payment Method</h3>
      
      <div className="row g-3">
        <div className="col-12">
          <div className={`p-4 rounded-4 border cursor-pointer d-flex align-items-center transition-all ${method === 'COD' ? 'border-dark bg-white shadow-sm' : 'border-light bg-light'}`}
            style={{borderWidth: '2px', cursor: 'pointer'}} onClick={() => {setMethod('COD'); setOnlineType('')}}>
            <FaMoneyBillWave className={`fs-3 me-3 ${method === 'COD' ? 'text-success' : 'text-secondary'}`} />
            <div className="flex-grow-1"><h6 className="m-0 fw-bold">Cash on Delivery</h6></div>
            <div className={`rounded-circle border d-flex align-items-center justify-content-center ${method === 'COD' ? 'bg-dark border-dark' : 'border-secondary'}`} style={{width: '20px', height: '20px'}}>
               {method === 'COD' && <div className="bg-white rounded-circle" style={{width: '8px', height: '8px'}}></div>}
            </div>
          </div>
        </div>

        <div className="col-12">
          <div className={`p-4 rounded-4 border ${method === 'Online' ? 'border-dark bg-white shadow-sm' : 'border-light bg-light'}`}
            style={{borderWidth: '2px', cursor: 'pointer'}} onClick={() => setMethod('Online')}>
            <div className="d-flex align-items-center mb-1">
               <FaGlobe className={`fs-3 me-3 ${method === 'Online' ? 'text-primary' : 'text-secondary'}`} />
               <h6 className="m-0 fw-bold flex-grow-1">Online Payment</h6>
               <div className={`rounded-circle border d-flex align-items-center justify-content-center ${method === 'Online' ? 'bg-dark border-dark' : 'border-secondary'}`} style={{width: '20px', height: '20px'}}>
                  {method === 'Online' && <div className="bg-white rounded-circle" style={{width: '8px', height: '8px'}}></div>}
               </div>
            </div>

            {method === 'Online' && (
              <div className="mt-4 border-top pt-4">
                <div className="row g-2 mb-4">
                  <div className="col-4">
                    <button onClick={(e) => {e.stopPropagation(); setOnlineType('UPI')}} className={`btn w-100 py-3 rounded-3 border ${onlineType === 'UPI' ? 'btn-dark' : 'btn-outline-secondary border-opacity-25'}`}>
                      <FaMobileAlt className="d-block mx-auto mb-1 fs-4"/> <small>UPI</small>
                    </button>
                  </div>
                  <div className="col-4">
                    <button onClick={(e) => {e.stopPropagation(); setOnlineType('QR')}} className={`btn w-100 py-3 rounded-3 border ${onlineType === 'QR' ? 'btn-dark' : 'btn-outline-secondary border-opacity-25'}`}>
                      <FaQrcode className="d-block mx-auto mb-1 fs-4"/> <small>Scan QR</small>
                    </button>
                  </div>
                  <div className="col-4">
                    <button onClick={(e) => {e.stopPropagation(); setOnlineType('NetBanking')}} className={`btn w-100 py-3 rounded-3 border ${onlineType === 'NetBanking' ? 'btn-dark' : 'btn-outline-secondary border-opacity-25'}`}>
                      <FaUniversity className="d-block mx-auto mb-1 fs-4"/> <small>Banking</small>
                    </button>
                  </div>
                </div>

                <div className="bg-light p-3 rounded-3 animate__animated animate__fadeIn">
                  {onlineType === 'UPI' && (
                    <div>
                      <label className="form-label fw-bold small text-uppercase">Enter VPA / UPI ID</label>
                      <input type="text" className="form-control form-control-lg rounded-3" placeholder="username@upi" value={upiId} onChange={(e) => setUpiId(e.target.value)} />
                      <p className="text-muted mt-2 mb-0" style={{fontSize: '12px'}}>Example: 9876543210@ybl, name@oksbi</p>
                    </div>
                  )}

                  {onlineType === 'QR' && (
                    <div className="text-center py-2">
                       <div className="bg-white d-inline-block p-3 rounded-3 shadow-sm border mb-2">
                          <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=ExamplePayment" alt="QR Code" />
                       </div>
                       <p className="fw-bold m-0 small">Scan this QR to pay ₹{total}</p>
                    </div>
                  )}

                  {onlineType === 'NetBanking' && (
                    <div>
                       <label className="form-label fw-bold small text-uppercase">Select Your Bank</label>
                       <select className="form-select form-select-lg rounded-3" value={selectedBank} onChange={(e) => setSelectedBank(e.target.value)}>
                          <option value="">-- Choose Bank --</option>
                          <option value="SBI">State Bank of India</option>
                          <option value="HDFC">HDFC Bank</option>
                          <option value="ICICI">ICICI Bank</option>
                          <option value="AXIS">Axis Bank</option>
                       </select>
                    </div>
                  )}

                  {!onlineType && <p className="text-center text-muted m-0 italic">Select a payment option above</p>}
                </div>
              </div>
            )}
          </div>
        </div>
      </div>

      <div className="mt-5 text-center">
          <div className="d-flex justify-content-between align-items-center mb-3 px-2">
             <span className="text-muted">Total Payable:</span>
             <span className="fs-4 fw-bold">₹{total}</span>
          </div>
          <button className="btn btn-dark w-100 rounded-pill fw-bold shadow-lg" onClick={handleFinalOrder} style={{letterSpacing: 'initial'}}>
             {method === 'COD' ? 'PLACE ORDER (COD)' : `PAY ₹${total} NOW`}
          </button>
          <p className="mt-3 text-muted small"><FaCheckCircle className="text-success me-1"/> 100% Secure Payments powered by Razorpay</p>
      </div>
    </div>
  );
};

export default Payment;