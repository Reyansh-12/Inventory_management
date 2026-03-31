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

  const validateField = (name, value) => {
    let error = "";
    const trimmedValue = value.trim();

    switch (name) {
      case 'con_name':
        if (!trimmedValue) error = "First name field is required.";
        else if (/[0-9]/.test(trimmedValue)) error = "Numbers are not allowed in name.";
        else if (trimmedValue.length < 2) error = "Name is too short (min 2 characters).";
        break;
      
      case 'con_lastName':
        if (!trimmedValue) error = "Last name field is required.";
        else if (/[0-9]/.test(trimmedValue)) error = "Numbers are not allowed in last name.";
        break;

      case 'con_email':
        // Updated Regex: Must start with a letter [a-zA-Z]
        const emailRegex = /^[a-zA-Z][a-zA-Z0-9._%+-]*@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
        
        if (!trimmedValue) {
          error = "Email field is required.";
        } else if (!/^[a-zA-Z]/.test(trimmedValue)) {
          error = "Email must start with a letter (a-z). Numbers/Symbols not allowed at start.";
        } else if (!emailRegex.test(trimmedValue)) {
          error = "Oops! That doesn't look like a valid email.";
        }
        break;

      case 'con_message':
        if (!trimmedValue) error = "Write us a message.";
        else if (/[0-9]/.test(trimmedValue)) error = "Numbers are not allowed in the message.";
        else if (trimmedValue.length < 15) error = "Please provide a bit more detail (min 15 characters).";
        break;

      default:
        break;
    }
    return error;
  };

  const handleChange = (e) => {
    const { name, value } = e.target;
    let finalValue = value;

    // Real-time filtering: Prevent numbers in Name and Message fields
    if (name === 'con_name' || name === 'con_lastName' || name === 'con_message') {
      finalValue = value.replace(/[0-9]/g, ''); 
    }

    setInputs(prev => ({ ...prev, [name]: finalValue }));

    // Validate on every keystroke
    const fieldError = validateField(name, finalValue);
    setErrors(prev => ({ ...prev, [name]: fieldError }));
  };

  const handleSubmit = async (event) => {
    event.preventDefault();
    
    const newErrors = {};
    Object.keys(inputs).forEach(key => {
      const error = validateField(key, inputs[key]);
      if (error) newErrors[key] = error;
    });

    if (Object.keys(newErrors).length > 0) {
      setErrors(newErrors);
      return;
    }

    Swal.fire({
      title: 'Sending...',
      text: 'We are processing your request',
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
          title: 'Message Sent!',
          text: 'We will get back to you shortly.',
          confirmButtonColor: '#e85a8a',
        });
        setInputs({ con_name: '', con_lastName: '', con_email: '', con_message: '' }); 
        setErrors({});
      } else {
        Swal.fire({ icon: 'error', title: 'Error', text: response.data.message });
      }
    } catch (error) {
      Swal.fire({ icon: 'error', title: 'Network Error', text: 'Please check your connection.' });
    }
  };

  return (
    <div ref={contactRef}>
      <Navbar />
      <main className="contact-wrapper">
        <div className="contact-banner-area position-relative">
          <img src={bannerImage} alt="Banner" style={{opacity: '70%'}} className="w-100 banner-img" />
          <div className="banner-overlay d-flex align-items-center justify-content-center">
            <div className="text-center text-white contact-header">
              <h1 className="display-3 fw-bold text-dark">Get In Touch</h1>
              <p className="fs-5 text-dark">Let's make beauty simple.</p>
            </div>
          </div>
        </div>

        <section className="contact-main-section py-5">
          <div className="container">
            <div className="row g-0 rounded-4 overflow-hidden shadow-lg bg-white form-card">
              <div className="col-lg-7 p-4 p-md-5">
                <h2 className="fw-bold mb-4">Send us a Message</h2>
                <form onSubmit={handleSubmit} noValidate>
                  <div className="row">
                    
                    {/* First Name */}
                    <div className="col-md-6 mb-4">
                      <div className={`input-group-modern ${errors.con_name ? 'is-invalid' : ''}`}>
                        <input 
                          className="modern-input" 
                          type="text" 
                          name="con_name" 
                          autoComplete="off"
                          value={inputs.con_name} 
                          onChange={handleChange} 
                        />
                        <label className="modern-label">First Name *</label>
                      </div>
                      {errors.con_name && <div className="parsley-custom-error">{errors.con_name}</div>}
                    </div>

                    {/* Last Name */}
                    <div className="col-md-6 mb-4">
                      <div className={`input-group-modern ${errors.con_lastName ? 'is-invalid' : ''}`}>
                        <input 
                          className="modern-input" 
                          type="text" 
                          name="con_lastName" 
                          autoComplete="off"
                          value={inputs.con_lastName} 
                          onChange={handleChange} 
                        />
                        <label className="modern-label">Last Name *</label>
                      </div>
                      {errors.con_lastName && <div className="parsley-custom-error">{errors.con_lastName}</div>}
                    </div>

                    {/* Email Address */}
                    <div className="col-12 mb-4">
                      <div className={`input-group-modern ${errors.con_email ? 'is-invalid' : ''}`}>
                        <input 
                          className="modern-input" 
                          type="email" 
                          name="con_email" 
                          autoComplete="off"
                          value={inputs.con_email} 
                          onChange={handleChange} 
                        />
                        <label className="modern-label">Email Address *</label>
                      </div>
                      {errors.con_email && <div className="parsley-custom-error">{errors.con_email}</div>}
                    </div>

                    {/* Message */}
                    <div className="col-12 mb-4">
                      <div className={`input-group-modern ${errors.con_message ? 'is-invalid' : ''}`}>
                        <textarea 
                          className="modern-input" 
                          name="con_message" 
                          value={inputs.con_message} 
                          onChange={handleChange} 
                          rows="4" 
                        />
                        <label className="modern-label">Your Message *</label>
                      </div>
                      {errors.con_message && <div className="parsley-custom-error">{errors.con_message}</div>}
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
                <h3 className="fw-bold mb-4 text-white">Contact Info</h3>
                <div className="mb-4 d-flex align-items-center gap-3">
                  <FaPhoneAlt className="text-pink" /> <span>+11 0203 03023</span>
                </div>
                <div className="mb-4 d-flex align-items-center gap-3">
                  <FaEnvelope className="text-pink" /> <span>support@cosmelina.com</span>
                </div>
                <div className="d-flex align-items-center gap-3">
                  <FaMapMarkerAlt className="text-pink" /> <span>Sunset Beach, NC, 28468</span>
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