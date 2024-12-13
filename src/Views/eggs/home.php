<div class="home-content">
    <header class="welcome-header">
        <h1>Bienvenue sur MinutOeuf</h1>
        <p>Choisissez votre type d'œuf dans le menu pour commencer</p>
    </header>

    <section class="eggs-grid">
        <?php if (empty($eggs)): ?>
            <p class="no-eggs-message">Aucun œuf n'est disponible pour le moment.</p>
        <?php else: ?>
            <?php foreach ($eggs as $egg): ?>
                <article class="egg-card">
                    <h2><?= htmlspecialchars($egg['name']) ?></h2>
                    <p class="cooking-time">Temps de cuisson : <?= htmlspecialchars($egg['minutes']) ?> minutes</p>
                    <a href="/eggs/<?= $egg['normalizedName'] ?>" class="button button-primary">Voir les instructions</a>
                </article>
            <?php endforeach; ?>
        <?php endif; ?>
    </section>
</div>
