<article class="recipe-card-component <?= htmlspecialchars($cardClass ?? '') ?>">
    <div
        class="recipe-card-component-image"
        <?php if (!empty($recipeImage)): ?>
            style="background-image: url('<?= htmlspecialchars($recipeImage) ?>'); background-size: cover; background-position: center;"
        <?php endif; ?>
    ></div>

    <div class="recipe-card-component-content">
        <h3>
            <a href="recipe.php?id=<?= urlencode($recipeId) ?>">
                <?= htmlspecialchars($recipeTitle) ?>
            </a>
        </h3>

        <?php if (!empty($recipeMeta)): ?>
            <p><?= htmlspecialchars($recipeMeta) ?></p>
        <?php endif; ?>
    </div>
</article>