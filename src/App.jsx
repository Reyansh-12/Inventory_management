import React, { useEffect } from 'react';
import { Routes, Route, useLocation, useNavigate } from 'react-router-dom';

// Layout Components
import Navbar from './components/Navbar';
import { HomePage } from './Pages/HomePage';
import { AboutUs } from './Pages/AboutUs/AboutUs';
import { Contact } from './Pages/Contact/Contact';
import ProductFourColumns from './Pages/Products/ProductFourColumns';
import ProductDetailsNormal from './Pages/Products/ProductDetailsNormal';
import ReviewPage from './Pages/Products/ReviewPage';
import Checkout from './Pages/Products/Checkout';

// CSS Imports - Order matters!
import './assets/styles/vendor/bootstrap.min.css'; // Bootstrap first
import './assets/styles/plugins/style.min.css';     // Theme styles
import './App.css';                                 // Your custom overrides last

function App() {
  const location = useLocation();
  const navigate = useNavigate();

  useEffect(() => {
    const searchParams = new URLSearchParams(location.search);
    const authUser = searchParams.get('auth_user');

    if (authUser) {
      localStorage.setItem("user", authUser);
      window.history.replaceState(null, "", window.location.pathname);
      navigate("/", { replace: true });
      window.location.reload(); 
    }
  }, [location, navigate]);

  return (
    // overflow-hidden class X-axis scroll ko khatam kar degi
    <div className="main-app-wrapper" style={{ overflowX: 'hidden', width: '100vw' }}>
      <Navbar />
      <Routes>
        <Route path='/' element={<HomePage />} />
        <Route path='/about' element={<AboutUs />} />
        <Route path='/contact' element={<Contact />} />
        <Route path='/shop' element={<ProductFourColumns />} />
        <Route path="/product/:id" element={<ProductDetailsNormal />} />
        <Route path="/rating/:id" element={<ReviewPage />} />
        <Route path="/checkout" element={<Checkout />} />
      </Routes>
    </div>
  );
}

export default App;