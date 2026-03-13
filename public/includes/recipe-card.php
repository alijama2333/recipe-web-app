<article class="recipe-card-component <?= htmlspecialchars($cardClass ?? '') ?>">
    <div class="recipe-card-component-image"></div>

    <div class="recipe-card-component-content">
        <h3>
            <a href="recipe.php?id=<?= urlencode($recipeId ?? 1) ?>">
                <?= htmlspecialchars($recipeTitle ?? 'Recipe Title') ?>
            </a>
        </h3>

        <?php if (!empty($recipeMeta)): ?>
            <p><?= htmlspecialchars($recipeMeta) ?></p>
        <?php endif; ?>
    </div>
</article>