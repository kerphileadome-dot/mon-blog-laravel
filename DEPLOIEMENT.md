# 🚀 Guide de déploiement - Railway

## 📋 Prérequis

1. Compte GitHub (gratuit)
2. Compte Railway (gratuit) : https://railway.app

---

## 🎯 Étapes de déploiement

### **Étape 1 : Pousser le code sur GitHub**

1. **Initialiser Git** (si pas déjà fait)
   ```bash
   git init
   git add .
   git commit -m "Initial commit - Blog personnel Laravel"
   ```

2. **Créer un dépôt sur GitHub**
   - Va sur https://github.com/new
   - Nomme-le : `mon-blog-laravel`
   - **Ne pas** cocher "Add README" ni ".gitignore"
   - Clique sur "Create repository"

3. **Pousser le code**
   ```bash
   git branch -M main
   git remote add origin https://github.com/TON-USERNAME/mon-blog-laravel.git
   git push -u origin main
   ```

---

### **Étape 2 : Déployer sur Railway**

1. **Créer un compte Railway**
   - Va sur https://railway.app
   - Clique sur "Login" → "Login with GitHub"
   - Autorise Railway à accéder à tes repos

2. **Créer un nouveau projet**
   - Clique sur "New Project"
   - Sélectionne "Deploy from GitHub repo"
   - Choisis `mon-blog-laravel`
   - Clique sur "Deploy Now"

3. **Configurer les variables d'environnement**
   - Clique sur ton projet
   - Va dans l'onglet "Variables"
   - Ajoute ces variables :

   ```
   APP_NAME=KerpheX Blog
   APP_ENV=production
   APP_DEBUG=false
   APP_KEY=base64:XXXXXXX
   DB_CONNECTION=sqlite
   ```

4. **Générer APP_KEY**
   Dans ton terminal local :
   ```bash
   php artisan key:generate --show
   ```
   Copie la clé et ajoute-la dans Railway comme `APP_KEY`

5. **Attendre le déploiement**
   - Railway va compiler et déployer automatiquement
   - Ça prendra 3-5 minutes
   - Tu verras "Success" quand c'est prêt

6. **Obtenir l'URL**
   - Clique sur "Settings"
   - Clique sur "Generate Domain"
   - Ton site sera accessible sur : `https://ton-projet.up.railway.app`

---

## ✅ Vérification

1. **Ouvre l'URL de ton site**
2. **Teste la page d'accueil**
3. **Connecte-toi avec** :
   - Email : `admin@blog.com`
   - Mot de passe : `password`
4. **Vérifie le dashboard**

---

## 🔧 En cas de problème

### Erreur "APP_KEY not set"
- Génère une clé : `php artisan key:generate --show`
- Ajoute-la dans les variables Railway

### Erreur "Database not found"
- Vérifie que `DB_CONNECTION=sqlite` est bien dans les variables
- Redéploie le projet

### Le site ne charge pas
- Va dans "Deployments" sur Railway
- Clique sur le dernier déploiement
- Regarde les logs pour voir l'erreur

---

## 🎉 Félicitations !

Ton blog est maintenant en ligne ! 🚀

**URL à partager** : https://ton-projet.up.railway.app

**Identifiants admin** :
- Email : admin@blog.com
- Mot de passe : password

⚠️ **IMPORTANT** : Change ces identifiants après la première connexion !

---

## 🔄 Mettre à jour le site

Quand tu fais des modifications :

```bash
git add .
git commit -m "Description des modifications"
git push
```

Railway redéploiera automatiquement ! 🎯
