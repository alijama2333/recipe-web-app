<<<<<<< HEAD
<<<<<<< HEAD
<?php
session_start();

require_once '../includes/db.php';
require_once '../includes/auth.php';

require_login();

$stmt = $pdo->prepare("
    SELECT r.id, r.title, r.description, r.total_time
    FROM favourites f
    INNER JOIN recipes r ON f.recipe_id = r.id
    WHERE f.user_id = ?
    ORDER BY f.created_at DESC
");
$stmt->execute([current_user_id()]);
$favourites = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Account</title>
</head>
<body>
    <h1>Welcome, <?= htmlspecialchars(current_user_name()) ?></h1>
    <p>You are logged in.</p>

    <h2>Your Favourite Recipes</h2>

    <?php if (empty($favourites)): ?>
        <p>No favourites saved yet.</p>
    <?php else: ?>
        <ul>
            <?php foreach ($favourites as $recipe): ?>
                <li>
                    <strong><?= htmlspecialchars($recipe['title']) ?></strong>
                    <?php if (!empty($recipe['total_time'])): ?>
                        - <?= (int)$recipe['total_time'] ?> mins
                    <?php endif; ?>

                    <form method="POST" action="remove_favourite.php" style="display:inline;">
                        <input type="hidden" name="recipe_id" value="<?= (int)$recipe['id'] ?>">
                        <button type="submit">Remove</button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <h2>Quick Add Favourite</h2>
    <form method="POST" action="save_favourite.php">
        <label for="recipe_id">Recipe ID:</label><br>
        <input id="recipe_id" type="number" name="recipe_id" min="1" required><br><br>
        <button type="submit">Save Favourite</button>
    </form>

    <h2>Quick Rate Recipe</h2>
    <form method="POST" action="rate_recipe.php">
        <label for="rate_recipe_id">Recipe ID:</label><br>
        <input id="rate_recipe_id" type="number" name="recipe_id" min="1" required><br><br>

        <label for="rating">Rating (1 to 5):</label><br>
        <input id="rating" type="number" name="rating" min="1" max="5" required><br><br>

        <label for="review">Review:</label><br>
        <textarea id="review" name="review" rows="4" cols="40"></textarea><br><br>

        <button type="submit">Submit Rating</button>
    </form>

    <p><a href="logout.php">Logout</a></p>
</body>
=======
<?php
session_start();

require_once '../includes/auth.php';

require_login();
?>
<?php include __DIR__ . '/includes/header.php'; ?>

<div class="app-shell">
    <?php include __DIR__ . '/includes/sidebar.php'; ?>

    <section class="main-panel">
        <div class="detail-topbar">
            <a href="index.php" class="back-link">← Back to home</a>
        </div>

        <section class="account-header">
            <h1>My Account</h1>
            <p>Manage your account and view your saved recipe activity.</p>
        </section>

        <section class="account-summary-grid">
            <div class="account-card profile-card">
                <div class="account-avatar">
                    <?= strtoupper(substr(htmlspecialchars(current_user_name()), 0, 1)) ?>
                </div>

                <div class="account-user-info">
                    <h2><?= htmlspecialchars(current_user_name()) ?></h2>
                    <p>You are currently logged in.</p>
                </div>
            </div>

            <div class="account-card stats-card">
                <h2>Quick Summary</h2>
                <div class="account-stats">
                    <div class="stat-item">
                        <span class="stat-number">4</span>
                        <span class="stat-label">Saved Recipes</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">3</span>
                        <span class="stat-label">Ratings Given</span>
                    </div>
                </div>
            </div>
        </section>

        <section class="account-actions-section">
            <div class="section-heading">
                <h2>Account Actions</h2>
            </div>

            <div class="account-actions-grid">
                <a href="favourites.php" class="account-action-card">
                    <h3>View Favourites</h3>
                    <p>See all recipes you have saved to your favourites list.</p>
                </a>

                <a href="search.php" class="account-action-card">
                    <h3>Browse Recipes</h3>
                    <p>Search and explore more recipes based on category and filters.</p>
                </a>

                <a href="logout.php" class="account-action-card logout-card">
                    <h3>Log Out</h3>
                    <p>Securely sign out of your account.</p>
                </a>
            </div>
        </section>

        <section class="homepage-section">
            <div class="section-heading">
                <h2>Recently Saved</h2>
                <a href="favourites.php">View all →</a>
            </div>

            <div class="popular-row">
                <article class="popular-card">
                    <div class="popular-image"></div>
                    <h3><a href="recipe.php?id=1">Spaghetti Bolognese</a></h3>
                </article>

                <article class="popular-card">
                    <div class="popular-image"></div>
                    <h3><a href="recipe.php?id=3">Healthy Pizza</a></h3>
                </article>

                <article class="popular-card">
                    <div class="popular-image"></div>
                    <h3><a href="recipe.php?id=5">Couscous Salad</a></h3>
                </article>
            </div>
        </section>
    </section>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
>>>>>>> 5f6bb54aeb591c58866448179a684fc2536edabc
