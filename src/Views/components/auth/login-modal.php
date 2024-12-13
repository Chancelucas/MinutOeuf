<?php
/**
 * Modal de connexion
 */
?>
<div id="loginModal" class="modal">
    <div class="modal-content">
        <h2>Connexion Administration</h2>
        <form id="loginForm">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <div class="form-error" id="loginError"></div>
            
            <div class="modal-actions">
                <button type="submit" class="button primary">Se connecter</button>
                <button type="button" class="button secondary" onclick="closeLoginModal()">Annuler</button>
            </div>
        </form>
    </div>
</div>
