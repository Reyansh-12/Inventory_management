import React, { useEffect, useRef } from "react";
import gsap from "gsap";
import { ScrollTrigger } from "gsap/ScrollTrigger";
import Navbar from '../../components/Navbar';
import Footer from '../../components/Footer';

import aboutLogo from "../../assets/images/photos/about-title.webp";
import about1 from "../../../src/assets/images/photos/about1.webp";
import about2 from "../../assets/images/photos/about2.webp";
import funfact1 from "../../assets/images/icons/funfact1.webp";
import funfact2 from "../../assets/images/icons/funfact2.webp";
import funfact3 from "../../assets/images/icons/funfact3.webp";
import feature1 from "../../assets/images/icons/feature1.webp";
import feature2 from "../../assets/images/icons/feature2.webp";
import feature3 from "../../assets/images/icons/feature3.webp";
import brandlogo1 from "../../assets/images/brand-logo/1.webp";
import brandlogo2 from "../../assets/images/brand-logo/2.webp";
import brandlogo3 from "../../assets/images/brand-logo/3.webp";
import brandlogo4 from "../../assets/images/brand-logo/4.webp";

import "../../assets/styles/plugins/AboutUs.css";

export const AboutUs = () => {
  const aboutRef = useRef(null);
  gsap.registerPlugin(ScrollTrigger);

  useEffect(() => {
    const ctx = gsap.context(() => {
      gsap.from(".about-hero-text", { opacity: 0, x: -50, duration: 1, ease: "power3.out" });
      gsap.from(".about-hero-img", { opacity: 0, scale: 0.9, duration: 1.2, ease: "power3.out" });

      gsap.from(".funfact-card", {
        scrollTrigger: { trigger: ".funfact-area", start: "top 80%" },
        y: 50, opacity: 0, stagger: 0.2, duration: 0.8, ease: "back.out(1.7)"
      });

      gsap.from(".feature-box", {
        scrollTrigger: { trigger: ".feature-area", start: "top 85%" },
        opacity: 0, y: 30, stagger: 0.2, duration: 0.8
      });
    }, aboutRef);
    return () => ctx.revert();
  }, []);

  return (
    <div ref={aboutRef} className="about-page-wrapper">
      <Navbar />
      
      <main className="main-content">
        <section className="about-hero section-space">
          <div className="container">
            <div className="row align-items-center">
              <div className="col-lg-5 about-hero-text">
                <div className="title-tag">OUR STORY</div>
                <div className="about-title-img mb-4">
                  <img src={aboutLogo} alt="About Title" className="img-fluid" />
                </div>
                <h1 className="display-4 fw-bold mb-3">We are SayyamCode</h1>
                <h4 className="text-danger fw-light mb-4 italic">Best cosmetics provider</h4>
                <p className="lead text-muted">
                  Empowering beauty through nature and science. We curate products that celebrate your unique skin.
                </p>
              </div>
              <div className="col-lg-7 text-center about-hero-img">
                <div className="image-stack">
                  <img src={about1} alt="About Main" className="img-fluid rounded-4 shadow-2xl main-img" />
                  <div className="image-accent-box"></div>
                </div>
              </div>
            </div>
          </div>
        </section>

        <section className="funfact-area py-5 bg-white">
          <div className="container">
            <div className="row g-4 justify-content-center">
              {[
                { img: funfact1, num: "5000+", title: "Happy Clients" },
                { img: funfact2, num: "250+", title: "Premium Projects" },
                { img: funfact3, num: "1.5M+", title: "Products Sold" }
              ].map((fact, i) => (
                <div key={i} className="col-md-4 funfact-card">
                  <div className="stat-box text-center p-5 rounded-4 border">
                    <img src={fact.img} alt="Icon" width="80" className="mb-3 hover-jump" />
                    <h2 className="fw-bold display-5 m-0">{fact.num}</h2>
                    <p className="text-muted text-uppercase small tracking-widest">{fact.title}</p>
                  </div>
                </div>
              ))}
            </div>
          </div>
        </section>

        <div className="brand-ticker-container py-5 border-top border-bottom bg-light">
          <div className="container">
            <div className="d-flex justify-content-between align-items-center opacity-50 flex-wrap gap-4">
              {[brandlogo1, brandlogo2, brandlogo3, brandlogo4].map((logo, i) => (
                <img key={i} src={logo} alt="Brand" className="brand-bw-filter" height="40" />
              ))}
            </div>
          </div>
        </div>

        <section className="section-space">
          <div className="container">
            <div className="row align-items-center g-5">
              <div className="col-lg-6">
                <div className="about-thumb-container">
                  <img src={about2} alt="Cosmetics" className="img-fluid rounded-5 shadow-lg" />
                  <div className="floating-badge shadow">Natural First</div>
                </div>
              </div>
              <div className="col-lg-6">
                <h2 className="display-5 fw-bold mb-4">Redefining Beauty Standards</h2>
                <p className="text-secondary fs-5 lh-lg">
                  Lorem ipsum dolor sit amet, consectetur adipiscing elit. In vel arcu aliquet sem risus nisl. Neque, scelerisque
                  in erat lacus ridiculus habitant porttitor. Malesuada pulvinar sollicitudin enim, quis sapien tellus est.
                  Pellentesque amet vel maecenas nisi.
                </p>
                <div className="mt-4 border-start border-danger border-4 ps-4">
                  <p className="fst-italic">"Beauty is about being comfortable in your own skin. It's about knowing and accepting who you are."</p>
                </div>
              </div>
            </div>
          </div>
        </section>

        <section className="feature-area py-5 bg-dark text-white rounded-t-5">
          <div className="container py-5">
            <div className="row g-5">
              {[
                { img: feature1, title: "Expert Support", desc: "Dedicated team for your beauty queries." },
                { img: feature2, title: "Pure Certification", desc: "100% authentic and certified products." },
                { img: feature3, title: "Natural Ingredients", desc: "No harmful chemicals, only nature's best." }
              ].map((feat, i) => (
                <div key={i} className="col-md-4 feature-box">
                  <div className="feature-item-modern">
                    <div className="icon-wrap mb-4">
                      <img src={feat.img} width="60" alt="Icon" />
                    </div>
                    <h5 className="fw-bold">{feat.title}</h5>
                    <p className="opacity-50">{feat.desc}</p>
                  </div>
                </div>
              ))}
            </div>
          </div>
        </section>
      </main>
      
      <Footer />
    </div>
  );
};