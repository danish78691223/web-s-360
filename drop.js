document.addEventListener("DOMContentLoaded", () => {
    const profileToggle = document.getElementById("profile-toggle"); // Profile toggle button
    const profileMenu = document.getElementById("profile-menu"); // Dropdown menu

    if (profileToggle && profileMenu) {
        // Toggle dropdown menu on click
        profileToggle.addEventListener("click", (event) => {
            event.preventDefault(); // Prevent the default link behavior
            profileMenu.classList.toggle("visible");
        });

        // Close dropdown menu when clicking outside
        document.addEventListener("click", (event) => {
            if (!profileToggle.contains(event.target) && !profileMenu.contains(event.target)) {
                profileMenu.classList.remove("visible");
            }
        });
    }
});
