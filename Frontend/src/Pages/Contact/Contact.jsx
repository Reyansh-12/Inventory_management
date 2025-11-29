
import React, { useState } from 'react';
import axios from 'axios';
import callIcon from "../../assets/images/icons/1.webp";
import emailIcon from "../../assets/images/icons/2.webp";
import locationIcon from "../../assets/images/icons/3.webp";
import Navbar from '../../components/Navbar';

export const Contact = () => {
  const [inputs, setInputs] = useState({
    con_name: '',
    con_lastName: '',
    con_email: '',
    con_message: ''
  });
  
  const [errors, setErrors] = useState({});

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
      console.log(response);
      alert("Message sent successfully!");
      setInputs({ con_name: '', con_lastName: '', con_email: '', con_message: '' }); 
    })
    .catch(error => {
      console.error(error);
      alert("Error sending message. Please try again.");
    });
  };

  return (
    <>
    <Navbar />
    <div className="wrapper">
      <main className="main-content">
        <section className="contact-area">
          <div className="container">
            <div className="row">
              <div className="offset-lg-6 col-lg-6">
                <div className="section-title position-relative">
                  <h2 className="title">Get in touch</h2>
                  <p className="m-0">Lorem ipsum dolor sit amet, consectetur adipiscing aliquam, purus sit amet luctus venenatis</p>
                  <div className="line-left-style mt-4 mb-1" />
                </div>

                <div className="contact-form">
                  <form id="contact-form" onSubmit={handleSubmit}>
                    <div className="row">
                      <div className="col-md-6">
                        <div className="form-group">
                          <input 
                            className={`form-control ${errors.con_name ? 'is-invalid' : ''}`} 
                            type="text" 
                            name="con_name" 
                            value={inputs.con_name} 
                            onChange={handleChange} 
                            placeholder="First Name"
                          />
                          {errors.con_name && <div className="invalid-feedback">{errors.con_name}</div>}
                        </div>
                      </div>
                      <div className="col-md-6">
                        <div className="form-group">
                          <input 
                            className={`form-control ${errors.con_lastName ? 'is-invalid' : ''}`} 
                            type="text" 
                            name="con_lastName" 
                            value={inputs.con_lastName} 
                            onChange={handleChange} 
                            placeholder="Last Name" 
                          />
                          {errors.con_lastName && <div className="invalid-feedback">{errors.con_lastName}</div>}
                        </div>
                      </div>
                      <div className="col-12">
                        <div className="form-group">
                          <input 
                            className={`form-control ${errors.con_email ? 'is-invalid' : ''}`} 
                            type="email" 
                            name="con_email" 
                            value={inputs.con_email} 
                            onChange={handleChange} 
                            placeholder="Email address" 
                          />
                          {errors.con_email && <div className="invalid-feedback">{errors.con_email}</div>}
                        </div>
                      </div>
                      <div className="col-12">
                        <div className="form-group">
                          <textarea 
                            className={`form-control ${errors.con_message ? 'is-invalid' : ''}`} 
                            name="con_message" 
                            value={inputs.con_message} 
                            onChange={handleChange} 
                            placeholder="Message" 
                          />
                          {errors.con_message && <div className="invalid-feedback">{errors.con_message}</div>}
                        </div>
                      </div>
                      <div className="col-12">
                        <div className="form-group mb-0">
                          <button className="btn btn-sm rounded-pill" type="submit" style={{background: '#ff6565', color: 'white'}}>SUBMIT</button>
                        </div>
                      </div>
                    </div>
                  </form>
                </div>
                <div className="form-message" />
              </div>
            </div>
          </div>
        </section>

        <section className="section-space">
          <div className="container">
            <div className="contact-info d-flex flex-wrap">
              <div className="contact-info-item">
                <img className="icon" src={callIcon} width="30" height="30" alt="Icon" />
                <div>
                  <a href="tel:+11020303023" className='text-decoration-none'>+11 0203 03023</a>
                  <a href="tel:+11020303023" className='text-decoration-none'>+11 0203 03023</a>
                </div>
              </div>

              <div className="contact-info-item">
                <img className="icon" src={emailIcon} width="30" height="30" alt="Icon" />
                <div>
                  <a href="mailto:example@demo.com" className='text-decoration-none'>example@demo.com</a>
                  <a href="mailto:demo@example.com" className='text-decoration-none'>demo@example.com</a>
                </div>
              </div>

              <div className="contact-info-item mb-0">
                <img className="icon" src={locationIcon} width="30" height="30" alt="Icon" />
                <p className="mb-0">Sunset Beach, North Carolina(NC), 28468</p>
              </div>
            </div>
          </div>
        </section>

        <div className="map-area">
          <iframe
            title="location"
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d802879.9165497769!2d144.83475730949783!3d-38.180874157285366!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x6ad646b5d2ba4df7%3A0x4045675218ccd90!2sMelbourne%20VIC%2C%20Australia!5e0!3m2!1sen!2sbd!4v1636803638401!5m2!1sen!2sbd"
            style={{ border: 0, width: '100%', height: 450 }}
            allowFullScreen=""
            loading="lazy"
            referrerPolicy="no-referrer-when-downgrade"
          />
        </div>
      </main>
    </div>
    </>
  );
};
