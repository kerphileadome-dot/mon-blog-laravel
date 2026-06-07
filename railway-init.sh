#!/bin/bash
set -e

INIT_FLAG="storage/app/.railway_initialized"

echo "🚀 Démarrage KerpheX..."
echo "ℹ️  Emails (reset mot de passe) : configurez MAIL_MAILER=smtp et les variables MAIL_* sur Railway."

if [ ! -f database/database.sqlite ]; then
    echo "📦 Création de la base SQLite..."
    touch database/database.sqlite
fi

if [ ! -f "$INIT_FLAG" ]; then
    echo "🔧 Première initialisation..."
    php artisan migrate --force
    php artisan db:seed --class=AdminUserSeeder --force
    php artisan db:seed --class=ArticlesSeeder --force
    php artisan storage:link 2>/dev/null || true
    touch "$INIT_FLAG"
else
    echo "⚡ Démarrage rapide (déjà initialisé)"
    php artisan migrate --force
    php artisan db:seed --class=AdminUserSeeder --force
fi

echo "🗄️ Mise en cache Laravel..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "✅ Prêt."
