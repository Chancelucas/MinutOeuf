<?php
/**
 * Composant de la barre latérale
 */

use App\Controllers\EggController;

// Récupération des œufs pour la navbar
$eggController = new EggController();
$eggs = $eggController->getNavbarEggs();
?>
<nav class="sidebar">
    <div class="sidebar-header">
        <a href="/" class="logo-link">
            <img src="/assets/images/logo.jpg" alt="MinutOeuf Logo" class="logo">
            <h1>MinutOeuf</h1>
            <span class="subtitle">Le minuteur parfait pour vos œufs</span>
        </a>
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

    <div class="egg-list">
        <?php if (empty($eggs)): ?>
            <div class="no-eggs">Aucun œuf disponible</div>
        <?php else: ?>
            <?php foreach ($eggs as $egg): ?>
                <a href="/eggs/<?= $egg['normalizedName'] ?>" 
                   class="egg-item <?= isset($currentEgg) && $currentEgg === $egg['normalizedName'] ? 'active' : '' ?>">
                    <?= htmlspecialchars($egg['name']) ?>
                </a>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <div class="sidebar-footer">
        <?php if (isset($_SESSION['user'])): ?>
            <button id="logoutBtn" onclick="window.location.href='/auth/logout'">Déconnexion</button>
        <?php else: ?>
            <button id="loginBtn">Connexion</button>
        <?php endif; ?>
    </div>
</nav>
