document.addEventListener("DOMContentLoaded", function() {
    const params = new URLSearchParams(window.location.search);

    let toastElement = document.getElementById("actionToast");
    let toastMessage = document.getElementById("toastMessage");

    if (params.get("added") === "1") {
        toastMessage.textContent = "Product added successfully!";
        toastElement.classList.add("text-bg-success", "text-white");
    } 
    else if (params.get("updated") === "1") {
        toastMessage.textContent = "Product updated successfully!";
        toastElement.classList.add("text-bg-success", "text-white");
    } 
    else {
        return; 
    }

    let timerBar = toastElement.querySelector(".toast-timer");
    timerBar.style.animation = "none";
    timerBar.offsetHeight;
    timerBar.style.animation = "shrink 5s linear forwards";

    let toast = new bootstrap.Toast(toastElement, { delay: 3000 });
    toast.show();

    setTimeout(() => {
        window.history.replaceState({}, document.title, window.location.pathname);
    }, 3500);
});


function enableBrandSelector() {
    let category = document.getElementById("categorySelector").value;
    let brandSelect = document.getElementById("brandSelect");

    brandSelect.removeAttribute("disabled");

    let options = brandSelect.querySelectorAll("option");

    options.forEach(option => {
        if (option.classList.contains(category) || option.value === "Choose Brand") {
            option.style.display = "block"; 
        } else {
            option.style.display = "none";    
        }
    });

    brandSelect.value = "";
}

$(function() {
    $("#datepicker").datepicker({
        dateFormat: "yy-mm-dd",
        changeMonth: true,
        changeYear: true,
        showAnim: "slideDown",
        minDate: 0,
        yearRange: "2024:2035"
    });
});

document.getElementById('productImage').addEventListener('change', function () {
    const file = this.files[0];
    const title = this.closest('.image-upload').querySelector('h4');

    if (file) {
        title.textContent = file.name;   
    } else {
        title.textContent = "Drag and drop a file to upload"; 
    }
});