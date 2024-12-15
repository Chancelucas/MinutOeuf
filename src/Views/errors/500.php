<?php
$title = 'Erreur serveur';
ob_start();
?>

<div class="error-container">
    <h1>Erreur serveur</h1>
    <p>Une erreur inattendue s'est produite. Veuillez réessayer plus tard.</p>
    <a href="/" class="btn btn-primary">Retour à l'accueil</a>
</div>

<?php
$content = ob_get_clean();
require dirname(__DIR__) . '/layout.php';
