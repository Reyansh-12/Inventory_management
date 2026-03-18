import React, { useState, useEffect, useRef } from 'react';
import axios from 'axios';
import gsap from 'gsap';
import Swal from 'sweetalert2';
import { FaPhoneAlt, FaEnvelope, FaMapMarkerAlt, FaPaperPlane } from 'react-icons/fa';
import Navbar from '../../components/Navbar';
import Footer from '../../components/Footer';
import bannerImage from '../../../src/assets/images/47.jpg';

import "../../assets/styles/plugins/Contact.css";

export const Contact = () => {
  const [inputs, setInputs] = useState({
    con_name: '',
    con_lastName: '',
    con_email: '',
    con_message: ''
  });
  
  const [errors, setErrors] = useState({});
  const contactRef = useRef(null);

  useEffect(() => {
    const ctx = gsap.context(() => {
      gsap.from(".contact-header", { y: -50, opacity: 0, duration: 1, ease: "power3.out" });
      gsap.from(".form-card", { x: -100, opacity: 0, duration: 1, delay: 0.2, ease: "power3.out" });
    }, contactRef);
    return () => ctx.revert();
  }, []);

  const handleChange = (e) => {
    const { name, value } = e.target;
    setInputs(prev => ({ ...prev, [name]: value }));
    setErrors(prev => ({ ...prev, [name]: '' })); 
  };

  const validate = () => {
    const newErrors = {};
    if (!inputs.con_name.trim()) newErrors.con_name = 'First name is required';
    if (!inputs.con_lastName.trim()) newErrors.con_lastName = 'Last name is required';
    if (!inputs.con_email.trim()) newErrors.con_email = 'Email is required';
    else if (!/^[\w-.]+@([\w-]+\.)+[\w-]{2,4}$/.test(inputs.con_email)) newErrors.con_email = 'Invalid email';
    if (!inputs.con_message.trim()) newErrors.con_message = 'Message is required';
    return newErrors;
  };

  const handleSubmit = async (event) => {
    event.preventDefault();
    const validationErrors = validate();
    if (Object.keys(validationErrors).length > 0) {
      setErrors(validationErrors);
      return;
    }

    // Show Loading
    Swal.fire({
      title: 'Please Wait...',
      text: 'Saving your message',
      allowOutsideClick: false,
      didOpen: () => Swal.showLoading()
    });
    
    try {
      const response = await axios.post('http://localhost/Inventory_management/Backend/src/Pages/APIs/userContact.php', {
        name: inputs.con_name,
        lastName: inputs.con_lastName,
        email: inputs.con_email,
        message: inputs.con_message
      });

      if (response.data.status === "success") {
        Swal.fire({
          icon: 'success',
          title: 'Thank You!',
          text: 'We have received your message.',
          confirmButtonColor: '#e85a8a',
        });
        setInputs({ con_name: '', con_lastName: '', con_email: '', con_message: '' }); 
      } else {
        Swal.fire({
          icon: 'error',
          title: 'Database Error',
          text: response.data.message || 'Something went wrong',
        });
      }
    } catch (error) {
      console.error("Axios Error:", error);
      Swal.fire({ 
        icon: 'error', 
        title: 'Network Error', 
        text: 'Server is not responding. Please check XAMPP.' 
      });
    }
  };

  return (
    <div ref={contactRef}>
      <Navbar />
      <main className="contact-wrapper">
        <div className="contact-banner-area position-relative">
          <img src={bannerImage} alt="Banner" className="w-100 banner-img" />
          <div className="banner-overlay d-flex align-items-center justify-content-center">
            <div className="text-center text-white contact-header">
              <h1 className="display-3 fw-bold">Get In Touch</h1>
              <p className="fs-5">Let's make beauty simple.</p>
            </div>
          </div>
        </div>

        <section className="contact-main-section py-5">
          <div className="container">
            <div className="row g-0 rounded-4 overflow-hidden shadow-lg bg-white">
              <div className="col-lg-7 p-4 p-md-5">
                <h2 className="fw-bold mb-4">Send us a Message</h2>
                <form onSubmit={handleSubmit}>
                  <div className="row">
                    <div className="col-md-6 mb-4">
                      <div className="input-group-modern">
                        <input className="modern-input" type="text" name="con_name" value={inputs.con_name} onChange={handleChange} required />
                        <label className="modern-label">First Name *</label>
                        {errors.con_name && <small className="text-danger">{errors.con_name}</small>}
                      </div>
                    </div>
                    <div className="col-md-6 mb-4">
                      <div className="input-group-modern">
                        <input className="modern-input" type="text" name="con_lastName" value={inputs.con_lastName} onChange={handleChange} required />
                        <label className="modern-label">Last Name *</label>
                      </div>
                    </div>
                    <div className="col-12 mb-4">
                      <div className="input-group-modern">
                        <input className="modern-input" type="email" name="con_email" value={inputs.con_email} onChange={handleChange} required />
                        <label className="modern-label">Email Address *</label>
                      </div>
                    </div>
                    <div className="col-12 mb-4">
                      <div className="input-group-modern">
                        <textarea className="modern-input" name="con_message" value={inputs.con_message} onChange={handleChange} rows="4" required />
                        <label className="modern-label">Your Message *</label>
                      </div>
                    </div>
                    <div className="col-12">
                      <button className="premium-submit-btn" type="submit">
                        <span>Send Message</span> <FaPaperPlane className="ms-2" />
                      </button>
                    </div>
                  </div>
                </form>
              </div>
              
              <div className="col-lg-5 bg-dark text-white p-5 d-flex flex-column justify-content-center">
                <h3 className="fw-bold mb-4">Contact Info</h3>
                <div className="mb-3 d-flex align-items-center gap-3">
                  <FaPhoneAlt /> <span>+11 0203 03023</span>
                </div>
                <div className="mb-3 d-flex align-items-center gap-3">
                  <FaEnvelope /> <span>support@cosmelina.com</span>
                </div>
                <div className="d-flex align-items-center gap-3">
                  <FaMapMarkerAlt /> <span>Sunset Beach, NC, 28468</span>
                </div>
              </div>
            </div>
          </div>
        </section>
      </main>
      <Footer />
    </div>
  );
};