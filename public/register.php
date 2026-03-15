
<?php
session_start();

require_once '../includes/db.php';
require_once '../includes/validation.php';

$errors = [];
$name = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = clean_input($_POST['name'] ?? '');
    $email = clean_input($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    $name_error = validate_name($name);
    if ($name_error) {
        $errors[] = $name_error;
    }

    $email_error = validate_email_address($email);
    if ($email_error) {
        $errors[] = $email_error;
    }

    $password_error = validate_password($password);
    if ($password_error) {
        $errors[] = $password_error;
    }

    if ($password !== $confirm_password) {
        $errors[] = 'Passwords do not match.';
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);

        if ($stmt->fetch()) {
            $errors[] = 'Email already exists.';
        } else {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $pdo->prepare("INSERT INTO users (name, email, password_hash) VALUES (?, ?, ?)");
            $stmt->execute([$name, $email, $password_hash]);

            header('Location: login.php');
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
</head>
<body>
    <h1>Register</h1>

    <?php foreach ($errors as $error): ?>
        <p style="color:red;"><?= htmlspecialchars($error) ?></p>
    <?php endforeach; ?>

    <form method="POST" action="">
        <label for="name">Name:</label><br>
        <input id="name" type="text" name="name" value="<?= htmlspecialchars($name) ?>" required><br><br>

        <label for="email">Email:</label><br>
        <input id="email" type="email" name="email" value="<?= htmlspecialchars($email) ?>" required><br><br>

        <label for="password">Password:</label><br>
        <input id="password" type="password" name="password" required><br><br>

        <label for="confirm_password">Confirm Password:</label><br>
        <input id="confirm_password" type="password" name="confirm_password" required><br><br>

        <button type="submit">Register</button>
    </form>

    <p><a href="login.php">Already have an account? Login</a></p>
</body>
</html>
=======
<?php
session_start();

require_once '../includes/db.php';
require_once '../includes/validation.php';

$errors = [];
$name = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = clean_input($_POST['name'] ?? '');
    $email = clean_input($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    $name_error = validate_name($name);
    if ($name_error) {
        $errors[] = $name_error;
    }

    $email_error = validate_email_address($email);
    if ($email_error) {
        $errors[] = $email_error;
    }

    $password_error = validate_password($password);
    if ($password_error) {
        $errors[] = $password_error;
    }

    if ($password !== $confirm_password) {
        $errors[] = 'Passwords do not match.';
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);

        if ($stmt->fetch()) {
            $errors[] = 'Email already exists.';
        } else {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $pdo->prepare("INSERT INTO users (name, email, password_hash) VALUES (?, ?, ?)");
            $stmt->execute([$name, $email, $password_hash]);

            header('Location: login.php');
            exit;
        }
    }
}
?>

<?php include __DIR__ . '/includes/header.php'; ?>

<section class="auth-page">
    <div class="auth-card">

        <div class="auth-header">
            <a href="index.php" class="back-link">← Back to home</a>
            <h1>Create Account</h1>
            <p>Register to save favourite recipes and track your cooking.</p>
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
                <label for="name">Full name</label>
                <input
                    id="name"
                    type="text"
                    name="name"
                    value="<?= htmlspecialchars($name) ?>"
                    required
                >
            </div>

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

            <div class="form-group">
                <label for="confirm_password">Confirm password</label>
                <input
                    id="confirm_password"
                    type="password"
                    name="confirm_password"
                    required
                >
            </div>

            <button type="submit" class="primary-button auth-submit">
                Create account
            </button>

        </form>

        <p class="auth-footer-text">
            Already have an account?
            <a href="login.php" class="text-link">Login here</a>
        </p>

    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
>>>>>>> 5f6bb54aeb591c58866448179a684fc2536edabc
