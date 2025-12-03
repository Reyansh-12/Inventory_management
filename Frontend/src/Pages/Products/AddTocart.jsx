const addToCart = () => {
    fetch("http://localhost/Inventory_management/Backend/src/Pages/APIs/updateQuantityAPI.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ id: product.id })
    })
      .then((res) => res.json())
      .then((data) => {
        if (data.success) {
          console.log("Quantity updated!");
          navigate('/ProductDetailsNormal');
        } else {
          console.log("Update failed:", data.error);
        }
      })
      .catch(err => console.log("API Error:", err));
  };
  export default addToCart;