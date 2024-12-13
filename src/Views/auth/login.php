<div class="auth-container">
    <div class="login-form">
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
                <button type="submit" class="auth-btn auth-btn-primary">Se connecter</button>
                <button type="button" class="auth-btn auth-btn-secondary" onclick="closeLoginModal()">Annuler</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('loginForm').addEventListener('submit', async (e) => {
        e.preventDefault();

        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;
        const errorDiv = document.getElementById('loginError');

        try {
            const response = await fetch('/auth/login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    email,
                    password
                })
            });

            const data = await response.json();

            if (data.success) {
                window.location.href = data.redirect;
            } else {
                errorDiv.textContent = data.error;
                errorDiv.style.display = 'block';
            }
        } catch (error) {
            errorDiv.textContent = 'Erreur de connexion';
            errorDiv.style.display = 'block';
        }
    });