#!/bin/sh
set -e

echo "Attente de MongoDB..."
sleep 10

echo "Tentative d'initialisation de la base de données..."
php /var/www/html/scripts/init_eggs.php

echo "Démarrage d'Apache..."
apache2-foreground
