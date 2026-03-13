<?php include __DIR__ . '/includes/header.php'; ?>

<div class="app-shell">
    <?php include __DIR__ . '/includes/sidebar.php'; ?>

    <section class="main-panel">
        <div class="detail-topbar">
            <a href="search.php" class="back-link">← Back to recipes</a>

            <div class="detail-actions">
                <button class="icon-button" type="button" aria-label="Save recipe">♡</button>
                <button class="icon-button" type="button" aria-label="More options">⋮</button>
            </div>
        </div>

        <section class="recipe-hero">
            <div class="recipe-hero-image"></div>

            <div class="recipe-hero-content">
                <h1>Spaghetti Bolognese</h1>

                <p class="recipe-meta-line">Main · Meat · 15 min prep · 20 min cook · 4.5 stars</p>

                <p class="recipe-description">
                    A hearty and comforting pasta dish with rich tomato sauce, minced beef, and classic Italian-inspired flavour.
                    This recipe is ideal for a family dinner and can be prepared using simple ingredients.
                </p>

                <p class="recipe-description">
                    You can serve it with grated cheese and a side salad. It also works well for meal prep and can be stored for later.
                </p>

                <div class="recipe-button-row">
                    <button class="primary-button" type="button">Save to favourites</button>
                    <button class="secondary-button" type="button">Start cooking</button>
                </div>
            </div>
        </section>

        <section class="recipe-content-grid">
            <div class="recipe-panel">
                <h2>Ingredients</h2>
                <ul class="ingredients-list">
                    <li>200g spaghetti</li>
                    <li>300g minced beef</li>
                    <li>1 onion, chopped</li>
                    <li>2 garlic cloves</li>
                    <li>400g chopped tomatoes</li>
                    <li>2 tbsp tomato purée</li>
                    <li>1 tbsp olive oil</li>
                    <li>Salt and pepper to taste</li>
                </ul>
            </div>

            <div class="recipe-panel">
                <h2>Method</h2>
                <ol class="method-list">
                    <li>Heat the oil in a pan and cook the onion and garlic until softened.</li>
                    <li>Add the minced beef and cook until browned.</li>
                    <li>Stir in the chopped tomatoes and tomato purée, then simmer.</li>
                    <li>Cook the spaghetti according to the packet instructions.</li>
                    <li>Serve the sauce over the spaghetti and season to taste.</li>
                </ol>
            </div>
        </section>

        <section class="homepage-section">
            <div class="section-heading">
                <h2>Similar Recipes</h2>
                <a href="search.php">View all →</a>
            </div>

            <div class="related-list">
                <article class="related-card">
                    <div class="related-image"></div>
                    <div class="related-content">
                        <h3><a href="recipe.php?id=3">Healthy Pizza</a></h3>
                        <p>Vegetarian · 30 mins · 4.3 stars</p>
                    </div>
                </article>

                <article class="related-card">
                    <div class="related-image"></div>
                    <div class="related-content">
                        <h3><a href="recipe.php?id=4">Easy Lamb Biryani</a></h3>
                        <p>Meat · 45 mins · 4.6 stars</p>
                    </div>
                </article>
            </div>
        </section>
    </section>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>