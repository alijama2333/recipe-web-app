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

$stmt = $pdo->prepare("DELETE FROM favourites WHERE user_id = ? AND recipe_id = ?");
$stmt->execute([current_user_id(), $recipe_id]);

header('Location: account.php');
exit;