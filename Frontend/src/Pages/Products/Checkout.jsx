import React, { useState, useEffect } from "react";
import { useNavigate } from "react-router-dom";
import { toast } from "react-toastify";
import { FaTruck, FaCreditCard, FaLock, FaChevronRight } from "react-icons/fa"; // Icon change for right side
import Swal from "sweetalert2";

const Checkout = () => {
  const navigate = useNavigate();
  const [items, setItems] = useState([]);
  const [loading, setLoading] = useState(false);
  const [savedAddress, setSavedAddress] = useState(null);
const [useSaved, setUseSaved] = useState(false); 
useEffect(() => {
  const user = JSON.parse(localStorage.getItem("user"));
  
  if (user && user.id) {
      fetch(`http://localhost/Inventory_management/Backend/src/Pages/APIs/getLastAddress.php?customer_id=${user.id}`)
          .then(res => res.json())
          .then(res => {
              if(res.success) {
                  setSavedAddress(res.data);
              }
          });
  }
}, []);
  const [formData, setFormData] = useState({
    fullName: "",
    email: "",
    address: "",
    city: "",
    pincode: "",
    phone: "",
    paymentMethod: "COD"
  });

  useEffect(() => {
    const data = JSON.parse(localStorage.getItem("temp_checkout"));
    const user = JSON.parse(localStorage.getItem("user"));
    
    if (!data || data.length === 0) {
      toast.error("No items to checkout!");
      navigate("/");
    } else {
      setItems(data);
      if(user) {
        setFormData(prev => ({...prev, fullName: user.name, email: user.email}));
      }
    }
  }, [navigate]);

  const subtotal = items.reduce((acc, item) => acc + item.price * item.qty, 0);
  const shipping = 50;
  const total = subtotal + shipping;

  const handleInputChange = (e) => {
    setFormData({ ...formData, [e.target.name]: e.target.value });
  };

  const handlePlaceOrder = async (e) => {
    e.preventDefault();
    setLoading(true);
    
    const user = JSON.parse(localStorage.getItem("user"));
    if(!user) {
        setLoading(false);
        return toast.error("Please login to place order");
    }

    const orderData = {
      customer_id: user.id || 0,
      customer: formData.fullName,
      email: formData.email,
      phone: formData.phone,
      address: formData.address,
      city: formData.city,
      pincode: formData.pincode,
      items: items, 
      shipping_charge: shipping,
      payment_method: formData.paymentMethod,
      total_amount: total
    };

    try {
      const response = await fetch("http://localhost/Inventory_management/Backend/src/Pages/APIs/placeOrderAPI.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(orderData)
      });

      const text = await response.text();
      try {
        const result = JSON.parse(text);
        if (result.success) {
          Swal.fire({
            title: 'Order Placed!',
            text: `Thank you, ${formData.fullName}! Order ID: ${result.order_id}`,
            icon: 'success',
            confirmButtonColor: 'rgba(227, 39, 95, 1)',
          }).then(() => {
            localStorage.removeItem("cart");
            localStorage.removeItem("temp_checkout");
            window.dispatchEvent(new Event("cartUpdated"));
            navigate("/");
          });
        } else {
          Swal.fire('Error', result.message || 'Failed to place order', 'error');
        }
      } catch (err) {
        console.error("PHP Response was not JSON:", text);
        toast.error("Server return invalid response. Check console.");
      }
    } catch (error) {
      console.error("Fetch Error:", error);
      toast.error("Server connection failed!");
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="container" style={{ marginTop: "100px", marginBottom: "50px" }}>
      <div className="d-flex justify-content-end mb-3">
        <button 
          onClick={() => navigate(-1)} 
          className="btn d-flex align-items-center gap-2 text-dark fw-bold border-0 p-0 ps-2 pe-2"
          style={{ transition: '0.3s', letterSpacing:'initial' }}
          onMouseOver={(e) => e.target.style.color = 'rgba(227, 39, 95, 1)'}
          onMouseOut={(e) => e.target.style.color = '#000'}
        >
          Back to Products <FaChevronRight />
        </button>
      </div>

      <h2 className="fw-bold mb-4">Checkout</h2>
      <div className="row g-4">
        <div className="col-lg-7">
          <div className="card shadow-sm border-0 p-4">
            <h5 className="fw-bold mb-3"><FaTruck className="me-2"/> Shipping Address</h5>
            <form onSubmit={handlePlaceOrder}>
              <div className="row">
                <div className="col-md-6 mb-3">
                  <label className="small fw-bold">Full Name</label>
                  <input type="text" name="fullName" value={formData.fullName} className="form-control" onChange={handleInputChange} required />
                </div>
                <div className="col-md-6 mb-3">
                  <label className="small fw-bold">Email Address</label>
                  <input type="email" name="email" value={formData.email} className="form-control" onChange={handleInputChange} required />
                </div>
              </div>
              <div className="mb-3">
                <label className="small fw-bold">Detailed Address</label>
                <textarea name="address" className="form-control" rows="3" onChange={handleInputChange} required></textarea>
              </div>
              <div className="row">
                <div className="col-md-4 mb-3">
                  <label className="small fw-bold">City</label>
                  <input type="text" name="city" className="form-control" onChange={handleInputChange} required />
                </div>
                <div className="col-md-4 mb-3">
                  <label className="small fw-bold">Pincode</label>
                  <input type="text" name="pincode" className="form-control" onChange={handleInputChange} required />
                </div>
                <div className="col-md-4 mb-3">
                  <label className="small fw-bold">Phone</label>
                  <input type="number" name="phone" className="form-control" onChange={handleInputChange} required />
                </div>
              </div>
              
              <h5 className="fw-bold mt-4 mb-3"><FaCreditCard className="me-2"/> Payment Method</h5>
              <div className="form-check mb-4">
                <input className="form-check-input" type="radio" defaultChecked />
                <label className="form-check-label">Cash on Delivery (COD)</label>
              </div>
              <button type="submit" disabled={loading} className="btn btn-dark w-100 py-3 fw-bold" style={{letterSpacing: 'initial'}}>
                {loading ? "PLACING ORDER..." : `PLACE ORDER `}
              </button>
            </form>
          </div>
        </div>

        <div className="col-lg-5">
          <div className="card shadow-sm border-0 p-4 bg-light">
            <h5 className="fw-bold mb-4">Order Summary</h5>
            {items.map((item, index) => (
              <div key={index} className="d-flex align-items-center mb-3">
                <img src={item.image} className="rounded" alt="" style={{ width: "50px", height: "50px", objectFit: "cover" }} />
                <div className="ms-3 flex-grow-1">
                  <h6 className="mb-0 small fw-bold">{item.name}</h6>
                  <small>Qty: {item.qty}</small>
                </div>
                <span className="fw-bold">₹{item.price * item.qty}</span>
              </div>
            ))}
            <hr />
            <div className="d-flex justify-content-between"><span>Subtotal</span><span>₹{subtotal}</span></div>
            <div className="d-flex justify-content-between"><span>Shipping</span><span>₹{shipping}</span></div>
            <hr />
            <div className="d-flex justify-content-between"><h5 className="fw-bold">Total</h5><h5 className="fw-bold text-danger">₹{total}</h5></div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default Checkout;