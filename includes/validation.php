<?php

function clean_input(string $value): string
{
    return trim($value);
}

function validate_name(string $name): ?string
{
    if ($name === '') {
        return 'Name is required.';
    }

    if (strlen($name) < 2) {
        return 'Name must be at least 2 characters.';
    }

    return null;
}

function validate_email_address(string $email): ?string
{
    if ($email === '') {
        return 'Email is required.';
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return 'Valid email is required.';
    }

    return null;
}

function validate_password(string $password): ?string
{
    if ($password === '') {
        return 'Password is required.';
    }

    if (strlen($password) < 6) {
        return 'Password must be at least 6 characters.';
    }

    return null;
}

function validate_rating($rating): ?string
{
    if ($rating === '' || $rating === null) {
        return 'Rating is required.';
    }

    if (!is_numeric($rating)) {
        return 'Rating must be a number.';
    }

    $rating = (int)$rating;

    if ($rating < 1 || $rating > 5) {
        return 'Rating must be between 1 and 5.';
    }

    return null;
}