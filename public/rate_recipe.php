<?php
session_start();

require_once '../includes/db.php';
require_once '../includes/auth.php';
require_once '../includes/validation.php';

require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die('Invalid request method.');
}

$recipe_id = $_POST['recipe_id'] ?? null;
$rating = $_POST['rating'] ?? '';
$review = trim($_POST['review'] ?? '');

if (!$recipe_id || !is_numeric($recipe_id)) {
    die('Invalid recipe ID.');
}

$recipe_id = (int)$recipe_id;

$rating_error = validate_rating($rating);
if ($rating_error) {
    die($rating_error);
}

$rating = (int)$rating;

$stmt = $pdo->prepare("SELECT recipe_id FROM recipes WHERE recipe_id = ?");
$stmt->execute([$recipe_id]);

if (!$stmt->fetch()) {
    die('Recipe not found.');
}

$stmt = $pdo->prepare("
    INSERT INTO ratings (user_id, recipe_id, rating, review)
    VALUES (?, ?, ?, ?)
    ON DUPLICATE KEY UPDATE
        rating = VALUES(rating),
        review = VALUES(review)
");
$stmt->execute([current_user_id(), $recipe_id, $rating, $review]);

header('Location: recipe.php?id=' . $recipe_id);
exit;