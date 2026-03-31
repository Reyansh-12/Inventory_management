
document.getElementById('productImage').addEventListener('change', function() {
    const file = this.files[0];
    const title = document.getElementById('imageUploadTitle');
    if (file) {
        title.textContent = file.name;
    } else {
        title.textContent = "Drag and drop a file to upload";
    }
});
document.addEventListener("DOMContentLoaded", function() {
    if (isEditMode) {
        document.getElementById("brandSelect").disabled = false;
    }
});
$('#discountInput').on('input', function() {
    let inputValue = $(this).val();
    let filteredValue = inputValue.replace(/[^0-9]/g, '');
    $(this).val(filteredValue);
});
let parsleyForm = $('#myForm').parsley();
$('#resetButton').on('click', function() {
    parsleyForm.reset();
});
document.addEventListener("DOMContentLoaded", function() {
    let toastElement = document.getElementById("actionToast");
    let toastMessage = document.getElementById("toastMessage");
    if (params.get("added") === "1") {
        toastMessage.textContent = "Product added successfully!";
        toastElement.classList.add("text-bg-success", "text-white");
    } else if (params.get("updated") === "1") {
        toastMessage.textContent = "Product updated successfully!";
        toastElement.classList.add("text-bg-success", "text-white");
    } else {
        return;
    }
    let timerBar = toastElement.querySelector(".toast-timer");
    timerBar.style.animation = "none";
    timerBar.offsetHeight;
    timerBar.style.animation = "shrink 5s linear forwards";
    let toast = new bootstrap.Toast(toastElement, {
        delay: 3000
    });
    toast.show();
    setTimeout(() => {
        window.history.replaceState({}, document.title, window.location.pathname);
    }, 3500);
});
function filterBrandsByCategory() {
    const categorySelect = document.getElementById("categorySelector");
    const brandSelect = document.getElementById("brandSelect");
    const selectedCategory = categorySelect.value;

    if (!selectedCategory) {
        brandSelect.disabled = true;
        return;
    }
    brandSelect.disabled = false;
    const options = brandSelect.querySelectorAll("option");
    options.forEach(option => {
        if (option.disabled || option.value === "") {
            option.style.display = "block";
            return;
        }
        if (option.classList.contains(selectedCategory)) {
            option.style.display = "block";
        } else {
            option.style.display = "none";
        }
    });
}
document.getElementById('productImage').addEventListener('change', function() {
    const file = this.files[0];
    const title = this.closest('.image-upload').querySelector('h4');
    if (file) {
        title.textContent = file.name;
    } else {
        title.textContent = "Drag and drop a file to upload";
    }
});
$(function() {
    window.Parsley.addValidator('gteMinquantity', {
        validateNumber: function(value) {
            let minQty = parseInt($('#minQuantity').val(), 10);
            if (isNaN(minQty)) return true;
            return value >= minQty;
        },
        messages: {
            en: 'Max Quantity must be greater than or equal to Min Quantity'
        }
    });
    $('#quantity').on('keydown', function() {
        let parsleyField = $(this).parsley();
        setTimeout(() => {
            parsleyField.validate();
        }, 0);
    });
    $('#minQuantity').on('keydown', function() {
        setTimeout(() => {
            $('#quantity').parsley().validate();
        }, 0);
    });
});
function validateProductName() {
    const input = document.getElementById('productName');
    const error = document.getElementById('productNameError');

    if (input.value.length > 0 && input.value.length < 3) {
        error.style.display = 'block';
    } else {
        error.style.display = 'none';
    }
}
function validateQuantity() {
    const minInput = document.getElementById('minQuantity');
    const maxInput = document.getElementById('quantity');
    const minError = document.getElementById('minQuantityError');
    const maxError = document.getElementById('maxError');
    const minValue = minInput.value;
    const maxValue = maxInput.value;
    if (minValue === '') {
        minError.style.display = 'block';
    } else {
        minError.style.display = 'none';
    }

    if (maxValue !== '' && minValue !== '' && Number(maxValue) < Number(minValue)) {
        maxError.style.display = 'block';
    } else {
        maxError.style.display = 'none';
    }
}
document.getElementById('price').addEventListener('input', function() {
    let value = this.value;
    if (value < 0) {
        this.value = 0;
        return;
    }
    if (Number(value) > 1000000) {
        this.value = 1000000;
    }
});
document.getElementById('productImage').addEventListener('change', function() {
    const file = this.files[0];
    const preview = document.getElementById('imagePreview');
    const title = document.getElementById('imageUploadTitle');
    const errorBox = document.getElementById('imageError');
    errorBox.style.display = 'none';
    errorBox.innerText = '';
    if (!file) return;
    if (!ALLOWED_TYPES.includes(file.type)) {
        errorBox.innerText = "Only JPG, PNG, and WEBP image formats are allowed.";
        errorBox.style.display = 'block';
        resetImageInput();
        return;
    }
    if (file.size > MAX_IMAGE_SIZE) {
        errorBox.innerText = "Image size must be less than 100KB.";
        errorBox.style.display = 'block';
        resetImageInput();
        return;
    }
    const reader = new FileReader();
    reader.onload = function(e) {
        preview.src = e.target.result;
    };
    reader.readAsDataURL(file);
    title.textContent = file.name;
});
function resetImageInput() {
    const input = document.getElementById('productImage');
    const preview = document.getElementById('imagePreview');
    const title = document.getElementById('imageUploadTitle');
    input.value = '';
    preview.src = '/Backend/assets/images/icons/upload.svg';
    title.textContent = 'Drag and drop a file to upload';
}
document.getElementById('price').addEventListener('input', function() {
    let value = this.value;
    if (parseFloat(value) > 1000000) {
        this.value = 1000000;
    }
});
document.getElementById('minQuantity').addEventListener('input', function() {
    let value = this.value;

    if (parseFloat(value) > 1000000) {
        this.value = 1000000;
    }
});
document.getElementById('quantity').addEventListener('input', function() {
    let value = this.value;

    if (parseFloat(value) > 1000000) {
        this.value = 1000000;
    }
});
const MAX_IMAGES = 5;
let selectedFiles = [];
let removedImages = [];
let existingCount = 0;
const input = document.getElementById('galleryInput');
const preview = document.getElementById('galleryPreview');
const uploadBox = document.getElementById('uploadBox');
const counterText = document.getElementById('counterText');
function addPreview(file = null, imagePath = null) {
    const div = document.createElement('div');
    div.className = 'gallery-item';
    const img = document.createElement('img');
    img.src = imagePath ? imagePath : URL.createObjectURL(file);
    const remove = document.createElement('div');
    remove.className = 'remove-btn';
    remove.innerHTML = '&times;';
    remove.onclick = function() {
        div.remove();
        if (file) {
            selectedFiles = selectedFiles.filter(f => f !== file);
            syncFilesToInput();
        }
        if (imagePath) {
            removedImages.push(imagePath);
            existingCount--;
            document.getElementById('removedGalleryImages').value =
                JSON.stringify(removedImages);
        }

        updateCounter();
    };

    div.appendChild(img);
    div.appendChild(remove);
    preview.appendChild(div);
}

