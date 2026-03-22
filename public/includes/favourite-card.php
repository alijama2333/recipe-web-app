<article class="favourite-card-component">
    <div
        class="favourite-card-component-image"
        <?php if (!empty($recipeImage)): ?>
            style="background-image: url('<?= htmlspecialchars($recipeImage) ?>'); background-size: cover; background-position: center;"
        <?php endif; ?>
    ></div>

    <div class="favourite-card-component-content">
        <h3>
            <a href="recipe.php?id=<?= urlencode($recipeId) ?>">
                <?= htmlspecialchars($recipeTitle) ?>
            </a>
        </h3>

        <?php if (!empty($recipeMeta)): ?>
            <p><?= htmlspecialchars($recipeMeta) ?></p>
        <?php endif; ?>

        <div class="favourite-card-component-actions">
            <form method="POST" action="remove_favourite.php" style="display:inline;">
                <input type="hidden" name="recipe_id" value="<?= (int)$recipeId ?>">
                <button
                    type="submit"
                    class="secondary-button small-button"
                    onclick="return confirm('Remove this recipe from favourites?')"
                >
                    Remove
                </button>
            </form>

            <a href="recipe.php?id=<?= urlencode($recipeId) ?>" class="text-link">View recipe</a>
        </div>
    </div>
</article>