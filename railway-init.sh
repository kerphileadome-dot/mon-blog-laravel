#!/bin/bash

echo "🚀 Initialisation du blog..."

# Créer la base de données SQLite si elle n'existe pas
if [ ! -f database/database.sqlite ]; then
    echo "📦 Création de la base de données..."
    touch database/database.sqlite
fi

# Vider tous les caches
echo "🧹 Vidage des caches..."
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# Exécuter les migrations
echo "🔄 Exécution des migrations..."
php artisan migrate --force

# Créer le compte admin s'il n'existe pas
echo "👤 Vérification du compte admin..."
php artisan db:seed --class=AdminUserSeeder --force

# Créer les articles si la base est vide
echo "📝 Vérification des articles..."
php artisan db:seed --class=ArticlesSeeder --force

# Créer les utilisateurs de test
echo "👥 Vérification des utilisateurs..."
php artisan db:seed --class=UsersSeeder --force

# Créer le lien storage
echo "🔗 Création du lien storage..."
php artisan storage:link

echo "✅ Initialisation terminée avec succès !"
echo "✅ Admin: kerphilesaint@gmail.com / Franklinblog20?"
echo "✅ 4 articles créés"
echo "✅ 3 utilisateurs de test créés"
