import React, { useState, useEffect } from "react";
import { useNavigate } from "react-router-dom";
import { FaMoneyBillWave, FaGlobe, FaMobileAlt, FaQrcode, FaUniversity, FaChevronLeft, FaCheckCircle } from "react-icons/fa";
import Swal from "sweetalert2";

const Payment = () => {
  const navigate = useNavigate();
  const [method, setMethod] = useState("COD");
  const [onlineType, setOnlineType] = useState(""); 
  const [total, setTotal] = useState(0);
  const [cartItems, setCartItems] = useState([]);
  const [user, setUser] = useState(null);
  
  const [upiId, setUpiId] = useState("");
  const [selectedBank, setSelectedBank] = useState("");

  useEffect(() => {
    const cart = JSON.parse(localStorage.getItem("cart")) || [];
    const buyNow = JSON.parse(localStorage.getItem("buyNowItem"));
    const itemsToProcess = buyNow || cart;
    
    setCartItems(itemsToProcess);
    const subtotal = itemsToProcess.reduce((acc, item) => acc + item.price * (item.qty || 1), 0);
    setTotal(subtotal);

    const userData = JSON.parse(localStorage.getItem("user"));
    setUser(userData);
  }, []);

  const handleFinalOrder = async () => {
    const currentUser = JSON.parse(localStorage.getItem("user"));
    const savedAddress = JSON.parse(localStorage.getItem("shippingAddress"));
    const buyNowItem = JSON.parse(localStorage.getItem("buyNowItem"));
    const cartItemsData = JSON.parse(localStorage.getItem("cart")) || [];
    
    const itemsToOrder = buyNowItem ? buyNowItem : cartItemsData;
  
    if (!savedAddress) {
      return Swal.fire("Error", "Please select a shipping address", "error");
    }
  
    const orderData = {
      customer_id: currentUser?.id || 0,
      customer: savedAddress.name,
      email: currentUser?.user_email || currentUser?.email || "",
      phone: savedAddress.phone,
      address: savedAddress.address,
      city: savedAddress.city,
      pincode: savedAddress.pincode,
      shipping_charge: 0,
      payment_method: onlineType || method,
      total_amount: total,
      items: itemsToOrder.map(item => ({
        id: item.id,
        name: item.name,
        qty: item.qty || 1,
        price: item.price,
        image: item.image,
        category: item.category || "General",
        brand: item.brand || "Cosmelina"
      }))
    };
  
    Swal.fire({ title: 'Processing...', didOpen: () => Swal.showLoading() });
  
    try {
      const response = await fetch("http://localhost/Inventory_management/Backend/src/Pages/APIs/placeOrderAPI.php", {
        method: "POST",
        headers: { 
          "Content-Type": "application/json" 
        },
        body: JSON.stringify(orderData)
      });
  
      const result = await response.json();
  
      if (result.success) {
        Swal.fire("Success", "Order Placed Successfully!", "success").then(() => {
          localStorage.removeItem("cart");
          localStorage.removeItem("buyNowItem");
          navigate("/order-success");
        });
      } else {
        Swal.fire("Error", result.message, "error");
      }
    } catch (error) {
      Swal.fire("Error", "Server connection failed", "error");
    }
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

                <div className="bg-light p-3 rounded-3">
                  {onlineType === 'UPI' && (
                    <div>
                      <label className="form-label fw-bold small text-uppercase">Enter VPA / UPI ID</label>
                      <input type="text" className="form-control form-control-lg rounded-3" placeholder="username@upi" value={upiId} onChange={(e) => setUpiId(e.target.value)} />
                    </div>
                  )}

                  {onlineType === 'QR' && (
                    <div className="text-center py-2">
                       <div className="bg-white d-inline-block p-3 rounded-3 shadow-sm border mb-2">
                          <img src={`https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=upi://pay?pa=YOUR_UPI@ID&am=${total}`} alt="QR Code" />
                       </div>
                       <p className="fw-bold m-0 small">Scan to pay ₹{total}</p>
                    </div>
                  )}

                  {onlineType === 'NetBanking' && (
                    <div>
                       <label className="form-label fw-bold small text-uppercase">Select Your Bank</label>
                       <select className="form-select form-select-lg rounded-3" value={selectedBank} onChange={(e) => setSelectedBank(e.target.value)}>
                          <option value="">-- Choose Bank --</option>
                          <option value="SBI">SBI</option>
                          <option value="HDFC">HDFC</option>
                       </select>
                    </div>
                  )}
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
          <p className="mt-3 text-muted small"><FaCheckCircle className="text-success me-1"/> Secure Payments powered by Razorpay</p>
      </div>
    </div>
  );
};

export default Payment;