input.addEventListener('change', function() {
    const files = Array.from(this.files);

    files.forEach(file => {
        if ((selectedFiles.length + existingCount) >= MAX_IMAGES) return;
        selectedFiles.push(file);
        addPreview(file);
    });

    syncFilesToInput();
    input.value = '';
    updateCounter();
});

function updateCounter() {
    const used = selectedFiles.length + existingCount;
    const left = MAX_IMAGES - used;

    counterText.innerText = `Select up to 5 images (${left} left)`;
    uploadBox.style.display = left === 0 ? 'none' : 'flex';
    input.disabled = left === 0;
}

function syncFilesToInput() {
    const dataTransfer = new DataTransfer();
    selectedFiles.forEach(file => dataTransfer.items.add(file));
    input.files = dataTransfer.files;
}


const MAX_IMAGE_SIZE = 100 * 5120;
const ALLOWED_TYPES = ['image/jpeg', 'image/png', 'image/webp', 'image/jpg'];

function loadBrands(categoryId, selectedBrand = "") {
const brandSelect = document.getElementById("brandSelect");
brandSelect.innerHTML = '<option value="">Loading...</option>';
brandSelect.disabled = true;

if (!categoryId) return;

fetch("/Backend/src/Pages/products/get_brands.php?category_id=" + categoryId)
.then(res => res.json())
.then(data => {

    brandSelect.innerHTML = '<option value="">Choose Brand</option>';

    let found = false;

    data.forEach(brand => {
        const opt = document.createElement("option");
        opt.value = brand.trim();
        opt.textContent = brand.trim();

        if (
            selectedBrand &&
            brand.trim().toLowerCase() === selectedBrand.trim().toLowerCase()
        ) {
            opt.selected = true;
            found = true;
        }

        brandSelect.appendChild(opt);
    });

    if (selectedBrand && !found) {
        const opt = document.createElement("option");
        opt.value = selectedBrand;
        opt.textContent = selectedBrand + " (saved)";
        opt.selected = true;
        brandSelect.appendChild(opt);
    }

    brandSelect.disabled = false;
});
}




