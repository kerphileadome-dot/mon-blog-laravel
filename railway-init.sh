#!/bin/bash
set -e

INIT_FLAG="storage/app/.railway_initialized"
DRIVER_FLAG="storage/app/.railway_db_driver"

echo "🚀 Démarrage KerpheX..."

if [ -z "$APP_URL" ] && [ -n "$RAILWAY_PUBLIC_DOMAIN" ]; then
    export APP_URL="https://${RAILWAY_PUBLIC_DOMAIN}"
    echo "ℹ️  APP_URL défini depuis Railway : $APP_URL"
fi

if [ -n "$MAIL_PASSWORD" ]; then
    MAIL_PASSWORD="$(printf '%s' "$MAIL_PASSWORD" | sed 's/^[[:space:]]*//;s/[[:space:]]*$//')"
    export MAIL_PASSWORD
fi

if [ -z "$MAIL_PASSWORD" ]; then
    echo "⚠️  MAIL_PASSWORD manquant — les emails (reset MDP) ne fonctionneront pas."
else
    echo "ℹ️  MAIL_PASSWORD configuré (${#MAIL_PASSWORD} caractères)."
fi

if [ -n "$MAIL_FROM_ADDRESS" ] && [ -n "$MAIL_USERNAME" ] && [ "$MAIL_FROM_ADDRESS" != "$MAIL_USERNAME" ]; then
    echo "⚠️  MAIL_FROM_ADDRESS ($MAIL_FROM_ADDRESS) ≠ MAIL_USERNAME ($MAIL_USERNAME) — Gmail peut refuser l'envoi."
fi

DB_DRIVER="${DB_CONNECTION:-sqlite}"
PREVIOUS_DRIVER=""
if [ -f "$DRIVER_FLAG" ]; then
    PREVIOUS_DRIVER="$(cat "$DRIVER_FLAG")"
fi

if [ -n "$PREVIOUS_DRIVER" ] && [ "$PREVIOUS_DRIVER" != "$DB_DRIVER" ]; then
    echo "🔄 Changement de base : ${PREVIOUS_DRIVER} → ${DB_DRIVER}"
    rm -f "$INIT_FLAG"
fi

if [ "$DB_DRIVER" = "mysql" ]; then
    echo "📦 Base de données : MySQL (${DB_HOST:-?}:${DB_PORT:-3306}/${DB_DATABASE:-?})"

    echo "⏳ Attente MySQL..."
    for i in $(seq 1 30); do
        if php artisan migrate:status >/dev/null 2>&1; then
            echo "✅ MySQL accessible."
            break
        fi
        if [ "$i" -eq 30 ]; then
            echo "❌ MySQL inaccessible après 30 tentatives."
            exit 1
        fi
        sleep 2
    done
else
    echo "📦 Base de données : SQLite (database/database.sqlite)"
    if [ ! -f database/database.sqlite ]; then
        echo "📦 Création de la base SQLite..."
        touch database/database.sqlite
    fi
fi

if [ ! -f "$INIT_FLAG" ]; then
    echo "🔧 Première initialisation (${DB_DRIVER})..."
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

echo "$DB_DRIVER" > "$DRIVER_FLAG"

echo "🗄️ Mise en cache Laravel..."
php artisan config:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

if [ -n "$MAIL_USERNAME" ] && [ -n "$MAIL_PASSWORD" ]; then
    echo "📧 Test SMTP au démarrage..."
    if php artisan blog:test-mail "$MAIL_USERNAME" 2>&1; then
        echo "✅ SMTP OK"
    else
        echo "❌ SMTP échec — vérifiez MAIL_PASSWORD (16 car., sans guillemets)"
    fi
fi

echo "✅ Prêt."
