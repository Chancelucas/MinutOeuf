<div id="eggContent" class="egg-content">
    <header class="egg-header">
        <h2><?= htmlspecialchars($egg['name']) ?></h2>
    </header>

    <section class="instructions">
        <h3>Instructions</h3>
        <ul id="eggInstructions">
            <?php 
            $instructions = is_string($egg['instructions']) ? explode("\n", $egg['instructions']) : $egg['instructions'];
            foreach ($instructions as $instruction): ?>
                <li><?= htmlspecialchars($instruction) ?></li>
            <?php endforeach; ?>
        </ul>
    </section>

    <section class="timer-section">
        <div class="timer">
            <span id="minutes"><?= str_pad($egg['minutes'], 2, '0', STR_PAD_LEFT) ?></span>:<span id="seconds">00</span>
        </div>
        <div class="timer-controls">
            <button id="startTimer" class="button button-primary">Démarrer</button>
            <button id="pauseTimer" class="button button-secondary" disabled>Pause</button>
            <button id="resetTimer" class="button button-secondary" disabled>Réinitialiser</button>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Initialiser les boutons du minuteur
            const startBtn = document.getElementById('startTimer');
            const pauseBtn = document.getElementById('pauseTimer');
            const resetBtn = document.getElementById('resetTimer');

            if (startBtn) startBtn.addEventListener('click', () => publicApp.startTimer());
            if (pauseBtn) pauseBtn.addEventListener('click', () => publicApp.pauseTimer());
            if (resetBtn) resetBtn.addEventListener('click', () => publicApp.resetTimer());
        });
    </script>
</div>
