# Recipe Web Application

This project requires PHP version **8.x**.

---

## Table of Contents

1. [Overview](#overview)  
2. [Features](#features)  
3. [Requirements](#requirements)  
4. [Setup Instructions](#setup-instructions)  
5. [Database Setup](#database-setup)  
6. [Project Structure](#project-structure)  
7. [Data Models](#data-models)  
8. [Testing](#testing)  

---

## Overview

Web Application to manage **Users**, **Recipes**, **Ingredients**, **Diet**, **Ratings**, and **Favourites** with both a **Backend (PHP)** and a **Frontend GUI (PHP and HTML)**.

---

## Features

- Users:
  - Create accounts
  - Read user data

- Recipes:
  - Read recipes
  - Search and filter recipes

- Favourites:
  - Add recipes to favourites
  - View favourite recipes
  - Remove recipes from favourites

- Ratings:
  - Add and view ratings for recipes

---

### Database
- MySQL relational database  
- Normalised schema with foreign key relationships  
- Includes SQL dump with sample data  

---

### Backend
- PHP version **8.x**
- Uses PDO prepared statements for secure database queries  
- Database initialised using included SQL dump (`recipe_app.sql`)  
- Dynamic SQL queries for filtering (search, ingredient, diet, category, time)  

---

### Frontend (GUI)
- Implemented using PHP and HTML  
- Server-rendered pages combining backend logic and frontend output  
- Pages include:
  - Home  
  - Search
  - Recipe
  - Rate Recipe
  - Account  
  - Favourites  
  - Register  
  - Login
- Built using Figma prototype:  
  `https://www.figma.com/proto/VvxTSrUMR9tRrcQOlN5tRj/CSCK543---Group-Project?node-id=0-1&t=A1CvBkIpVisALOvu-1` 

---

## Requirements

- PHP **8.x** 
- MySQL 
- Apache server (XAMPP)  

---

## Setup Instructions

1. Extract the ZIP file  

2. Move the project folder into:
C:\xampp\htdocs\

3. Start Apache and MySQL using XAMPP  

4. Create the database:
    CREATE DATABASE recipe_app;

5. Import the SQL dump

6. Open the application:

  `http://localhost/recipe-web-app/public/`


---

## Database Setup

The project includes a SQL dump file:
  recipe_app.sql


To initialise the database:

1. Open phpMyAdmin  
2. Select `recipe_app`  
3. Go to **Import**  
4. Upload the `.sql` file  

This will:

- create all tables 
- insert sample data

---

## Project Structure
````text
recipe-web-app/
│
├── public/
│   ├── index.php
│   ├── search.php
│   ├── recipe.php
│   ├── account.php
│   ├── favourites.php
│   ├── login.php
│   ├── logout.php
│   ├── rate_recipe.php
│   ├── register.php
│   ├── remove_favourite.php
│   ├── save_favourite.php
│
├── includes/
│   ├── db.php
│   ├── auth.php
│   ├── validation.php
│
├── database/
│   └── schema.sql
│
├── scripts/
│   └── scripts/create_recipe_tables_last_2300.php
│
└── README.md
````

## Data Models

#### `recipes`

- `recipe_id`
- `recipe_name`
- `course`
- `food_category`
- `prep_time`
- `cook_time`
- `total_time`
- `serves`
- `image_path`
- `author`
- `date_added`
- `date_updated`

#### `ingredients`

- `ingredient_id`
- `recipe_id`
- `ingredient`
- `qty`
- `measurement_id`

#### `measurements`

- `measurement_id`
- `meas_met`
- `meas_imp`

#### `methods`
- `method_id`
- `recipe_id`
- `step`
- `method`

#### `diet`

- `diet_id`
- `recipe_id`
- `d_val`

#### `tips`

- `tip_id`
- `recipe_id`
- `tip_text`


#### `users`

- `id`
- `name`
- `email`
- `password_hash`
- `created_at`

#### `ratings`

- `id`
- `user_id`
- `recipe_id`
- `rating`
- `review`
- `created_at`


#### `favourites`

- `id`
- `user_id`
- `recipe_id`
- `created_at`

## Testing

  **Manual UAT (User Acceptance Testing)**

  - Open `http://localhost/recipe-web-app/public/` in a browser.

  - Run through:
    - Creating a user account and navigating to user account page
    - Searching and filtering recipes
    - Adding recipes to favourites
    - Viewing favourite recipes in account page
    - Removing recipes from favourites
    - Adding and viewing ratings for recipes

---


