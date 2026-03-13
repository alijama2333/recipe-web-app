<?php
session_start();

require_once '../includes/db.php';
require_once '../includes/auth.php';

require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die('Invalid request method.');
}

$recipe_id = $_POST['recipe_id'] ?? null;

if (!$recipe_id || !is_numeric($recipe_id)) {
    die('Invalid recipe ID.');
}

$recipe_id = (int)$recipe_id;

$stmt = $pdo->prepare("SELECT id FROM recipes WHERE id = ?");
$stmt->execute([$recipe_id]);

if (!$stmt->fetch()) {
    die('Recipe not found.');
}

$stmt = $pdo->prepare("INSERT IGNORE INTO favourites (user_id, recipe_id) VALUES (?, ?)");
$stmt->execute([current_user_id(), $recipe_id]);

header('Location: account.php');
exit;