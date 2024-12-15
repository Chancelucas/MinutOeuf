<?php
$title = 'Modifier un œuf';
ob_start();
?>

<div class="container">
    <div class="admin-header">
        <h1>Modifier un œuf</h1>
    </div>

    <form action="/admin/edit/<?= (string)$egg['_id'] ?>" method="POST" class="egg-form">
        <div class="form-group">
            <label for="type">Type d'œuf</label>
            <input type="text" id="type" name="type" value="<?= htmlspecialchars($egg['type']) ?>" required>
        </div>

        <div class="form-group">
            <label for="cookingTime">Temps de cuisson (minutes)</label>
            <input type="number" id="cookingTime" name="cookingTime" min="1" value="<?= htmlspecialchars($egg['cookingTime']) ?>" required>
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description" required><?= htmlspecialchars($egg['description']) ?></textarea>
        </div>

        <div class="form-group">
            <label for="instructions">Instructions (une par ligne)</label>
            <textarea id="instructions" name="instructions" rows="5" required><?= htmlspecialchars(implode("\n", $egg['instructions'])) ?></textarea>
        </div>

        <div class="form-actions">
            <a href="/admin" class="btn btn-secondary">Annuler</a>
            <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
        </div>
    </form>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layout.php';
