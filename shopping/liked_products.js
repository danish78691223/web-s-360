document.addEventListener("DOMContentLoaded", () => {
    let likedProducts = JSON.parse(localStorage.getItem("likedProducts")) || [];
    let productList = document.getElementById("liked-products-list");

    if (likedProducts.length === 0) {
        productList.innerHTML = "<p class='text-center'>No liked products.</p>";
    } else {
        likedProducts.forEach(product => {
            let productCard = document.createElement("div");
            productCard.classList.add("col-md-4");

            productCard.innerHTML = `
                <div class="product-card">
                    <img src="../uploads/${product.image}" alt="${product.name}">
                    <h4>${product.name}</h4>
                    <h5>₹${product.price}</h5>
                    <button class="btn btn-danger remove-like-btn" data-id="${product.id}">Remove</button>
                </div>
            `;

            productList.appendChild(productCard);
        });

        document.querySelectorAll(".remove-like-btn").forEach(button => {
            button.addEventListener("click", () => {
                let productId = button.getAttribute("data-id");
                likedProducts = likedProducts.filter(product => product.id !== productId);
                localStorage.setItem("likedProducts", JSON.stringify(likedProducts));
                location.reload();
            });
        });
    }
});
