import React, { useEffect } from 'react';
import Navbar from './components/Navbar';
import ReviewPage from './Pages/Products/ReviewPage';
import { HomePage } from './Pages/HomePage';
import { Contact } from './Pages/Contact/Contact';
import { Routes, Route, useLocation, useNavigate } from 'react-router-dom';
import { AboutUs } from './Pages/AboutUs/AboutUs';
import Category from './Pages/Categories/Category';
import ProductFourColumns from './Pages/Products/ProductFourColumns';
import ProductDetailsNormal from './Pages/Products/ProductDetailsNormal';

// Styles
import './App.css';
import './assets/styles/plugins/fancybox.min.css';
import './assets/styles/plugins/font-awesome.min.css';
import './assets/styles/plugins/nice-select.css';
import './assets/styles/plugins/range-slider.css';
import './assets/styles/plugins/swiper-bundle.min.css';
import './assets/styles/plugins/style.min.css';
import './assets/styles/vendor/bootstrap.min.css';

function App() {
  const location = useLocation();
  const navigate = useNavigate();

  useEffect(() => {
    // 1. URL parameters check karein
    const searchParams = new URLSearchParams(location.search);
    const authUser = searchParams.get('auth_user');

    if (authUser) {
      try {
        // 2. Local Storage mein 'user' key set karein
        // authUser pehle se hi JSON string hai jo PHP se aayi hai
        localStorage.setItem("user", authUser);
        
        // 3. URL se query parameter hatayein taaki clean dikhe
        // Hum redirect kar rahe hain bina query string ke
        navigate("/", { replace: true });
        
        // 4. Page refresh ki zaroorat pad sakti hai agar Navbar state update nahi ho rahi
        window.location.reload();
      } catch (error) {
        console.error("Auth Error:", error);
      }
    }
  }, [location, navigate]);

  return (
    <>
      <Navbar />
      <Routes>
        <Route path='/' element={<HomePage />} />
        <Route path='/about' element={<AboutUs />} />
        <Route path='/contact' element={<Contact />} />
        <Route path='/shop' element={<ProductFourColumns />} />
        <Route path="/ProductDetailsNormal" element={<ProductDetailsNormal />} />
        <Route path="/product/:id" element={<ProductDetailsNormal />} />
        <Route path="/rating" element={<ReviewPage />} />
      </Routes>
    </>
  );
}

export default App;