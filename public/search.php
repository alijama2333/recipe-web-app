<?php include __DIR__ . '/includes/header.php'; ?>

<?php
include "../includes/db.php";

$q = $_GET['q'] ?? '';
$food_category = $_GET['food_category'] ?? '';
$time = $_GET['time'] ?? '';
$diet = $_GET['diet'] ?? '';
$sort = $_GET['sort'] ?? 'rating';

$query = "SELECT DISTINCT recipes.*, AVG(ratings.rating) AS avg_rating
FROM recipes 
LEFT JOIN diet ON recipes.recipe_id = diet.recipe_id
LEFT JOIN ratings ON recipes.recipe_id = ratings.recipe_id
WHERE 1=1";

$params = [];

if ($q !== '') {
    $query .= " AND recipes.recipe_name LIKE :q";
    $params['q'] = "%$q%";
}

if ($time === '30') {
    $query .= " AND recipes.total_time <= 30";
} elseif ($time === '31') {
    $query .= " AND recipes.total_time > 30";
}

if ($diet !== '') {
    $query .= " AND diet.d_val LIKE :diet";
    $params['diet'] = "%$diet%";
}

if ($food_category !== '') {
    $query .= " AND recipes.food_category LIKE :food_category";
    $params['food_category'] = "%$food_category%";
}

$query .= " GROUP BY recipes.recipe_id";

switch ($sort) {
    case 'time':
        $query .= " ORDER BY recipes.total_time ASC";
        break;
    case 'az':
        $query .= " ORDER BY recipes.recipe_name ASC";
        break;
    default:
        $query .= " ORDER BY avg_rating DESC";
        break;
}

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$recipes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="app-shell">
    <?php include __DIR__ . '/includes/sidebar.php'; ?>

    <section class="main-panel">
        <div class="detail-topbar">
            <a href="index.php" class="back-link">← Back to home</a>
        </div>

        <section class="search-page-header">
            <h1>Search Recipes</h1>
            <p>Find recipes by keyword, category, cooking time and rating.</p>
        </section>

        <section class="search-toolbar">
            <form class="search-page-form" action="search.php" method="get">
                <div class="search-page-input-row">
                    <label for="search-query" class="sr-only">Search recipes or ingredients</label>
                    <input
                        type="text"
                        id="search-query"
                        name="q"
                        value="<?= htmlspecialchars($q) ?>"
                        placeholder="Search recipes or ingredients..."
                    >
                    <button type="submit">Search</button>
                </div>

                <div class="filter-grid">
                    <div class="filter-group">
                        <label for="food_category">Category</label>
                        <select id="food_category" name="food_category">
                            <option value="">All</option>
                            <option value="vegetarian" <?= $food_category === 'vegetarian' ? 'selected' : '' ?>>Vegetarian</option>
                            <option value="meat" <?= $food_category === 'meat' ? 'selected' : '' ?>>Meat</option>
                            <option value="pasta" <?= $food_category === 'pasta' ? 'selected' : '' ?>>Pasta</option>
                            <option value="breakfast" <?= $food_category === 'breakfast' ? 'selected' : '' ?>>Breakfast</option>
                            <option value="dessert" <?= $food_category === 'dessert' ? 'selected' : '' ?>>Dessert</option>
                            <option value="quick-meals" <?= $food_category === 'quick-meals' ? 'selected' : '' ?>>Quick Meals</option>
                            <option value="meat/vegetarian" <?= $food_category === 'meat/vegetarian' ? 'selected' : '' ?>>Meat/Vegetarian</option>
                            <option value="vegan/vegetarian" <?= $food_category === 'vegan/vegetarian' ? 'selected' : '' ?>>Vegan/Vegetarian</option>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label for="time">Cook Time</label>
                        <select id="time" name="time">
                            <option value="">Any</option>
                            <option value="30" <?= $time === '30' ? 'selected' : '' ?>>30 min or less</option>
                            <option value="31" <?= $time === '31' ? 'selected' : '' ?>>More than 30 min</option>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label for="diet">Dietary Type</label>
                        <select id="diet" name="diet">
                            <option value="">All</option>
                            <option value="vegan" <?= $diet === 'vegan' ? 'selected' : '' ?>>Vegan</option>
                            <option value="vegetarian" <?= $diet === 'vegetarian' ? 'selected' : '' ?>>Vegetarian</option>
                            <option value="meat" <?= $diet === 'meat' ? 'selected' : '' ?>>Meat</option>
                            <option value="gluten free" <?= $diet === 'gluten free' ? 'selected' : '' ?>>Gluten Free</option>
                            <option value="nut free" <?= $diet === 'nut free' ? 'selected' : '' ?>>Nut Free</option>
                            <option value="pregnancy-friendly" <?= $diet === 'pregnancy-friendly' ? 'selected' : '' ?>>Pregnancy Friendly</option>
                            <option value="healthy" <?= $diet === 'healthy' ? 'selected' : '' ?>>Healthy</option>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label for="sort">Sort By</label>
                        <select id="sort" name="sort">
                            <option value="rating" <?= $sort === 'rating' ? 'selected' : '' ?>>Highest rated</option>
                            <option value="time" <?= $sort === 'time' ? 'selected' : '' ?>>Shortest time</option>
                            <option value="az" <?= $sort === 'az' ? 'selected' : '' ?>>A-Z</option>
                        </select>
                    </div>
                </div>
            </form>
        </section>

        <section class="results-section">
            <div class="section-heading">
                <h2>Results</h2>
                <span class="results-count"><?= count($recipes) ?> recipes found</span>
            </div>

            <?php if (empty($recipes)): ?>
                <p>No recipes found. Try changing your search or filters.</p>
            <?php else: ?>
                <div class="search-results-grid">
                    <?php foreach ($recipes as $recipe): ?>
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
    </section>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>