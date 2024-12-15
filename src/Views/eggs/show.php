<?php
$title = htmlspecialchars($egg['type']);
ob_start();
?>

<div class="container">
    <div class="egg-detail">
        <a href="/" class="back-link">&larr; Retour aux types d'œufs</a>
        
        <h1><?= htmlspecialchars($egg['type']) ?></h1>
        
        <div class="timer-section">
            <div class="timer-display">
                <span class="minutes">00</span>:<span class="seconds">00</span>
            </div>
            
            <div class="timer-controls">
                <button class="start-timer btn btn-primary" data-time="<?= htmlspecialchars($egg['cookingTime']) ?>">
                    Démarrer
                </button>
                <button class="stop-timer btn btn-secondary" disabled>Stop</button>
                <button class="reset-timer btn btn-secondary" disabled>Réinitialiser</button>
            </div>
            
            <!-- Audio element as fallback -->
            <audio id="alarm-sound" preload="auto">
                <source src="/assets/sounds/alarms.mp3" type="audio/mpeg">
                Votre navigateur ne supporte pas l'élément audio.
            </audio>
        </div>

        <div class="egg-instructions">
            <div class="egg-description">
                <h2>Description</h2>
                <p><?= htmlspecialchars($egg['description']) ?></p>
            </div>

            <div class="egg-cooking-info">
                <h2>Informations de cuisson</h2>
                <ul>
                    <li>Temps de cuisson : <strong><?= htmlspecialchars($egg['cookingTime']) ?> minutes</strong></li>
                    <li>Température de l'eau : <strong>bouillante</strong></li>
                </ul>
            </div>

            <div class="egg-steps">
                <h2>Instructions étape par étape</h2>
                <ol>
                    <?php foreach ($egg['instructions'] as $instruction): ?>
                        <li><?= htmlspecialchars($instruction) ?></li>
                    <?php endforeach; ?>
                </ol>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require dirname(__DIR__) . '/layout.php';
?>
