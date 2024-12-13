<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MinutOeuf - <?= $title ?? 'Accueil' ?></title>
    <link rel="stylesheet" href="/assets/css/config.css">
    <link rel="stylesheet" href="/assets/css/navbar.css">
    <link rel="stylesheet" href="/assets/css/style.css">
    <?php if (isset($_SESSION['user']) && isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'admin'): ?>
    <link rel="stylesheet" href="/assets/css/admin.css">
    <?php endif; ?>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="app-container">
        <nav class="sidebar">
            <div class="sidebar-header">
                <img src="/assets/images/logo.jpg" alt="MinutOeuf" class="logo">
                <h1>MinutOeuf</h1>
                <p class="subtitle">Le minuteur intelligent</p>
            </div>
            
            <?php if (isset($_SESSION['user']) && isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'admin'): ?>
            <div class="admin-menu">
                <h3>Administration</h3>
                <ul>
                    <li><button class="admin-btn" onclick="showAddEggModal()">Ajouter un œuf</button></li>
                    <li><button class="admin-btn" onclick="window.location.href='/admin'">Gérer les œufs</button></li>
                </ul>
            </div>
            <?php endif; ?>

            <div class="egg-list" id="eggList">
                <!-- La liste des œufs sera ajoutée ici dynamiquement -->
            </div>

            <div class="sidebar-footer">
                <?php if (isset($_SESSION['user'])): ?>
                    <button id="logoutBtn" onclick="window.location.href='/auth/logout'">Déconnexion</button>
                <?php else: ?>
                    <button id="loginBtn">Connexion</button>
                <?php endif; ?>
            </div>
        </nav>

        <main class="main-content">
            <div id="eggContent" class="egg-content hidden">
                <header class="egg-header">
                    <h2 id="eggTitle"></h2>
                </header>
                
                <?= $content ?? '' ?>
            </div>
            
            <div id="welcomeMessage" class="welcome-message">
                <img src="/assets/images/logo-large.png" alt="MinutOeuf" class="welcome-logo">
                <h2>Bienvenue sur MinutOeuf</h2>
                <p>Sélectionnez un type d'œuf dans le menu pour commencer</p>
            </div>
        </main>
    </div>

    <!-- Modal de connexion -->
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

    <!-- Modal d'ajout d'œuf -->
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

    <div class="notification" id="notification">
        <p>Votre œuf est prêt !</p>
        <button id="closeNotification">OK</button>
    </div>

    <script src="/assets/js/app.js"></script>
    <script>
    // Gestion du modal de connexion
    const loginBtn = document.getElementById('loginBtn');
    const loginModal = document.getElementById('loginModal');
    const loginForm = document.getElementById('loginForm');
    const loginError = document.getElementById('loginError');

    if (loginBtn) {
        loginBtn.addEventListener('click', () => {
            loginModal.style.display = 'block';
        });
    }

    function closeLoginModal() {
        loginModal.style.display = 'none';
        loginError.style.display = 'none';
        loginForm.reset();
    }

    window.addEventListener('click', (e) => {
        if (e.target === loginModal) {
            closeLoginModal();
        }
    });

    loginForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const formData = new FormData(loginForm);
        
        try {
            const response = await fetch('/auth/login', {
                method: 'POST',
                body: formData
            });
            
            const data = await response.json();
            
            if (data.success) {
                window.location.href = data.redirect;
            } else {
                loginError.textContent = data.error || 'Erreur de connexion';
                loginError.style.display = 'block';
            }
        } catch (error) {
            loginError.textContent = 'Erreur de connexion';
            loginError.style.display = 'block';
        }
    });

    // Gestion du modal d'ajout d'œuf
    <?php if (isset($_SESSION['user']) && isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'admin'): ?>
    const addEggModal = document.getElementById('addEggModal');
    const addEggForm = document.getElementById('addEggForm');

    function showAddEggModal() {
        addEggModal.style.display = 'block';
    }

    function closeAddEggModal() {
        addEggModal.style.display = 'none';
        addEggForm.reset();
    }

    window.addEventListener('click', (e) => {
        if (e.target === addEggModal) {
            closeAddEggModal();
        }
    });

    addEggForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const formData = new FormData(addEggForm);
        const eggData = Object.fromEntries(formData.entries());
        
        try {
            const response = await fetch('/admin/eggs', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(eggData)
            });
            
            const data = await response.json();
            
            if (data.success) {
                closeAddEggModal();
                // Recharger la liste des œufs
                location.reload();
            } else {
                alert(data.error || 'Erreur lors de l\'ajout de l\'œuf');
            }
        } catch (error) {
            alert('Erreur lors de l\'ajout de l\'œuf');
        }
    });
    <?php endif; ?>
    </script>
</body>
</html>
