<?php
$title = 'Page non trouvée';
ob_start();
?>

<div class="container error-404">
    <h1>404 - Page non trouvée</h1>
    <p>La page que vous recherchez n'existe pas.</p>
    <a href="/" class="button">Retour à l'accueil</a>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/layout.php';
