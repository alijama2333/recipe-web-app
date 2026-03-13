<?php include __DIR__ . '/includes/header.php'; ?>

<?php
$featuredRecipes = [
    ['id' => 1, 'title' => 'Spaghetti Bolognese', 'meta' => '35 mins · Pasta · 4.5 stars'],
    ['id' => 2, 'title' => 'Vegan Pancakes', 'meta' => '20 mins · Breakfast · 4.7 stars'],
];

$popularRecipes = [
    ['id' => 6, 'title' => 'Mango Pie'],
    ['id' => 5, 'title' => 'Couscous Salad'],
    ['id' => 3, 'title' => 'Healthy Pizza'],
    ['id' => 4, 'title' => 'Lamb Biryani'],
    ['id' => 7, 'title' => 'Plum Clafoutis'],
];

$categories = [
    'Vegetarian',
    'Meat',
    'Pasta',
    'Breakfast',
    'Dessert',
    'Healthy',
    'Quick Meals'
];
?>

<div class="app-shell">
    <?php include __DIR__ . '/includes/sidebar.php'; ?>

    <section class="main-panel">
        <div class="page-header">
            <h1>Recipe Finder</h1>
        </div>

        <section class="search-section">
            <form class="hero-search" action="search.php" method="get">
                <label for="homepage-search" class="sr-only">Search recipes or ingredients</label>
                <input
                    type="text"
                    id="homepage-search"
                    name="q"
                    placeholder="Search recipes or ingredients..."
                >
                <button type="submit">Search</button>
            </form>
        </section>

        <section class="homepage-section">
            <div class="section-heading">
                <h2>Browse by Category</h2>
                <a href="search.php">View all →</a>
            </div>

            <div class="category-row">
                <?php foreach ($categories as $category): ?>
                    <a href="search.php?category=<?= urlencode($category) ?>" class="category-item">
                        <div class="category-icon"></div>
                        <span><?= htmlspecialchars($category) ?></span>
                    </a>
                <?php endforeach; ?>
            </div>
        </section>

        <section class="homepage-section">
            <div class="section-heading">
                <h2>Featured Recipes</h2>
                <a href="search.php">View all →</a>
            </div>

            <div class="featured-grid">
                <?php foreach ($featuredRecipes as $recipe): ?>
                    <?php
                    $recipeId = $recipe['id'];
                    $recipeTitle = $recipe['title'];
                    $recipeMeta = $recipe['meta'];
                    $cardClass = '';
                    include __DIR__ . '/includes/recipe-card.php';
                    ?>
                <?php endforeach; ?>
            </div>
        </section>

        <section class="homepage-section">
            <div class="section-heading">
                <h2>Most Popular Recipes</h2>
            </div>

            <div class="popular-row">
                <?php foreach ($popularRecipes as $recipe): ?>
                    <article class="popular-card">
                        <div class="popular-image"></div>
                        <h3><a href="recipe.php?id=<?= urlencode($recipe['id']) ?>"><?= htmlspecialchars($recipe['title']) ?></a></h3>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>
    </section>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>