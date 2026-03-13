<?php
/*
 * Recipe Database Table Creation Script
 * Creates all 6 tables for the recipe database schema
 * No XML parsing - SQL statements are hardcoded for direct execution
 */

// ==================== DATABASE CONNECTION PLACEHOLDERS ====================
$db_host = 'localhost';          // Database host
$db_user = 'root';               // Database username
$db_password = '';               // Database password
$db_name = 'recipe_app';         // Database name

// ==================== CREATE DATABASE CONNECTION ====================
try {
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);
    
    // Check connection
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
    
    echo "Connected to database successfully.<br><br>";
    
    // Set character set to UTF-8
    $conn->set_charset("utf8mb4");
    
} catch (Exception $e) {
    die("Database Connection Error: " . $e->getMessage());
}

// ==================== CREATE TABLE STATEMENTS ====================

// Array to store all SQL CREATE TABLE statements
$sql_statements = array(

    // TABLE 1: T2-diet (Lookup table - must be created first)
    "CREATE TABLE IF NOT EXISTS `T2-diet` (
        `id` INT(2) NOT NULL AUTO_INCREMENT,
        `d_val` SET('egg free', 'nut free', 'vegan', 'vegetarian', 'gluten free', 'pregnancy friendly', 'dairy free', 'shellfish free') DEFAULT NULL,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

    // TABLE 2: T3-ingred (Lookup table - must be created first)
    "CREATE TABLE IF NOT EXISTS `T3-ingred` (
        `id` INT(2) NOT NULL AUTO_INCREMENT,
        `ingredient` VARCHAR(255) NOT NULL,
        `qty` INT(5) DEFAULT NULL,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

    // TABLE 3: T4-meas (Lookup table - must be created first)
    "CREATE TABLE IF NOT EXISTS `T4-meas` (
        `id` INT(2) NOT NULL AUTO_INCREMENT,
        `meas_met` VARCHAR(20) DEFAULT NULL,
        `meas_imp` VARCHAR(20) DEFAULT NULL,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

    // TABLE 4: T1-rec-index (Main recipes table)
    "CREATE TABLE IF NOT EXISTS `T1-rec-index` (
        `rec_id` INT(4) NOT NULL AUTO_INCREMENT,
        `rec_name` VARCHAR(255) NOT NULL,
        `course` VARCHAR(20) DEFAULT NULL,
        `food_cat` VARCHAR(20) DEFAULT NULL,
        `prep_time` VARCHAR(255) DEFAULT NULL,
        `cook_time` VARCHAR(255) DEFAULT NULL,
        `serves` INT(2) DEFAULT NULL,
        `image_path` VARCHAR(255) DEFAULT NULL,
        `author` VARCHAR(255) DEFAULT NULL,
        `date_added` DATE DEFAULT NULL,
        `date_updated` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (`rec_id`),
        INDEX `idx_course` (`course`),
        INDEX `idx_food_cat` (`food_cat`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

    // TABLE 5: T5-method (Method steps - depends on T1)
    "CREATE TABLE IF NOT EXISTS `T5-method` (
        `id` INT(2) NOT NULL AUTO_INCREMENT,
        `rec_id` INT(4) NOT NULL,
        `step` INT(2) NOT NULL,
        `method` TEXT NOT NULL,
        PRIMARY KEY (`id`),
        INDEX `idx_rec_id` (`rec_id`),
        FOREIGN KEY (`rec_id`) REFERENCES `T1-rec-index`(`rec_id`) 
            ON DELETE CASCADE 
            ON UPDATE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

    // TABLE 6: T6-tips (Tips - depends on T1)
    "CREATE TABLE IF NOT EXISTS `T6-tips` (
        `id` INT(2) NOT NULL AUTO_INCREMENT,
        `rec_id` INT(4) NOT NULL,
        `tip` TEXT NOT NULL,
        PRIMARY KEY (`id`),
        INDEX `idx_rec_id` (`rec_id`),
        FOREIGN KEY (`rec_id`) REFERENCES `T1-rec-index`(`rec_id`) 
            ON DELETE CASCADE 
            ON UPDATE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"

);

// ==================== EXECUTE CREATE TABLE STATEMENTS ====================

$tables_created = 0;
$tables_failed = 0;

echo "Creating tables...<br>";
echo str_repeat("-", 50) . "<br><br>";

$table_names = array('T2-diet', 'T3-ingred', 'T4-meas', 'T1-rec-index', 'T5-method', 'T6-tips');

foreach ($sql_statements as $index => $sql) {
    try {
        if ($conn->query($sql) === TRUE) {
            $tables_created++;
            echo "[✓] " . $table_names[$index] . " created successfully.<br>";
        } else {
            throw new Exception($conn->error);
        }
    } catch (Exception $e) {
        $tables_failed++;
        echo "[✗] " . $table_names[$index] . " creation failed: " . $e->getMessage() . "<br>";
    }
}

// ==================== SUMMARY REPORT ====================

echo "<br>" . str_repeat("-", 50) . "<br>";
echo "Creation Summary:<br>";
echo "Tables Created: " . $tables_created . "/6<br>";
echo "Tables Failed: " . $tables_failed . "/6<br>";

if ($tables_failed === 0) {
    echo "<br>✓ All tables created successfully!<br>";
} else {
    echo "<br>✗ Some tables failed to create. Check errors above.<br>";
}

// ==================== CLOSE CONNECTION ====================
$conn->close();
echo "<br>Database connection closed.<br>";
?>