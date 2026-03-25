import React, { useState, useEffect } from "react";
import { useNavigate } from "react-router-dom";
import { FaMoneyBillWave, FaGlobe, FaMobileAlt, FaQrcode, FaUniversity, FaChevronLeft } from "react-icons/fa";
import Swal from "sweetalert2";
import Stepper from "../../components/Stepper";

const Payment = () => {
  const navigate = useNavigate();
  const [method, setMethod] = useState("COD");
  const [onlineType, setOnlineType] = useState(""); 
  const [total, setTotal] = useState(0);

  useEffect(() => {
    const cart = JSON.parse(localStorage.getItem("cart")) || [];
    const subtotal = cart.reduce((acc, item) => acc + item.price * (item.qty || 1), 0);
    setTotal(subtotal);
  }, []);

  const handleFinalOrder = () => {
    Swal.fire('Order Placed!', `Payment via ${method} ${onlineType}`, 'success')
      .then(() => {
        localStorage.removeItem("cart");
        navigate("/");
      });
  };

  return (
    <div className="container" style={{ maxWidth: "800px" }}>
        {/* <div>
    <Stepper currentStep={3} /> 
  </div> */}
      <button className="btn btn-link text-dark p-0 mb-4 text-decoration-none" onClick={() => navigate(-1)}>
        <FaChevronLeft /> Back to Address
      </button>
      
      <h3 className="fw-bold mb-4 text-center">Payment Method</h3>
      
      <div className="row g-4">
        <div className="col-12">
          <div className={`p-4 rounded-4 border-2 cursor-pointer d-flex align-items-center ${method === 'COD' ? 'border-dark bg-white shadow-sm' : 'border-light bg-light'}`}
            style={{borderStyle: 'solid'}} onClick={() => {setMethod('COD'); setOnlineType('')}}>
            <FaMoneyBillWave className="fs-3 me-3 text-success" />
            <div className="flex-grow-1"><h6 className="m-0 fw-bold">Cash on Delivery</h6></div>
            <input type="radio" checked={method === 'COD'} readOnly />
          </div>
        </div>

        <div className="col-12">
          <div className={`p-4 rounded-4 border-2 ${method === 'Online' ? 'border-dark bg-white shadow-sm' : 'border-light bg-light'}`}
            style={{borderStyle: 'solid'}} onClick={() => setMethod('Online')}>
            <div className="d-flex align-items-center mb-3">
               <FaGlobe className="fs-3 me-3 text-primary" />
               <h6 className="m-0 fw-bold flex-grow-1">Online Payment</h6>
               <input type="radio" checked={method === 'Online'} readOnly />
            </div>

            {method === 'Online' && (
              <div className="row g-2 mt-2 border-top pt-3">
                <div className="col-md-4">
                  <button onClick={() => setOnlineType('UPI')} className={`btn w-100 p-3 rounded-3 border ${onlineType === 'UPI' ? 'btn-dark' : 'btn-outline-dark'}`}>
                    <FaMobileAlt className="me-2"/> UPI
                  </button>
                </div>
                <div className="col-md-4">
                  <button onClick={() => setOnlineType('QR')} className={`btn w-100 p-3 rounded-3 border ${onlineType === 'QR' ? 'btn-dark' : 'btn-outline-dark'}`}>
                    <FaQrcode className="me-2"/> QR Code
                  </button>
                </div>
                <div className="col-md-4">
                  <button onClick={() => setOnlineType('NetBanking')} className={`btn w-100 p-3 rounded-3 border ${onlineType === 'NetBanking' ? 'btn-dark' : 'btn-outline-dark'}`}>
                    <FaUniversity className="me-2"/> Net Banking
                  </button>
                </div>
              </div>
            )}
          </div>
        </div>
      </div>

      <div className="card mt-5 p-4 border-0 shadow-sm rounded-4 text-center bg-dark text-white">
          <p className="mb-1 opacity-75">Payable Amount</p>
          <h2 className="fw-bold">₹{total}</h2>
          <button className="btn btn-light w-100 py-3 rounded-pill fw-bold mt-3" onClick={handleFinalOrder}>
             CONFIRM & PAY NOW
          </button>
      </div>
    </div>
  );
};

export default Payment;