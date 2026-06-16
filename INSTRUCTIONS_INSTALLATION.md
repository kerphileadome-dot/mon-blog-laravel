# 📦 GUIDE D'INSTALLATION - Blog Laravel 13

## 🎯 OBJECTIF

Ce guide vous permet d'installer et de faire fonctionner le blog Laravel sur votre machine locale pour le développement.

---

## 🔧 PRÉREQUIS

### Logiciels requis

1. **PHP 8.2 ou supérieur**
   - Vérifier : `php --version`
   - Télécharger : https://www.php.net/downloads

2. **Composer** (gestionnaire de dépendances PHP)
   - Vérifier : `composer --version`
   - Télécharger : https://getcomposer.org/download/

3. **Node.js 18+ et npm** (pour compiler les assets)
   - Vérifier : `node --version` et `npm --version`
   - Télécharger : https://nodejs.org/

4. **Git**
   - Vérifier : `git --version`
   - Télécharger : https://git-scm.com/downloads

### Extensions PHP requises

Le projet nécessite ces extensions PHP (généralement déjà incluses) :
- ✅ OpenSSL
- ✅ PDO (SQLite)
- ✅ Mbstring
- ✅ Tokenizer
- ✅ XML
- ✅ Ctype
- ✅ JSON
- ✅ BCMath
- ✅ Fileinfo

---

## 📥 INSTALLATION DEPUIS GITHUB

### 1. Cloner le repository

```bash
# Cloner le projet
git clone https://github.com/kerphileadome-dot/mon-blog-laravel.git

# Entrer dans le dossier
cd mon-blog-laravel
```

### 2. Installer les dépendances PHP

```bash
composer install
```

Cette commande va :
- Télécharger toutes les dépendances Laravel
- Créer le dossier `vendor/`
- Installer les packages requis

⏱️ Temps estimé : 2-3 minutes

### 3. Installer les dépendances Node.js

```bash
npm install
```

Cette commande va :
- Télécharger les dépendances frontend (Tailwind CSS, Vite, etc.)
- Créer le dossier `node_modules/`

⏱️ Temps estimé : 1-2 minutes

### 4. Configurer les variables d'environnement

```bash
# Copier le fichier .env.example vers .env
cp .env.example .env

# Sur Windows (si cp ne fonctionne pas)
copy .env.example .env
```

### 5. Générer la clé d'application

```bash
php artisan key:generate
```

Cette commande génère automatiquement `APP_KEY` dans le fichier `.env`.

### 6. Créer la base de données

```bash
# Créer le fichier SQLite
touch database/database.sqlite

# Sur Windows (si touch ne fonctionne pas)
type nul > database/database.sqlite
```

### 7. Exécuter les migrations

```bash
php artisan migrate
```

Cette commande va créer toutes les tables :
- users (avec rôle admin/visitor)
- posts
- comments
- likes
- sessions
- cache
- jobs

### 8. Créer le compte administrateur

```bash
php artisan db:seed --class=AdminUserSeeder
```

Cette commande crée un compte admin par défaut :
- **Email** : admin@blog.com
- **Mot de passe** : password
- **Rôle** : admin

⚠️ **Important** : Changez ces identifiants en production !

### 9. Créer le lien symbolique pour le storage

```bash
php artisan storage:link
```

Cette commande crée un lien entre `storage/app/public` et `public/storage` pour les uploads d'images.

### 10. Compiler les assets (CSS/JS)

```bash
# Pour le développement (avec hot reload)
npm run dev

# OU pour la production (compilation unique)
npm run build
```

Pour le développement local, utilisez `npm run dev` qui recompile automatiquement à chaque modification.

---

## 🚀 DÉMARRER LE SERVEUR

### Méthode 1 : Artisan Serve (Recommandée pour dev)

```bash
php artisan serve
```

Le site sera accessible sur : **http://127.0.0.1:8000**

### Méthode 2 : Laragon (Si installé)

