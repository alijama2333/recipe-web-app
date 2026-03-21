<?php
session_start();

require_once '../includes/db.php';
require_once '../includes/auth.php';

require_login();

$stmt = $pdo->prepare("
    SELECT r.*, AVG(rt.rating) AS avg_rating
    FROM favourites f
    JOIN recipes r ON f.recipe_id = r.recipe_id
    LEFT JOIN ratings rt ON r.recipe_id = rt.recipe_id
    WHERE f.user_id = ?
    GROUP BY r.recipe_id
    ORDER BY f.created_at DESC
");

$stmt->execute([current_user_id()]);
$favouriteRecipes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include __DIR__ . '/includes/header.php'; ?>

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

            <?php if (empty($favouriteRecipes)): ?>
                <p>You haven’t saved any recipes yet.</p>
            <?php else: ?>
                <div class="favourites-grid">
                    <?php foreach ($favouriteRecipes as $recipe): ?>
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

                        include __DIR__ . '/includes/favourite-card.php';
                        ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
    </section>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>