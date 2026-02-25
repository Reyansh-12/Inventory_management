import { useEffect } from "react";
import "../../src/assets/styles/plugins/ProductCards.css";
import image from '../assets/images/1256-removebg-preview.png';
import image2 from '../assets/images/4127915_3-removebg-preview.png';
import image3 from '../assets/images/f099014a-f3f3-4022-a60a-fb049f5fb58a-removebg-preview.png';
import { FaLessThan } from "react-icons/fa6";
import { FaGreaterThan } from "react-icons/fa6";
import video from "../assets/images/Cosmetic_Skincare_Product_1080P.mp4";
import banner from "../assets/images/47.jpg"
import { FaLongArrowAltRight } from "react-icons/fa";

const HeroSlider = () => {

  useEffect(() => {
    const list = document.querySelectorAll('.carousel .list .item');
    const carousel = document.querySelector('.carousel');
    const next = document.getElementById('next');
    const prev = document.getElementById('prev');
    const mockup = document.querySelector('.mockup');

    if (!list.length || !next || !prev) return;

    let count = list.length;
    let active = 0;
    let leftMockup = 0;
    let left_each_item = 100 / (count - 1);
    let refreshInterval;

    const changeCarousel = () => {
      const hidden_old = document.querySelector('.item.hidden');
      if (hidden_old) hidden_old.classList.remove('hidden');

      const active_old = document.querySelector('.item.active');
      if (active_old) {
        active_old.classList.remove('active');
        active_old.classList.add('hidden');
      }

      list[active].classList.add('active');
      mockup.style.setProperty('--left', leftMockup + '%');

      clearInterval(refreshInterval);
      refreshInterval = setInterval(() => next.click(), 3000);
    };

    next.onclick = () => {
      active = active >= count - 1 ? 0 : active + 1;
      leftMockup += left_each_item;
      carousel.classList.remove('right');
      changeCarousel();
    };

    prev.onclick = () => {
      active = active <= 0 ? count - 1 : active - 1;
      leftMockup -= left_each_item;
      carousel.classList.add('right');
      changeCarousel();
    };

    refreshInterval = setInterval(() => next.click(), 3000);

    return () => clearInterval(refreshInterval);

  }, []);

  return (
    <>
    <div>
      <img src={banner} alt="" className="" style={{width: '1000px', height: '700px', marginLeft: '100px'}}/>
      <div className="d-flex justify-content-center ">
      <a href="/shop"><button className="btn btn-white border border-secondary" style={{marginTop: '-70px'}}>Explore Collection <FaLongArrowAltRight /></button></a>
      </div>
      {/* <video autoPlay loop muted className="w-100 h-100 object-cover">
        <source src={video}/>
      </video> */}
    </div>
    {/* <div className="carousel">
      <div className="list">
        <div className="item active">
            <img src={image} className="fruit" alt="" />
          <div className="content" style={{color: "#128ba3"}}>Men's Skincare</div>
        </div>
        <div className="item">
        <img src={image2} className="fruit" alt="" />
          <div className="content" style={{ color: "#2D5643" }}>Avocado</div>
        </div>
        <div className="item hidden">
        <img src={image3} className="fruit" alt="" />
          <div className="content" style={{color: '#E7A043'}}>Sunscreen</div>
        </div>
      </div>

      <div className="mockup"></div>

      <div className="arrow">
        <button id="prev"><FaLessThan className="text-center prevSliderIcon"/></button>
        <button id="next"><FaGreaterThan className="text-center nextSliderIcon"/></button>
      </div>
    </div> */}
    </>
  );
};

export default HeroSlider;
