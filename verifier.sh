#!/bin/bash

# Script de vérification du blog personnel
# À exécuter dans Git Bash ou terminal Linux

echo "🔍 Vérification du blog personnel Laravel"
echo "=========================================="
echo ""

# Couleurs
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Compteurs
PASSED=0
FAILED=0

# Fonction de test
test_file() {
    if [ -f "$1" ]; then
        echo -e "${GREEN}✓${NC} $2"
        ((PASSED++))
    else
        echo -e "${RED}✗${NC} $2"
        ((FAILED++))
    fi
}

test_dir() {
    if [ -d "$1" ]; then
        echo -e "${GREEN}✓${NC} $2"
        ((PASSED++))
    else
        echo -e "${RED}✗${NC} $2"
        ((FAILED++))
    fi
}

echo "📁 Vérification des fichiers..."
echo ""

# Migrations
test_file "database/migrations/2026_06_01_172951_add_role_to_users_table.php" "Migration role users"

# Seeders
test_file "database/seeders/AdminUserSeeder.php" "Seeder admin"

# Contrôleurs
test_file "app/Http/Controllers/Admin/DashboardController.php" "DashboardController"

# Middleware
test_file "app/Http/Middleware/AdminMiddleware.php" "AdminMiddleware"

# Policies
test_file "app/Policies/PostPolicy.php" "PostPolicy"

# Vues admin
test_dir "resources/views/admin" "Dossier vues admin"
test_file "resources/views/admin/dashboard.blade.php" "Vue dashboard"
test_file "resources/views/admin/posts.blade.php" "Vue gestion posts"
test_file "resources/views/admin/comments.blade.php" "Vue gestion commentaires"

# Documentation
test_file "README.md" "README"
test_file "INSTRUCTIONS_INSTALLATION.md" "Instructions installation"
test_file "GUIDE_PRESENTATION.md" "Guide présentation"
test_file "AMELIORATIONS_FUTURES.md" "Améliorations futures"
test_file "RESUME_MODIFICATIONS.md" "Résumé modifications"

echo ""
echo "=========================================="
echo -e "Résultat: ${GREEN}${PASSED} réussis${NC}, ${RED}${FAILED} échoués${NC}"
echo ""

if [ $FAILED -eq 0 ]; then
    echo -e "${GREEN}✓ Tous les fichiers sont présents !${NC}"
    echo ""
    echo "📋 Prochaines étapes:"
    echo "1. Exécuter: php artisan migrate"
    echo "2. Exécuter: php artisan db:seed --class=AdminUserSeeder"
    echo "3. Exécuter: php artisan storage:link"
    echo "4. Démarrer Laragon"
    echo "5. Tester la connexion avec admin@blog.com / password"
    echo ""
    echo "📖 Consulte INSTRUCTIONS_INSTALLATION.md pour plus de détails"
else
    echo -e "${RED}✗ Certains fichiers sont manquants${NC}"
    echo "Vérifie que toutes les modifications ont été appliquées"
fi

echo ""
echo "🚀 Bon courage pour mercredi !"
