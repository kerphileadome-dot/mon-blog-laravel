# 🚀 GUIDE DE DÉPLOIEMENT RAILWAY

**Date:** 3 juin 2026  
**Statut:** ✅ Code poussé sur GitHub - Déploiement en cours

---

## 📦 CE QUI A ÉTÉ DÉPLOYÉ

### ✅ Commit effectué
```
Finalisation complete du projet - Corrections securite + tests valides (33/33)
```

**Statistiques:**
- 52 fichiers modifiés
- 1,222 insertions
- 946 suppressions

### ✅ Fichiers importants mis à jour
1. ✅ `nixpacks.toml` - Configuration Railway (PHP 8.3)
2. ✅ `railway-init.sh` - Script d'initialisation
3. ✅ `Procfile` - Commande de démarrage
4. ✅ Toutes les migrations (13 migrations)
5. ✅ AdminUserSeeder - Création automatique du compte admin
6. ✅ Tous les contrôleurs corrigés
7. ✅ Tous les modèles optimisés
8. ✅ Toutes les vues mises à jour

---

## 🌐 URL DU PROJET

**URL Railway:** https://web-production-c5c2f.up.railway.app

---

## 🔄 PROCESSUS DE DÉPLOIEMENT RAILWAY

Railway va automatiquement :

1. ✅ **Détecter le push GitHub**
2. 🔄 **Builder l'application**
   - Installer PHP 8.3
   - Installer Composer
   - Installer les dépendances
3. 🔄 **Exécuter le script d'initialisation**
   - Créer la base de données SQLite
   - Exécuter les migrations
   - Créer le compte admin
   - Créer le lien storage
4. 🔄 **Démarrer le serveur**
   - Lancer l'application sur le port assigné

---

## ⏱️ TEMPS ESTIMÉ

**Déploiement complet:** 3-5 minutes

---

## 🔍 VÉRIFIER LE DÉPLOIEMENT

### 1️⃣ Sur le Dashboard Railway

1. Va sur [Railway Dashboard](https://railway.app/dashboard)
2. Sélectionne ton projet "mon-blog" (ou le nom que tu lui as donné)
3. Regarde l'onglet "Deployments"
4. Le dernier déploiement devrait être en cours (🔄 Building)

### 2️⃣ Logs de déploiement

Dans Railway, clique sur "View Logs" pour voir :
```
🚀 Initialisation du blog...
📦 Création de la base de données...
🧹 Vidage des caches...
🔄 Exécution des migrations...
👤 Vérification du compte admin...
🔗 Création du lien storage...
✅ Initialisation terminée !
```

### 3️⃣ Test de l'application

Une fois déployé, teste ces URLs :

**Page d'accueil:**
```
https://web-production-c5c2f.up.railway.app
```

**Login Admin:**
```
https://web-production-c5c2f.up.railway.app/admin/login
```
- Email: `kerphilesaint@gmail.com`
- Mot de passe: `Franklinblog20?`

**Login Visiteur:**
```
https://web-production-c5c2f.up.railway.app/login
```

---

## ⚙️ VARIABLES D'ENVIRONNEMENT RAILWAY

### ✅ Variables déjà configurées (normalement)

Railway devrait avoir ces variables :

```env
APP_NAME=KerpheX
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:... (généré automatiquement)
APP_URL=https://web-production-c5c2f.up.railway.app

DB_CONNECTION=sqlite

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database

MAIL_MAILER=log
```

### ⚠️ Variables Google OAuth (si nécessaire)

Si tu veux activer Google OAuth en production :

```env
GOOGLE_CLIENT_ID=ton-client-id
GOOGLE_CLIENT_SECRET=ton-client-secret
GOOGLE_REDIRECT_URI=https://web-production-c5c2f.up.railway.app/auth/google/callback
```

---

## 🐛 EN CAS DE PROBLÈME

### Erreur 1: "500 Internal Server Error"

**Cause:** APP_KEY manquante ou migrations non exécutées

**Solution:**
1. Va dans Railway Dashboard
2. Variables → Ajoute `APP_KEY` (copie depuis ton .env local)
3. Redéploie

### Erreur 2: "Database not found"

**Cause:** railway-init.sh non exécuté

**Solution:**
1. Dans Railway Dashboard → View Logs
2. Vérifie que tu vois "🚀 Initialisation du blog..."
3. Si non, redéploie manuellement

### Erreur 3: Compte admin introuvable

**Cause:** AdminUserSeeder non exécuté

**Solution:**
1. Dans Railway Dashboard → Shell
2. Exécute: `php artisan db:seed --class=AdminUserSeeder --force`

### Erreur 4: CSS/JS ne chargent pas

**Cause:** APP_URL incorrect ou assets non compilés

**Solution:**
1. Vérifie que `APP_URL` dans Railway = ton URL Railway
2. Dans Railway Shell: `npm run build` (si nécessaire)

---

## 📊 APRÈS LE DÉPLOIEMENT

### ✅ Checklist de validation

1. ⬜ Page d'accueil s'affiche
2. ⬜ Login admin fonctionne
3. ⬜ Dashboard affiche les statistiques
4. ⬜ Création d'article fonctionne
5. ⬜ Upload de média fonctionne
6. ⬜ Inscription visiteur fonctionne
7. ⬜ Google OAuth fonctionne (si configuré)
8. ⬜ Commentaires fonctionnent
9. ⬜ Likes fonctionnent
10. ⬜ Favoris fonctionnent

---

## 🔐 COMPTE ADMINISTRATEUR

### Production (Railway)
- **URL:** https://web-production-c5c2f.up.railway.app/admin/login
- **Email:** kerphilesaint@gmail.com
- **Mot de passe:** Franklinblog20?

### Google OAuth Admin
- **Email:** kerphileadome@gmail.com
- **Méthode:** "Se connecter avec Google"

---

## 📝 COMMANDES UTILES RAILWAY

### Accéder au Shell Railway

Dans le Dashboard Railway, clique sur ton service → Shell

```bash
# Voir les migrations
php artisan migrate:status

# Créer le compte admin
php artisan db:seed --class=AdminUserSeeder --force

# Vider les caches
php artisan optimize:clear

# Voir les routes
php artisan route:list --name=admin

# Voir la base de données
php artisan db:show

# Voir les logs
tail -f storage/logs/laravel.log
```

---

## 🎯 PROCHAINES ÉTAPES

1. ⬜ Attendre que le déploiement se termine (3-5 min)
2. ⬜ Vérifier que l'URL fonctionne
3. ⬜ Tester la connexion admin
4. ⬜ Tester les fonctionnalités principales
5. ⬜ Préparer la présentation jeudi

---

## 📱 SURVEILLANCE

### Logs en temps réel

Dans Railway Dashboard:
- Clique sur ton service
- Onglet "Logs"
- Tu verras tous les logs en temps réel

### Métriques

Dans Railway Dashboard:
- Onglet "Metrics"
- CPU, RAM, Requêtes/sec

---

## ✅ STATUT ACTUEL

**Code:** ✅ Poussé sur GitHub (commit 093e1a1)  
**Railway:** 🔄 Déploiement en cours...  
**URL:** https://web-production-c5c2f.up.railway.app

**Prochaine étape:** Attendre 3-5 minutes et tester l'URL !

---

Date: 3 juin 2026, 18:00
