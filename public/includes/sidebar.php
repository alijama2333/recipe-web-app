<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<aside class="sidebar" aria-label="Sidebar navigation">
    <button
        class="menu-button"
        aria-label="Toggle menu"
        aria-controls="mobileMenu"
        aria-expanded="false"
        onclick="toggleMenu(this)"
    >
        ☰
    </button>

    <nav class="sidebar-nav" id="mobileMenu">
        <a href="index.php" class="sidebar-link <?= $currentPage === 'index.php' ? 'active' : '' ?>">Home</a>
        <a href="favourites.php" class="sidebar-link <?= $currentPage === 'favourites.php' ? 'active' : '' ?>">Favourites</a>
        <a href="search.php" class="sidebar-link <?= $currentPage === 'search.php' ? 'active' : '' ?>">Search</a>
        <a href="account.php" class="sidebar-link <?= $currentPage === 'account.php' ? 'active' : '' ?>">Account</a>
    </nav>
</aside>

<script>
function toggleMenu(button) {
    const menu = document.getElementById("mobileMenu");
    const isOpen = menu.classList.toggle("open");
    button.setAttribute("aria-expanded", isOpen ? "true" : "false");
}

document.addEventListener("DOMContentLoaded", function () {
    const menu = document.getElementById("mobileMenu");
    const menuButton = document.querySelector(".menu-button");

    if (!menu || !menuButton) {
        return;
    }

    const menuLinks = menu.querySelectorAll(".sidebar-link");

    menuLinks.forEach(function (link) {
        link.addEventListener("click", function () {
            menu.classList.remove("open");
            menuButton.setAttribute("aria-expanded", "false");
        });
    });
});
</script>