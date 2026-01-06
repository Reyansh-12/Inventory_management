import slider1 from "../assets/images/slider/slider1.webp";
import slider2 from "../assets/images/slider/slider2.webp";
import text1 from "../assets/images/slider/text1.webp";
import textTheme from "../assets/images/slider/text-theme.webp";

import React from 'react';
import { FaPinterest } from "react-icons/fa";
import { FaTwitter } from "react-icons/fa";
import { FaFacebook } from "react-icons/fa";
const HeroSlider = () => {
  const slides = [
    {
      title: 'CLEAN FRESH',
      description: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit ut aliquam, purus sit amet luctus venenatis.',
      image: slider1,
      textImg: '/assets/images/slider/text-theme.webp',
      textShape: '/assets/images/slider/text1.webp',
    },
    {
      title: 'Facial Cream',
      description: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit ut aliquam, purus sit amet luctus venenatis.',
      image: slider2,
      textImg: '/assets/images/slider/text-theme.webp',
      textShape: '/assets/images/slider/text1.webp',
    },
  ];

  return (
    <section className="hero-slider-area position-relative mb-5">
      <div className="swiper hero-slider-container">
        <div className="swiper-wrapper">
          {slides.map((slide, index) => (
            <div key={index} className="swiper-slide hero-slide-item">
              <div className="container">
                <div className="row align-items-center position-relative">
                  <div className="col-12 col-md-6">
                    <div className="hero-slide-content">
                    <div class="hero-slide-text-img"><img src={textTheme} width="427" height="232" alt="Image" style={{marginTop: '-150px'}}></img></div>
                      <h2 className="hero-slide-title" style={{marginTop: '100px', fontSize: '75px'}} >{slide.title}</h2>
                      <p className="hero-slide-desc">{slide.description}</p>
                      <a className="btn rounded-3 ps-5 pe-5 align-items-center text-dark fw-bold" style={{background: 'linear-gradient(180deg,rgb(149, 218, 250),rgb(80, 170, 87))', boxShadow: '5px 2px 9px 2px grey', lineHeight: '2.5'}}>BUY NOW</a>
                    </div>
                  </div>
                  <div className="col-12 col-md-6">
                  <div className="hero-slide-content">
                      <div className="hero-slide-text-img">
                        <img src={text1} width="39" alt="Text" style={{marginTop: '-265px', marginLeft: '477px'}}/>
                      </div>
                      <div className="hero-slide-thumb">
                        <img src={slider1} width="841" height="832" alt={slide.title} />
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div className="hero-slide-text-shape">
                <img src={slider1} width="70" height="955" alt="Shape" />
              </div>
            </div>
          ))}
        </div>
      </div>
      <div className="hero-slide-social-media">
        <a href="https://www.pinterest.com/" target="_blank" rel="noopener noreferrer">
          < FaPinterest/>
        </a>
        <a href="https://twitter.com/" target="_blank" rel="noopener noreferrer">
          <FaTwitter />
        </a>
        <a href="https://www.facebook.com/" target="_blank" rel="noopener noreferrer">
          <FaFacebook />
        </a>
      </div>
    </section>
  );
};

export default HeroSlider;