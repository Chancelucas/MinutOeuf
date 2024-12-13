<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MinutOeuf - <?= $title ?? 'Accueil' ?></title>
    <link rel="stylesheet" href="/assets/css/config.css">
    <link rel="stylesheet" href="/assets/css/navbar.css">
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/css/layout.css">
    <link rel="stylesheet" href="/assets/css/home.css">
    <link rel="stylesheet" href="/assets/css/egg-details.css">
    <link rel="stylesheet" href="/assets/css/notification.css">
    <link rel="stylesheet" href="/assets/css/error.css">
    <?php if (isset($_SESSION['user']) && isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'admin'): ?>
    <link rel="stylesheet" href="/assets/css/admin.css">
    <?php endif; ?>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="app-container">
        <?php require_once __DIR__ . '/../components/navbar.php'; ?>
        <main class="main-content">
            <?= $content ?>
        </main>
    </div>

    <?php 
    // Inclusion des composants modaux
    require_once __DIR__ . '/../components/auth/login-modal.php';
    require_once __DIR__ . '/../components/admin/add-egg-modal.php';
    require_once __DIR__ . '/../components/notification.php';
    ?>

    <!-- Scripts -->
    <script src="/assets/js/app.js"></script>
    <script src="/assets/js/auth.js"></script>
    <?php if (isset($_SESSION['user']) && isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'admin'): ?>
    <script src="/assets/js/admin.js"></script>
    <?php endif; ?>
</body>
</html>