1. Ouvrir Laragon
2. Démarrer Apache et MySQL
3. Copier le projet dans `C:\laragon\www\`
4. Accéder via : **http://mon-blog.test**

### Méthode 3 : XAMPP/WAMP (Si installé)

1. Copier le projet dans `htdocs/` (XAMPP) ou `www/` (WAMP)
2. Démarrer Apache
3. Accéder via : **http://localhost/mon-blog-laravel/public**

---

## 🔑 PREMIER ACCÈS

### 1. Accéder au site

Ouvrir votre navigateur et aller sur :
- **http://127.0.0.1:8000** (si php artisan serve)
- **http://mon-blog.test** (si Laragon)

### 2. Se connecter en tant qu'admin

1. Cliquer sur "Connexion" en haut à droite
2. Utiliser les identifiants par défaut :
   - **Email** : admin@blog.com
   - **Mot de passe** : password

### 3. Explorer le dashboard

Une fois connecté :
- Accéder au **Dashboard** (menu en haut)
- Créer votre **premier article**
- Gérer les **commentaires**

---

## ✅ VÉRIFICATION DE L'INSTALLATION

### Checklist

Vérifier que tout fonctionne :

- [ ] Site accessible (page d'accueil affiche)
- [ ] Design se charge (CSS/JS appliqués)
- [ ] Connexion admin fonctionne
- [ ] Dashboard accessible
- [ ] Création d'article fonctionne
- [ ] Upload d'image fonctionne
- [ ] Édition d'article fonctionne
- [ ] Suppression d'article fonctionne
- [ ] Commentaires affichés
- [ ] Likes fonctionnent

### Commandes de diagnostic

```bash
# Vérifier la version de PHP
php --version

# Vérifier la version de Laravel
php artisan --version

# Vérifier les routes
php artisan route:list

# Vérifier la connexion DB
php artisan migrate:status

# Vider les caches (si problème)
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

---

## 🔧 CONFIGURATION AVANCÉE

### Changer les identifiants admin

**Option 1 : Via Tinker (Console Laravel)**

```bash
php artisan tinker

>>> $user = \App\Models\User::where('email', 'admin@blog.com')->first();
>>> $user->email = 'votre-email@exemple.com';
>>> $user->password = bcrypt('VotreMotDePasse');
>>> $user->save();
>>> exit
```

**Option 2 : Via un nouveau Seeder**

Modifier `database/seeders/AdminUserSeeder.php` :

```php
\App\Models\User::create([
    'name' => 'Votre Nom',
    'email' => 'votre-email@exemple.com',
    'password' => bcrypt('VotreMotDePasse'),
    'role' => 'admin',
    'email_verified_at' => now(),
]);
```

Puis :
```bash
php artisan migrate:fresh --seed
```

⚠️ Attention : `migrate:fresh` supprime toutes les données !

### Configurer l'email (pour reset password)

Dans `.env`, modifier :

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=votre-email@gmail.com
MAIL_PASSWORD=votre-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=votre-email@gmail.com
MAIL_FROM_NAME="${APP_NAME}"
```

> **Production Railway :** le plan Hobby bloque SMTP Gmail. Utilisez **Brevo** (`MAIL_MAILER=brevo`, `BREVO_API_KEY`) — voir `env.railway.template` et `scripts/setup-brevo-railway.ps1`.

### Changer le nom du blog

Dans `.env` :

```env
APP_NAME="Mon Blog Perso"
```

Puis vider le cache :
```bash
php artisan config:clear
```

---

## 🐛 RÉSOLUTION DES PROBLÈMES

### Problème 1 : "Class not found"

**Solution** :
```bash
composer dump-autoload
php artisan config:clear
```

### Problème 2 : "No application encryption key"

**Solution** :
```bash
php artisan key:generate
```

### Problème 3 : Design ne se charge pas

**Solution** :
```bash
# Recompiler les assets
npm run build

# Vider le cache navigateur (Ctrl+Shift+R)
```

### Problème 4 : "SQLSTATE[HY000]: General error: 1 no such table"

**Solution** :
```bash
# Créer le fichier de base de données
touch database/database.sqlite

# Exécuter les migrations
php artisan migrate
```

### Problème 5 : Erreur 403 sur /posts/create

**Solution** :

Vérifier que vous êtes connecté en tant qu'admin :
```bash
php artisan tinker
>>> \App\Models\User::where('email', 'admin@blog.com')->first()->role
>>> # Doit afficher "admin"
```

### Problème 6 : Images ne s'affichent pas

**Solution** :
```bash
# Recréer le lien symbolique
php artisan storage:link
```

### Problème 7 : Port 8000 déjà utilisé

**Solution** :
```bash
# Utiliser un autre port
php artisan serve --port=8001
```

---

## 🔄 WORKFLOW DE DÉVELOPPEMENT

### 1. Démarrage quotidien

```bash
# Terminal 1 : Serveur Laravel
php artisan serve

# Terminal 2 : Compilation assets (auto-reload)
npm run dev
```

### 2. Faire des modifications

- Modifier les fichiers PHP (routes, controllers, models, views)
- Modifier les fichiers CSS/JS (resources/css, resources/js)
- Les changements sont automatiquement détectés

### 3. Tester les modifications

- Rafraîchir le navigateur (F5)
- Tester les nouvelles fonctionnalités
- Vérifier les erreurs dans la console

### 4. Vider les caches si nécessaire

```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

