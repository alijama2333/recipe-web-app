<<<<<<< HEAD

=======
>>>>>>> 0e9332d3cb821a34ed466083b9e108a5db6f09bf
<?php

function is_logged_in(): bool
{
    return isset($_SESSION['user_id']);
}

function require_login(): void
{
    if (!is_logged_in()) {
        header('Location: login.php');
        exit;
    }
}

function current_user_id(): ?int
{
    return $_SESSION['user_id'] ?? null;
}

function current_user_name(): ?string
{
    return $_SESSION['user_name'] ?? null;
}