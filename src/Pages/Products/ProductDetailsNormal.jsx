import React, { useState, useEffect } from 'react';
import Footer from '../../components/Footer';
import banner7 from '../../assets/images/shop/banner/7.webp';
import shop2 from '../../assets/images/shop/product-details/2.webp';
import ProductItem from "@/Pages/Products/ProductItem.jsx";
import shop4 from '../../assets/images/shop/category/4.webp';
import 'bootstrap/dist/css/bootstrap.min.css';

import { ToastContainer, toast } from 'react-toastify';
import { useParams } from "react-router-dom";
import { FaStar } from "react-icons/fa";
import { FaStarHalfAlt } from "react-icons/fa";
import { FaRegStar } from "react-icons/fa";
import { FaRegHeart } from "react-icons/fa";

const ProductDetailsNormal = () => {
  const { id } = useParams();               
  const [product, setProduct] = useState(null);
  const [qty, setQty] = useState(1);
  const [shipping, setShipping] = useState(true);
  const [activeTab, setActiveTab] = useState('review');
  const [review, setReview] = useState({ message: '', name: '', email: '', rating: '5', anonymous: true });
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

  const firstThree = products.slice(0, 3);

   const increment = () => setQty(q => q + 1);
  const decrement = () => setQty(q => Math.max(1, q - 1));
  const handleAddToCart = () => toast.success('Product added to cart!');
  const handleSubmitReview = (e) => { e.preventDefault(); console.log('submit review', review); };

if (!product) {
    return <h3 className="text-center mt-5">Loading...</h3>; 
  }

  return (
    <main className="main-content" style={{marginTop: '80px'}}>
      {/* <section className="page-header-area pt-10 pb-9" style={{ backgroundColor: '#FFF3DA' }}>
        <div className="container">
          <div className="row">
            <div className="col-md-5">
              <div className="page-header-st3-content text-center text-md-start">
                <h2 className="page-header-title">Product Detail</h2>
              </div>
            </div>
            <div className="col-md-7">
              <h5 className="showing-pagination-results text-center text-md-end">Showing Single Product</h5>
            </div>
          </div>
        </div>
      </section> */}

      <section className="section-space">
        <div className="container">
          <div className="row product-details">
            <div className="col-lg-6">
              <div className="product-details-thumb shadow-lg rounded-5">
                <img src={product.image || shop2} width="570" height="693" alt={product.name} onError={(e) => e.target.src = shop4} />
                <span className="flag-new">new</span>
              </div>
            </div>

            <div className="col-lg-6">
              <div className="product-details-content">
                <h3 className="product-details-title">{product.name}</h3>

                <div className="product-details-review mb-7">
                  <div className="product-review-icon">
                    <span><FaStar /><FaStar /><FaStar /><FaStarHalfAlt /><FaRegStar /></span>
                  </div>
                  <button type="button" className="product-review-show">150 reviews</button>
                </div>

                <p className="mb-7">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Delectus, repellendus...</p>

                <div className="product-details-pro-qty">
                  <div className="pro-qty" style={{ display: 'flex', gap: 8, alignItems: 'center' }}>
                    <button type="button" className='rounded-3 border-2 rounded-circle' style={{background: 'none', borderColor: '#e63946'}} onClick={decrement}>-</button>
                    <input type="text" title="Quantity" value={String(qty).padStart(2, '0')} readOnly />
                    <button type="button" className='rounded-3 border-2 rounded-circle' style={{background: 'none', borderColor: '#e63946'}} onClick={increment}>+</button>
                  </div>
                </div>

                <div className="product-details-action" style={{ marginTop: 16 }}>
                  <h4 className="price text-success">₹{product.price}</h4>
                  <div className="product-details-cart-wishlist">
                    <button type="button" className="btn-wishlist" onClick={() => toast.success("Added to wishlist!")}><FaRegHeart style={{marginTop: '-25px', marginLeft: '-9px'}} /></button>
                    <button type="button" className="btn border-2" onClick={handleAddToCart} style={{borderColor: '#e63946'}}>Add to cart</button>
                  </div>
                    <ToastContainer />
                </div>
              </div>
            </div>
          </div>

          {/* <div className="row" style={{ marginTop: 24 }}>
            <div className="col-lg-7">
              <div className="nav product-details-nav" role="tablist">
                <button className={`nav-link ${activeTab === 'spec' ? 'active' : ''}`} onClick={() => setActiveTab('spec')}>Specification</button>
                <button className={`nav-link ${activeTab === 'review' ? 'active' : ''}`} onClick={() => setActiveTab('review')}>Review</button>
              </div>

              <div className="tab-content">
                {activeTab === 'spec' && (
                  <div className="tab-pane">
                    <ul className="product-details-info-wrap">
                      <li><span>Weight</span><p>250 g</p></li>
                      <li><span>Dimensions</span><p>10 x 10 x 15 cm</p></li>
                      <li><span>Materials</span><p>60% cotton, 40% polyester</p></li>
                      <li><span>Other Info</span><p>American heirloom jean shorts pug seitan letterpress</p></li>
                    </ul>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit...</p>
                  </div>
                )}

                {activeTab === 'review' && (
                  <div className="tab-pane show active">
                    <div className="product-review-item">
                      <div className="product-review-top">
                        <div className="product-review-thumb"><img src="assets/images/shop/product-details/comment1.webp" alt="comment" /></div>
                        <div className="product-review-content">
                          <span className="product-review-name">Tomas Doe</span>
                          <span className="product-review-designation">Developer</span>
                          <div className="product-review-icon">
                            <i className="fa fa-star-o" /><i className="fa fa-star-o" /><i className="fa fa-star-o" />
                            <i className="fa fa-star-o" /><i className="fa fa-star-half-o" />
                          </div>
                        </div>
                      </div>
                      <p className="desc">Lorem ipsum dolor sit amet, consectetur adipiscing elit...</p>
                      <button type="button" className="review-reply"><i className="fa fa-undo" /></button>
                    </div>

                  </div>
                )}
              </div>
            </div>

            <div className="col-lg-5">
              <div className="product-reviews-form-wrap">
                <h4 className="product-form-title">Leave a reply</h4>
                <div className="product-reviews-form">
                  <form onSubmit={handleSubmitReview}>
                    <div className="form-input-item">
                      <textarea className="form-control" placeholder="Enter your feedback" value={review.message} onChange={(e) => setReview(r => ({ ...r, message: e.target.value }))} />
                    </div>
                    <div className="form-input-item">
                      <input className="form-control" type="text" placeholder="Full Name" value={review.name} onChange={(e) => setReview(r => ({ ...r, name: e.target.value }))} />
                    </div>
                    <div className="form-input-item">
                      <input className="form-control" type="email" placeholder="Email Address" value={review.email} onChange={(e) => setReview(r => ({ ...r, email: e.target.value }))} />
                    </div>

                    <div className="form-input-item">
                      <div className="form-ratings-item">
                        <select id="product-review-form-rating-select" className="select-ratings" value={review.rating} onChange={(e) => setReview(r => ({ ...r, rating: e.target.value }))}>
                          <option value="1">01</option>
                          <option value="2">02</option>
                          <option value="3">03</option>
                          <option value="4">04</option>
                          <option value="5">05</option>
                        </select>
                        <span className="title">Provide Your Ratings</span>
                      </div>

                      <div className="reviews-form-checkbox" style={{ marginTop: 8 }}>
                        <input className="form-check-input" type="checkbox" id="ReviewsFormCheckbox" checked={review.anonymous} onChange={() => setReview(r => ({ ...r, anonymous: !r.anonymous }))} />
                        <label className="form-check-label" htmlFor="ReviewsFormCheckbox">Provide ratings anonymously.</label>
                      </div>
                    </div>

                    <div className="form-input-item mb-0">
                      <button type="submit" className="btn">SUBMIT</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div> */}
        </div>
      </section>

      <section class="container">
         <div class="card__container swiper">
            <div class="card__content">
               <div class="swiper-wrapper">
                  <article class="card__article swiper-slide">
                     <div class="card__image">
                        <img src="assets/img/avatar-1.png" alt="image" class="card__img"></img>
                        <div class="card__shadow"></div>
                     </div>
      
                     <div class="card__data">
                        <h3 class="card__name">Kell Dawx</h3>
                        <p class="card__description">
                           Passionate about development and design, 
                           I carry out projects at the request of users.
                        </p>
      
                        <a href="#" class="card__button">View More</a>
                     </div>
                  </article>
      
                  <article class="card__article swiper-slide">
                     <div class="card__image">
                        <img src="assets/img/avatar-2.png" alt="image" class="card__img"></img>
                        <div class="card__shadow"></div>
                     </div>
      
                     <div class="card__data">
                        <h3 class="card__name">Lotw Fox</h3>
                        <p class="card__description">
                           Passionate about development and design, 
                           I carry out projects at the request of users.
                        </p>
      
                        <a href="#" class="card__button">View More</a>
                     </div>
                  </article>
      
                  <article class="card__article swiper-slide">
                     <div class="card__image">
                        <img src="assets/img/avatar-3.png" alt="image" class="card__img"></img>
                        <div class="card__shadow"></div>
                     </div>
      
                     <div class="card__data">
                        <h3 class="card__name">Sara Mit</h3>
                        <p class="card__description">
                           Passionate about development and design, 
                           I carry out projects at the request of users.
                        </p>
      
                        <a href="#" class="card__button">View More</a>
                     </div>
                  </article>
      
                  <article class="card__article swiper-slide">
                     <div class="card__image">
                        <img src="assets/img/avatar-4.png" alt="image" class="card__img"></img>
                        <div class="card__shadow"></div>
                     </div>
      
                     <div class="card__data">
                        <h3 class="card__name">Jenny Wert</h3>
                        <p class="card__description">
                           Passionate about development and design, 
                           I carry out projects at the request of users.
                        </p>
      
                        <a href="#" class="card__button">View More</a>
                     </div>
                  </article>

                  <article class="card__article swiper-slide">
                     <div class="card__image">
                        <img src="assets/img/avatar-5.png" alt="image" class="card__img"></img>
                        <div class="card__shadow"></div>
                     </div>
      
                     <div class="card__data">
                        <h3 class="card__name">Lexa Kin</h3>
                        <p class="card__description">
                           Passionate about development and design, 
                           I carry out projects at the request of users.
                        </p>
      
                        <a href="#" class="card__button">View More</a>
                     </div>
                  </article>
               </div>
            </div>

            <div class="swiper-button-next">
               <i class="ri-arrow-right-s-line"></i>
            </div>
            
            <div class="swiper-button-prev">
               <i class="ri-arrow-left-s-line"></i>
            </div>

            <div class="swiper-pagination"></div>
         </div>
      </section>

      <div className="container" style={{ marginTop: 24 }}>
        <a href="/products" className="product-banner-item">
          <img src={banner7} width="1170" height="240" alt="banner" />
        </a>
      </div>
        
      <section className="section-space">
        <div className="container">
          <div className="row">
            <div className="col-12">
              <div className="section-title">
                <h2 className="title">Related Products</h2>
                <p className="m-0">Lorem ipsum dolor sit amet, consectetur adipiscing elit ut aliquam...</p>
              </div>
            </div>
          </div>
        </div>
      </section>
      <section className="section-space pb-5">
          <div className="container">
            <div className="row mb-n4 mb-sm-n10 g-3 g-sm-6">
              {firstThree.map((product) => (
              <div className="col-6 col-lg-4 mb-4 mb-sm-8" key={product.id}>
                <div className="product-item text-start">
                  <div className="product-thumb">
                    <a className="d-block" href="product-details.html"></a>
                    <ProductItem product={product} />
                  </div>
                </div>
              </div>
              ))}
            </div>
          </div>
        </section>
      <Footer />
    </main>
  );
};

export default ProductDetailsNormal;
