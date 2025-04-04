document.getElementById("toggle-btn").addEventListener("click", function () {
    let sidebar = document.querySelector(".sidebar");
    if (sidebar.style.width === "260px") {
        sidebar.style.width = "0";
    } else {
        sidebar.style.width = "260px";
    }
});
