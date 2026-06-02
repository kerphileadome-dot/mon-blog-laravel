#!/bin/bash

echo "🚀 Initialisation du blog..."

# Créer la base de données SQLite si elle n'existe pas
if [ ! -f database/database.sqlite ]; then
    echo "📦 Création de la base de données..."
    touch database/database.sqlite
fi

# Exécuter les migrations
echo "🔄 Exécution des migrations..."
php artisan migrate --force

# Créer le compte admin s'il n'existe pas
echo "👤 Vérification du compte admin..."
php artisan db:seed --class=AdminUserSeeder --force

# Créer le lien storage
echo "🔗 Création du lien storage..."
php artisan storage:link

echo "✅ Initialisation terminée !"
