import React, { useState, useEffect } from "react";
import { useNavigate } from "react-router-dom";
import { toast } from "react-toastify";
import { FaMapMarkerAlt, FaPlus, FaCheckCircle, FaChevronLeft, FaShieldAlt } from "react-icons/fa";
import Swal from "sweetalert2";
import "../../assets/styles/plugins/checkout.css";
import Stepper from "../../components/Stepper";

const Checkout = () => {
  const navigate = useNavigate();
  const [items, setItems] = useState([]);
  const [loading, setLoading] = useState(false);
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
    const cartData = JSON.parse(localStorage.getItem("cart")) || [];
    if (cartData.length === 0) {
      navigate("/shop");
    } else {
      setItems(cartData);
    }
  }, [navigate]);

  const subtotal = items.reduce((acc, item) => acc + item.price * (item.qty || 1), 0);
  const shipping = 0;
  const total = subtotal + shipping;

  const handleAddNewAddress = (e) => {
    e.preventDefault();
    setAddresses([...addresses, { ...formData, id: Date.now(), type: "Office" }]);
    setShowAddressForm(false);
    toast.success("New address added!");
  };

  return (
    <div style={{ background: "#f4f7f6", minHeight: "100vh", paddingTop: "90px", paddingBottom: "50px" }}>
      <div className="container">
        {/* <div>
          <Stepper currentStep={2} />
        </div> */}
        <div className="d-flex justify-content-between align-items-center mb-4">
          <button onClick={() => navigate(-1)} className="btn btn-link border-0 text-dark text-decoration-none p-0" style={{ letterSpacing: 'initial' }}>
            <FaChevronLeft /> Back to Cart
          </button>
          <h3 className="fw-bold m-0">Secure Checkout</h3>
          <div className="text-muted small d-none d-md-block">
            {/* <FaShieldAlt className="text-success me-1" /> SSL Encrypted Payment */}
          </div>
        </div>

        <div className="row g-4">
          <div className="col-lg-8">
            <div className="card border-0 shadow-sm rounded-4 p-4 mb-4">
              <div className="d-flex justify-content-between align-items-center mb-4">
                <h5 className="fw-bold m-0"><FaMapMarkerAlt className="me-2 text-danger" /> Shipping Address</h5>
                {!showAddressForm && (
                  <button className="btn btn-sm rounded-pill px-3" onClick={() => setShowAddressForm(true)} style={{ letterSpacing: 'initial', borderColor: 'rgb(232, 90, 138)' }}>
                    <FaPlus className="me-1 small" /> Add New
                  </button>
                )}
              </div>

              {showAddressForm ? (
                <form onSubmit={handleAddNewAddress} className="row g-3">
                  <div className="col-md-6 mt-3">
                    <input type="text" placeholder="Full Name" className="form-control p-3 rounded-3" onChange={(e) => setFormData({ ...formData, name: e.target.value })} required />
                  </div>
                  <div className="col-md-6 mt-3">
                    <input type="number" placeholder="Phone Number" className="form-control p-3 rounded-3" onChange={(e) => setFormData({ ...formData, phone: e.target.value })} required />
                  </div>
                  <div className="col-12 mt-3">
                    <input type="text" placeholder="Address (House No, Building, Street)" className="form-control p-3 rounded-3" onChange={(e) => setFormData({ ...formData, address: e.target.value })} required />
                  </div>
                  <div className="col-md-6 mt-3">
                    <input type="text" placeholder="City" className="form-control p-3 rounded-3" onChange={(e) => setFormData({ ...formData, city: e.target.value })} required />
                  </div>
                  <div className="col-md-6 mt-3">
                    <input type="text" placeholder="Pincode" className="form-control p-3 rounded-3" onChange={(e) => setFormData({ ...formData, pincode: e.target.value })} required />
                  </div>
                  <div className="col-12">
                    <button type="submit" className="btn btn-dark px-4 mt-3 rounded-pill me-2" style={{ letterSpacing: 'initial' }}>Save Address</button>
                    <button type="button" className="btn btn-light px-4 py-2 rounded-pill" onClick={() => setShowAddressForm(false)} style={{ letterSpacing: '1.5px' }}>Cancel</button>
                  </div>
                </form>
              ) : (
                <div className="row g-3">
                  {addresses.map((addr, index) => (
                    <div className="col-md-6" key={addr.id}>
                      <div
                        onClick={() => setSelectedAddress(index)}
                        className={`p-3 rounded-4 border-2 cursor-pointer position-relative transition-all ${selectedAddress === index ? " bg-white shadow-sm" : "border-light bg-light"}`}
                        style={{ borderStyle: "solid", cursor: "pointer", transition: "0.3s", borderColor: 'rgb(232, 90, 138)' }}
                      >
                        {selectedAddress === index && <FaCheckCircle className="position-absolute top-0 end-0 m-3" style={{ color: 'rgb(232, 90, 138)' }} />}
                        <span className="badge bg-secondary mb-2 px-3">{addr.type}</span>
                        <h6 className="fw-bold mb-1">{addr.name}</h6>
                        <p className="small text-muted mb-2">{addr.address}, {addr.city} - {addr.pincode}</p>
                        <p className="small fw-bold m-0">{addr.phone}</p>
                      </div>
                    </div>
                  ))}
                </div>
              )}
            </div>

            {/* <div className="card border-0 shadow-sm rounded-4 p-4">
              <h5 className="fw-bold mb-4">Payment Method</h5>
              <div className="d-flex gap-3">
                <div className="p-3 rounded-4 d-flex align-items-center flex-grow-1 bg-light" style={{ border: '1px solid rgb(232, 90, 138)' }}>
                  <input type="radio" checked readOnly className="me-3" />
                  <div>
                    <h6 className="m-0 fw-bold">Cash on Delivery</h6>
                    <small className="text-muted">Pay when you receive</small>
                  </div>
                </div>
              </div>
            </div> */}
          </div>

          <div className="col-lg-4">
            <div className="card border-0  shadow-sm rounded-4 p-4 sticky-top" style={{ top: "120px" }}>
              <h5 className="fw-bold mb-4">Order Summary</h5>

              <div className="mb-4 overflow-auto orderSummary" style={{ maxHeight: "200px" }}>
                {items.map((item) => (
                  <div className="d-flex align-items-center mb-3" key={item.id}>
                    <img src={item.image} className="rounded-3" width="50" height="50" style={{ objectFit: "cover" }} alt="" />
                    <div className="ms-3 flex-grow-1">
                      <p className="small fw-bold mb-0">{item.name}</p>
                      <small className="text-muted">Qty: {item.qty || 1}</small>
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
                  <h4 className="fw-bold text-dark m-0">₹{total}</h4>
                </div>

                <button
                  onClick={() => navigate('/payment')}
                  className="btn w-100 text-white rounded-pill fw-bold shadow-lg"
                  style={{ letterSpacing: "1px", background: 'rgb(232, 90, 138)' }}
                >
                  {loading ? "PROCESSING..." : "CONFIRM ORDER"}
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