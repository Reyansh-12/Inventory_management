import { useEffect } from "react";
import "../../src/assets/styles/plugins/ProductCards.css";
import image from '../assets/images/17839.jpg';
import { FaLessThan } from "react-icons/fa6";

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
    <div className="carousel">
      <div className="list">
        <div className="item active" style={{ background: "#EA3D41" }}>
            <img src={image} className="fruit" alt="" />
          <div className="content">Strawberry</div>
        </div>
        <div className="item" style={{ background: "#2D5643" }}>
          <div className="content">Avocado</div>
        </div>
        <div className="item hidden" style={{ background: "#E7A043" }}>
          <div className="content">Orange</div>
        </div>
      </div>

      <div className="mockup"></div>

      <div className="arrow">
        <button id="prev"><FaLessThan className="text-center"/></button>
        <button id="next">Next</button>
      </div>
    </div>
  );
};

export default HeroSlider;
