<?php
session_start();

require_once '../includes/db.php';
require_once '../includes/validation.php';

$errors = [];
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = clean_input($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $email_error = validate_email_address($email);
    if ($email_error) {
        $errors[] = $email_error;
    }

    if ($password === '') {
        $errors[] = 'Password is required.';
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT id, name, password_hash FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password_hash'])) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];

            header('Location: account.php');
            exit;
        } else {
            $errors[] = 'Invalid email or password.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>
    <h1>Login</h1>

    <?php foreach ($errors as $error): ?>
        <p style="color:red;"><?= htmlspecialchars($error) ?></p>
    <?php endforeach; ?>

    <form method="POST" action="">
        <label for="email">Email:</label><br>
        <input id="email" type="email" name="email" value="<?= htmlspecialchars($email) ?>" required><br><br>

        <label for="password">Password:</label><br>
        <input id="password" type="password" name="password" required><br><br>

        <button type="submit">Login</button>
    </form>

    <p><a href="register.php">Need an account? Register</a></p>
</body>
</html>