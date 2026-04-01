import React, { useState, useEffect } from "react";
import { useNavigate } from "react-router-dom";
import { toast } from "react-toastify";
import { FaMapMarkerAlt, FaPlus, FaCheckCircle, FaChevronLeft } from "react-icons/fa";
import "../../assets/styles/plugins/checkout.css";

const Checkout = () => {
  const navigate = useNavigate();
  const [items, setItems] = useState([]);
  const [showAddressForm, setShowAddressForm] = useState(false);
  const [selectedAddress, setSelectedAddress] = useState(0);

  const [addresses, setAddresses] = useState([
    {
      id: 1,
      name: "Reyansh Raut",
      phone: "9370822659",
      address: "56, Santoshi Mata Mandir, Sai Baba Nagar, Kharbi Road",
      city: "Nagpur",
      pincode: "440034",
      type: "Home"
    }
  ]);

  const [formData, setFormData] = useState({
    name: "", phone: "", address: "", city: "", pincode: ""
  });

  useEffect(() => {
    const buyNowData = localStorage.getItem("buyNowItem");
    const cartData = localStorage.getItem("cart");

    if (buyNowData) {
      setItems(JSON.parse(buyNowData));
    } else if (cartData) {
      const parsedCart = JSON.parse(cartData);
      if (parsedCart.length > 0) {
        setItems(parsedCart);
      } else {
        navigate("/shop");
      }
    } else {
      navigate("/shop");
    }

  }, [navigate]);

  const subtotal = items.reduce((acc, item) => acc + item.price * (item.qty || 1), 0);
  const total = subtotal; 

  const handleInputChange = (e) => {
    const { name, value } = e.target;
    let filteredValue = value;

    if (name === "name" || name === "city") {
      filteredValue = value.replace(/[^a-zA-Z\s]/g, "");
    } 
    else if (name === "phone") {
      filteredValue = value.replace(/\D/g, "").slice(0, 10);
    } 
    else if (name === "pincode") {
      filteredValue = value.replace(/\D/g, "").slice(0, 6);
    }

    setFormData({ ...formData, [name]: filteredValue });
  };

  const handleAddNewAddress = (e) => {
    e.preventDefault();

    const phoneRegex = /^[6-9]\d{9}$/;
    if (!phoneRegex.test(formData.phone)) {
      toast.error("Enter a valid 10-digit Indian phone number starting with 6-9.");
      return;
    }

    if (formData.pincode.length !== 6) {
      toast.error("Pincode must be exactly 6 digits.");
      return;
    }

    const newAddr = { 
      id: Date.now(), 
      name: formData.name,
      phone: formData.phone,
      address: formData.address,
      city: formData.city,
      pincode: formData.pincode,
      type: "Office" 
    };

    setAddresses((prev) => {
        const newList = [...prev, newAddr];
        setSelectedAddress(newList.length - 1); 
        return newList;
    });

    setShowAddressForm(false);
    setFormData({ name: "", phone: "", address: "", city: "", pincode: "" }); 
    toast.success("New address added!");
  };

  return (
    <div style={{ background: "#f4f7f6", minHeight: "100vh", paddingTop: "50px", paddingBottom: "50px" }}>
      <div className="container">
        <div className="d-flex justify-content-between align-items-center mb-4">
          <button onClick={() => {
            localStorage.removeItem("buyNowItem"); 
            navigate(-1);
          }} className="btn btn-link border-0 text-dark text-decoration-none p-0" style={{letterSpacing: 'initial'}}>
            <FaChevronLeft /> Back to Cart
          </button>
          <h3 className="fw-bold m-0">Secure Checkout</h3>
          <div></div>
        </div>

        <div className="row g-4">
          <div className="col-lg-8">
            <div className="card border-0 shadow-sm rounded-4 p-4 mb-4">
              <div className="d-flex justify-content-between align-items-center mb-4">
                <h5 className="fw-bold m-0"><FaMapMarkerAlt className="me-2 text-danger" /> Shipping Address</h5>
                {!showAddressForm && (
                  <button className="btn btn-sm rounded-pill px-3" onClick={() => setShowAddressForm(true)} style={{ borderColor: 'rgb(232, 90, 138)', color: 'rgb(232, 90, 138)', letterSpacing: 'initial' }}>
                    <FaPlus className="me-1 small" /> Add New
                  </button>
                )}
              </div>

              {showAddressForm ? (
                <form onSubmit={handleAddNewAddress} className="row g-3">
                  <div className="col-md-6 mt-3">
                    <input 
                      type="text" 
                      name="name" 
                      placeholder="Full Name" 
                      className="form-control p-3 rounded-3" 
                      value={formData.name}
                      onChange={handleInputChange} 
                      required 
                    />
                  </div>
                  <div className="col-md-6 mt-3">
                    <input 
                      type="text" 
                      name="phone" 
                      placeholder="Phone Number" 
                      className="form-control p-3 rounded-3" 
                      value={formData.phone}
                      onChange={handleInputChange} 
                      required 
                    />
                  </div>
                  <div className="col-12 mt-3">
                    <input 
                      type="text" 
                      name="address"
                      placeholder="Address (House No, Building, Street)" 
                      className="form-control p-3 rounded-3" 
                      value={formData.address}
                      onChange={(e) => setFormData({ ...formData, address: e.target.value })} 
                      required 
                    />
                  </div>
                  <div className="col-md-6 mt-3">
                    <input 
                      type="text" 
                      name="city" 
                      placeholder="City" 
                      className="form-control p-3 rounded-3" 
                      value={formData.city}
                      onChange={handleInputChange} 
                      required 
                    />
                  </div>
                  <div className="col-md-6 mt-3">
                    <input 
                      type="text" 
                      name="pincode" 
                      placeholder="Pincode" 
                      className="form-control p-3 rounded-3" 
                      value={formData.pincode}
                      onChange={handleInputChange} 
                      required 
                    />
                  </div>
                  <div className="col-12">
                    <button type="submit" className="btn btn-dark px-4 mt-3 rounded-pill me-2" style={{letterSpacing:'initial'}}>Save Address</button>
                    <button type="button" className="btn btn-light px-4 mt-3 rounded-pill" onClick={() => setShowAddressForm(false)} style={{letterSpacing:'initial'}}>Cancel</button>
                  </div>
                </form>
              ) : (
                <div className="row g-3" style={{marginTop: '20px', marginBottom: '10px'}}>
                  {addresses.map((addr, index) => (
                    <div className="col-md-6" key={addr.id}>
                      <div
                        onClick={() => setSelectedAddress(index)}
                        className={`p-3 rounded-4 border-2 position-relative cursor-pointer transition-all ${selectedAddress === index ? " bg-white shadow-sm" : "border-light bg-light"}`}
                        style={{ borderStyle: "solid", transition: "0.3s", borderColor: selectedAddress === index ? 'rgb(232, 90, 138)' : '#eee' }}
                      >
                        {selectedAddress === index && <FaCheckCircle className="position-absolute top-0 end-0 m-3" style={{ color: 'rgb(232, 90, 138)' }} />}
                        {/* <span className="badge bg-secondary mb-2 px-3">{addr.type}</span> */}
                        <h6 className="fw-bold mb-1">{addr.name}</h6>
                        <p className="small text-muted mb-2">{addr.address}, {addr.city} - {addr.pincode}</p>
                        <p className="small fw-bold m-0">{addr.phone}</p>
                      </div>
                    </div>
                  ))}
                </div>
              )}
            </div>
          </div>

          <div className="col-lg-4">
            <div className="card border-0 shadow-sm rounded-4 p-4 sticky-top" style={{ top: "120px" }}>
              <h5 className="fw-bold mb-4">Order Summary</h5>

              <div className="mb-4 overflow-auto" style={{ maxHeight: "250px" }}>
                {items.map((item) => (
                  <div className="d-flex align-items-center mb-3" key={item.id}>
                    <img src={item.image} className="rounded-3" width="55" height="55" style={{ objectFit: "cover" }} alt={item.name} />
                    <div className="ms-3 flex-grow-1">
                      <p className="small fw-bold mb-0">{item.name}</p>
                      <small className="text-muted">Qty: {item.qty || 1} × ₹{item.price}</small>
                    </div>
                    <span className="small fw-bold">₹{item.price * (item.qty || 1)}</span>
                  </div>
                ))}
              </div>

              <div className="border-top pt-3">
                <div className="d-flex justify-content-between mb-2">
                  <span className="text-muted">Subtotal</span>
                  <span className="fw-bold">₹{subtotal}</span>
                </div>
                <div className="d-flex justify-content-between mb-2">
                  <span className="text-muted">Shipping</span>
                  <span className="text-success fw-bold">FREE</span>
                </div>
                <hr className="my-3" />
                <div className="d-flex justify-content-between align-items-center mb-4">
                  <h5 className="fw-bold m-0">Total Amount</h5>
                  <h4 className="fw-bold m-0" style={{ color: 'rgb(232, 90, 138)' }}>₹{total}</h4>
                </div>

                <button
                  onClick={() => {
                    const selectedAddr = addresses[selectedAddress];
                    localStorage.setItem("shippingAddress", JSON.stringify(selectedAddr));
                    navigate('/payment');
                  }}
                  className="btn w-100 text-white rounded-pill fw-bold shadow-lg"
                  style={{ background: 'rgb(232, 90, 138)', letterSpacing: 'initial' }}
                >
                  CONFIRM ORDER
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default Checkout;