<?php
$title = $egg['type'];
ob_start();
?>

<div class="container">
    <a href="/" class="back-link">&larr; Retour aux types d'œufs</a>
    
    <div class="egg-detail">
        <h1><?= htmlspecialchars($egg['type']) ?></h1>
        <p><?= htmlspecialchars($egg['description']) ?></p>
        
        <div class="timer-section">
            <h2>Minuteur</h2>
            <p>Temps de cuisson recommandé : <?= htmlspecialchars($egg['cookingTime']) ?> minutes</p>
            <div id="timer-display" class="timer-display">00:00</div>
            <div class="timer-controls">
                <button id="start-timer" class="btn btn-primary" onclick="window.timer.setTime(<?= htmlspecialchars($egg['cookingTime']) ?>); window.timer.start();">
                    Démarrer
                </button>
                <button id="stop-timer" class="btn btn-secondary" disabled>
                    Stop
                </button>
                <button id="reset-timer" class="btn btn-secondary">
                    Réinitialiser
                </button>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require dirname(__DIR__) . '/layout.php';
?>
