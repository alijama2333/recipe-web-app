<?php
session_start();

require_once '../includes/auth.php';

require_login();
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

    <p><a href="logout.php">Logout</a></p>
</body>
</html>