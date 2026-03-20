<?php
session_start();

require_once '../includes/db.php';
require_once '../includes/auth.php';

$recipeId = $_GET['id'] ?? null;

if (!$recipeId || !is_numeric($recipeId)) {
    die('Invalid recipe ID.');
}

$recipeId = (int)$recipeId;

/* Main recipe + average rating */
$stmt = $pdo->prepare("
    SELECT r.*, AVG(rt.rating) AS avg_rating
    FROM recipes r
    LEFT JOIN ratings rt ON r.recipe_id = rt.recipe_id
    WHERE r.recipe_id = ?
    GROUP BY r.recipe_id
");
$stmt->execute([$recipeId]);
$recipe = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$recipe) {
    die('Recipe not found.');
}

/* Diet tags */
$stmt = $pdo->prepare("
    SELECT d_val
    FROM diet
    WHERE recipe_id = ?
");
$stmt->execute([$recipeId]);
$diets = $stmt->fetchAll(PDO::FETCH_COLUMN);

/* Ingredients + measurements */
$stmt = $pdo->prepare("
    SELECT i.ingredient, i.qty, m.meas_met
    FROM ingredients i
    LEFT JOIN measurements m ON i.measurement_id = m.measurement_id
    WHERE i.recipe_id = ?
    ORDER BY i.ingredient_id ASC
");
$stmt->execute([$recipeId]);
$ingredients = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* Method steps */
$stmt = $pdo->prepare("
    SELECT step, method
    FROM methods
    WHERE recipe_id = ?
    ORDER BY step ASC
");
$stmt->execute([$recipeId]);
$methods = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* Tips */
$stmt = $pdo->prepare("
    SELECT tip_text
    FROM tips
    WHERE recipe_id = ?
    ORDER BY tip_id ASC
");
$stmt->execute([$recipeId]);
$tips = $stmt->fetchAll(PDO::FETCH_COLUMN);

/* Similar recipes */
$stmt = $pdo->prepare("
    SELECT recipe_id, recipe_name, food_category, total_time, image_path
    FROM recipes
    WHERE recipe_id != ?
      AND course = ?
    ORDER BY total_time ASC
    LIMIT 2
");
$stmt->execute([$recipeId, $recipe['course']]);
$similarRecipes = $stmt->fetchAll(PDO::FETCH_ASSOC);

$metaParts = [];

if (!empty($recipe['course'])) {
    $metaParts[] = $recipe['course'];
}

if (!empty($recipe['food_category'])) {
    $metaParts[] = $recipe['food_category'];
}

if (!empty($diets)) {
    $metaParts[] = implode(', ', $diets);
}

if (!empty($recipe['prep_time'])) {
    $metaParts[] = $recipe['prep_time'] . ' prep';
}

if (!empty($recipe['cook_time'])) {
    $metaParts[] = $recipe['cook_time'] . ' cook';
}

if (!empty($recipe['total_time'])) {
    $metaParts[] = $recipe['total_time'] . ' mins total';
}

if (!empty($recipe['avg_rating'])) {
    $metaParts[] = number_format((float)$recipe['avg_rating'], 1) . '★';
}

$recipeMeta = implode(' · ', $metaParts);
?>

<?php include __DIR__ . '/includes/header.php'; ?>

<div class="app-shell">
    <?php include __DIR__ . '/includes/sidebar.php'; ?>

    <section class="main-panel">
        <div class="detail-topbar">
            <a href="search.php" class="back-link">← Back to recipes</a>

            <div class="detail-actions">
                <?php if (!empty($_SESSION['user_id'])): ?>
                    <form method="POST" action="save_favourite.php" style="display:inline;">
                        <input type="hidden" name="recipe_id" value="<?= (int)$recipe['recipe_id'] ?>">
                        <button class="icon-button" type="submit" aria-label="Save recipe">♡</button>
                    </form>
                <?php endif; ?>

                <button class="icon-button" type="button" aria-label="More options">⋮</button>
            </div>
        </div>

        <section class="recipe-hero">
            <div
                class="recipe-hero-image"
                <?php if (!empty($recipe['image_path'])): ?>
                    style="background-image: url('<?= htmlspecialchars($recipe['image_path']) ?>'); background-size: cover; background-position: center;"
                <?php endif; ?>
            ></div>

            <div class="recipe-hero-content">
                <h1><?= htmlspecialchars($recipe['recipe_name']) ?></h1>

                <p class="recipe-meta-line"><?= htmlspecialchars($recipeMeta) ?></p>

                <p class="recipe-description">
                    Created by <?= htmlspecialchars($recipe['author']) ?>.
                    Serves <?= (int)$recipe['serves'] ?>.
                </p>

                <?php if (!empty($tips)): ?>
                    <p class="recipe-description">
                        Includes <?= count($tips) ?> cooking tip<?= count($tips) === 1 ? '' : 's' ?> below.
                    </p>
                <?php endif; ?>

                <div class="recipe-button-row">
                    <?php if (!empty($_SESSION['user_id'])): ?>
                        <form method="POST" action="save_favourite.php">
                            <input type="hidden" name="recipe_id" value="<?= (int)$recipe['recipe_id'] ?>">
                            <button class="primary-button" type="submit">Save to favourites</button>
                        </form>
                    <?php endif; ?>

                    <a href="#method" class="secondary-button">Start cooking</a>
                </div>
            </div>
        </section>

        <section class="recipe-content-grid">
            <div class="recipe-panel">
                <h2>Ingredients</h2>

                <?php if (empty($ingredients)): ?>
                    <p>No ingredients available.</p>
                <?php else: ?>
                    <ul class="ingredients-list">
                        <?php foreach ($ingredients as $ingredient): ?>
                            <li>
                                <?=
                                    htmlspecialchars(
                                        rtrim(rtrim((string)$ingredient['qty'], '0'), '.') .
                                        ' ' .
                                        trim(($ingredient['meas_met'] ?? '') . ' ' . $ingredient['ingredient'])
                                    )
                                ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>

            <div class="recipe-panel" id="method">
                <h2>Method</h2>

                <?php if (empty($methods)): ?>
                    <p>No method steps available.</p>
                <?php else: ?>
                    <ol class="method-list">
                        <?php foreach ($methods as $step): ?>
                            <li><?= htmlspecialchars($step['method']) ?></li>
                        <?php endforeach; ?>
                    </ol>
                <?php endif; ?>
            </div>
        </section>

        <?php if (!empty($tips)): ?>
            <section class="homepage-section">
                <div class="section-heading">
                    <h2>Tips</h2>
                </div>

                <div class="recipe-panel">
                    <ul class="ingredients-list">
                        <?php foreach ($tips as $tip): ?>
                            <li><?= htmlspecialchars($tip) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </section>
        <?php endif; ?>

        <section class="homepage-section">
            <div class="section-heading">
                <h2>Similar Recipes</h2>
                <a href="search.php">View all →</a>
            </div>

            <?php if (empty($similarRecipes)): ?>
                <p>No similar recipes found.</p>
            <?php else: ?>
                <div class="related-list">
                    <?php foreach ($similarRecipes as $similar): ?>
                        <article class="related-card">
                            <div
                                class="related-image"
                                <?php if (!empty($similar['image_path'])): ?>
                                    style="background-image: url('<?= htmlspecialchars($similar['image_path']) ?>'); background-size: cover; background-position: center;"
                                <?php endif; ?>
                            ></div>
                            <div class="related-content">
                                <h3>
                                    <a href="recipe.php?id=<?= urlencode($similar['recipe_id']) ?>">
                                        <?= htmlspecialchars($similar['recipe_name']) ?>
                                    </a>
                                </h3>
                                <p>
                                    <?= htmlspecialchars($similar['food_category']) ?>
                                    <?php if (!empty($similar['total_time'])): ?>
                                        · <?= (int)$similar['total_time'] ?> mins
                                    <?php endif; ?>
                                </p>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
    </section>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>