<<<<<<< HEAD

=======
>>>>>>> 0e9332d3cb821a34ed466083b9e108a5db6f09bf
<?php
$host = 'localhost';
$dbname = 'recipe_app';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
};
