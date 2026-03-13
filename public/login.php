<<<<<<< HEAD
<<<<<<< HEAD
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
=======
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
<?php include __DIR__ . '/includes/header.php'; ?>

<section class="auth-page">
    <div class="auth-card">
        <div class="auth-header">
            <a href="index.php" class="back-link">← Back to home</a>
            <h1>Login</h1>
            <p>Sign in to save favourites and manage your account.</p>
        </div>

        <?php if (!empty($errors)): ?>
            <div class="auth-errors" role="alert">
                <?php foreach ($errors as $error): ?>
                    <p><?= htmlspecialchars($error) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="" class="auth-form">
            <div class="form-group">
                <label for="email">Email address</label>
                <input
                    id="email"
                    type="email"
                    name="email"
                    value="<?= htmlspecialchars($email) ?>"
                    required
                >
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input
                    id="password"
                    type="password"
                    name="password"
                    required
                >
            </div>

            <button type="submit" class="primary-button auth-submit">Login</button>
        </form>

        <p class="auth-footer-text">
            Need an account?
            <a href="register.php" class="text-link">Register here</a>
        </p>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
>>>>>>> 5f6bb54aeb591c58866448179a684fc2536edabc
