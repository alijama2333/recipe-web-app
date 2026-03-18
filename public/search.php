<?php include __DIR__ . '/includes/header.php'; ?>

<?php
include "../includes/db.php";

//Insert PHP statements to manage the database
$q = $_GET['q'] ?? '';
$food_category = $_GET['category'] ?? '';
$time = $_GET['time'] ?? '';
$diet = $_GET['diet'] ?? '';
$sort = $_GET['sort'] ?? 'rating';

$query = "SELECT DISTINCT recipes.*
FROM recipes 
LEFT JOIN diet ON recipes.recipe_id = diet.recipe_id
WHERE 1=1";

$params = [];

if ($q !== '') {
    $query .= " AND recipes.recipe_name LIKE :q";
    $params['q'] = "%$q%";
}

if ($time === '30') {
    $query .= " AND cook_time <= 30";
}elseif($time === '31'){
    $query .= " AND cook_time > 30";    
}

if($diet !== '' ){
    $query .= " AND diet.d_val LIKE :diet";
    $params['diet'] = "%$diet%";
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
                        placeholder="Search recipes or ingredients..."
                    >
                    <button type="submit">Search</button>
                </div>

                <div class="filter-grid">
                    <div class="filter-group">
                        <label for="category">Category</label>
                        <select id="category" name="category">
                            <option value="">All</option>
                            <option value="Vegetarian">Vegetarian</option>
                            <option value="Meat">Meat</option>
                            <option value="Pasta">Pasta</option>
                            <option value="Breakfast">Breakfast</option>
                            <option value="Dessert">Dessert</option>
                            <option value="Healthy">Healthy</option>
                            <option value="Quick Meals">Quick Meals</option>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label for="time">Cook Time</label>
                        <select id="time" name="time">
                            <option value="">Any</option>
                            <option value="30">30 min or less</option>
                            <option value="31">More than 30 min</option>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label for="diet">Dietary Type</label>
                        <select id="diet" name="diet">
                            <option value="">All</option>
                            <option value="vegan">Vegan</option>
                            <option value="vegetarian">Vegetarian</option>
                            <option value="meat">Meat</option>
                            <option value="nut free"> Nut Free </option>
                            <option value="pregnancy friendly"> Pregnancy Friendly </option>
                            <option value="healthy"> Healthy </option>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label for="sort">Sort By</label>
                        <select id="sort" name="sort">
                            <option value="rating">Highest rated</option>
                            <option value="time">Shortest time</option>
                            <option value="az">A-Z</option>
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

            <div class="search-results-grid">
                <?php foreach ($recipes as $recipe): ?>
                    <?php
                    $recipeId = $recipe['id'];
                    $recipeTitle = $recipe['recipe_name'];
                    $recipeMeta = $recipe['meta'];
                    $cardClass = '';
                    include __DIR__ . '/includes/recipe-card.php';
                    ?>
                <?php endforeach; ?>
            </div>
        </section>
    </section>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
