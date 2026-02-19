
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
import Navbar from '../../components/Navbar';
import Footer from '../../components/Footer';
// import video from "../../assets/images/brush_fall.mp4";

export const AboutUs = () => {

  return (
    <>
    <Navbar />
    <div className="wrapper">
      <main className="main-content">
        <section className="page-header-area">
          <div className="container">
            <div className="row align-items-center">
              <div className="col-md-7 col-lg-7 col-xl-5">
                <div className="page-header-content">
                  <div className="title-img">
                    <img src={aboutLogo} alt="Image" />
                  </div>
                  <h2 className="page-header-title">We, are SayyamCode</h2>
                  <h4 className="page-header-sub-title">Best cosmetics provider</h4>
                  <p className="page-header-desc">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit ut aliquam, purus sit amet luctus venenatis.
                  </p>
                </div>
              </div>
              <div className="col-md-5 col-lg-5 col-xl-7">
                <div className="page-header-thumb">
                  <img src={about1} width="570" height="669" alt="Image" />
                  {/* <video autoPlay loop muted className="w-100 h-100 object-cover">
                          <source src={video}/>
                        </video> */}
                </div>
              </div>
            </div>
          </div>
        </section>

        <section className="funfact-area section-space">
          <div className="container">
            <div className="row mb-n6">
              <div className="col-sm-6 col-lg-4 mb-6">
                <div className="funfact-item">
                  <div className="icon">
                    <img src={funfact1} width="110" height="110" alt="Icon" />
                  </div>
                  <h2 className="funfact-number">5000+</h2>
                  <h6 className="funfact-title">Clients</h6>
                </div>
              </div>
              <div className="col-sm-6 col-lg-4 mb-6">
                <div className="funfact-item">
                  <div className="icon">
                    <img src={funfact2} width="110" height="110" alt="Icon" />
                  </div>
                  <h2 className="funfact-number">250+</h2>
                  <h6 className="funfact-title">Projects</h6>
                </div>
              </div>
              <div className="col-sm-6 col-lg-4 mb-6">
                <div className="funfact-item">
                  <div className="icon">
                    <img src={funfact3} width="110" height="110" alt="Icon" />
                  </div>
                  <h2 className="funfact-number">1.5M+</h2>
                  <h6 className="funfact-title">Revenue</h6>
                </div>
              </div>
            </div>
          </div>
        </section>

        <div className="section-space">
          <div className="container">
            <div className="swiper brand-logo-slider-container">
              <div className="swiper-wrapper">
                <div className="swiper-slide brand-logo-item">
                  <img src={brandlogo1} width="155" height="110" alt="Brand" />
                </div>
                <div className="swiper-slide brand-logo-item">
                  <img src={brandlogo2} width="241" height="110" alt="Brand" />
                </div>
                <div className="swiper-slide brand-logo-item">
                  <img src={brandlogo3} width="147" height="110" alt="Brand" />
                </div>
                <div className="swiper-slide brand-logo-item">
                  <img src={brandlogo4} width="196" height="110" alt="Brand" />
                </div>
              </div>
            </div>
          </div>
        </div>

        <section className="section-space pt-0 mb-n1">
          <div className="container">
            <div className="about-thumb">
              <img src={about2} alt="Image" />
            </div>
            <div className="about-content">
              <h2 className="title">Best Cosmetics Provider</h2>
              <p className="desc">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. In vel arcu aliquet sem risus nisl. Neque, scelerisque
                in erat lacus ridiculus habitant porttitor. Malesuada pulvinar sollicitudin enim, quis sapien tellus est.
                Pellentesque amet vel maecenas nisi. In elementum magna nulla ridiculus sapien mollis volutpat sit. Arcu egestas
                massa consectetur felis urna porttitor ac.
              </p>
            </div>
          </div>
        </section>

        <div className="feature-area section-space">
          <div className="container">
            <div className="row mb-n9">
              <div className="col-md-6 col-lg-4 mb-8">
                <div className="feature-item">
                  <h5 className="title">
                    <img className="icon" src={feature1} width="60" height="60" alt="Icon" /> Support Team
                  </h5>
                  <p className="desc">Lorem ipsum dolor amet, consectetur adipiscing. Ac tortor enim metus, turpis.</p>
                </div>
              </div>
              <div className="col-md-6 col-lg-4 mb-8">
                <div className="feature-item">
                  <h5 className="title">
                    <img className="icon" src={feature2} width="60" height="60" alt="Icon" /> Certification
                  </h5>
                  <p className="desc">Lorem ipsum dolor amet, consectetur adipiscing. Ac tortor enim metus, turpis.</p>
                </div>
              </div>
              <div className="col-md-6 col-lg-4 mb-8">
                <div className="feature-item">
                  <h5 className="title">
                    <img className="icon" src={feature3} width="60" height="60" alt="Icon" /> Natural Products
                  </h5>
                  <p className="desc">Lorem ipsum dolor amet, consectetur adipiscing. Ac tortor enim metus, turpis.</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </main>
    </div>
    
    <Footer />
    </>
  );
};

