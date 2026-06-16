# 🚀 GUIDE DE DÉPLOIEMENT - Blog Laravel sur Railway

## 📋 TABLE DES MATIÈRES
1. [Prérequis](#prérequis)
2. [Configuration GitHub](#configuration-github)
3. [Configuration Railway](#configuration-railway)
4. [Variables d'environnement](#variables-denvironnement)
5. [Déploiement initial](#déploiement-initial)
6. [Déploiements suivants](#déploiements-suivants)
7. [Résolution des problèmes](#résolution-des-problèmes)

---

## 🔧 PRÉREQUIS

### Outils nécessaires
- ✅ Git installé localement
- ✅ Compte GitHub
- ✅ Compte Railway (https://railway.app)
- ✅ Node.js et npm (pour compiler les assets)
- ✅ PHP 8.2+ et Composer (pour développement local)

### Fichiers de configuration présents
- ✅ `nixpacks.toml` - Configuration build Railway
- ✅ `railway-init.sh` - Script d'initialisation
- ✅ `Procfile` - Commande de démarrage (optionnel)
- ✅ `.gitignore` modifié - Assets compilés inclus

---

## 🐙 CONFIGURATION GITHUB

### 1. Créer un repository sur GitHub

1. Aller sur https://github.com
2. Cliquer sur "New repository"
3. Nom du repository : `mon-blog-laravel`
4. Visibilité : Public ou Private
5. **NE PAS** initialiser avec README/gitignore/licence
6. Cliquer sur "Create repository"

### 2. Pousser le projet vers GitHub

```bash
# Initialiser Git (si pas déjà fait)
git init

# Ajouter tous les fichiers
git add .

# Premier commit
git commit -m "Initial commit - Blog Laravel 13"

# Ajouter le remote GitHub
git remote add origin https://github.com/VOTRE-USERNAME/mon-blog-laravel.git

# Renommer la branche en main
git branch -M main

# Pousser vers GitHub
git push -u origin main
```

### 3. Vérifier sur GitHub

- Aller sur votre repository GitHub
- Vérifier que tous les fichiers sont présents
- Vérifier que `public/build/` est bien versionné

---

## 🚄 CONFIGURATION RAILWAY

### 1. Créer un compte Railway

1. Aller sur https://railway.app
2. Cliquer sur "Login"
3. Se connecter avec GitHub (recommandé)
4. Autoriser Railway à accéder à vos repositories

### 2. Créer un nouveau projet

1. Cliquer sur "New Project"
2. Choisir "Deploy from GitHub repo"
3. Sélectionner le repository `mon-blog-laravel`
4. Cliquer sur "Deploy Now"

### 3. Configuration automatique

Railway va automatiquement :
- ✅ Détecter que c'est un projet PHP/Laravel
- ✅ Utiliser Nixpacks pour le build
- ✅ Lire le fichier `nixpacks.toml`
- ✅ Installer les dépendances
- ✅ Exécuter le build

---

## 🔐 VARIABLES D'ENVIRONNEMENT

### Variables à configurer sur Railway

1. Aller dans l'onglet "Variables" du projet Railway
2. Ajouter ces variables :

```
APP_NAME=KerpheX Blog
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:VOTRE_CLE_GENEREE
APP_URL=https://VOTRE-DOMAINE.up.railway.app

DB_CONNECTION=sqlite

LOG_CHANNEL=stack
LOG_LEVEL=error

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database

# Production Railway : Brevo (SMTP Gmail bloqué sur plan Hobby)
MAIL_MAILER=brevo
BREVO_API_KEY=xkeysib-VOTRE_CLE
MAIL_FROM_ADDRESS=kerphilesaint@gmail.com
MAIL_FROM_NAME=KerpheX Blog
```

> **Emails :** en local (Laragon), utilisez Gmail SMTP (`MAIL_MAILER=smtp` dans `.env`). Sur Railway, utilisez **Brevo** — voir `env.railway.template` et `scripts/setup-brevo-railway.ps1`.
>
> **Local vs prod :** Laragon utilise **MySQL** (`.env` local). Railway utilise **SQLite** par défaut — ne définissez pas `DB_HOST` / `DB_DATABASE` MySQL sur Railway sauf migration MySQL (voir [docs/MYSQL_RAILWAY.md](docs/MYSQL_RAILWAY.md)).

### Générer APP_KEY

**Sur votre machine locale :**
```bash
php artisan key:generate --show
```

Copier la clé générée (format: `base64:xxxxx`) et la coller dans Railway.

### Obtenir votre URL Railway

1. Aller dans l'onglet "Settings" du projet
2. Section "Domains"
3. Cliquer sur "Generate Domain"
4. Copier l'URL générée (ex: `web-production-c5c2f.up.railway.app`)
5. L'utiliser pour `APP_URL`

---

## 🎬 DÉPLOIEMENT INITIAL

### 1. Première compilation des assets

**Sur votre machine locale** (IMPORTANT) :

```bash
# Installer les dépendances Node
npm install

# Compiler les assets pour production
npm run build
```

Cela va créer le dossier `public/build/` avec les fichiers CSS et JS compilés.

### 2. Vérifier .gitignore

Assurez-vous que `public/build` **N'EST PAS** dans `.gitignore`.

Si présent, supprimez cette ligne :
```
/public/build
```

### 3. Pousser les assets compilés

```bash
# Ajouter les assets
git add public/build/

# Commit
git commit -m "Ajout assets compilés pour production"

# Push vers GitHub
git push origin main
```

### 4. Railway déploie automatiquement

Railway va :
1. Détecter le push GitHub
2. Lancer un nouveau build
3. Exécuter `nixpacks.toml` :
   - Installer PHP 8.2
   - Installer Composer
   - Installer dépendances PHP
   - Cacher config/routes/views
4. Exécuter `railway-init.sh` :
   - Créer base de données SQLite
   - Vider les caches
   - Exécuter migrations
   - Créer compte admin
   - Créer lien storage
5. Démarrer le serveur PHP

### 5. Vérifier le déploiement

1. Aller dans l'onglet "Deployments" sur Railway
2. Attendre que le statut passe à "Success" (1-3 minutes)
3. Cliquer sur l'URL du site
4. Vérifier que le design se charge correctement

### 6. Créer le compte admin (première fois)

**Option 1 : Via route temporaire**
```
https://VOTRE-SITE.up.railway.app/create-admin-account
```

**Option 2 : Via Railway CLI** (avancé)
```bash
railway run php artisan tinker
>>> \App\Models\User::create([
...   'name' => 'Admin',
...   'email' => 'admin@blog.com',
...   'password' => bcrypt('votre-mot-de-passe'),
...   'role' => 'admin',
...   'email_verified_at' => now(),
... ]);
```

### 7. Tester la connexion

1. Aller sur https://VOTRE-SITE.up.railway.app/login
2. Se connecter avec les identifiants admin
3. Vérifier que le menu "Dashboard" et "Nouvel article" apparaissent
4. Créer un article de test

---

## 🔄 DÉPLOIEMENTS SUIVANTS

### Workflow de mise à jour

Chaque fois que vous modifiez le code :

```bash
# 1. Si vous modifiez les styles ou JS, recompiler
npm run build

# 2. Ajouter les modifications
git add .

# 3. Commit avec message descriptif
git commit -m "Fix: Correction du bug X"

# 4. Push vers GitHub
git push origin main
```

Railway redéploie **automatiquement** à chaque push !

### Suivre le déploiement

1. Aller sur Railway Dashboard
2. Onglet "Deployments"
3. Voir le déploiement en cours (statut "Building")
4. Voir les logs en temps réel
5. Attendre le statut "Success"

### Temps de déploiement moyen

- ⏱️ Build : 1-2 minutes
- ⏱️ Démarrage : 30 secondes
- ⏱️ **Total : 2-3 minutes**

---

## 🔍 RÉSOLUTION DES PROBLÈMES

### Problème 1 : Design ne se charge pas (404 sur CSS/JS)

**Symptômes :**
- Page blanche
- Erreurs 404 dans la console : `app-xxx.css not found`

**Solutions :**

1. **Vérifier que les assets sont compilés :**
```bash
# Local
npm run build
git add public/build/
git commit -m "Ajout assets compilés"
git push origin main
```

2. **Vérifier .gitignore :**
```bash
# public/build NE DOIT PAS être dans .gitignore
# Si présent, le supprimer et recommiter
```

3. **Vérifier HTTPS forcé :**
Dans `app/Providers/AppServiceProvider.php` :
```php
if ($this->app->environment('production')) {
    \Illuminate\Support\Facades\URL::forceScheme('https');
}
```

### Problème 2 : Erreur 500 au démarrage

**Symptômes :**
- Page "Server Error 500"
- Site inaccessible

**Solutions :**

1. **Vérifier APP_KEY dans Railway :**
```bash
# Générer une nouvelle clé
php artisan key:generate --show

# Copier dans Railway Variables
APP_KEY=base64:xxxxx
```

2. **Vérifier les logs Railway :**
- Onglet "Deployments"
- Cliquer sur le déploiement échoué
- Lire les logs d'erreur

3. **Vider les caches :**
Le script `railway-init.sh` le fait automatiquement, mais si besoin :
```bash
# Sur Railway (via CLI ou logs)
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
```

### Problème 3 : Base de données vide après redéploiement

**Cause :** Railway peut recréer le conteneur, perdant la base SQLite.

**Solutions :**

**Option 1 : Utiliser Railway Volume (recommandé)**
1. Aller dans Settings > Volumes
2. Créer un volume monté sur `/app/database`
3. Redéployer

**Option 2 : Passer à PostgreSQL**
1. Ajouter service PostgreSQL dans Railway
2. Modifier `.env` pour utiliser PostgreSQL
3. Les données seront persistantes

**Option 3 : Backup manuel**
```bash
# Télécharger database.sqlite depuis Railway
# Le sauvegarder localement
# Le restaurer après redéploiement
```

### Problème 4 : Connexion admin échoue

**Symptômes :**
- "Ces identifiants ne correspondent pas"
- Mot de passe correct mais refusé

**Solutions :**

1. **Vérifier que le compte existe :**
```bash
# Via Railway CLI ou route temporaire
# Recréer le compte admin
```

2. **Utiliser la route temporaire :**
```
https://VOTRE-SITE.up.railway.app/create-admin-account
```

3. **Vérifier le seeder :**
Dans `railway-init.sh`, vérifier que cette ligne est présente :
```bash
php artisan db:seed --class=AdminUserSeeder --force
```

### Problème 5 : Modifications non visibles après push

**Cause :** Caches Laravel persistants

**Solution :**

Dans `railway-init.sh`, ajouter au début :
```bash
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
```

Puis redéployer :
```bash
git add railway-init.sh
git commit -m "Fix: Ajout clear cache au démarrage"
git push origin main
```

### Problème 6 : Build échoue sur Railway

**Symptômes :**
- Statut "Failed" dans Deployments
- Erreurs dans les logs

**Solutions :**

1. **Vérifier nixpacks.toml :**
```toml
[phases.setup]
nixPkgs = ['php82', 'php82Packages.composer']

[phases.install]
cmds = ['composer install --no-dev --optimize-autoloader']

[phases.build]
cmds = [
    'php artisan config:cache',
    'php artisan route:cache',
    'php artisan view:cache'
]

[start]
cmd = 'bash railway-init.sh && php -S 0.0.0.0:$PORT -t public'
```

2. **Vérifier composer.json :**
Assurez-vous que toutes les dépendances sont présentes.

3. **Vérifier railway-init.sh :**
```bash
#!/bin/bash
# Doit commencer par le shebang
# Doit être exécutable
```

### Problème 7 : "Permission denied" sur railway-init.sh

**Solution :**

Rendre le script exécutable localement avant de commit :
```bash
chmod +x railway-init.sh
git add railway-init.sh
git commit -m "Fix: Rendre railway-init.sh exécutable"
git push origin main
```

### Problème 8 : APP_DEBUG=true expose les erreurs

**Important pour production :**

Sur Railway, définir :
```
APP_DEBUG=false
```

Cela cache les détails techniques aux visiteurs.

---

## 📊 MONITORING

### Voir les logs en temps réel

1. Railway Dashboard
2. Projet > Deployments
3. Cliquer sur le déploiement actif
4. Onglet "View Logs"

### Métriques disponibles

- CPU usage
- Memory usage
- Network traffic
- Request count

### Alertes

Railway peut envoyer des alertes par email si :
- Le déploiement échoue
- L'application crash
- Les ressources sont épuisées

---

## 💰 COÛTS RAILWAY

### Plan Hobby (Gratuit)
- $5 de crédits gratuits par mois
- Suffisant pour un blog personnel
- Pas besoin de carte de crédit initialement

### Utilisation typique
- ~$0.50 - $2/mois pour un blog personnel
- Dépend du trafic et du temps d'activité

### Optimisation
- Le site "sleep" après 5 minutes d'inactivité (gratuit)
- Premier visiteur reveille le site (~10 secondes)

---

## ✅ CHECKLIST DÉPLOIEMENT

### Avant le premier déploiement

- [ ] Assets compilés localement (`npm run build`)
- [ ] `public/build/` inclus dans Git (pas dans `.gitignore`)
- [ ] Repository GitHub créé et poussé
- [ ] Compte Railway créé
- [ ] Fichiers `nixpacks.toml` et `railway-init.sh` présents

### Configuration Railway

- [ ] Projet créé depuis GitHub
- [ ] Variables d'environnement configurées
- [ ] `APP_KEY` généré et ajouté
- [ ] `APP_DEBUG=false` en production
- [ ] URL Railway copié dans `APP_URL`

### Après le déploiement

- [ ] Site accessible (vérifier l'URL)
- [ ] Design se charge correctement
- [ ] Compte admin créé
- [ ] Connexion admin fonctionne
- [ ] Dashboard accessible
- [ ] Création d'article fonctionne
- [ ] Édition/suppression fonctionnent

### Pour chaque mise à jour

- [ ] Modifications testées localement
- [ ] Assets recompilés si nécessaire (`npm run build`)
- [ ] Commit avec message descriptif
- [ ] Push vers GitHub
- [ ] Attendre le redéploiement Railway (2-3 min)
- [ ] Tester sur le site de production

---

## 🔗 LIENS UTILES

- **Railway Dashboard** : https://railway.app/dashboard
- **Documentation Railway** : https://docs.railway.app
- **Documentation Laravel** : https://laravel.com/docs
- **Documentation Nixpacks** : https://nixpacks.com/docs

---

## 🆘 SUPPORT

### Si vous êtes bloqué

1. **Vérifier les logs Railway** (le plus important !)
2. **Lire cette documentation complètement**
3. **Vérifier que tous les fichiers sont bien commités**
4. **Tester en local d'abord** (`php artisan serve`)

### Commandes de diagnostic

```bash
# Vérifier l'état Git
git status

# Vérifier les derniers commits
git log --oneline -5

# Vérifier que les assets existent
ls -la public/build/

# Tester en local
php artisan serve
# Puis ouvrir http://127.0.0.1:8000
```

---

**Bon déploiement ! 🚀**

Si tout est configuré correctement, le déploiement devrait être automatique et sans problème à chaque push !
