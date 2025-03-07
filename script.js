document.addEventListener("DOMContentLoaded", () => {
    // Hamburger menu functionality
    const hamburger = document.getElementById("hamburger");
    const navLinks = document.querySelector(".nav-links");

    hamburger.addEventListener("click", () => {
        navLinks.classList.toggle("active");
    });

    // Close the sidebar if clicked outside
    document.addEventListener("click", (event) => {
        if (!hamburger.contains(event.target) && !navLinks.contains(event.target)) {
            navLinks.classList.remove("active");
        }
    });

    // Dropdown functionality for profile menu
    const profileToggle = document.getElementById("profile-toggle");
    const profileMenu = document.getElementById("profile-menu");

    if (profileToggle && profileMenu) {
        profileToggle.addEventListener("click", (event) => {
            event.preventDefault();
            profileMenu.classList.toggle("visible");
        });

        document.addEventListener("click", (event) => {
            if (!profileToggle.contains(event.target) && !profileMenu.contains(event.target)) {
                profileMenu.classList.remove("visible");
            }
        });
    }
});




// JS to determine the roll and display the dashboard button
// Simulating a logged-in user role (can be fetched from server/session)

const userRole = "employee"; // can Change this to "admin", "user", etc., for testing

// Function to handle role-based UI
function handleDashboardVisibility(role) {
    const dashboardButton = document.getElementById("dashboard-btn");

    if (role === "admin" || role === "employee") {
        dashboardButton.style.display = "block"; // Show for admins and employees
    } else {
        dashboardButton.style.display = "none"; // Hide for other users
    }
}

// Call the function with the user's role
handleDashboardVisibility(userRole);


// If roles are fetched from a backend, you can use an API call to fetch the role and update the UI dynamically.

fetch("/api/getUserRole") // Replace with your actual API endpoint
    .then((response) => response.json())
    .then((data) => {
        const userRole = data.role; // Example: "admin", "employee", or "user"
        handleDashboardVisibility(userRole);
    })
    .catch((error) => console.error("Error fetching user role:", error));
