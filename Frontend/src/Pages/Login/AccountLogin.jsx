import React from 'react';

const AccountLogin = () => {
  const handleLogin = (e) => {
    e.preventDefault();
    const fm = new FormData(e.target);
    console.log('login', Object.fromEntries(fm.entries()));
  };

  const handleRegister = (e) => {
    e.preventDefault();
    const fm = new FormData(e.target);
    console.log('register', Object.fromEntries(fm.entries()));
  };

  return (
    <div className="wrapper">
      <main className="main-content">
        <section className="page-header-area pt-10 pb-9" style={{ backgroundColor: '#FFF3DA' }}>
          <div className="container">
            <div className="row">
              <div className="col-md-5">
                <div className="page-header-st3-content text-center text-md-start">
                  <ol className="breadcrumb justify-content-center justify-content-md-start">
                    <li className="breadcrumb-item"><a className="text-dark" href="/">Home</a></li>
                    <li className="breadcrumb-item active text-dark" aria-current="page">Account</li>
                  </ol>
                  <h2 className="page-header-title">Account</h2>
                </div>
              </div>
            </div>
          </div>
        </section>

        <section className="section-space">
          <div className="container">
            <div className="row mb-n8">
              <div className="col-lg-6 mb-8">
                <div className="my-account-item-wrap">
                  <h3 className="title">Login</h3>
                  <div className="my-account-form">
                    <form onSubmit={handleLogin}>
                      <div className="form-group mb-6">
                        <label htmlFor="login_username">Username or Email Address <sup>*</sup></label>
                        <input type="email" id="login_username" name="username" className="form-control" />
                      </div>

                      <div className="form-group mb-6">
                        <label htmlFor="login_pwsd">Password <sup>*</sup></label>
                        <input type="password" id="login_pwsd" name="password" className="form-control" />
                      </div>

                      <div className="form-group d-flex align-items-center mb-14">
                        <button className="btn" type="submit">Login</button>

                        <div className="form-check ms-3">
                          <input type="checkbox" className="form-check-input" id="remember_pwsd" name="remember" />
                          <label className="form-check-label" htmlFor="remember_pwsd">Remember Me</label>
                        </div>
                      </div>

                      <a className="lost-password" href="/my-account">Lost your Password?</a>
                    </form>
                  </div>
                </div>
              </div>

              <div className="col-lg-6 mb-8">
                <div className="my-account-item-wrap">
                  <h3 className="title">Register</h3>
                  <div className="my-account-form">
                    <form onSubmit={handleRegister}>
                      <div className="form-group mb-6">
                        <label htmlFor="register_username">Username or Email Address <sup>*</sup></label>
                        <input type="email" id="register_username" name="register_email" className="form-control" />
                      </div>

                      <div className="form-group mb-6">
                        <label htmlFor="register_pwsd">Password <sup>*</sup></label>
                        <input type="password" id="register_pwsd" name="register_password" className="form-control" />
                      </div>

                      <div className="form-group">
                        <p className="desc mb-4">Your personal data will be used to support your experience throughout this website, to manage access to your account, and for other purposes described in our privacy policy.</p>
                        <button className="btn" type="submit">Register</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>

            </div>
          </div>
        </section>
      </main>

      

      {/* Modals / Offcanvas (kept markup for Bootstrap) */}
      <div id="scroll-to-top" className="scroll-to-top"><span className="fa fa-angle-up" /></div>

      <aside className="product-action-modal modal fade" id="action-WishlistModal" tabIndex={-1} aria-hidden="true">
        <div className="modal-dialog modal-dialog-centered">
          <div className="modal-content"><div className="modal-body">
            <div className="product-action-view-content">
              <button type="button" className="btn-close" data-bs-dismiss="modal"><i className="fa fa-times" /></button>
              <div className="modal-action-messages"><i className="fa fa-check-square-o" /> Added to wishlist successfully!</div>
              <div className="modal-action-product">
                <div className="thumb"><img src="/assets/images/shop/modal1.webp" alt="" width="466" height="320" /></div>
                <h4 className="product-name"><a href="/product-details">Readable content DX22</a></h4>
              </div>
            </div>
          </div></div>
        </div>
      </aside>

      <aside className="product-action-modal modal fade" id="action-CartAddModal" tabIndex={-1} aria-hidden="true">
        <div className="modal-dialog modal-dialog-centered">
          <div className="modal-content"><div className="modal-body">
            <div className="product-action-view-content">
              <button type="button" className="btn-close" data-bs-dismiss="modal"><i className="fa fa-times" /></button>
              <div className="modal-action-messages"><i className="fa fa-check-square-o" /> Added to cart successfully!</div>
              <div className="modal-action-product">
                <div className="thumb"><img src="/assets/images/shop/modal1.webp" alt="" width="466" height="320" /></div>
                <h4 className="product-name"><a href="/product-details">Readable content DX22</a></h4>
              </div>
            </div>
          </div></div>
        </div>
      </aside>

      <aside className="aside-search-box-wrapper offcanvas offcanvas-top" tabIndex={-1} id="AsideOffcanvasSearch" aria-labelledby="offcanvasTopLabel">
        <div className="offcanvas-header">
          <h5 className="d-none" id="offcanvasTopLabel">Aside Search</h5>
          <button type="button" className="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"><i className="fa fa-close" /></button>
        </div>
        <div className="offcanvas-body">
          <div className="container pt--0 pb--0">
            <div className="search-box-form-wrap">
              <div className="search-note"><p>Start typing and press Enter to search</p></div>
              <form>
                <div className="aside-search-form position-relative">
                  <label htmlFor="SearchInput" className="visually-hidden">Search</label>
                  <input id="SearchInput" type="search" className="form-control" placeholder="Search entire store…" />
                  <button className="search-button" type="submit"><i className="fa fa-search" /></button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </aside>

      <aside className="product-cart-view-modal modal fade" id="action-QuickViewModal" tabIndex={-1} aria-hidden="true">
        <div className="modal-dialog modal-dialog-centered">
          <div className="modal-content"><div className="modal-body">
            <div className="product-quick-view-content">
              <button type="button" className="btn-close" data-bs-dismiss="modal"><span className="fa fa-close" /></button>
              <div className="container">
                <div className="row">
                  <div className="col-lg-6"><div className="product-single-thumb"><img src="/assets/images/shop/quick-view1.webp" width="544" height="560" alt="" /></div></div>
                  <div className="col-lg-6">
                    <div className="product-details-content">
                      <h5 className="product-details-collection">Premioum collection</h5>
                      <h3 className="product-details-title">Offbline Instant Age Rewind Eraser.</h3>
                      <div className="product-details-review mb-5">
                        <div className="product-review-icon"><i className="fa fa-star-o" /><i className="fa fa-star-o" /><i className="fa fa-star-o" /><i className="fa fa-star-o" /><i className="fa fa-star-half-o" /></div>
                        <button type="button" className="product-review-show">150 reviews</button>
                      </div>
                      <p className="mb-6">Lorem ipsum dolor, sit amet consectetur adipisicing elit.</p>
                      <div className="product-details-pro-qty"><div className="pro-qty"><input type="text" title="Quantity" defaultValue="01" /></div></div>
                      <div className="product-details-action"><h4 className="price">$254.22</h4><div className="product-details-cart-wishlist"><button type="button" className="btn" data-bs-toggle="modal" data-bs-target="#action-CartAddModal">Add to cart</button></div></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div></div>
        </div>
      </aside>

      <aside className="aside-cart-wrapper offcanvas offcanvas-end" tabIndex={-1} id="AsideOffcanvasCart" aria-labelledby="offcanvasRightLabel">
        <div className="offcanvas-header">
          <h1 className="d-none" id="offcanvasRightLabel">Shopping Cart</h1>
          <button className="btn-aside-cart-close" data-bs-dismiss="offcanvas" aria-label="Close">Shopping Cart <i className="fa fa-chevron-right" /></button>
        </div>
        <div className="offcanvas-body">
          <ul className="aside-cart-product-list">
            <li className="aside-product-list-item">
              <a href="#/" className="remove">×</a>
              <a href="/product-details">
                <img src="/assets/images/shop/cart1.webp" width="68" height="84" alt="Image" />
                <span className="product-title">Leather Mens Slipper</span>
              </a>
              <span className="product-price">1 × £69.99</span>
            </li>
            <li className="aside-product-list-item">
              <a href="#/" className="remove">×</a>
              <a href="/product-details">
                <img src="/assets/images/shop/cart2.webp" width="68" height="84" alt="Image" />
                <span className="product-title">Quickiin Mens shoes</span>
              </a>
              <span className="product-price">1 × £20.00</span>
            </li>
          </ul>
          <p className="cart-total"><span>Subtotal:</span><span className="amount">£89.99</span></p>
          <a className="btn-total" href="/product-cart">View cart</a>
          <a className="btn-total" href="/product-checkout">Checkout</a>
        </div>
      </aside>

      <aside className="off-canvas-wrapper offcanvas offcanvas-start" tabIndex={-1} id="AsideOffcanvasMenu" aria-labelledby="offcanvasExampleLabel">
        <div className="offcanvas-header">
          <h1 className="d-none" id="offcanvasExampleLabel">Aside Menu</h1>
          <button className="btn-menu-close" data-bs-dismiss="offcanvas" aria-label="Close">menu <i className="fa fa-chevron-left" /></button>
        </div>
        <div className="offcanvas-body">
          <div id="offcanvasNav" className="offcanvas-menu-nav">
            <ul>
              <li className="offcanvas-nav-parent"><a className="offcanvas-nav-item" href="#">home</a>
                <ul>
                  <li><a href="/">Home One</a></li>
                  <li><a href="/index-two">Home Two</a></li>
                </ul>
              </li>
              <li className="offcanvas-nav-parent"><a className="offcanvas-nav-item" href="/about-us">about</a></li>
              <li className="offcanvas-nav-parent"><a className="offcanvas-nav-item" href="#">shop</a>
                <ul>
                  <li><a href="#" className="offcanvas-nav-item">Shop Layout</a>
                    <ul>
                      <li><a href="/product">Shop 3 Column</a></li>
                      <li><a href="/product-four-columns">Shop 4 Column</a></li>
                      <li><a href="/product-left-sidebar">Shop Left Sidebar</a></li>
                      <li><a href="/product-right-sidebar">Shop Right Sidebar</a></li>
                    </ul>
                  </li>
                  <li><a href="#" className="offcanvas-nav-item">Single Product</a>
                    <ul>
                      <li><a href="/product-details-normal">Single Product Normal</a></li>
                      <li><a href="/product-details">Single Product Variable</a></li>
                      <li><a href="/product-details-group">Single Product Group</a></li>
                      <li><a href="/product-details-affiliate">Single Product Affiliate</a></li>
                    </ul>
                  </li>
                  <li><a href="#" className="offcanvas-nav-item">Others Pages</a>
                    <ul>
                      <li><a href="/product-cart">Shopping Cart</a></li>
                      <li><a href="/product-checkout">Checkout</a></li>
                      <li><a href="/product-wishlist">Wishlist</a></li>
                      <li><a href="/product-compare">Compare</a></li>
                    </ul>
                  </li>
                </ul>
              </li>
              <li className="offcanvas-nav-parent"><a className="offcanvas-nav-item" href="#">Blog</a>
                <ul>
                  <li><a className="offcanvas-nav-item" href="#">Blog Layout</a>
                    <ul>
                      <li><a href="/blog">Blog Grid</a></li>
                      <li><a href="/blog-left-sidebar">Blog Left Sidebar</a></li>
                      <li><a href="/blog-right-sidebar">Blog Right Sidebar</a></li>
                    </ul>
                  </li>
                  <li><a href="/blog-details">Blog Details</a></li>
                </ul>
              </li>
              <li className="offcanvas-nav-parent"><a className="offcanvas-nav-item" href="#">Pages</a>
                <ul>
                  <li><a href="/account-login">My Account</a></li>
                  <li><a href="/faq">Frequently Questions</a></li>
                  <li><a href="/page-not-found">Page Not Found</a></li>
                </ul>
              </li>
              <li className="offcanvas-nav-parent"><a className="offcanvas-nav-item" href="/contact">Contact</a></li>
            </ul>
          </div>
        </div>
      </aside>
    </div>
  );
};

export default AccountLogin;