### 5. Commiter les modifications

```bash
git add .
git commit -m "Description des modifications"
git push origin main
```

---

## 📚 STRUCTURE DU PROJET

```
mon-blog-laravel/
├── app/                    # Code PHP principal
│   ├── Http/
│   │   ├── Controllers/   # Controllers (logique)
│   │   ├── Middleware/    # Middleware (sécurité)
│   │   └── Requests/      # Validation des formulaires
│   ├── Models/            # Models Eloquent (données)
│   ├── Policies/          # Policies (autorisations)
│   └── Providers/         # Service providers
├── bootstrap/             # Fichiers de bootstrap Laravel
├── config/                # Configuration Laravel
├── database/
│   ├── migrations/        # Migrations (structure BDD)
│   └── seeders/           # Seeders (données initiales)
├── public/                # Fichiers publics
│   ├── build/            # Assets compilés (CSS/JS)
│   └── index.php         # Point d'entrée
├── resources/
│   ├── css/              # Fichiers CSS source
│   ├── js/               # Fichiers JS source
│   └── views/            # Templates Blade
├── routes/
│   ├── web.php           # Routes web
│   └── auth.php          # Routes authentification
├── storage/              # Fichiers générés, uploads
├── vendor/               # Dépendances Composer
├── .env                  # Variables d'environnement
├── composer.json         # Dépendances PHP
├── package.json          # Dépendances Node.js
└── artisan               # CLI Laravel
```

---

## 🎓 COMMANDES UTILES

### Artisan (Laravel CLI)

```bash
# Lister toutes les commandes
php artisan list

# Créer un controller
php artisan make:controller NomController

# Créer un model
php artisan make:model NomModel -m

# Créer une migration
php artisan make:migration create_table_name

# Exécuter les migrations
php artisan migrate

# Rollback dernière migration
php artisan migrate:rollback

# Rafraîchir la BDD (⚠️ supprime les données)
php artisan migrate:fresh --seed

# Créer un seeder
php artisan make:seeder NomSeeder

# Créer une policy
php artisan make:policy NomPolicy

# Créer un middleware
php artisan make:middleware NomMiddleware

# Console interactive
php artisan tinker

# Lister les routes
php artisan route:list

# Vider les caches
php artisan optimize:clear
```

### NPM (Frontend)

```bash
# Installer dépendances
npm install

# Dev avec hot reload
npm run dev

# Build production
npm run build

# Vérifier les mises à jour
npm outdated
```

### Git

```bash
# Voir l'état
git status

# Voir les modifications
git diff

# Historique
git log --oneline

# Créer une branche
git checkout -b nom-branche

# Revenir à main
git checkout main
```

---

## 📖 DOCUMENTATION

### Laravel
- **Site officiel** : https://laravel.com
- **Documentation** : https://laravel.com/docs
- **Laracasts** (vidéos) : https://laracasts.com

### Tailwind CSS
- **Documentation** : https://tailwindcss.com/docs

### Blade (Templates)
- **Documentation** : https://laravel.com/docs/blade

### Eloquent (ORM)
- **Documentation** : https://laravel.com/docs/eloquent

---

## ✅ PROCHAINES ÉTAPES

Maintenant que le projet est installé :

1. **Personnaliser le contenu**
   - Créer des articles de test
   - Uploader des images de couverture
   - Tester les commentaires

2. **Modifier le design** (si souhaité)
   - Éditer `resources/css/app.css`
   - Éditer `resources/views/layouts/app.blade.php`
   - Recompiler avec `npm run build`

3. **Ajouter des fonctionnalités**
   - Voir `AMELIORATIONS_FUTURES.md` pour des idées
   - Créer des branches Git pour tester

4. **Déployer en production**
   - Voir `DEPLOIEMENT.md` pour Railway
   - Ou tout autre hébergeur PHP

---

## 🆘 BESOIN D'AIDE ?

### Ressources

1. **Documentation du projet**
   - `README.md` - Vue d'ensemble
   - `AUDIT_COMPLET.md` - Analyse détaillée
   - `DEPLOIEMENT.md` - Guide de déploiement
   - `GUIDE_PRESENTATION.md` - Guide de présentation

2. **Communauté Laravel**
   - Forum : https://laracasts.com/discuss
   - Discord : https://discord.gg/laravel
   - Stack Overflow : tag `laravel`

3. **Documentation officielle**
   - Laravel : https://laravel.com/docs
   - Tailwind CSS : https://tailwindcss.com/docs

---

**Installation terminée ! 🎉**

Vous êtes prêt à développer votre blog Laravel.

Bon développement ! 💻
