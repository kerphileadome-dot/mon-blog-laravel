#!/bin/bash
set -e

INIT_FLAG="storage/app/.railway_initialized"

echo "🚀 Démarrage KerpheX..."

if [ -z "$APP_URL" ] && [ -n "$RAILWAY_PUBLIC_DOMAIN" ]; then
    export APP_URL="https://${RAILWAY_PUBLIC_DOMAIN}"
    echo "ℹ️  APP_URL défini depuis Railway : $APP_URL"
fi

if [ -z "$MAIL_PASSWORD" ]; then
    echo "⚠️  MAIL_PASSWORD manquant — les emails (reset MDP) ne fonctionneront pas."
else
    echo "ℹ️  MAIL_PASSWORD configuré."
fi

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

echo "🗄️ Mise en cache Laravel (après chargement des variables Railway)..."
php artisan config:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "✅ Prêt."
