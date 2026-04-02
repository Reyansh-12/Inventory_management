import React, { useEffect } from 'react';
import { Routes, Route, useLocation, useNavigate} from 'react-router-dom';

import Navbar from './components/Navbar';
import { HomePage } from './Pages/HomePage';
import { AboutUs } from './Pages/AboutUs/AboutUs';
import { Contact } from './Pages/Contact/Contact';
import ProductFourColumns from './Pages/Products/ProductFourColumns';
import ProductDetailsNormal from './Pages/Products/ProductDetailsNormal';
import ReviewPage from './Pages/Products/ReviewPage';
import Checkout from './Pages/Products/Checkout';
import Cart from "./Pages/Products/Cart";
import Payment from "./Pages/Products/Payment";
import OrderSuccess from "./Pages/Products/OrderSuccess";
import MyOrders from "./Pages/Products/MyOrders";
import Profile from "./Pages/Profile";
import OrderDetails from "./Pages/Products/OrderDetails";

import './assets/styles/vendor/bootstrap.min.css';
import './assets/styles/plugins/style.min.css';     
import './App.css';                                 

function App() {
  const location = useLocation();
  const navigate = useNavigate();

  const hideNavbarPaths = ["/checkout", "/payment", "/order-success"];
  
  const shouldShowNavbar = !hideNavbarPaths.includes(location.pathname.toLowerCase());

  useEffect(() => {
    const searchParams = new URLSearchParams(location.search);
    const authUserString = searchParams.get('auth_user');
  
    if (authUserString) {
      try {
        const decodedData = decodeURIComponent(authUserString);
        const userData = JSON.parse(decodedData);
        
        localStorage.setItem("user", JSON.stringify(userData));
  
        window.history.replaceState(null, "", window.location.pathname);
        navigate("/home", { replace: true });
        window.location.reload(); 
      } catch (error) {
        console.error("User Data Parse Error:", error);
      }
    }
  }, [location, navigate]);

  return (
    <div className="main-app-wrapper" style={{ overflowX: 'hidden', width: '100vw' }}>
      {shouldShowNavbar && <Navbar />}
      
      <Routes>
        <Route path='/home' element={<HomePage />} />
        <Route path='/about' element={<AboutUs />} />
        <Route path='/contact' element={<Contact />} />
        <Route path='/shop' element={<ProductFourColumns />} />
        <Route path="/product/:id" element={<ProductDetailsNormal />} />
        <Route path="/rating/:id" element={<ReviewPage />} />
        <Route path="/checkout" element={<Checkout />} />
        <Route path="/cart" element={<Cart />} />
        <Route path="/payment" element={<Payment />} />
        <Route path="/order-success" element={<OrderSuccess />} />
        <Route path="/my-orders" element={<MyOrders />} />
        <Route path="/profile" element={<Profile />} />
        <Route path="/order-details/:id" element={<OrderDetails />} />
      </Routes>
    </div>
  );
}

export default App;