import { useEffect } from "react";
import "../../src/assets/styles/plugins/ProductCards.css";
import image from '../assets/images/4127915_3-removebg-preview.png';
import image2 from '../assets/images/47.jpg';
import image3 from '../assets/images/f099014a-f3f3-4022-a60a-fb049f5fb58a-removebg-preview.png';
import { FaArrowRightLong } from "react-icons/fa6";
import video from "../assets/images/Cosmetic_Skincare_Product_1080P.mp4";
import banner from "../assets/images/HeroBanner(1).png";
import textLogo from "../assets/images/textLogo-removebg-preview.png";
import second from "../assets/images/secondSection.png";
import '../assets/styles/plugins/HeroSlider.css';

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
      <div className="mb-0">
        <div className="d-flex flex-column  text-start justify-content-center position-absolute text-black" style={{ marginTop: '170px', zIndex: 1, marginLeft: '80px' }}>
          <h5 style={{ fontSize: '60px' }}>Discover Your</h5>
          <img src={textLogo} alt="" />
          <h6 style={{ fontSize: '30px' }}>Premium Cosmetic Collection</h6>
          <button className="w-50 ms-5 rounded-5 mt-3" style={{ background: 'linear-gradient(90deg,rgba(227, 39, 95, 1) 50%, rgba(245, 137, 164, 1) 100%)' }}>Shop Now <FaArrowRightLong className="ms-3" /></button>
        </div>
        <img src={banner} alt="" className="" style={{ width: '100%', height: '700px', marginLeft: '0px' }} />

      </div>
      <div style={{ backgroundImage: `url(${second})`, backgroundSize: 'cover', backgroundPosition: 'center', height: '1000px' }}>
        <section class="category-section" style={{padding: '20px 3%'}}>
          <h3 className="mb-4"><strong>Shop by Category</strong></h3>

          <div class="category-grid">
            <div class="category-card">
              <img src={image} alt="Skincare"></img>
                <h3>Skincare</h3>
            </div>

            <div class="category-card">
            <img src={image2} alt="Skincare"></img>
                <h3>Makeup</h3>
            </div>

            <div class="category-card">
            <img src={image3} alt="Skincare"></img>
                <h3>Fragrance</h3>
            </div>

            <div class="category-card">
                <h3>Hair Care</h3>
            </div>
          </div>
        </section>
      </div>
    </>
  );
};

export default HeroSlider;
