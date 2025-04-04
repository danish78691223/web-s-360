document.addEventListener("DOMContentLoaded", function() {
    const sections = document.querySelectorAll(".section");

    sections.forEach(section => {
        section.addEventListener("mouseenter", () => {
            section.style.boxShadow = "0px 8px 20px rgba(255, 75, 75, 0.5)";
        });

        section.addEventListener("mouseleave", () => {
            section.style.boxShadow = "0px 4px 10px rgba(255, 255, 255, 0.1)";
        });
    });
});
