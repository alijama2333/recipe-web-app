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
</html>