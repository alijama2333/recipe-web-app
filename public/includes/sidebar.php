<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<aside class="sidebar" aria-label="Sidebar navigation">
    <button class="menu-button" aria-label="Open menu">☰</button>

    <nav class="sidebar-nav">
        <a href="index.php" class="sidebar-link <?= $currentPage === 'index.php' ? 'active' : '' ?>">Home</a>
        <a href="favourites.php" class="sidebar-link <?= $currentPage === 'favourites.php' ? 'active' : '' ?>">Favourites</a>
        <a href="search.php" class="sidebar-link <?= $currentPage === 'search.php' ? 'active' : '' ?>">Search</a>
        <a href="account.php" class="sidebar-link <?= $currentPage === 'account.php' ? 'active' : '' ?>">Account</a>
    </nav>
</aside>