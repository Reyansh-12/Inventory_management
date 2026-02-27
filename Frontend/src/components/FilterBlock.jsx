import { useState } from "react";

const FilterBlock = ({ title, items, initialCount = 5 }) => {
  const [showAll, setShowAll] = useState(false);

  const visibleItems = showAll ? items : items.slice(0, initialCount);

  return (
    <div className="filter-block">
      <h6>{title}</h6>

      {visibleItems.map(item => (
        <label className="filter-option" key={item}>
          <input type="checkbox" /> {item}
        </label>
      ))}

      {items.length > initialCount && (
        <button
          type="button"
          className="show-more-btn"
          onClick={() => setShowAll(!showAll)}
        >
          {showAll ? "Show Less" : `Show More (${items.length - initialCount})`}
        </button>
      )}
    </div>
  );
};

export default FilterBlock;