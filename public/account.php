<?php
session_start();

require_once '../includes/db.php';
require_once '../includes/auth.php';

require_login();

$userId = current_user_id();

/* Count saved recipes */
$stmt = $pdo->prepare("
    SELECT COUNT(*) 
    FROM favourites
    WHERE user_id = ?
");
$stmt->execute([$userId]);
$savedCount = (int)$stmt->fetchColumn();

/* Count ratings given */
$stmt = $pdo->prepare("
    SELECT COUNT(*)
    FROM ratings
    WHERE user_id = ?
");
$stmt->execute([$userId]);
$ratingsCount = (int)$stmt->fetchColumn();

/* Recently saved recipes */
$stmt = $pdo->prepare("
    SELECT r.recipe_id, r.recipe_name, r.image_path
    FROM favourites f
    JOIN recipes r ON f.recipe_id = r.recipe_id
    WHERE f.user_id = ?
    ORDER BY f.created_at DESC
    LIMIT 3
");
$stmt->execute([$userId]);
$recentRecipes = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
                        <span class="stat-number"><?= $savedCount ?></span>
                        <span class="stat-label">Saved Recipes</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number"><?= $ratingsCount ?></span>
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

            <?php if (empty($recentRecipes)): ?>
                <p>You haven’t saved any recipes yet.</p>
            <?php else: ?>
                <div class="popular-row">
                    <?php foreach ($recentRecipes as $recipe): ?>
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
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
    </section>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>