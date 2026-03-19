<?php
/*
 * Recipe Database Table Creation + Sample Data Insert Script
 * Creates all 6 tables and inserts sample data
 */

// ==================== DATABASE CONNECTION ====================
$db_host = 'localhost';          // Database host
$db_user = 'root';               // Database username
$db_password = '';               // Database password
$db_name = 'recipe_app';         // Database name
$db_port = 3306;


try {
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name, $db_port);

    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    echo "Connected to database successfully.<br><br>";
    $conn->set_charset("utf8mb4");

} catch (Exception $e) {
    die("Database Connection Error: " . $e->getMessage());
}

// ==================== CREATE TABLE STATEMENTS ====================

$sql_statements = [

    // 1. recipes
    "CREATE TABLE IF NOT EXISTS `recipes` (
        `recipe_id` INT NOT NULL AUTO_INCREMENT,
        `recipe_name` VARCHAR(255) NOT NULL,
        `course` VARCHAR(100) NOT NULL,
        `food_category` VARCHAR(100) NOT NULL,
        `prep_time` VARCHAR(100) NOT NULL,
        `cook_time` VARCHAR(100) NOT NULL,
        `total_time` INT NOT NULL,
        `serves` INT NOT NULL,
        `image_path` VARCHAR(255) DEFAULT NULL,
        `author` VARCHAR(255) NOT NULL,
        `date_added` DATE NOT NULL,
        `date_updated` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (`recipe_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

    // 2. diet
    "CREATE TABLE IF NOT EXISTS `diet` (
        `diet_id` INT NOT NULL AUTO_INCREMENT,
        `recipe_id` INT NOT NULL,
        `d_val` VARCHAR(100) NOT NULL,
        PRIMARY KEY (`diet_id`),
        CONSTRAINT `fk_diet_recipe`
            FOREIGN KEY (`recipe_id`) REFERENCES `recipes`(`recipe_id`)
            ON DELETE CASCADE
            ON UPDATE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

    // 3. measurements
    "CREATE TABLE IF NOT EXISTS `measurements` (
        `measurement_id` INT NOT NULL AUTO_INCREMENT,
        `meas_met` VARCHAR(100) DEFAULT NULL,
        `meas_imp` VARCHAR(100) DEFAULT NULL,
        PRIMARY KEY (`measurement_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

    // 4. ingredients
    "CREATE TABLE IF NOT EXISTS `ingredients` (
        `ingredient_id` INT NOT NULL AUTO_INCREMENT,
        `recipe_id` INT NOT NULL,
        `ingredient` VARCHAR(255) NOT NULL,
        `qty` DECIMAL(10,2) NOT NULL,
        `measurement_id` INT DEFAULT NULL,
        PRIMARY KEY (`ingredient_id`),
        CONSTRAINT `fk_ingredients_recipe`
            FOREIGN KEY (`recipe_id`) REFERENCES `recipes`(`recipe_id`)
            ON DELETE CASCADE
            ON UPDATE CASCADE,
        CONSTRAINT `fk_ingredients_measurement`
            FOREIGN KEY (`measurement_id`) REFERENCES `measurements`(`measurement_id`)
            ON DELETE SET NULL
            ON UPDATE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

    // 5. methods
    "CREATE TABLE IF NOT EXISTS `methods` (
        `method_id` INT NOT NULL AUTO_INCREMENT,
        `recipe_id` INT NOT NULL,
        `step` INT NOT NULL,
        `method` TEXT NOT NULL,
        PRIMARY KEY (`method_id`),
        CONSTRAINT `fk_methods_recipe`
            FOREIGN KEY (`recipe_id`) REFERENCES `recipes`(`recipe_id`)
            ON DELETE CASCADE
            ON UPDATE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

    // 6. tips
    "CREATE TABLE IF NOT EXISTS `tips` (
        `tip_id` INT NOT NULL AUTO_INCREMENT,
        `recipe_id` INT NOT NULL,
        `tip_text` TEXT NOT NULL,
        PRIMARY KEY (`tip_id`),
        CONSTRAINT `fk_tips_recipe`
            FOREIGN KEY (`recipe_id`) REFERENCES `recipes`(`recipe_id`)
            ON DELETE CASCADE
            ON UPDATE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

];

// ==================== RESET TABLES ====================

try {
    $conn->query("SET FOREIGN_KEY_CHECKS = 0");

    $conn->query("DROP TABLE IF EXISTS tips");
    $conn->query("DROP TABLE IF EXISTS methods");
    $conn->query("DROP TABLE IF EXISTS ingredients");
    $conn->query("DROP TABLE IF EXISTS diet");
    $conn->query("DROP TABLE IF EXISTS measurements");
    $conn->query("DROP TABLE IF EXISTS recipes");

    $conn->query("SET FOREIGN_KEY_CHECKS = 1");

    echo "!! Existing tables reset successfully !!<br><br>";

} catch (Exception $e) {
    echo "Warning: Could not reset old tables - " . $e->getMessage() . "<br><br>";
    $conn->query("SET FOREIGN_KEY_CHECKS = 1");
}

// ==================== EXECUTE CREATE TABLE STATEMENTS ====================

$tables_created = 0;
$tables_failed = 0;

echo "Creating tables...<br>";
echo str_repeat("-", 70) . "<br>";

$table_names = [
    'recipes',
    'diet',
    'measurements',
    'ingredients',
    'methods',
    'tips'
];

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
        echo "[✗] " . $table_names[$index] . " creation failed: " . $e->getMessage() . "<br><br>";
    }
}

