<?php include __DIR__ . '/includes/header.php'; ?>

<?php
$favouriteRecipes = [
    ['id' => 1, 'title' => 'Spaghetti Bolognese', 'meta' => 'Main · Meat · 35 mins · 4.5 stars'],
    ['id' => 2, 'title' => 'Vegan Pancakes', 'meta' => 'Breakfast · Vegan · 20 mins · 4.7 stars'],
    ['id' => 3, 'title' => 'Healthy Pizza', 'meta' => 'Main · Vegetarian · 30 mins · 4.3 stars'],
    ['id' => 5, 'title' => 'Couscous Salad', 'meta' => 'Healthy · Vegetarian · 15 mins · 4.4 stars'],
];

$recommendedRecipes = [
    ['id' => 6, 'title' => 'Mango Pie'],
    ['id' => 4, 'title' => 'Lamb Biryani'],
    ['id' => 8, 'title' => 'Mushroom Doner'],
];
?>

<div class="app-shell">
    <?php include __DIR__ . '/includes/sidebar.php'; ?>

    <section class="main-panel">
        <div class="detail-topbar">
            <a href="index.php" class="back-link">← Back to home</a>
        </div>

        <section class="search-page-header">
            <h1>My Favourites</h1>
            <p>View and manage the recipes you have saved.</p>
        </section>

        <section class="favourites-summary">
            <div class="favourites-summary-card">
                <h2>Saved Recipes</h2>
                <p class="favourites-count"><?= count($favouriteRecipes) ?> recipes saved</p>
            </div>
        </section>

        <section class="results-section">
            <div class="section-heading">
                <h2>Saved Recipes</h2>
            </div>

            <div class="favourites-grid">
                <?php foreach ($favouriteRecipes as $recipe): ?>
                    <?php
                    $recipeId = $recipe['id'];
                    $recipeTitle = $recipe['title'];
                    $recipeMeta = $recipe['meta'];
                    include __DIR__ . '/includes/favourite-card.php';
                    ?>
                <?php endforeach; ?>
            </div>
        </section>

        <section class="homepage-section">
            <div class="section-heading">
                <h2>You might also like</h2>
                <a href="search.php">Browse more →</a>
            </div>

            <div class="popular-row">
                <?php foreach ($recommendedRecipes as $recipe): ?>
                    <article class="popular-card">
                        <div class="popular-image"></div>
                        <h3>
                            <a href="recipe.php?id=<?= urlencode($recipe['id']) ?>">
                                <?= htmlspecialchars($recipe['title']) ?>
                            </a>
                        </h3>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>
    </section>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>