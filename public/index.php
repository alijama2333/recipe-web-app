<?php
require_once '../includes/db.php';
?>

<?php include __DIR__ . '/includes/header.php'; ?>

<?php
/* Featured recipes */
$stmt = $pdo->prepare("
    SELECT r.*, AVG(rt.rating) AS avg_rating
    FROM recipes r
    LEFT JOIN ratings rt ON r.recipe_id = rt.recipe_id
    GROUP BY r.recipe_id
    ORDER BY avg_rating DESC, r.total_time ASC
    LIMIT 2
");
$stmt->execute();
$featuredRecipes = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* Popular / additional recipes */
$stmt = $pdo->prepare("
    SELECT r.*, AVG(rt.rating) AS avg_rating
    FROM recipes r
    LEFT JOIN ratings rt ON r.recipe_id = rt.recipe_id
    GROUP BY r.recipe_id
    ORDER BY avg_rating DESC, r.recipe_name ASC
    LIMIT 5
");
$stmt->execute();
$popularRecipes = $stmt->fetchAll(PDO::FETCH_ASSOC);

$categories = [
    ['label' => 'Vegetarian', 'value' => 'vegetarian', 'icon' => '🥦'],
    ['label' => 'Meat', 'value' => 'meat', 'icon' => '🍗'],
    ['label' => 'Pasta', 'value' => 'pasta', 'icon' => '🍝'],
    ['label' => 'Breakfast', 'value' => 'breakfast', 'icon' => '🍳'],
    ['label' => 'Dessert', 'value' => 'dessert', 'icon' => '🍰'],
    ['label' => 'Quick Meals', 'value' => 'quick-meals', 'icon' => '⚡'],
    ['label' => 'Vegan/Vegetarian', 'value' => 'vegan/vegetarian', 'icon' => '🥗']
];
?>

<div class="app-shell">
    <?php include __DIR__ . '/includes/sidebar.php'; ?>

    <section class="main-panel">
        <div class="page-header">
            <h1>Recipe Finder</h1>
            <p class="homepage-intro">
                Discover simple, tasty recipes and explore meals by category, dietary need, and cooking time.
            </p>
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
                <a href="search.php?food_category=<?= urlencode($category['value']) ?>" class="category-item">
                    <div class="category-icon">
                        <span class="category-emoji"><?= htmlspecialchars($category['icon']) ?></span>
                    </div>
                    <span class="category-label"><?= htmlspecialchars($category['label']) ?></span>
                </a>
            <?php endforeach; ?>
        </div>
        </section>

        <section class="homepage-section">
            <div class="section-heading">
                <h2>Featured Recipes</h2>
                <a href="search.php">View all →</a>
            </div>

            <?php if (empty($featuredRecipes)): ?>
                <p>No featured recipes available.</p>
            <?php else: ?>
                <div class="featured-grid">
                    <?php foreach ($featuredRecipes as $recipe): ?>
                        <?php
                        $metaParts = [];

                        if (!empty($recipe['course'])) {
                            $metaParts[] = $recipe['course'];
                        }

                        if (!empty($recipe['food_category'])) {
                            $metaParts[] = $recipe['food_category'];
                        }

                        if (!empty($recipe['total_time'])) {
                            $metaParts[] = $recipe['total_time'] . ' mins';
                        }

                        if (!empty($recipe['avg_rating'])) {
                            $metaParts[] = number_format((float)$recipe['avg_rating'], 1) . '★';
                        }

                        $recipeId = $recipe['recipe_id'];
                        $recipeTitle = $recipe['recipe_name'];
                        $recipeImage = $recipe['image_path'] ?? '';
                        $recipeMeta = implode(' · ', $metaParts);
                        $cardClass = '';

                        include __DIR__ . '/includes/recipe-card.php';
                        ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>

        <section class="homepage-section">
            <div class="section-heading">
                <h2>Most Popular Recipes</h2>
            </div>

            <?php if (empty($popularRecipes)): ?>
                <p>No popular recipes available.</p>
            <?php else: ?>
                <div class="popular-row">
                    <?php foreach ($popularRecipes as $recipe): ?>
                        <?php
                        $popularMetaParts = [];

                        if (!empty($recipe['food_category'])) {
                            $popularMetaParts[] = $recipe['food_category'];
                        }

                        if (!empty($recipe['total_time'])) {
                            $popularMetaParts[] = $recipe['total_time'] . ' mins';
                        }

                        $popularMeta = implode(' · ', $popularMetaParts);
                        ?>
                        <article class="popular-card">
                            <div
                                class="popular-image"
                                <?php if (!empty($recipe['image_path'])): ?>
                                    style="background-image: url('<?= htmlspecialchars($recipe['image_path']) ?>'); background-size: cover; background-position: center;"
                                <?php endif; ?>
                            ></div>

                            <h3>
                                <a href="recipe.php?id=<?= urlencode($recipe['recipe_id']) ?>">
                                    <?= htmlspecialchars($recipe['recipe_name']) ?>
                                </a>
                            </h3>

                            <?php if (!empty($popularMeta)): ?>
                                <p class="popular-meta"><?= htmlspecialchars($popularMeta) ?></p>
                            <?php endif; ?>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
    </section>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>