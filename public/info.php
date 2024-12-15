<?php

// Afficher toutes les informations sur PHP
phpinfo();

// Afficher spécifiquement les variables d'environnement
echo "<h2>Environment Variables:</h2>";
echo "<pre>";
print_r($_ENV);
echo "</pre>";

echo "<h2>Server Variables:</h2>";
echo "<pre>";
print_r($_SERVER);
echo "</pre>";

echo "<h2>getenv() Variables:</h2>";
echo "<pre>";
$env_vars = getenv();
print_r($env_vars);
echo "</pre>";
