import React from 'react';
import Navbar from '../components/Navbar.jsx';
import HeroSlider from './HeroSlider';
import ProductCategory from './ProductCategory';
import TopSaleProducts from './TopSaleProducts';
import BlogPage from './Blog';
import Footer from '../components/Footer.jsx';

 export const HomePage = () => {
  return (
 
    <main className="main-content">
        <Navbar />
        <HeroSlider />
        {/* <ProductCategory /> */}
        <TopSaleProducts />
        <BlogPage />
        <Footer />
    </main>
  );
};

