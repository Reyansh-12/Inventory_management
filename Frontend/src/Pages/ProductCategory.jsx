import React from 'react';
import category1 from "/xampp/htdocs/Inventory_management/Frontend/public/images/shop/category/1.webp";
import category2 from "../assets/images/shop/category/category2.webp";
import category3 from "../assets/images/shop/category/category3.webp";
import category4 from "../assets/images/shop/category/category4.webp";
import category5 from "../assets/images/shop/category/category5.webp";
import category6 from "../assets/images/shop/category/category6.webp";

const ProductCategory = () => {
  const categories = [
    { id: 1,category:'haircare' ,title: 'Hair care', image: category1, badge: 'new', bgColor: null,},
    { id: 2,category:'skincare' ,title: 'Skin care', image: category2, bgColor: '#FFEDB4' },
    { id: 3,category:'lipstick' ,title: 'Lip stick', image: category3, bgColor: '#DFE4FF' },
    { id: 4,category:'faceskin' ,title: 'Face skin', image: category4, badge: 'sale', badgeBgColor: '#835BF4', bgColor: '#FFEACC' },
    { id: 5,category:'blusher' ,title: 'Blusher', image: category5, bgColor: '#FFDAE0' },
    { id: 6,category:'natural' ,title: 'Natural', image: category6, bgColor: '#FFF3DA' },
  ];

  return (
    <section className="section-space pb-0">
      <div className="container">
        <div className="row g-3 g-sm-6">
          {categories.map((cat) => (
            <div key={cat.id} className="col-6 col-lg-2">
              <a
                href="/product"
                className="product-category-item"
                style={cat.bgColor ? { backgroundColor: cat.bgColor } : {}}
              >
                <img className="icon" src={cat.image} width="80" height="80" alt={cat.title} />
                <h3 className="title">{cat.title}</h3>
                {cat.badge && (
                  <span
                    className="flag-new"
                    style={cat.badgeBgColor ? { backgroundColor: cat.badgeBgColor } : {}}
                  >
                    {cat.badge}
                  </span>
                )}
              </a>
            </div>
          ))}
        </div>
      </div>
    </section>
  );
};

export default ProductCategory;