import React from 'react';
import blog1 from "../assets/images/blog/1.webp";
import blog2 from "../assets/images/blog/2.webp";
import blog3 from "../assets/images/blog/3.webp";
const BlogPage = () => {
  return (
    <div className="wrapper">
         
        <section className="section-space">
          <div className="container">
            <div className="row">
              <div className="col-12">
                <div className="section-title text-center">
                  <h2 className="title">Blog posts</h2>
                  <p>
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit ut
                    aliquam, purus sit amet luctus venenatis
                  </p>
                </div>
              </div>
            </div>
            <div className="row mb-n9">
              <div className="col-sm-6 col-lg-4 mb-8">
                <div className="post-item">
                  <a href="blog-details.html" className="thumb">
                    <img
                      src={blog1}
                      width="370"
                      height="320"
                      alt="Image-HasTech"
                    />
                  </a>
                  <div className="content">
                    <a className="post-category text-decoration-none" href="blog.html">
                      beauty
                    </a>
                    <h4 className="title">
                      <a href="blog-details.html" className='text-decoration-none'>
                        Lorem ipsum dolor sit amet consectetur adipiscing.
                      </a>
                    </h4>
                    <ul className="meta">
                      <li className="author-info">
                        <span>By:</span> <a href="blog.html" className='text-decoration-none'>Tomas De Momen</a>
                      </li>
                      <li className="post-date">February 13, 2022</li>
                    </ul>
                  </div>
                </div>
              </div>

              <div className="col-sm-6 col-lg-4 mb-8">
                <div className="post-item">
                  <a href="blog-details.html" className="thumb">
                    <img
                      src={blog2}
                      width="370"
                      height="320"
                      alt="Image-HasTech"
                    />
                  </a>
                  <div className="content">
                    <a
                      className="post-category post-category-two text-decoration-none"
                      data-bg-color="#A49CFF"
                      href="blog.html"
                    >
                      beauty
                    </a>
                    <h4 className="title">
                      <a href="blog-details.html" className='text-decoration-none'>
                        Facial Scrub is natural treatment for face.
                      </a>
                    </h4>
                    <ul className="meta">
                      <li className="author-info">
                        <span>By:</span> <a href="blog.html" className='text-decoration-none'>Tomas De Momen</a>
                      </li>
                      <li className="post-date">February 13, 2022</li>
                    </ul>
                  </div>
                </div>
              </div>

              <div className="col-sm-6 col-lg-4 mb-8">
                <div className="post-item">
                  <a href="blog-details.html" className="thumb">
                    <img
                      src={blog3}
                      width="370"
                      height="320"
                      alt="Image-HasTech"
                    />
                  </a>
                  <div className="content">
                    <a
                      className="post-category post-category-three text-decoration-none"
                      data-bg-color="#9CDBFF"
                      href="blog.html"
                    >
                      beauty
                    </a>
                    <h4 className="title">
                      <a href="blog-details.html" className='text-decoration-none'>
                        Benefit of Hot Ston Spa for your health &amp; life.
                      </a>
                    </h4>
                    <ul className="meta">
                      <li className="author-info">
                        <span>By:</span> <a href="blog.html" className='text-decoration-none'>Tomas De Momen</a>
                      </li>
                      <li className="post-date">February 13, 2022</li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
      <div id="scroll-to-top" className="scroll-to-top"><span className="fa fa-angle-up"></span></div>

      <aside className="product-action-modal modal fade" id="action-WishlistModal" tabIndex="-1" aria-hidden="true">
        <div className="modal-dialog modal-dialog-centered">
          <div className="modal-content">
            <div className="modal-body">
              <div className="product-action-view-content">
                <button type="button" className="btn-close" data-bs-dismiss="modal">
                  <i className="fa fa-times"></i>
                </button>
                <div className="modal-action-messages">
                  <i className="fa fa-check-square-o"></i> Added to wishlist successfully!
                </div>
                <div className="modal-action-product">
                  <div className="thumb">
                    <img src="/assets/images/shop/modal1.webp" alt="Organic Food Juice" width="466" height="320" />
                  </div>
                  <h4 className="product-name"><a href="product-details.html">Readable content DX22</a></h4>
                </div>
              </div>
            </div>
          </div>
        </div>
      </aside>

      <aside className="product-action-modal modal fade" id="action-CartAddModal" tabIndex="-1" aria-hidden="true">
        <div className="modal-dialog modal-dialog-centered">
          <div className="modal-content">
            <div className="modal-body">
              <div className="product-action-view-content">
                <button type="button" className="btn-close" data-bs-dismiss="modal">
                  <i className="fa fa-times"></i>
                </button>
                <div className="modal-action-messages">
                  <i className="fa fa-check-square-o"></i> Added to cart successfully!
                </div>
                <div className="modal-action-product">
                  <div className="thumb">
                    <img src="/assets/images/shop/modal1.webp" alt="Organic Food Juice" width="466" height="320" />
                  </div>
                  <h4 className="product-name"><a href="product-details.html">Readable content DX22</a></h4>
                </div>
              </div>
            </div>
          </div>
        </div>
      </aside>

      <aside className="aside-search-box-wrapper offcanvas offcanvas-top" tabIndex="-1" id="AsideOffcanvasSearch" aria-labelledby="offcanvasTopLabel">
        <div className="offcanvas-header">
          <h5 className="d-none" id="offcanvasTopLabel">Aside Search</h5>
          <button type="button" className="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"><i className="fa fa-close"></i></button>
        </div>
        <div className="offcanvas-body">
          <div className="container pt--0 pb--0">
            <div className="search-box-form-wrap">
              <div className="search-note">
                <p>Start typing and press Enter to search</p>
              </div>
              <form action="#" method="post">
                <div className="aside-search-form position-relative">
                  <label htmlFor="SearchInput" className="visually-hidden">Search</label>
                  <input id="SearchInput" type="search" className="form-control" placeholder="Search entire store…" />
                  <button className="search-button" type="submit"><i className="fa fa-search"></i></button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </aside>

      <aside className="product-cart-view-modal modal fade" id="action-QuickViewModal" tabIndex="-1" aria-hidden="true">
        <div className="modal-dialog modal-dialog-centered">
          <div className="modal-content">
            <div className="modal-body">
              <div className="product-quick-view-content">
                <button type="button" className="btn-close" data-bs-dismiss="modal">
                  <span className="fa fa-close"></span>
                </button>
                <div className="container">
                  <div className="row">
                    <div className="col-lg-6">
                      <div className="product-single-thumb">
                        <img src="/assets/images/shop/quick-view1.webp" width="544" height="560" alt="Image-HasTech" />
                      </div>
                    </div>
                    <div className="col-lg-6">
                      <div className="product-details-content">
                        <h5 className="product-details-collection">Premioum collection</h5>
                        <h3 className="product-details-title">Offbline Instant Age Rewind Eraser.</h3>
                        <div className="product-details-review mb-5">
                          <div className="product-review-icon">
                            <i className="fa fa-star-o"></i>
                            <i className="fa fa-star-o"></i>
                            <i className="fa fa-star-o"></i>
                            <i className="fa fa-star-o"></i>
                            <i className="fa fa-star-half-o"></i>
                          </div>
                          <button type="button" className="product-review-show">150 reviews</button>
                        </div>
                        <p className="mb-6">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Delectus, repellendus. Nam voluptate illo ut quia non sapiente provident alias quos laborum incidunt, earum accusamus, natus. Vero pariatur ut veniam sequi amet consectetur.</p>
                        <div className="product-details-pro-qty">
                          <div className="pro-qty">
                            <input type="text" title="Quantity" defaultValue="01" />
                          </div>
                        </div>
                        <div className="product-details-action">
                          <h4 className="price">$254.22</h4>
                          <div className="product-details-cart-wishlist">
                            <button type="button" className="btn" data-bs-toggle="modal" data-bs-target="#action-CartAddModal">Add to cart</button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </aside>

      <aside className="aside-cart-wrapper offcanvas offcanvas-end" tabIndex="-1" id="AsideOffcanvasCart" aria-labelledby="offcanvasRightLabel">
        <div className="offcanvas-header">
          <h1 className="d-none" id="offcanvasRightLabel">Shopping Cart</h1>
          <button className="btn-aside-cart-close" data-bs-dismiss="offcanvas" aria-label="Close">Shopping Cart <i className="fa fa-chevron-right"></i></button>
        </div>
        <div className="offcanvas-body">
          <ul className="aside-cart-product-list">
            <li className="aside-product-list-item">
              <a href="#/" className="remove">×</a>
              <a href="product-details.html">
                <img src="/assets/images/shop/cart1.webp" width="68" height="84" alt="Image" />
                <span className="product-title">Leather Mens Slipper</span>
              </a>
              <span className="product-price">1 × £69.99</span>
            </li>
            <li className="aside-product-list-item">
              <a href="#/" className="remove">×</a>
              <a href="product-details.html">
                <img src="/assets/images/shop/cart2.webp" width="68" height="84" alt="Image" />
                <span className="product-title">Quickiin Mens shoes</span>
              </a>
              <span className="product-price">1 × £20.00</span>
            </li>
          </ul>
          <p className="cart-total"><span>Subtotal:</span><span className="amount">£89.99</span></p>
          <a className="btn-total" href="product-cart.html">View cart</a>
          <a className="btn-total" href="product-checkout.html">Checkout</a>
        </div>
      </aside>

      <aside className="off-canvas-wrapper offcanvas offcanvas-start" tabIndex="-1" id="AsideOffcanvasMenu" aria-labelledby="offcanvasExampleLabel">
        <div className="offcanvas-header">
          <h1 className="d-none" id="offcanvasExampleLabel">Aside Menu</h1>
          <button className="btn-menu-close" data-bs-dismiss="offcanvas" aria-label="Close">menu <i className="fa fa-chevron-left"></i></button>
        </div>
        <div className="offcanvas-body">
          <div id="offcanvasNav" className="offcanvas-menu-nav">
            <ul>
              <li className="offcanvas-nav-parent"><a className="offcanvas-nav-item" href="#">home</a>
                <ul>
                  <li><a href="index.html">Home One</a></li>
                  <li><a href="index-two.html">Home Two</a></li>
                </ul>
              </li>
              <li className="offcanvas-nav-parent"><a className="offcanvas-nav-item" href="about-us.html">about</a></li>
              <li className="offcanvas-nav-parent"><a className="offcanvas-nav-item" href="#">shop</a>
                <ul>
                  <li><a href="#" className="offcanvas-nav-item">Shop Layout</a>
                    <ul>
                      <li><a href="product.html">Shop 3 Column</a></li>
                      <li><a href="product-four-columns.html">Shop 4 Column</a></li>
                      <li><a href="product-left-sidebar.html">Shop Left Sidebar</a></li>
                      <li><a href="product-right-sidebar.html">Shop Right Sidebar</a></li>
                    </ul>
                  </li>
                  <li><a href="#" className="offcanvas-nav-item">Single Product</a>
                    <ul>
                      <li><a href="product-details-normal.html">Single Product Normal</a></li>
                      <li><a href="product-details.html">Single Product Variable</a></li>
                      <li><a href="product-details-group.html">Single Product Group</a></li>
                      <li><a href="product-details-affiliate.html">Single Product Affiliate</a></li>
                    </ul>
                  </li>
                  <li><a href="#" className="offcanvas-nav-item">Others Pages</a>
                    <ul>
                      <li><a href="product-cart.html">Shopping Cart</a></li>
                      <li><a href="product-checkout.html">Checkout</a></li>
                      <li><a href="product-wishlist.html">Wishlist</a></li>
                      <li><a href="product-compare.html">Compare</a></li>
                    </ul>
                  </li>
                </ul>
              </li>
              <li className="offcanvas-nav-parent"><a className="offcanvas-nav-item" href="#">Blog</a>
                <ul>
                  <li><a className="offcanvas-nav-item" href="#">Blog Layout</a>
                    <ul>
                      <li><a href="blog.html">Blog Grid</a></li>
                      <li><a href="blog-left-sidebar.html">Blog Left Sidebar</a></li>
                      <li><a href="blog-right-sidebar.html">Blog Right Sidebar</a></li>
                    </ul>
                  </li>
                  <li><a href="blog-details.html">Blog Details</a></li>
                </ul>
              </li>
              <li className="offcanvas-nav-parent"><a className="offcanvas-nav-item" href="#">Pages</a>
                <ul>
                  <li><a href="account-login.html">My Account</a></li>
                  <li><a href="faq.html">Frequently Questions</a></li>
                  <li><a href="page-not-found.html">Page Not Found</a></li>
                </ul>
              </li>
              <li className="offcanvas-nav-parent"><a className="offcanvas-nav-item" href="contact.html">Contact</a></li>
            </ul>
          </div>
        </div>
      </aside>

    </div>
  );
};

export default BlogPage;