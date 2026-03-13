import React, { useState, useEffect } from 'react';
import Footer from '../../components/Footer';
import shop2 from '../../assets/images/shop/product-details/2.webp';
import ProductItem from "@/Pages/Products/ProductItem.jsx";
import shop4 from '../../assets/images/shop/category/4.webp';
import 'bootstrap/dist/css/bootstrap.min.css';
import { toast } from 'react-toastify';
import { useParams } from "react-router-dom";
import { FaStar, FaStarHalfAlt, FaRegStar } from "react-icons/fa";
import { RiArrowRightDoubleLine } from "react-icons/ri";
import { TfiArrowCircleRight } from "react-icons/tfi";
import {NavLink} from "react-router-dom";

const ProductDetailsNormal = () => {
  const { id } = useParams();
  const [product, setProduct] = useState(null);
  const [qty, setQty] = useState(1);
  const [products, setProducts] = useState([]);

  useEffect(() => {
    fetch(`http://localhost/Inventory_management/Backend/src/Pages/APIs/productDetailsAPI.php?id=${id}`)
      .then(res => res.json())
      .then(data => setProduct(data))
      .catch(err => console.error(err));
  }, [id]);

  useEffect(() => {
    fetch("http://localhost/Inventory_management/Backend/src/Pages/APIs/productListAPI.php")
      .then(res => res.json())
      .then(data => setProducts(data))
      .catch(err => console.log("API Error:", err));
  }, []);

  const firstThree = products.slice(0, 4);
  const increment = () => setQty(q => q + 1);
  const decrement = () => setQty(q => Math.max(1, q - 1));

  if (!product) return <h3 className="text-center mt-5" style={{ fontFamily: 'serif' }}>Loading...</h3>;

  const styles = {
    section: { backgroundColor: '#F9F8F6', padding: '80px 0' },
    card: { backgroundColor: '#fff', borderRadius: '20px', overflow: 'hidden', border: 'none' },
    title: { fontFamily: "'Playfair Display', serif", fontSize: '2.5rem', fontWeight: '600', color: '#1a1a1a' },
    price: { fontSize: '1.8rem', color: '#1a1a1a', fontWeight: '400', margin: '15px 0' },
    btnPrimary: { background: '#1a1a1a', color: '#fff', border: 'none', padding: '12px 30px', borderRadius: '5px', textTransform: 'uppercase', letterSpacing: '1px', transition: '0.3s' },
    btnSecondary: { background: 'transparent', color: '#1a1a1a', border: '1px solid #1a1a1a', padding: '12px 30px', borderRadius: '5px', textTransform: 'uppercase', letterSpacing: '1px' },
    quantityBox: { border: '1px solid #ddd', borderRadius: '5px', padding: '5px 15px', display: 'inline-flex', alignItems: 'center', backgroundColor: '#fff' },
    thumbMini: { width: '80px', height: '80px', objectFit: 'cover', borderRadius: '8px', cursor: 'pointer', marginBottom: '10px', border: '1px solid #eee' }
  };

  return (
    <main className="main-content" style={{ backgroundColor: '#F9F8F6' }}>
      <section style={styles.section} className='pb-5'>
        <div className="container">
          <div className="row align-items-center">
            <div className="col-lg-7">
              <div className="row g-3">
                <div className="col-2 d-none d-lg-block">
                  <img src={shop4} style={styles.thumbMini} alt="thumb" />
                  <img src={shop4} style={styles.thumbMini} alt="thumb" />
                  <img src={shop4} style={styles.thumbMini} alt="thumb" />
                </div>
                <div className="col-lg-10">
                  <div className="position-relative shadow-sm rounded-4 overflow-hidden bg-white">
                    <img src={product.image || shop2} className="img-fluid w-100" alt={product.name} />
                    <span className="badge position-absolute top-0 start-0 m-4 px-3 py-2" style={{ background: 'rgba(227, 39, 95, 1)', borderRadius: '0' }}>NEW</span>
                  </div>
                </div>
              </div>
            </div>

            <div className="col-lg-5 ps-lg-5">
              <nav className="mb-3" style={{ fontSize: '12px', textTransform: 'uppercase', letterSpacing: '1px', opacity: 0.6 }}>
                Skincare / Under Eye
              </nav>
              <h1 style={styles.title}>{product.name}</h1>
              <div className="d-flex align-items-center mb-3">
                <div className=" me-2" style={{ color: 'rgba(227, 39, 95, 1)' }}>
                  <FaStar /><FaStar /><FaStar /><FaStarHalfAlt /><FaRegStar />
                </div>
                <span style={{ fontSize: '14px', color: '#666' }}>(150 Reviews)</span>
              </div>

              <p className="text-muted mb-4" style={{ lineHeight: '1.8' }}>
                A luxurious, high-performance formula designed to visibly reduce puffiness and brighten the under-eye area. Infused with botanical extracts for a youthful glow.
              </p>

              <h2 style={styles.price}>₹{product.price}</h2>

              <div className="d-flex align-items-center gap-3 mb-4">
                <div style={styles.quantityBox}>
                  <button onClick={decrement} className="btn btn-sm p-0 fs-4 border-0"><strong>-</strong></button>
                  <input type="text" value={qty} readOnly className="form-control text-center border-0 bg-transparent" style={{ width: '40px' }} />
                  <button onClick={increment} className="btn btn-sm p-0 fs-4 border-0">+</button>
                </div>
              </div>

              <div className="d-grid gap-3">
                <button className="btn py-3 text-white" style={{ background: 'rgba(227, 39, 95, 1)', letterSpacing: 'initial' }} onClick={() => toast.success("Added to cart!")}>
                  Add to Cart
                </button>
                <button className="btn py-3 d-flex align-items-center justify-content-center gap-2" style={{ borderColor: 'rgba(227, 39, 95, 1)', letterSpacing: 'initial' }}>
                  Buy Now <RiArrowRightDoubleLine />
                </button>
              </div>

              <div className="mt-5 pt-4 border-top">
                <div className="row text-center g-2">
                  <div className="col-4">
                    <small className="d-block fw-bold text-uppercase" style={{ fontSize: '10px' }}>Organic</small>
                  </div>
                  <div className="col-4 border-start">
                    <small className="d-block fw-bold text-uppercase" style={{ fontSize: '10px' }}>Cruelty Free</small>
                  </div>
                  <div className="col-4 border-start">
                    <small className="d-block fw-bold text-uppercase" style={{ fontSize: '10px' }}>Vegan</small>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
      <section className="py-5" style={{ backgroundColor: '#fff', borderTop: '1px solid #eee' }}>
        <div className="container">
          <div className="row g-5">
            <div className="col-lg-4 pt-4">
              <h3 style={{ fontFamily: 'serif', fontWeight: '600', marginBottom: '20px' }}>Customer Reviews</h3>
              <div className="d-flex align-items-center mb-2">
                <h2 className="mb-0 me-3" style={{ fontSize: '3rem', fontWeight: '700' }}>4.5</h2>
                <div>
                  <div style={{ fontSize: '1.2rem', color: 'rgba(227, 39, 95, 1)' }}>
                    <FaStar /><FaStar /><FaStar /><FaStar /><FaStarHalfAlt />
                  </div>
                  <p className="text-muted mb-0">Based on 150 reviews</p>
                </div>
              </div>

              {[5, 4, 3, 2, 1].map((star) => (
                <div key={star} className="d-flex align-items-center mb-1" style={{ fontSize: '14px' }}>
                  <span style={{ width: '50px' }}>{star} Stars</span>
                  <div className="progress flex-grow-1 mx-2" style={{ height: '6px', borderRadius: '10px' }}>
                    <div
                      className="progress-bar"
                      style={{
                        width: star === 5 ? '80%' : star === 4 ? '15%' : '5%',
                        backgroundColor: 'rgba(227, 39, 95, 1)'
                      }}
                    ></div>
                  </div>
                  <span className="text-muted" style={{ width: '30px' }}>{star === 5 ? '120' : '20'}</span>
                </div>
              ))}

              <button className="btn mt-4 w-100" style={{ borderColor: 'rgba(227, 39, 95, 1)', letterSpacing: 'initial' }}>Write a Review</button>
            </div>

            <div className="col-lg-8">
              <div className="d-flex justify-content-between align-items-center mb-4 pt-4">
                <h4 style={{ fontWeight: '600' }}>Most Relevant Reviews</h4>
                <select className="form-select-sm border-0 bg-light" style={{ outline: 'none' }}>
                  <option>Newest First</option>
                  <option>Highest Rating</option>
                </select>
              </div>

              {[1, 5].map((i) => (
                <div key={i} className="pb-4 mb-4 border-bottom">
                  <div className="d-flex justify-content-between mb-2">
                    <div className="d-flex align-items-center">
                      <div
                        className="rounded-circle d-flex align-items-center justify-content-center text-white me-3"
                        style={{ width: '45px', height: '45px', background: '#d4c5b9', fontWeight: '600' }}
                      >
                        {i === 1 ? 'JD' : 'AS'}
                      </div>
                      <div>
                        <h6 className="mb-0 fw-bold">Jane Doe <span className="ms-2 badge bg-success" style={{ fontSize: '10px' }}>Verified Buyer</span></h6>
                        <div className="small" style={{ color: 'rgba(227, 39, 95, 1)' }}>
                          <FaStar /><FaStar /><FaStar /><FaStar /><FaStar />
                        </div>
                      </div>
                    </div>
                    <span className="text-muted small">Oct 12, 2025</span>
                  </div>
                  <h6 className="mt-3 fw-bold">Life Changer for Dark Circles!</h6>
                  <p className="text-muted" style={{ fontSize: '15px', lineHeight: '1.6' }}>
                    I've tried dozens of creams, but this one actually works. The texture is so smooth and it doesn't feel greasy under my makeup. Seen a visible difference in just 2 weeks.
                  </p>
                  <div className="d-flex gap-3 mt-2">
                    <img src={shop4} className="rounded" style={{ width: '60px', height: '60px', objectFit: 'cover' }} alt="user upload" />
                  </div>
                </div>
              ))}
              <NavLink to="/rating">
              <button className="btn text-white text-decoration-none fw-bold " style={{letterSpacing: 'initial', background:'rgba(227, 39, 95, 1)'}}>View All Reviews <TfiArrowCircleRight className='ms-1 fs-5'/></button>
              </NavLink>
            </div>
          </div>
        </div>
      </section>
      {/* <section className=" bg-white">
        <div className="container">
          <div className="text-center mb-5">
            <h2 style={{ fontFamily: 'serif', fontWeight: '600' }}>Complete Your Routine</h2>
            <div className="mx-auto" style={{ width: '50px', height: '2px', background: '#1a1a1a', marginTop: '10px' }}></div>
          </div>
          <div className="row g-4">
            {firstThree.map((item) => (
              <div className="col-6 col-md-3" key={item.id}>
                <ProductItem product={item} />
              </div>
            ))}
          </div>
        </div>
      </section> */}

      <Footer />
    </main>
  );
};

export default ProductDetailsNormal;