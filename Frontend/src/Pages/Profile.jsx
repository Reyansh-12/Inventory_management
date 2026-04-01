import React, { useState, useEffect } from "react";
import { FaCamera, FaChevronLeft, FaUser, FaEnvelope, FaPhoneAlt, FaShieldAlt } from "react-icons/fa";
import { toast } from "react-toastify";
import { useNavigate } from "react-router-dom";

const Profile = () => {
  const navigate = useNavigate();
  const [loading, setLoading] = useState(true);
  const [isEditing, setIsEditing] = useState(false);
  const [user, setUser] = useState({
    user_name: "",
    user_email: "",
    user_contact: "",
    image_path: "",
    user_role: ""
  });

  const IMAGE_BASE_URL = "http://localhost/Inventory_management/Backend/uploads/";

  useEffect(() => {
    const fetchProfile = async () => {
      try {
        const rawData = localStorage.getItem("user");
        if (!rawData) {
          navigate("/login");
          return;
        }
        const loggedInUser = JSON.parse(rawData);
        const email = loggedInUser.email || loggedInUser.user_email;

        const response = await fetch(`http://localhost/Inventory_management/Backend/src/Pages/APIs/fetchUserProfile.php?email=${encodeURIComponent(email)}`);
        const data = await response.json();

        if (data.success) {
          setUser(data.user);
        }
      } catch (err) {
        toast.error("Failed to connect to server");
      } finally {
        setLoading(false);
      }
    };
    fetchProfile();
  }, [navigate]);

  if (loading) return (
    <div className="d-flex justify-content-center align-items-center vh-100">
      <div className="spinner-border text-primary" role="status"></div>
    </div>
  );

  return (
    <div className="container-fluid py-5 min-vh-100" style={{ backgroundColor: "#f8f9fa", marginTop: "100px" }}>
      <div className="container">
        <div className="d-flex align-items-center mb-5">
          <button 
            onClick={() => navigate(-1)} 
            className="btn btn-white shadow-sm rounded-circle me-3 border-0 transition-all"
            style={{ width: "50px", height: "50px" }}
          >
            <FaChevronLeft className="text-dark" />
          </button>
          <div>
            <h2 className="fw-bold mb-0">My Profile</h2>
            <p className="text-muted mb-0">Manage your account settings and info</p>
          </div>
        </div>

        <div className="row g-4">
          <div className="col-lg-4">
            <div className="card border-0 shadow-sm rounded-4 overflow-hidden h-100">
              <div className="p-5 text-center bg-white">
                <div className="position-relative d-inline-block mb-4">
                  <div className="rounded-circle p-1 bg-gradient" style={{ background: "linear-gradient(45deg, #007bff, #6610f2)" }}>
                    <img 
                      src={user.image_path ? `${IMAGE_BASE_URL}${user.image_path}` : "https://via.placeholder.com/150"} 
                      className="rounded-circle border border-4 border-white shadow" 
                      style={{ width: "150px", height: "150px", objectFit: "cover" }} 
                      alt="Profile" 
                      onError={(e) => { e.target.src = "https://via.placeholder.com/150"; }}
                    />
                  </div>
                  <label htmlFor="file-upload" className="btn btn-primary position-absolute bottom-0 end-0 rounded-circle shadow p-2 border-3 border-white">
                    <FaCamera size={16} />
                    <input type="file" id="file-upload" style={{ display: 'none' }} />
                  </label>
                </div>
                
                <h3 className="fw-bold mb-1">{user.user_name || "Guest User"}</h3>
                <span className="badge bg-soft-primary text-primary px-3 py-2 rounded-pill mb-4" style={{ backgroundColor: "#e7f1ff" }}>
                  <FaShieldAlt className="me-1" /> {user.user_role}
                </span>

                <div className="list-group list-group-flush text-start border-top pt-4">
                  <div className="list-group-item border-0 d-flex align-items-center px-0 py-3">
                    <div className="p-2 bg-light rounded-3 me-3"><FaEnvelope className="text-muted" /></div>
                    <div><small className="text-muted d-block">Email Address</small><strong>{user.user_email}</strong></div>
                  </div>
                  <div className="list-group-item border-0 d-flex align-items-center px-0 py-3">
                    <div className="p-2 bg-light rounded-3 me-3"><FaPhoneAlt className="text-muted" /></div>
                    <div><small className="text-muted d-block">Phone</small><strong>{user.user_contact || "Not provided"}</strong></div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div className="col-lg-8">
            <div className="card border-0 shadow-sm rounded-4 h-100">
              <div className="card-body p-5">
                <div className="d-flex justify-content-between align-items-center mb-5">
                  <h4 className="fw-bold mb-0">Personal Information</h4>
                  <button 
                    className={`btn ${isEditing ? 'btn-outline-danger' : 'btn-dark'} rounded-pill px-4 fw-600 transition-all`}
                    onClick={() => setIsEditing(!isEditing)} style={{ letterSpacing: "1px" }}
                  >
                    {isEditing ? "Cancel" : "Edit Profile"}
                  </button>
                </div>

                <form>
                  <div className="row g-4">
                    <div className="col-md-6">
                      <div className="form-floating mb-3">
                        <input 
                          type="text" 
                          className={`form-control border-0 bg-light ${!isEditing && 'opacity-75'}`}
                          id="nameInput" 
                          placeholder="Full Name"
                          disabled={!isEditing} 
                          value={user.user_name || ""}
                          onChange={(e) => setUser({...user, user_name: e.target.value})}
                        />
                        <label htmlFor="nameInput" className="text-muted mt-0"><FaUser className="me-2" />Full Name</label>
                      </div>
                    </div>

                    <div className="col-md-6">
                      <div className="form-floating mb-3">
                        <input 
                          type="email" 
                          className="form-control border-0 bg-light opacity-75"
                          id="emailInput" 
                          placeholder="Email"
                          disabled 
                          value={user.user_email || ""}
                        />
                        <label htmlFor="emailInput" className="text-muted"><FaEnvelope className="me-2" />Email Address</label>
                      </div>
                    </div>

                    <div className="col-12">
                      <div className="form-floating mb-3">
                        <input 
                          type="text" 
                          className={`form-control border-0 bg-light ${!isEditing && 'opacity-75'}`}
                          id="phoneInput" 
                          placeholder="Phone"
                          disabled={!isEditing} 
                          value={user.user_contact || ""}
                          onChange={(e) => setUser({...user, user_contact: e.target.value})}
                          
                        />
                        <label htmlFor="phoneInput" className="text-muted"><FaPhoneAlt className="me-2" />Phone Number</label>
                      </div>
                    </div>
                  </div>

                  {isEditing && (
                    <div className="mt-5">
                      <button type="submit" className="btn btn-primary w-100 rounded-pill fw-bold shadow-sm transition-all" style={{ letterSpacing: "1px" }}>
                        SAVE ALL CHANGES
                      </button>
                    </div>
                  )}
                </form>

                {!isEditing && (
                  <div className="row mt-5 pt-4 border-top g-3">
                    <div className="col-6 col-sm-3">
                      <div className="p-3 text-center bg-light rounded-4">
                        <h4 className="fw-bold mb-0 text-primary">12</h4>
                        <small className="text-muted">Total Orders</small>
                      </div>
                    </div>
                    <div className="col-6 col-sm-3">
                      <div className="p-3 text-center bg-light rounded-4">
                        <h4 className="fw-bold mb-0 text-success">5</h4>
                        <small className="text-muted">Active Tasks</small>
                      </div>
                    </div>
                  </div>
                )}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default Profile;