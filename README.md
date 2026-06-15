# 📝 Blog Personnel Laravel 13

<div align="center">

![Laravel](https://img.shields.io/badge/Laravel-13.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white)
![CSS](https://img.shields.io/badge/CSS-Custom-38B2AC?style=for-the-badge)
![SQLite](https://img.shields.io/badge/SQLite-3.x-003B57?style=for-the-badge&logo=sqlite&logoColor=white)

**Blog personnel moderne et sécurisé développé avec Laravel 13**

[🌐 Voir le site](https://web-production-c5c2f.up.railway.app) • [📖 Documentation](#documentation) • [🚀 Déploiement](#déploiement)

</div>

---

## 🎯 À PROPOS

Ce projet est un **blog personnel professionnel** où seul l'administrateur peut publier des articles, tandis que les visiteurs peuvent les lire, commenter et aimer les publications.

### ✨ Caractéristiques principales

- 🔐 **Système d'authentification sécurisé** avec Laravel Breeze
- 👤 **Gestion des rôles** (admin/visitor) avec autorisations fines
- 📊 **Dashboard administrateur** avec statistiques en temps réel
- ✍️ **CRUD complet** pour les articles avec éditeur riche
- 🖼️ **Upload d'images** de couverture
- 💬 **Système de commentaires** avec modération
- ❤️ **Système de likes et favoris** par utilisateur connecté
- 👁️ **Compteur de vues** par article
- 📱 **Interface responsive** et moderne
- 🚀 **Déploiement automatisé** sur Railway

---

## 🛠️ TECHNOLOGIES

### Backend
- **Framework** : Laravel 13.x
- **Langage** : PHP 8.2+
- **Base de données** : SQLite
- **Authentification** : Laravel Breeze
- **ORM** : Eloquent

### Frontend
- **CSS** : Feuille de style éditoriale custom (`resources/css/app.css`)
- **Template Engine** : Blade
- **Build Tool** : Vite
- **JavaScript** : Vanilla JS

### DevOps
- **Versioning** : Git + GitHub
- **CI/CD** : Railway (auto-deploy)
- **Build** : Nixpacks

---

## 📁 STRUCTURE

```
mon-blog-laravel/
├── app/
│   ├── Http/Controllers/       # Logique métier
│   │   ├── PostController      # Gestion articles
│   │   ├── CommentController   # Gestion commentaires
│   │   ├── LikeController      # Gestion likes
│   │   └── Admin/              
│   │       └── DashboardController  # Dashboard admin
│   ├── Models/                 # Models Eloquent
│   │   ├── User               # Utilisateurs + rôles
│   │   ├── Post               # Articles
│   │   ├── Comment            # Commentaires
│   │   └── Like               # Likes
│   ├── Policies/              # Autorisations
│   │   └── PostPolicy         # Policy des articles
│   └── Middleware/            # Sécurité
│       └── AdminMiddleware    # Protection routes admin
├── database/
│   ├── migrations/            # Structure de la BDD
│   └── seeders/               # Données initiales
│       └── AdminUserSeeder    # Création compte admin
├── resources/
│   ├── views/                 # Templates Blade
│   │   ├── posts/            # Vues articles
│   │   ├── admin/            # Vues admin
│   │   └── layouts/          # Layouts
│   ├── css/                  # Styles (Tailwind)
│   └── js/                   # JavaScript
├── routes/
│   ├── web.php               # Routes publiques + admin
│   └── auth.php              # Routes authentification
├── public/
│   └── build/                # Assets compilés (CSS/JS)
└── storage/                  # Uploads + fichiers générés
```

---

## 🚀 DÉMARRAGE RAPIDE

### Prérequis

- PHP 8.2+
- Composer
- Node.js 18+
- Git

### Installation

```bash
# 1. Cloner le repository
git clone https://github.com/kerphileadome-dot/mon-blog-laravel.git
cd mon-blog-laravel

# 2. Installer les dépendances
composer install
npm install

# 3. Configuration
cp .env.example .env
php artisan key:generate

# 4. Base de données
touch database/database.sqlite
php artisan migrate
php artisan db:seed --class=AdminUserSeeder

# 5. Storage et assets
php artisan storage:link
npm run build

# 6. Démarrer le serveur
php artisan serve
```

Le site sera accessible sur **http://127.0.0.1:8000**

### Identifiants par défaut

- **Email** : admin@blog.com
- **Mot de passe** : password

⚠️ **Changez ces identifiants en production !**

---

## 📖 DOCUMENTATION

Ce projet inclut une documentation complète :

| Document | Description |
|----------|-------------|
| **[INSTRUCTIONS_INSTALLATION.md](INSTRUCTIONS_INSTALLATION.md)** | 📦 Guide complet d'installation locale |
| **[AUDIT_COMPLET.md](AUDIT_COMPLET.md)** | 🔍 Analyse détaillée du projet |
| **[DEPLOIEMENT.md](DEPLOIEMENT.md)** | 🚀 Guide de déploiement sur Railway |
| **[docs/MYSQL_RAILWAY.md](docs/MYSQL_RAILWAY.md)** | 🐬 Passer Railway en MySQL |
| **[CONFIGURATION_GOOGLE_OAUTH.md](CONFIGURATION_GOOGLE_OAUTH.md)** | 🔐 Connexion Google |
| **[GUIDE_PRESENTATION.md](GUIDE_PRESENTATION.md)** | 🎤 Guide pour présenter le projet |
| **[RESUME_MODIFICATIONS.md](RESUME_MODIFICATIONS.md)** | 📝 Historique des modifications |
| **[AMELIORATIONS_FUTURES.md](AMELIORATIONS_FUTURES.md)** | 💡 Idées d'évolutions futures |

---

## 🎨 FONCTIONNALITÉS

### Pour l'administrateur

- ✅ **Dashboard** avec statistiques (posts, commentaires, vues)
- ✅ **Création d'articles** avec :
  - Titre, extrait, contenu
  - Upload image de couverture
  - Catégories
  - Brouillon ou publication
- ✅ **Édition et suppression** d'articles
- ✅ **Modération des commentaires** (approuver/rejeter/supprimer)
- ✅ **Gestion complète** depuis le dashboard

### Pour les visiteurs

- ✅ **Lecture** des articles publiés
- ✅ **Commentaires** sur les articles
- ✅ **Likes** (un par IP)
- ✅ **Interface responsive** (mobile/tablette/desktop)
- ✅ **Pagination** des articles

### Techniques

- ✅ **Sécurité multi-niveaux** (middleware + policies)
- ✅ **Génération automatique** de slugs
- ✅ **Compteur de vues** incrémental
- ✅ **Relations Eloquent** optimisées
- ✅ **Validation** des formulaires
- ✅ **Messages flash** pour feedback utilisateur
- ✅ **Gestion d'images** avec storage Laravel

---

## 🗄️ BASE DE DONNÉES

### Schéma

```
users
├── id
├── name
├── email (unique)
├── role (admin/visitor)
├── password (hashed)
└── timestamps

posts
├── id
├── user_id (FK)
├── title
├── slug (unique)
├── excerpt
├── content
├── cover_image
├── category
├── views
├── published (boolean)
└── timestamps

comments
├── id
├── post_id (FK, cascade)
├── name
├── email
├── body
├── approved (boolean)
└── timestamps

likes
├── id
├── post_id (FK, cascade)
├── ip_address
└── timestamps
```

### Relations

- Un **User** a plusieurs **Posts**
- Un **Post** appartient à un **User**
- Un **Post** a plusieurs **Comments** et **Likes**
- Un **Comment** / **Like** appartient à un **Post**

---

## 🔐 SÉCURITÉ

### Authentification & Autorisation

- ✅ Mots de passe **hashés** avec bcrypt
- ✅ Protection **CSRF** sur tous les formulaires
- ✅ **Middleware admin** sur routes sensibles
- ✅ **Policies** pour autorisations fines
- ✅ Inscription publique **désactivée**
- ✅ Sessions **sécurisées**
- ✅ **HTTPS forcé** en production

### Validation

- ✅ Validation **côté serveur** de tous les formulaires
- ✅ Taille max images : **4MB**
- ✅ Types images autorisés : **jpeg, png, jpg, gif, webp**
- ✅ Sanitisation des **inputs utilisateur**

---

## 🌐 DÉPLOIEMENT

### Site en production

**URL** : https://web-production-c5c2f.up.railway.app

### Plateforme

- **Hébergeur** : Railway
- **Build** : Nixpacks
- **Auto-deploy** : ✅ Activé (push GitHub)

### Configuration

Le projet inclut les fichiers de configuration nécessaires :
- `nixpacks.toml` - Configuration de build
- `railway-init.sh` - Script d'initialisation
- `Procfile` - Commande de démarrage (optionnel)

Voir **[DEPLOIEMENT.md](DEPLOIEMENT.md)** pour le guide complet.

---

## 🧪 TESTS

### Tests manuels

Tous les parcours utilisateur ont été testés :

- ✅ Connexion admin
- ✅ Création d'article
- ✅ Édition d'article
- ✅ Suppression d'article
- ✅ Upload d'image
- ✅ Publication/brouillon
- ✅ Ajout de commentaire
- ✅ Modération commentaire
- ✅ Toggle like
- ✅ Compteur de vues
- ✅ Dashboard statistiques
- ✅ Responsive design

---

## 📊 STATISTIQUES DU PROJET

- **Lignes de code PHP** : ~1,200
- **Lignes de code Blade** : ~800
- **Contrôleurs** : 5
- **Models** : 4
- **Policies** : 1
- **Middleware personnalisés** : 1
- **Migrations** : 5
- **Routes** : 20+
- **Vues Blade** : 12+

---

## 💡 ÉVOLUTIONS FUTURES

### Court terme
- [ ] Catégories cliquables
- [ ] Recherche d'articles
- [ ] Tags sur articles
- [ ] Partage réseaux sociaux

### Moyen terme
- [ ] Section événements
- [ ] Galeries photos
- [ ] Newsletter par email
- [ ] Commentaires imbriqués

### Long terme
- [ ] Multi-langues (FR/EN)
- [ ] API REST
- [ ] Application mobile
- [ ] Thème sombre

Voir **[AMELIORATIONS_FUTURES.md](AMELIORATIONS_FUTURES.md)** pour plus de détails.

---

## 🤝 CONTRIBUTION

Ce projet est personnel, mais les suggestions sont les bienvenues !

Pour proposer des améliorations :
1. Fork le projet
2. Créer une branche (`git checkout -b feature/AmazingFeature`)
3. Commit les changements (`git commit -m 'Add AmazingFeature'`)
4. Push vers la branche (`git push origin feature/AmazingFeature`)
5. Ouvrir une Pull Request

---

## 📝 LICENCE

Ce projet est sous licence MIT. Voir le fichier `LICENSE` pour plus de détails.

---

## 👨‍💻 AUTEUR

**Kerphile Saint**

- 📧 Email : kerphilesaint@gmail.com
- 🌐 Site : https://web-production-c5c2f.up.railway.app
- 💼 GitHub : [@kerphileadome-dot](https://github.com/kerphileadome-dot)

---

## 🙏 REMERCIEMENTS

- **Laravel** - Pour ce framework incroyable
- **Tailwind CSS** - Pour le système de design
- **Railway** - Pour l'hébergement gratuit
- **GitHub** - Pour l'hébergement du code
- **La communauté Laravel** - Pour le support et les ressources

---

## 📸 APERÇU

### Page d'accueil
Liste des articles avec design moderne et responsive.

### Dashboard Admin
Statistiques en temps réel et gestion complète du contenu.

### Création d'article
Formulaire complet avec upload d'image et prévisualisation.

---

<div align="center">

**⭐ Si ce projet vous plaît, n'hésitez pas à lui donner une étoile ! ⭐**

Développé avec ❤️ et Laravel

</div>
