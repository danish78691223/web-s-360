// Check if user is logged in using PHP session
window.onload = function () {
    fetchUserData();
    loadProducts();
    showSlide(currentSlide);
};

// Fetch user data
function fetchUserData() {
    fetch('fetch_user.php')
        .then(response => response.json())
        .then(data => {
            const loginSection = document.getElementById('login-section');

            if (data.loggedIn) {
                loginSection.innerHTML = `<a href='profile.php'>Welcome, ${data.username}</a>`;
            } else {
                loginSection.innerHTML = "<a href='login.php'>Login</a>";
            }
        })
        .catch(error => console.error('Error fetching user data:', error));
}

// Load products dynamically
function loadProducts() {
    fetch('get_products.php')
        .then(response => response.json())
        .then(products => {
            const productContainer = document.getElementById('productContainer');

            productContainer.innerHTML = products.map(product => `
                <div class="product-card">
                    <img src="${product.image}" alt="${product.name}">
                    <h3>${product.name}</h3>
                    <p>₹${product.price}</p>
                    <button onclick="showDetails('${product.name}', '₹${product.price}', '${product.image}')">View Details</button>
                    <button onclick="buyNow('${product.name}', '₹${product.price}')">Buy Now</button>
                </div>
            `).join('');
        })
        .catch(error => console.error('Error loading products:', error));
}

// Show product details in a modal
function showDetails(name, price, image) {
    document.getElementById('modalImage').src = image;
    document.getElementById('modalTitle').innerText = name;
    document.getElementById('modalPrice').innerText = price;
    document.getElementById('productModal').style.display = "block";
}

// Close the modal
function closeModal() {
    document.getElementById('productModal').style.display = "none";
}

// Carousel Slider
let currentSlide = 0;
const slides = document.querySelectorAll('.slider .slide');

function showSlide(index) {
    slides.forEach((slide, i) => {
        slide.style.display = i === index ? 'block' : 'none';
    });
}

function nextSlide() {
    currentSlide = (currentSlide + 1) % slides.length;
    showSlide(currentSlide);
}

function prevSlide() {
    currentSlide = (currentSlide - 1 + slides.length) % slides.length;
    showSlide(currentSlide);
}

setInterval(nextSlide, 4000); // Auto-slide every 4 seconds


