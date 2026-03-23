
-- USERS

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(150) UNIQUE,
    password_hash VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


-- RECIPES

CREATE TABLE recipes (
    recipe_id INT AUTO_INCREMENT PRIMARY KEY,
    recipe_name VARCHAR(255),
    course VARCHAR(100),
    food_category VARCHAR(100),
    prep_time VARCHAR(100),
    cook_time VARCHAR(100),
    total_time INT,
    serves INT,
    image_path VARCHAR(255),
    author VARCHAR(255),
    date_added DATE,
    date_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);


-- MEASUREMENTS

CREATE TABLE measurements (
    measurement_id INT AUTO_INCREMENT PRIMARY KEY,
    meas_met VARCHAR(100),
    meas_imp VARCHAR(100)
);


-- INGREDIENTS

CREATE TABLE ingredients (
    ingredient_id INT AUTO_INCREMENT PRIMARY KEY,
    recipe_id INT,
    ingredient VARCHAR(255),
    qty DECIMAL(10,2),
    measurement_id INT,
    FOREIGN KEY (recipe_id) REFERENCES recipes(recipe_id) ON DELETE CASCADE,
    FOREIGN KEY (measurement_id) REFERENCES measurements(measurement_id) ON DELETE SET NULL
);


-- METHODS (Steps)

CREATE TABLE methods (
    method_id INT AUTO_INCREMENT PRIMARY KEY,
    recipe_id INT,
    step INT,
    method TEXT,
    FOREIGN KEY (recipe_id) REFERENCES recipes(recipe_id) ON DELETE CASCADE
);


-- DIET

CREATE TABLE diet (
    diet_id INT AUTO_INCREMENT PRIMARY KEY,
    recipe_id INT,
    d_val VARCHAR(100),
    FOREIGN KEY (recipe_id) REFERENCES recipes(recipe_id) ON DELETE CASCADE
);


-- RATINGS

CREATE TABLE ratings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    recipe_id INT,
    rating INT,
    review TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (recipe_id) REFERENCES recipes(recipe_id) ON DELETE CASCADE,
    UNIQUE (user_id, recipe_id)
);


-- FAVOURITES

CREATE TABLE favourites (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    recipe_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (recipe_id) REFERENCES recipes(recipe_id) ON DELETE CASCADE,
    UNIQUE (user_id, recipe_id)
);


-- TIPS

CREATE TABLE tips (
    tip_id INT AUTO_INCREMENT PRIMARY KEY,
    recipe_id INT,
    tip_text TEXT,
    FOREIGN KEY (recipe_id) REFERENCES recipes(recipe_id) ON DELETE CASCADE
);