// ==================== CREATE SUMMARY REPORT ====================

echo "" . str_repeat("-", 70) . "<br>";
echo "Table Creation Summary:<br>";
echo "  Tables Created: " . $tables_created . "/6<br>";
echo "  Tables Failed: " . $tables_failed . "/6<br>";

if ($tables_failed === 0) {
    echo "✓ All tables created successfully!<br><br>";
} else {
    echo "✗ Some tables failed to create. Check errors above.<br><br>";
}

// ==================== INSERT SAMPLE DATA STATEMENTS ====================

$insert_statements = [

    // 1. recipes
    "INSERT INTO recipes
    (recipe_name, course, food_category, prep_time, cook_time, serves, image_path, author, date_added)
    VALUES
    ('Tomatoes','Main','meat/vegatarian','less than 30 mins','1-2 hours',80,8,'relative path','Jo Pratt','2026-03-01'),
    ('Easy Lamb Biryani','Main','meat','over night','1-2 hours',420,6,'relative path','Sunil Vijayakar','2026-03-02'),
    ('Healthy Pizza','Main','vegetarian','less than 30 mins','10-30 minutes',25,2,'relative path','Justine Pattison','2026-03-03'),
    ('Mushroom Doner','Main','vegetarian','less than 30 mins','10-30 minutes',25,4,'relative path','Sabrina Ghayour','2026-03-04'),
    ('Couscous Salad','Salad','vegan/vegetarian','less than 30 mins','Less than 10 minutes',20,6,'relative path','Nargisse Benkabbou','2026-03-05'),
    ('Vegan Pancakes','Dessert','vegan/vegetarian','less than 30 mins','10-30 minutes',25,2,'relative path','Justine Pattison','2026-03-06'),
    ('Plum Clafoutis','Dessert','vegetarian','less than 30 mins','30 minutes to 1 hour',45,4,'relative path','James Martin','2026-03-07'),
    ('Mango Pie','Dessert','vegetarian','30 minutes to 1 hour','30 minutes to 1 hour',60,16,'relative path','Samin Nosrat','2026-03-08')",

    // 2. diet
    "INSERT INTO diet (recipe_id, d_val) VALUES
    (1,'egg free'),
    (1,'nut free'),
    (1,'vegetarian'),
    (2,'egg free'),
    (2,'gluten free'),
    (2,'pregnancy friendly'),
    (3,'egg free'),
    (3,'nut free'),
    (3,'vegetarian,pregnancy-friendly'),
    (3,'healthy'),
    (4,'egg free'),
    (4,'nut free'),
    (4,'vegetarian,pregnancy-friendly'),
    (4,'healthy'),
    (5,'egg free'),
    (5,'vegan'),
    (5,'vegetarian'),
    (6,'egg free'),
    (6,'vegan'),
    (6,'vegetarian'),
    (7,'vegetarian'),
    (8,'egg free'),
    (8,'nut free')",

    // 3. measurements
    "INSERT INTO measurements (measurement_id, meas_met, meas_imp) VALUES
    (1,'tbsp','tbsp'),
    (2,'rashers','rashers'),
    (3,'large','large'),
    (4,'cloves','cloves'),
    (5,'kg','lb'),
    (6,'large glasses','large glasses'),
    (7,'tins','tins'),
    (8,'jar','jar'),
    (9,'leaves','leaves'),
    (10,'tsp','tsp'),
    (11,'drizzle','drizzle'),
    (12,'halves','halves'),
    (13,'to taste','to taste'),
    (14,'handful torn into small pieces','handful torn into small pieces'),
    (15,'g','oz'),
    (16,'fruit','fruit'),
    (17,'peppers','peppers'),
    (18,'125g','4.4 oz'),
    (19,'pinch','pinch'),
    (20,'1 cm slices','1 cm slices'),
    (21,'slices','slices'),
    (22,'to serve','to serve'),
    (23,'tin','tin'),
    (24,'pitta','pitta'),
    (25,'cabbage','cabbage'),
    (26,'tomatoes','tomatoes'),
    (27,'small fruits','small fruits'),
    (28,'ml','fl oz'),
    (29,'drops','drops'),
    (30,'eggs','eggs'),
    (31,'cup','cup'),
    (32,'packet','packet'),
    (33,'litre','quart'),
    (34,'whole','whole')",

    // 4. ingredients
    "INSERT INTO ingredients (recipe_id, ingredient, qty, measurement_id) VALUES
    (1,'Olive Oil',2,1),
    (1,'Streaky Bacon (Chopped)',6,2),
    (1,'Onions (Chopped)',2,3),
    (1,'Garlic Cloves (Crushed)',3,4),
    (1,'Lean Minced Beef',1,5),
    (1,'Red Wine',2,6),
    (1,'Chopped Tomatoes',2,7),
    (1,'Anti Pasti Marinated Mushrooms',1,8),
    (1,'Bay Leaves',2,9),
    (1,'Oregano',1,10),
    (1,'Thyme',1,10),
    (1,'Balsamic Vinegar',1,11),
    (1,'Sun Dried Tomatoes',14,12),
    (1,'Salt',0,13),
    (1,'Black Pepper',0,13),
    (1,'Basil',1,14),
    (1,'Spaghetti',1,32),
    (1,'Parmesan Cheese',0,13),
    (2,'Vegetable Oil',5,1),
    (2,'Onions (Chopped)',2,3),
    (2,'Yoghurt',200,15),
    (2,'Ginger (Finely Grated)',4,34),
    (2,'Grated Garlic',3,4),
    (2,'Chilli Powder',2,1),
    (2,'Cumin (Ground)',5,10),
    (2,'Cardamom Seeds (Ground)',1,10),
    (2,'Sea Salt',4,10),
    (2,'Lime (Juice Only)',1,16),
    (2,'Coriander (Chopped)',30,15),
    (2,'Mint (Chopped Leaves)',30,15),
    (2,'Chillies (Finely Chopped)',4,17),
    (2,'Lamb (Boneless)',800,15),
    (2,'Double Cream',4,1),
    (2,'Full Fat Milk',1.5,33),
    (2,'Saffron Strands',1,19),
    (2,'Basmati Rice',400,15),
    (2,'Pomegranate Seeds',2,1),
    (3,'Wholemeal Flour (Self-Raising)',1,18),
    (3,'Sea Salt',1,19),
    (3,'Yoghurt',1,18),
    (3,'Bell Pepper (Orange)',1,34),
    (3,'Courgette',1,20),
    (3,'Red Onion (Sliced)',1,21),
    (3,'Olive Oil',1,1),
    (3,'Dried Chilli Flakes',0.5,10),
    (3,'Goats Cheese or Vegetarian Alternative',50,15),
    (3,'Mozzarella (Torn, Balls)',50,15),
    (3,'Cheddar Cheese',50,15),
    (3,'Black Pepper',0,13),
    (3,'Basil',0,22),
    (3,'Passata Sauce',6,1),
    (3,'Oregano',1,10),
    (4,'Chopped Tomatoes',1,23),
    (4,'Rose Harissa',2,1),
    (4,'Caster Sugar',2,10),
    (4,'Lemon (Juice)',0.5,16),
    (4,'Onions (Chopped)',1,34),
    (4,'White Wine Vinegar',2,1),
    (4,'Parsley (Finely Chopped)',20,15),
    (4,'Yoghurt',150,15),
    (4,'Mint (Chopped Leaves)',1,10),
    (4,'Salt',0,13),
    (4,'Black Pepper',0,13),
    (4,'Oyster Mushrooms',500,15),
    (4,'Garlic Oil',2,1),
    (4,'Paprika',2,10),
    (4,'Coriander (Chopped)',2,1),
    (4,'Celery Salt',2,10),
    (4,'Garlic Granules',3,10),
    (4,'White Pitta Bread',4,24),
    (4,'White Cabbage (Shredded)',0.25,25),
    (4,'Tomatoes (Sliced into Wedges)',2,26),
    (4,'Pickled Chillies (Optional)',6,17),
    (5,'Couscous (Prepared)',225,15),
    (5,'Preserved Lemons (Chopped)',8,27),
    (5,'Dried Cranberries',180,15),
    (5,'Toasted Pine Nuts',120,15),
    (5,'Unsalted Shelled Pistachios',150,15),
    (5,'Olive Oil',125,28),
    (5,'Parsley (Finely Chopped)',60,15),
    (5,'Garlic Cloves (Crushed)',4,4),
    (5,'Red Wine Vinegar',4,1),
    (5,'Red Onion (Finely Chopped)',1,34),
    (5,'Salt',1,10),
    (5,'Rocket Leaves',80,15),
    (6,'Self Raising Flour',125,15),
    (6,'Caster Sugar',2,1),
    (6,'Baking Powder',1,10),
    (6,'Salt',1,19),
    (6,'Soya Milk or Almond Milk',150,28),
    (6,'Almond Milk or Oat Milk',150,28),
    (6,'Vanilla Extract',0.25,10),
    (6,'Sunflower Oil (For Frying)',4,1),
    (7,'Milk',125,28),
    (7,'Double Cream',125,28),
    (7,'Vanilla Essence',3,29),
    (7,'Eggs',4,30),
    (7,'Caster Sugar',170,15),
    (7,'Plain Flour',1,31),
    (7,'Butter',30,15),
    (7,'Plums (Cut in Half, Stones Removed)',500,15),
    (7,'Brown Sugar',2,1),
    (7,'Flaked Almonds',30,15),
    (7,'Icing Sugar (For Dusting)',0,22),
    (7,'Double Cream to Serve',0,22),
    (8,'Digestive Biscuits',280,15),
    (8,'Granulated Sugar',65,15),
    (8,'Cardamom Seeds (Ground)',0.25,10),
    (8,'Butter',128,15),
    (8,'Sea Salt',1,19),
    (8,'Granulated Sugar',100,15),
    (8,'Gelatine',2,9),
    (8,'Double Cream',120,28),
    (8,'Cream Cheese',115,15),
    (8,'Alfonso Mango Pulp',1,23),
    (8,'Sea Salt',0.25,10)",

    // 5. methods
    "INSERT INTO methods (recipe_id, step, method) VALUES
    (1,1,'Heat the oil in a large, heavy-based saucepan and fry the bacon until golden over a medium heat. Add the onions and garlic, frying until softened. Increase the heat and add the minced beef. Fry it until it has browned, breaking down any chunks of meat with a wooden spoon. Pour in the wine and boil until it has reduced in volume by about a third. Reduce the temperature and stir in the tomatoes, drained mushrooms, bay leaves, oregano, thyme and balsamic vinegar.'),
    (1,2,'Either blitz the sun-dried tomatoes in a small blender with a little of the oil to loosen, or just finely chop before adding to the pan. Season well with salt and pepper. Cover with a lid and simmer the sauce over a gentle heat for 1-1 1/2 hours until it''s rich and thickened, stirring occasionally. At the end of the cooking time, stir in the basil and add any extra seasoning if necessary.'),
    (1,3,'Remove from the heat to ''settle'' while you cook the spaghetti in plenty of boiling salted water (for the time stated on the packet). Drain and divide between warmed plates. Scatter a little parmesan over the spaghetti before adding a good ladleful of the sauce, finishing with a scattering of more cheese and a twist of black pepper.'),

    (2,1,'Heat the oil in a non-stick frying pan over a medium heat. Add the onions and stir-fry for 15-18 minutes, or until lightly browned and crispy.'),
    (2,2,'Put half the onions in a non-metallic mixing bowl with the yoghurt, ginger, garlic, chilli powder, cumin, cardamom, half of the salt, the lime juice, half of the chopped coriander and mint and the green chillies. Stir well to combine. Set aside the remaining coriander and mint for layering the biryani.'),
    (2,3,'Add the lamb to the mixture and stir to coat evenly. Cover and marinate in the fridge for 6-8 hours, or overnight if possible.'),
    (2,4,'Preheat the oven to 240C/Fan 220C/Gas 9.'),
    (2,5,'Heat the cream and milk in a small saucepan, add the saffron, remove from the heat and leave to infuse for 30 minutes.'),
    (2,6,'Cook the rice in a large saucepan in plenty of boiling water with the remaining salt for 6-8 minutes, or until it is just cooked, but still has a bite. Drain the rice.'),
    (2,7,'Spread half of the lamb mixture evenly in a wide, heavy-based casserole and cover with a layer of half the rice. Sprinkle over half of the reserved onions and half of the reserved coriander and mint. Sprinkle over half of the saffron mixture. Repeat with the remaining lamb, rice, onions, herbs and saffron mixture.'),
    (2,8,'Cover with a tight fitting lid, turn down the oven to 200C/Fan 180C/Gas 6 and cook for 1 hour. Remove and allow to stand for 15-20 minutes before serving. Garnish with pomegranate seeds if desired.'),

    (3,1,'Preheat the oven to 220C/200C Fan/Gas 7.'),
    (3,2,'To prepare the topping, put the pepper, courgette, red onion and oil in a bowl, season with lots of black pepper and mix together. Scatter the vegetables over a large baking tray and roast for 15 minutes.'),
    (3,3,'Meanwhile, make the pizza base. Mix the flour and salt in a large bowl. Add the yoghurt and 1 tablespoon of cold water and mix with a spoon, then use your hands to form a soft, spongy dough. Turn out onto a lightly floured surface and knead for about 1 minute.'),
    (3,4,'Using a floured rolling pin, roll the dough into a roughly oval shape, approx. 3mm/1/8in thick, turning regularly. (Ideally, the pizza should be around 30cm/12in long and 20cm/8in wide, but it doesn''t matter if the shape is uneven, it just needs to fit onto the same baking tray that the vegetables were cooked on.)'),
    (3,5,'Transfer the roasted vegetables to a bowl. Slide the pizza dough onto the baking tray and bake for 5 minutes. Take the tray out of the oven and turn the dough over.'),
    (3,6,'For the tomato sauce, mix the passata with the oregano and spread over the dough. Top with the roasted vegetables, sprinkle with the chilli flakes and then the cheese. Bake the pizza for a further 8-10 minutes, or until the dough is cooked through and the cheese beginning to brown.'),
    (3,7,'Season with black pepper, drizzle with a slurp of olive oil and, if you like, scatter fresh basil leaves on top just before serving.'),

    (4,1,'Preheat the oven to 180C/200C Fan/Gas 4.'),
    (4,2,'To make the chilli sauce, heat the chopped tomatoes, rose harissa, sugar and lemon juice in a small saucepan over a medium heat. Bring to a gentle boil and cook for 10 minutes, stirring regularly, until reduced to a thick sauce-like consistency. Remove from the heat and set aside to cool. You can blend the sauce until it''s smooth using a hand-blender if you like, or just leave it chunky.'),
    (4,3,'For the onion, mix together the onion slices, vinegar and parsley and set aside.'),
    (4,4,'To make the yoghurt sauce, mix the yoghurt with the dried mint, season with salt and pepper and set aside.'),
    (4,5,'Put the pittas in the oven to warm for 5 minutes.'),
    (4,6,'To make the ''doner'', heat a frying pan over a medium-high heat. Add the mushrooms and dry-fry for 2 minutes, stirring once or twice. Add the garlic oil, paprika, coriander, celery salt, garlic granules and black pepper and quickly coat the mushrooms. Add 2-3 tablespoons of water to the pan and stir-fry for 1 minute before removing from the heat.'),
    (4,7,'Split the warmed pitta breads. Spoon a little white cabbage into each pitta and add a little tomato and onion. Divide the mushrooms between the pittas, add a little more cabbage and tomato, then drizzle with the chilli and yoghurt sauces. Serve immediately, topped with the pickled chillies, if using.'),

    (5,1,'In a large bowl mix all the ingredients together except the rocket, then taste and adjust the seasoning, adding more salt if necessary. Toss in the rocket and serve immediately.'),

    (6,1,'Put the flour, sugar, baking powder and salt in a bowl and mix thoroughly. Add the milk and vanilla extract and mix with a whisk until smooth.'),
    (6,2,'Place a large non-stick frying pan over a medium heat. Add 2 teaspoons of the oil and wipe around the pan with a heatproof brush or carefully using a thick wad of kitchen paper.'),
    (6,3,'Once the pan is hot, pour a small ladleful (around two dessert spoons) of the batter into one side of the pan and spread with the back of the spoon until around 10cm/4in in diameter. Make a second pancake in exactly the same way, greasing the pan with the remaining oil before adding the batter.'),
    (6,4,'Cook for about a minute, or until bubbles are popping on the surface and just the edges look dry and slightly shiny. Quickly and carefully flip over and cook on the other side for a further minute, or until light, fluffy and pale golden brown. If you turn the pancakes too late, they will be too set to rise evenly. You can always flip again if you need the first side to go a little browner.'),
    (6,5,'Transfer to a plate and keep warm in a single layer (so they don''t get squished) on a baking tray in a low oven while the rest of the pancakes are cooked in exactly the same way. Serve with your preferred toppings.'),

    (7,1,'Preheat the oven to 180C/350F/Gas 4.'),
    (7,2,'Pour the milk, cream and vanilla into a pan and boil for a minute. Remove from the heat and set aside to cool.'),
    (7,3,'Tip the eggs and sugar into a bowl and beat together until light and fluffy. Fold the flour into the mixture, a little at a time.'),
    (7,4,'Pour the cooled milk and cream onto the egg and sugar mixture, whisking lightly. Set aside.'),
    (7,5,'Place a little butter into an ovenproof dish and heat in the oven until foaming. Add the plums and brown sugar and bake for 5 minutes, then pour the batter into the dish and scatter with flaked almonds, if using.'),
    (7,6,'Cook in the oven for about 30 minutes, until golden-brown and set but still light and soft inside.'),
    (7,7,'Dust with icing sugar and serve immediately with cream.'),

    (8,1,'To make the biscuit base, finely crush the biscuits by putting into a sealed plastic bag and bashing with a rolling pin (alternatively, pulse to crumbs using a food processor). Transfer to a mixing bowl and add the sugar, cardamom and salt, stirring well to combine.'),
    (8,2,'Pour the melted butter over the biscuit crumbs and mix until thoroughly combined. Put half the crumb mixture in a 23cm/9in metal pie tin, and press evenly with your fingers. Build up the sides of the tin, compressing the base as much as possible to prevent it crumbling. Repeat with the rest of the mixture in the second tin.'),
    (8,3,'Preheat the oven to 160C/325F/Gas 3. Put the pie bases in the freezer for 15 minutes. Remove and bake for 12 minutes, or until golden brown. Transfer to a wire rack to cool.'),
    (8,4,'Meanwhile, whip the cream with the remaining sugar to form medium stiff peaks. Set aside.'),
    (8,5,'Heat about a quarter of the mango pulp in a saucepan over a medium-low heat, until just warm. Make sure you do not boil it. Pour into the gelatine mixture and whisk, until well combined. The gelatine should dissolve completely. Gradually whisk in the remaining mango pulp.'),
    (8,6,'Fold about a quarter of the mango mixture into the whipped cream using a spatula. Repeat with the rest of the cream, until no streaks remain.'),
    (8,7,'Divide the filling between the cooled bases, using a rubber spatula to smooth out the tops.')",

    // 6. tips
    "INSERT INTO tips (recipe_id, tip_text) VALUES
    (1,'You can make a veggie version of this recipe by substituting soya mince or Quorn for the meat, adding it to the sauce halfway through cooking. Or simply add lots of diced vegetables to the onions, such as courgettes, carrots, peppers and aubergines.'),
    (1,'how to brown mince (hyperlink) https://www.bbc.co.uk/food/how_to_cook/watch/how_to_brown_mince'),
    (2,'Kashmiri red chilli powder is quite mild with a slightly smoky flavour that really adds to the dish.'),
    (2,'how to chop onion https://www.bbc.co.uk/food/how_to_cook/watch/slicing_onions'),
    (2,'how to chop chilli https://www.bbc.co.uk/food/how_to_cook/watch/chopping_chillies'),
    (3,'You can use any cheese you like for this pizza - it''s also a great way to use up a mix of odds and ends from the fridge.'),
    (3,'Any leftover passata can be used for pasta sauces, stews or curries. It freezes well for up to 4 months. Instead of passata, you can use a bought pizza topping or strained tinned tomatoes'),
    (3,'Make two pizzas instead of one large pizza if you like.'),
    (3,'If you don''t have self-raising wholemeal flour, use plain wholemeal flour and add 1 teaspoon of baking powder and an extra tablespoon of water if needed.'),
    (5,'Couscous salads are great to make ahead for easy entertaining or weekday lunches. It will keep well for a few days in the fridge.'),
    (6,'Whipped coconut cream is good with these too, but it must be well chilled before whipping.'),
    (6,'You can keep the pancakes warm in a low oven while you make the full batch.'),
    (8,'This recipe makes two pies, so halve the ingredients if you''re not feeding a crowd.')",

];

