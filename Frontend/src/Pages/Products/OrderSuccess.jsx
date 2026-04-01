import React, { useEffect, useState } from "react";
import { useNavigate, useLocation } from "react-router-dom";
import { FaCheckCircle, FaBoxOpen, FaTruck, FaArrowRight, FaDownload } from "react-icons/fa";
import confetti from "canvas-confetti";

const OrderSuccess = () => {
  const navigate = useNavigate();
  const location = useLocation();
  const [orderId, setOrderId] = useState("");
  const [orderDate, setOrderDate] = useState("");

  useEffect(() => {
    const randomId = "ORD" + Math.floor(100000 + Math.random() * 900000);
    setOrderId(randomId);
    
    const today = new Date().toLocaleDateString('en-IN', {
      day: 'numeric', month: 'long', year: 'numeric'
    });
    setOrderDate(today);

    const duration = 3 * 1000;
    const animationEnd = Date.now() + duration;
    const defaults = { startVelocity: 30, spread: 360, ticks: 60, zIndex: 0 };

    const randomInRange = (min, max) => Math.random() * (max - min) + min;

    const interval = setInterval(function() {
      const timeLeft = animationEnd - Date.now();
      if (timeLeft <= 0) return clearInterval(interval);

      const particleCount = 50 * (timeLeft / duration);
      confetti({ ...defaults, particleCount, origin: { x: randomInRange(0.1, 0.3), y: Math.random() - 0.2 } });
      confetti({ ...defaults, particleCount, origin: { x: randomInRange(0.7, 0.9), y: Math.random() - 0.2 } });
    }, 250);

    return () => clearInterval(interval);
  }, []);

  return (
    <div className="container py-5 text-center" style={{ maxWidth: "700px" }}>
      <div className="mb-4 animate__animated animate__bounceIn">
        <FaCheckCircle className="text-success" style={{ fontSize: "100px" }} />
      </div>

      <h1 className="fw-bold mb-2">Order Placed Successfully!</h1>
      <p className="text-muted mb-4">
        Thank you for your purchase. Your order has been received and is being processed.
      </p>

      <div className="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-light text-start">
        <div className="row g-3">
          <div className="col-6">
            <small className="text-uppercase text-muted fw-bold">Order ID</small>
            <p className="fw-bold mb-0">#{orderId}</p>
          </div>
          <div className="col-6 text-end">
            <small className="text-uppercase text-muted fw-bold">Date</small>
            <p className="fw-bold mb-0">{orderDate}</p>
          </div>
          <hr className="my-3 opacity-10" />
          <div className="col-12 d-flex align-items-center">
            <div className="bg-white p-2 rounded-3 me-3">
               <FaTruck className="text-primary fs-4" />
            </div>
            <div>
              <p className="mb-0 fw-bold">Estimated Delivery</p>
              <small className="text-muted">By Thursday, 2nd April 2026</small>
            </div>
          </div>
        </div>
      </div>

      <div className="d-flex justify-content-between mb-5 px-4 position-relative">
         <div className="text-center" style={{zIndex: 2}}>
            <div className="bg-success text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2" style={{width: '35px', height: '35px'}}>1</div>
            <small className="fw-bold">Placed</small>
         </div>
         <div className="text-center" style={{zIndex: 2}}>
            <div className="bg-white border border-2 rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2" style={{width: '35px', height: '35px'}}>2</div>
            <small className="text-muted">Packed</small>
         </div>
         <div className="text-center" style={{zIndex: 2}}>
            <div className="bg-white border border-2 rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2" style={{width: '35px', height: '35px'}}>3</div>
            <small className="text-muted">Shipped</small>
         </div>
         <div className="position-absolute top-0 start-0 w-100 mt-3" style={{height: '2px', background: '#e9ecef', zIndex: 1}}></div>
      </div>

      <div className="row g-3">
        <div className="col-md-6">
          <button className="btn btn-dark w-100 rounded-pill fw-bold" onClick={() => navigate("/home")} style={{letterSpacing: 'initial'}}>
             CONTINUE SHOPPING <FaArrowRight className="ms-2" />
          </button>
        </div>
        <div className="col-md-6">
          <button className="btn btn-outline-dark w-100 rounded-pill fw-bold" style={{letterSpacing: 'initial'}}>
             DOWNLOAD INVOICE <FaDownload className="ms-2" />
          </button>
        </div>
      </div>

      <p className="mt-5 text-muted small">
        A confirmation email has been sent to your registered email address.
      </p>
    </div>
  );
};

export default OrderSuccess;