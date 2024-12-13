#!/bin/sh

# Attendre que MongoDB soit prêt
sleep 10

# Initialiser la base de données
php /var/www/html/scripts/init_eggs.php

# Démarrer Apache
apache2-foreground
