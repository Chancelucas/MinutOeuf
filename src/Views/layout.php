<?php
// Vérifier si nous sommes sur une page d'administration
$isAdminPage = str_starts_with($_SERVER['REQUEST_URI'] ?? '', '/admin');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MinutOeuf</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body class="fade-in">
    <header>
        <nav>
            <div class="logo hover-lift">
                <a href="/" >
                    <img src="/assets/image/minutoeuf.jpg" alt="Logo">
                </a>
            </div>
            <ul>
                <li><a href="/" class="hover-lift <?= !$isAdminPage ? 'active' : '' ?>">Accueil</a></li>
            </ul>
        </nav>
    </header>

    <main class="container slide-in">
        <?= $content ?>
    </main>

    <footer>
        <p>&copy; <?= date('Y') ?> MinutOeuf. Tous droits réservés.</p>
    </footer>

    <script src="/assets/js/timer.js"></script>
</body>
</html>
