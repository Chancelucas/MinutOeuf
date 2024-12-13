<?php
/**
 * Modal d'ajout d'œuf (accessible uniquement aux administrateurs)
 */
?>
<?php if (isset($_SESSION['user']) && isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'admin'): ?>
<div id="addEggModal" class="modal">
    <div class="modal-content">
        <h2>Ajouter un œuf</h2>
        <form id="addEggForm">
            <div class="form-group">
                <label for="eggName">Nom de l'œuf</label>
                <input type="text" id="eggName" name="name" required>
            </div>
            
            <div class="form-group">
                <label for="eggTime">Temps de cuisson (en secondes)</label>
                <input type="number" id="eggTime" name="time" required>
            </div>
            
            <div class="form-group">
                <label for="eggInstructions">Instructions</label>
                <textarea id="eggInstructions" name="instructions" required></textarea>
            </div>
            
            <div class="form-group">
                <label for="eggTips">Conseils</label>
                <textarea id="eggTips" name="tips" required></textarea>
            </div>
            
            <div class="modal-actions">
                <button type="submit" class="button primary">Ajouter</button>
                <button type="button" class="button secondary" onclick="closeAddEggModal()">Annuler</button>
            </div>
        </form>
    </div>
</div>
<?php endif; ?>
