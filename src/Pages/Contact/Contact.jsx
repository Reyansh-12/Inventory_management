import React, { useState, useEffect, useRef } from 'react';
import axios from 'axios';
import gsap from 'gsap';
import { FaPhoneAlt, FaEnvelope, FaMapMarkerAlt, FaPaperPlane } from 'react-icons/fa';
import Navbar from '../../components/Navbar';
import Footer from '../../components/Footer';
import bannerImage from '../../../src/assets/images/47.jpg';
import { toast } from 'react-toastify';

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
      gsap.from(".info-side", { x: 100, opacity: 0, duration: 1, delay: 0.4, ease: "power3.out" });
    }, contactRef);
    return () => ctx.revert();
  }, []);

  const handleChange = (event) => {
    const { name, value } = event.target;
    setInputs(prev => ({ ...prev, [name]: value }));
    setErrors(prev => ({ ...prev, [name]: '' })); 
  };

  const validate = () => {
    const newErrors = {};
    if (!inputs.con_name.trim()) newErrors.con_name = 'First name is required';
    if (!inputs.con_lastName.trim()) newErrors.con_lastName = 'Last name is required';
    if (!inputs.con_email.trim()) newErrors.con_email = 'Email is required';
    else if (!/^[\w-.]+@([\w-]+\.)+[\w-]{2,4}$/.test(inputs.con_email)) newErrors.con_email = 'Invalid email format';
    if (!inputs.con_message.trim()) newErrors.con_message = 'Message is required';
    return newErrors;
  };

  const handleSubmit = (event) => {
    event.preventDefault();
    const validationErrors = validate();
    if (Object.keys(validationErrors).length > 0) {
      setErrors(validationErrors);
      return;
    }
    
    axios.post('http://localhost/Inventory_management/Backend/src/Pages/userContact.php', {
      name: inputs.con_name,
      lastName: inputs.con_lastName,
      email: inputs.con_email,
      message: inputs.con_message
    })
    .then(response => {
      toast.success("Message sent successfully!");
      setInputs({ con_name: '', con_lastName: '', con_email: '', con_message: '' }); 
    })
    .catch(error => {
      toast.error("Error sending message.");
    });
  };

  return (
    <div ref={contactRef}>
      <Navbar />
      
      <main className="contact-wrapper">
        <div className="contact-banner-area position-relative">
            <img src={bannerImage} alt="Contact Banner" className="w-100 banner-img" />
            <div className="banner-overlay d-flex align-items-center justify-content-center">
                <div className="text-center text-white contact-header">
                    <h1 className="display-3 fw-bold">Get In Touch</h1>
                    <p className="fs-5">We'd love to hear from you. Let's make beauty simple.</p>
                </div>
            </div>
        </div>

        <section className="contact-main-section py-5">
          <div className="container">
            <div className="row g-0 rounded-4 overflow-hidden shadow-2xl main-contact-box">
              
              <div className="col-lg-7 bg-white p-4 p-md-5 form-card">
                <div className="mb-4">
                    <h2 className="fw-bold mb-2">Send us a Message</h2>
                    <p className="text-muted">Fields marked with * are required.</p>
                </div>
                
                <form id="contact-form" onSubmit={handleSubmit}>
                    <div className="row">
                      <div className="col-md-6 mb-4">
                        <div className="input-group-modern">
                          <input className={`modern-input ${errors.con_name ? 'error' : ''}`} type="text" name="con_name" value={inputs.con_name} onChange={handleChange} required />
                          <label className="modern-label">First Name *</label>
                          {errors.con_name && <span className="error-text">{errors.con_name}</span>}
                        </div>
                      </div>
                      <div className="col-md-6 mb-4">
                        <div className="input-group-modern">
                          <input className={`modern-input ${errors.con_lastName ? 'error' : ''}`} type="text" name="con_lastName" value={inputs.con_lastName} onChange={handleChange} required />
                          <label className="modern-label">Last Name *</label>
                          {errors.con_lastName && <span className="error-text">{errors.con_lastName}</span>}
                        </div>
                      </div>
                      <div className="col-12 mb-4">
                        <div className="input-group-modern">
                          <input className={`modern-input ${errors.con_email ? 'error' : ''}`} type="email" name="con_email" value={inputs.con_email} onChange={handleChange} required />
                          <label className="modern-label">Email Address *</label>
                          {errors.con_email && <span className="error-text">{errors.con_email}</span>}
                        </div>
                      </div>
                      <div className="col-12 mb-4">
                        <div className="input-group-modern">
                          <textarea className={`modern-input ${errors.con_message ? 'error' : ''}`} name="con_message" value={inputs.con_message} onChange={handleChange} rows="4" required />
                          <label className="modern-label">Your Message *</label>
                          {errors.con_message && <span className="error-text">{errors.con_message}</span>}
                        </div>
                      </div>
                      <div className="col-12">
                        <button className="premium-submit-btn" type="submit">
                          <span>Send Message</span>
                          <FaPaperPlane className="ms-2" />
                        </button>
                      </div>
                    </div>
                </form>
              </div>

              <div className="col-lg-5 info-side text-white p-4 p-md-5 d-flex flex-column justify-content-center">
                <h3 className="fw-bold mb-4">Contact Information</h3>
                <p className="mb-5 opacity-75">Fill out the form and our team will get back to you within 24 hours.</p>
                
                <div className="info-item d-flex align-items-center mb-4">
                    <div className="icon-circle me-3"><FaPhoneAlt /></div>
                    <div>
                        <p className="mb-0 small opacity-50">Call Us</p>
                        <a href="tel:+11020303023" className="text-white text-decoration-none fw-bold">+11 0203 03023</a>
                    </div>
                </div>

                <div className="info-item d-flex align-items-center mb-4">
                    <div className="icon-circle me-3"><FaEnvelope /></div>
                    <div>
                        <p className="mb-0 small opacity-50">Email Us</p>
                        <a href="mailto:support@cosmelina.com" className="text-white text-decoration-none fw-bold">support@cosmelina.com</a>
                    </div>
                </div>

                <div className="info-item d-flex align-items-center">
                    <div className="icon-circle me-3"><FaMapMarkerAlt /></div>
                    <div>
                        <p className="mb-0 small opacity-50">Visit Us</p>
                        <p className="mb-0 fw-bold">Sunset Beach, North Carolina, 28468</p>
                    </div>
                </div>

                <div className="social-links mt-5 d-flex gap-3">
                    <div className="social-blob"></div>
                </div>
              </div>

            </div>
          </div>
        </section>

        <div className="container-fluid p-0 mt-5">
            <div className="map-glass-container">
                <iframe
                    title="location"
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3326.685324335542!2d-78.50858!3d33.88219!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMzPCsDUyJzU1LjkiTiA3OMKwMzAnMzAuOSJX!5e0!3m2!1sen!2sus!4v1625500000000!5m2!1sen!2sus"
                    style={{ border: 0, width: '100%', height: 450, filter: 'grayscale(100%) invert(90%)' }}
                    allowFullScreen=""
                    loading="lazy"
                />
            </div>
        </div>
      </main>

      <Footer />
    </div>
  );
};