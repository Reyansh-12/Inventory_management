import Navbar from './components/Navbar';
import { HomePage } from './Pages/HomePage';
import { Contact } from './Pages/Contact/Contact';
import './App.css';
import './assets/styles/plugins/fancybox.min.css';
import './assets/styles/plugins/font-awesome.min.css';
import './assets/styles/plugins/nice-select.css';
import './assets/styles/plugins/range-slider.css';
import './assets/styles/plugins/swiper-bundle.min.css';
import './assets/styles/plugins/style.min.css';
import './assets/styles/vendor/bootstrap.min.css';
import {Routes , Route} from 'react-router-dom';
import { AboutUs } from './Pages/AboutUs/AboutUs';
import Category from './Pages/Categories/Category';
import ProductFourColumns from './Pages/Products/ProductFourColumns';
import  ProductDetailsNormal  from './Pages/Products/ProductDetailsNormal';

function App() {
  return (
     <>
      <Navbar />
      <Routes>
          <Route path='/'  element={<HomePage />}></Route>
          <Route path='/about' element={<AboutUs />}></Route>
          <Route path='/contact' element={<Contact />}></Route>
          <Route path='/shop' element={<ProductFourColumns />}></Route>
          <Route path="/ProductDetailsNormal" element={<ProductDetailsNormal />}></Route>
          <Route path="/product/:id" element={<ProductDetailsNormal />} />
        </Routes>
    </>
  )
}

export default App
