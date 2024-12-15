<?php
$title = 'Types d\'œufs';
ob_start();

function slugify($text) {
    // Remplace les caractères accentués par leurs équivalents non accentués
    $text = transliterator_transliterate('Any-Latin; Latin-ASCII; Lower()', $text);
    // Convertit en minuscules
    $text = strtolower($text);
    // Remplace tous les caractères non alphanumériques par un tiret
    $text = preg_replace('/[^a-z0-9]/', '-', $text);
    // Supprime les tirets multiples
    $text = preg_replace('/-+/', '-', $text);
    // Supprime les tirets au début et à la fin
    return trim($text, '-');
}
?>

<div class="container">
    <h1>Types d'œufs</h1>
    
    <div class="egg-grid">
        <?php if (!empty($eggs)): ?>
            <?php foreach ($eggs as $egg): ?>
                <div class="egg-card">
                    <h2><?= htmlspecialchars($egg['type']) ?></h2>
                    <div class="egg-info">
                        <p>Temps de cuisson : <?= htmlspecialchars($egg['cookingTime']) ?> minutes</p>
                        <p><?= htmlspecialchars($egg['description']) ?></p>
                    </div>
                    <a href="/egg/<?= urlencode($egg['type']) ?>" class="btn btn-primary btn-see-more">
                        Voir plus
                    </a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Aucun œuf n'a été trouvé.</p>
        <?php endif; ?>
    </div>
</div>

<?php
$content = ob_get_clean();
require dirname(__DIR__) . '/layout.php';
