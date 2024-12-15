<?php
$title = 'Ajouter un œuf';
ob_start();
?>

<div class="container">
    <h1>Ajouter un œuf</h1>

    <form action="/admin/create" method="POST" class="egg-form">
        <div class="form-group">
            <label for="type">Type d'œuf</label>
            <input type="text" id="type" name="type" required>
        </div>

        <div class="form-group">
            <label for="cookingTime">Temps de cuisson (minutes)</label>
            <input type="number" id="cookingTime" name="cookingTime" min="1" required>
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description" required></textarea>
        </div>

        <div class="form-group">
            <label for="instructions">Instructions (une par ligne)</label>
            <textarea id="instructions" name="instructions" rows="5" required></textarea>
        </div>

        <div class="form-actions">
            <a href="/admin" class="btn btn-secondary">Annuler</a>
            <button type="submit" class="btn btn-primary">Ajouter</button>
        </div>
    </form>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layout.php';
