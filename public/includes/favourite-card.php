<article class="favourite-card-component">
    <div class="favourite-card-component-image"></div>

    <div class="favourite-card-component-content">
        <h3>
            <a href="recipe.php?id=<?= urlencode($recipeId ?? 1) ?>">
                <?= htmlspecialchars($recipeTitle ?? 'Recipe Title') ?>
            </a>
        </h3>

        <?php if (!empty($recipeMeta)): ?>
            <p><?= htmlspecialchars($recipeMeta) ?></p>
        <?php endif; ?>

        <div class="favourite-card-component-actions">
            <button type="button" class="secondary-button small-button">Remove</button>
            <a href="recipe.php?id=<?= urlencode($recipeId ?? 1) ?>" class="text-link">View recipe</a>
        </div>
    </div>
</article>