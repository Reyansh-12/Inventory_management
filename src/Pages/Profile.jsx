import React, { useState, useEffect } from "react";
import { FaUserEdit, FaMapMarkerAlt, FaShoppingBag, FaKey, FaCamera } from "react-icons/fa";
import { toast } from "react-toastify";

const Profile = () => {
  const [user, setUser] = useState({
    name: "Shivam",
    email: "shivam@example.com",
    phone: "+91 98765 43210",
    address: "123, Beauty Lane, Nagpur, Maharashtra",
    profilePic: "https://via.placeholder.com/150"
  });

  const [isEditing, setIsEditing] = useState(false);

  useEffect(() => {
    const savedUser = localStorage.getItem("user");
    if (savedUser) {
      setUser(JSON.parse(savedUser));
    }
  }, []);

  const handleUpdate = (e) => {
    e.preventDefault();
    localStorage.setItem("user", JSON.stringify(user));
    setIsEditing(false);
    toast.success("Profile updated successfully!");
  };

  return (
    <div className="container py-5 mt-5" style={{ maxWidth: "900px" }}>
      <div className="row g-4">
        {/* Sidebar - Profile Summary */}
        <div className="col-lg-4">
          <div className="card border-0 shadow-sm rounded-4 text-center p-4 bg-white">
            <div className="position-relative d-inline-block mx-auto mb-3">
              <img 
                src={user.profilePic} 
                className="rounded-circle border border-4 border-light shadow-sm" 
                style={{ width: "120px", height: "120px", objectFit: "cover" }} 
                alt="Profile" 
              />
              <button className="btn btn-sm btn-dark position-absolute bottom-0 end-0 rounded-circle p-2">
                <FaCamera size={12} />
              </button>
            </div>
            <h5 className="fw-bold mb-1">{user.name}</h5>
            <p className="text-muted small mb-3">{user.email}</p>
            <div className="d-grid gap-2">
              <button className="btn btn-outline-dark btn-sm rounded-pill">Change Password</button>
            </div>
            <hr className="my-4 opacity-10" />
            <div className="text-start">
              <div className="d-flex align-items-center mb-3 cursor-pointer text-dark" style={{textDecoration: 'none'}}>
                <FaShoppingBag className="me-3 text-muted" /> <span>My Orders</span>
              </div>
              <div className="d-flex align-items-center mb-3 text-dark">
                <FaMapMarkerAlt className="me-3 text-muted" /> <span>Saved Addresses</span>
              </div>
            </div>
          </div>
        </div>

        {/* Main Content - Edit Form */}
        <div className="col-lg-8">
          <div className="card border-0 shadow-sm rounded-4 p-4 bg-white">
            <div className="d-flex justify-content-between align-items-center mb-4">
              <h4 className="fw-bold m-0">Account Details</h4>
              <button 
                className={`btn btn-sm ${isEditing ? 'btn-danger' : 'btn-dark'} rounded-pill px-4`}
                onClick={() => setIsEditing(!isEditing)}
              >
                {isEditing ? "Cancel" : "Edit Profile"}
              </button>
            </div>

            <form onSubmit={handleUpdate}>
              <div className="row g-3">
                <div className="col-md-6">
                  <label className="form-label small fw-bold text-uppercase text-muted">Full Name</label>
                  <input 
                    type="text" 
                    className="form-control rounded-3 py-2" 
                    disabled={!isEditing} 
                    value={user.name}
                    onChange={(e) => setUser({...user, name: e.target.value})}
                  />
                </div>
                <div className="col-md-6">
                  <label className="form-label small fw-bold text-uppercase text-muted">Email Address</label>
                  <input 
                    type="email" 
                    className="form-control rounded-3 py-2" 
                    disabled={!isEditing} 
                    value={user.email}
                    onChange={(e) => setUser({...user, email: e.target.value})}
                  />
                </div>
                <div className="col-md-12">
                  <label className="form-label small fw-bold text-uppercase text-muted">Phone Number</label>
                  <input 
                    type="text" 
                    className="form-control rounded-3 py-2" 
                    disabled={!isEditing} 
                    value={user.phone}
                    onChange={(e) => setUser({...user, phone: e.target.value})}
                  />
                </div>
                <div className="col-md-12">
                  <label className="form-label small fw-bold text-uppercase text-muted">Default Delivery Address</label>
                  <textarea 
                    className="form-control rounded-3 py-2" 
                    rows="3" 
                    disabled={!isEditing}
                    value={user.address}
                    onChange={(e) => setUser({...user, address: e.target.value})}
                  ></textarea>
                </div>
              </div>

              {isEditing && (
                <div className="mt-4">
                  <button type="submit" className="btn btn-dark w-100 py-3 rounded-pill fw-bold shadow-sm">
                    SAVE CHANGES
                  </button>
                </div>
              )}
            </form>
          </div>

          {/* Quick Stats */}
          <div className="row g-3 mt-2">
            <div className="col-6">
              <div className="card border-0 shadow-sm rounded-4 p-3 bg-primary-subtle text-primary">
                <h3 className="fw-bold mb-0">05</h3>
                <small className="fw-bold">Total Orders</small>
              </div>
            </div>
            <div className="col-6">
              <div className="card border-0 shadow-sm rounded-4 p-3 bg-success-subtle text-success">
                <h3 className="fw-bold mb-0">02</h3>
                <small className="fw-bold">Active Wishlist</small>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default Profile;