// ==================== EXECUTE INSERT STATEMENTS ====================

$insert_success = 0;
$insert_failed = 0;

echo "Inserting sample data...<br>";
echo str_repeat("-", 70) . "<br>";

$insert_names = [
    'recipes',
    'diet',
    'measurements',
    'ingredients',
    'methods',
    'tips'
];

foreach ($insert_statements as $index => $sql) {
    try {
        if ($conn->query($sql) === TRUE) {
            $insert_success++;
            echo "[✓] Sample data inserted into " . $insert_names[$index] . " successfully.<br>";
        } else {
            throw new Exception($conn->error);
        }
    } catch (Exception $e) {
        $insert_failed++;
        echo "[✗] Insert failed for " . $insert_names[$index] . ": " . $e->getMessage() . "<br>";
    }
}

// ==================== INSERT SUMMARY REPORT ====================

echo "" . str_repeat("-", 70) . "<br>";
echo "Sample Data Insert Summary:<br>";
echo "  Inserts Successful: " . $insert_success . "/6<br>";
echo "  Inserts Failed: " . $insert_failed . "/6<br>";

if ($insert_failed === 0) {
    echo "✓ All sample data inserted successfully!<br><br><br>";
} else {
    echo "✗ Some sample data inserts failed. Check errors above.<br><br>";
}

// ==================== CLOSE CONNECTION ====================

$conn->close();
echo "Database connection closed.<br>";
?>
