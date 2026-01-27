import React, { useEffect, useState } from "react";
import Slider from "react-slick";

import "slick-carousel/slick/slick.css";
import "slick-carousel/slick/slick-theme.css";
import '../../src/assets/styles/plugins/ProductCards.css';

const CategorySlider = ({ onSelectCategory }) => {
  const [categories, setCategories] = useState([]);

  useEffect(() => {
    fetch("http://localhost/Inventory_management/Backend/src/Pages/APIs/categoryListAPI.php")
      .then(res => res.json())
      .then(data => setCategories(data))
      .catch(err => console.error(err));
  }, []);

  if (categories.length === 0) return null; 

  const settings = {
    dots: false,
    arrows: true,
    infinite: false,
    speed: 500,
    slidesToShow: 6,
    slidesToScroll: 1,
  };

  return (
    <Slider {...settings}>
      {categories.map(cat => (
        <div key={cat.id} className="px-2">
          <button
            onClick={() => onSelectCategory(cat.name.toLowerCase().replace(/\s+/g, ""))}
            style={{
              border: "none",
              background: "linear-gradient(180deg, #acd9ee, #6B8E6E)",
              padding: "20px",
              width: "100%",
              justifyItems: "center",
              borderRadius: '15px'
            }}
          >
            <img
              src={cat.image}
              alt={cat.name}
              style={{ width: 80, height: 80, objectFit: "contain" }}
            />
            <h6 className="mt-2">{cat.name}</h6>
          </button>
        </div>
      ))}
    </Slider>
  );
};

export default CategorySlider;
