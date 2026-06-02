# 📝 Blog Personnel - Laravel

Un blog personnel moderne et élégant développé avec Laravel 13, conçu pour un auteur unique avec un système d'administration complet.

## ✨ Fonctionnalités

### 🎯 Fonctionnalités principales

- **Gestion des articles**
  - Création, modification et suppression d'articles
  - Système de brouillons et publication
  - Upload d'images de couverture
  - Génération automatique de slugs
  - Catégorisation des articles
  - Compteur de vues

- **Système de commentaires**
  - Commentaires publics (sans inscription)
  - Modération des commentaires
  - Approbation/rejet par l'admin

- **Système de likes**
  - Likes basés sur l'adresse IP
  - Toggle like/unlike
  - Compteur de likes par article

- **Dashboard administrateur**
  - Statistiques en temps réel
  - Gestion centralisée des articles
  - Modération des commentaires
  - Vue d'ensemble des performances

### 🔒 Sécurité

- **Système de rôles** : Admin et Visitor
- **Policies Laravel** : Protection des ressources
- **Inscription désactivée** : Blog personnel (un seul auteur)
- **Middleware admin** : Routes protégées
- **Validation des données** : Toutes les entrées sont validées

## 🛠️ Technologies utilisées

- **Backend** : Laravel 13 (PHP 8.3)
- **Frontend** : Blade Templates, TailwindCSS, Alpine.js
- **Base de données** : SQLite
- **Authentification** : Laravel Breeze
- **Build** : Vite

## 📦 Installation

### Prérequis

- PHP 8.3 ou supérieur
- Composer
- Node.js et npm
- Laragon (ou tout autre environnement PHP)

### Étapes d'installation

1. **Cloner le projet**
   ```bash
   git clone <votre-repo>
   cd mon-blog
   ```

2. **Installer les dépendances PHP**
   ```bash
   composer install
   ```

3. **Installer les dépendances JavaScript**
   ```bash
   npm install
   ```

4. **Configurer l'environnement**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Créer la base de données**
   ```bash
   touch database/database.sqlite
   ```

6. **Exécuter les migrations**
   ```bash
   php artisan migrate
   ```

7. **Ajouter le champ role à la table users**
   ```bash
   php artisan migrate
   ```

8. **Créer le compte administrateur**
   ```bash
   php artisan db:seed --class=AdminUserSeeder
   ```

   **Identifiants par défaut :**
   - Email : `admin@blog.com`
   - Mot de passe : `password`
   
   ⚠️ **Important** : Changez ces identifiants après la première connexion !

9. **Créer le lien symbolique pour le storage**
   ```bash
   php artisan storage:link
   ```

10. **Compiler les assets**
    ```bash
    npm run build
    ```

11. **Démarrer le serveur de développement**
    ```bash
    php artisan serve
    ```

    Ou avec Laragon, démarrez simplement Apache.

## 🚀 Utilisation

### Accès au blog

- **Page d'accueil** : `http://localhost/mon-blog` (ou votre URL Laragon)
- **Connexion admin** : `http://localhost/mon-blog/login`
- **Dashboard admin** : `http://localhost/mon-blog/admin/dashboard`

### Compte administrateur

Après l'installation, connectez-vous avec :
- Email : `admin@blog.com`
- Mot de passe : `password`

**⚠️ Changez immédiatement ces identifiants !**

### Créer un nouvel article

1. Connectez-vous en tant qu'admin
2. Cliquez sur "📊 Dashboard" dans la navigation
3. Cliquez sur "➕ Nouvel article"
4. Remplissez le formulaire
5. Cochez "Publier" pour publier immédiatement, ou laissez décoché pour un brouillon

### Modérer les commentaires

1. Accédez au dashboard admin
2. Les commentaires en attente apparaissent sur la page d'accueil
3. Cliquez sur "💬 Gérer les commentaires" pour voir tous les commentaires
4. Approuvez ou supprimez les commentaires

## 📁 Structure du projet

```
mon-blog/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/
│   │   │   │   └── DashboardController.php
│   │   │   ├── PostController.php
│   │   │   ├── CommentController.php
│   │   │   └── LikeController.php
│   │   └── Middleware/
│   │       └── AdminMiddleware.php
│   ├── Models/
│   │   ├── User.php
│   │   ├── Post.php
│   │   ├── Comment.php
│   │   └── Like.php
│   └── Policies/
│       └── PostPolicy.php
├── database/
│   ├── migrations/
│   └── seeders/
│       └── AdminUserSeeder.php
├── resources/
│   ├── views/
│   │   ├── admin/
│   │   │   ├── dashboard.blade.php
│   │   │   ├── posts.blade.php
│   │   │   └── comments.blade.php
│   │   ├── posts/
│   │   └── layouts/
│   └── css/
└── routes/
    ├── web.php
    └── auth.php
```

## 🎨 Personnalisation

### Changer le nom du blog

Modifiez la variable `APP_NAME` dans le fichier `.env` :

```env
APP_NAME="Mon Blog"
```

### Modifier les styles

Les styles sont dans `resources/css/app.css` et utilisent TailwindCSS.

### Ajouter des catégories

Les catégories sont actuellement en texte libre. Pour ajouter une liste prédéfinie, modifiez les vues `posts/create.blade.php` et `posts/edit.blade.php`.

## 🔐 Sécurité

### Bonnes pratiques implémentées

- ✅ Policies pour protéger les ressources
- ✅ Middleware admin pour les routes sensibles
- ✅ Validation de toutes les entrées utilisateur
- ✅ Protection CSRF sur tous les formulaires
- ✅ Hachage des mots de passe avec bcrypt
- ✅ Inscription publique désactivée

### Recommandations

- Changez les identifiants admin par défaut
- Utilisez des mots de passe forts
- Activez HTTPS en production
- Configurez les sauvegardes régulières de la base de données

## 📊 Base de données

### Tables principales

- **users** : Utilisateurs (admin uniquement)
- **posts** : Articles du blog
- **comments** : Commentaires sur les articles
- **likes** : Likes des articles (par IP)

### Relations

- Un utilisateur a plusieurs posts
- Un post a plusieurs commentaires
- Un post a plusieurs likes

## 🐛 Dépannage

### Erreur "Class 'Post' not found"

```bash
composer dump-autoload
```

### Erreur de permissions sur storage

```bash
chmod -R 775 storage bootstrap/cache
```

### Les images ne s'affichent pas

```bash
php artisan storage:link
```

### Erreur 403 sur les routes admin

Vérifiez que vous êtes connecté avec un compte admin (role = 'admin').

## 📝 TODO / Améliorations futures

- [ ] Système de tags
- [ ] Recherche d'articles
- [ ] Filtres par catégorie
- [ ] Éditeur WYSIWYG (TinyMCE/Trix)
- [ ] Export RSS
- [ ] Newsletter
- [ ] Partage sur réseaux sociaux
- [ ] Optimisation des images
- [ ] Cache des articles populaires
- [ ] Tests automatisés

## 👨‍💻 Auteur

Développé avec ❤️ pour un projet de présentation.

## 📄 Licence

Ce projet est sous licence MIT.

---

**Date de présentation** : Mercredi

**Bon courage pour ta présentation ! 🚀**
