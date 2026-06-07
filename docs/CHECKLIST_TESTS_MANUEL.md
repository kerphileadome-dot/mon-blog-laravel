# Checklist de tests manuels — KerpheX Blog

Utilisez cette liste pour valider le projet de A à Z.

**Local :** http://mon-blog.test:8080  
**Production :** https://web-production-c5c2f.up.railway.app

Cochez chaque case après test réussi.

---

## Prérequis

- [x] Laragon démarré (Apache/Nginx + MySQL)
- [x] `php artisan migrate` à jour
- [x] `php artisan storage:link` actif
- [x] Script local exécuté : `powershell -ExecutionPolicy Bypass -File scripts/setup-local-env.ps1`
- [x] Email test OK : `php artisan blog:test-mail`
- [x] `google.local.env` renseigné (+ URI local dans Google Cloud Console si OAuth local)

---

## 1. Pages publiques (sans connexion)

| # | Page | URL | À vérifier |
|---|------|-----|------------|
| 1.1 | Accueil | `/` | 4 articles, article à la une, catégories, stats |
| 1.2 | Article | `/posts/{slug}` | Titre, couverture entière, contenu, tags, vues |
| 1.3 | Catégories | `/categories` | Liste des catégories |
| 1.4 | Catégorie | `/categories/Politique` | Articles filtrés |
| 1.5 | Tags | `/tags` | Liste des tags |
| 1.6 | Tag | `/tags/Bénin` | Articles filtrés |
| 1.7 | Recherche | `/search?q=wadagni` | Résultats pertinents |
| 1.8 | À propos | `/about` | Page statique OK |
| 1.9 | 404 | `/page-inexistante` | Page d'erreur personnalisée |

- [x] 1.1 Accueil (prod auto OK)
- [x] 1.2 Article Wadagni (prod auto OK)
- [x] 1.3–1.9 Autres pages publiques (prod auto OK — `scripts/run-checklist-prod.ps1`)

---

## 2. Visiteur — Inscription & connexion

| # | Test | Étapes | Résultat attendu |
|---|------|--------|------------------|
| 2.1 | Inscription Gmail | `/register` avec `test@gmail.com` | Compte créé, redirection accueil |
| 2.2 | Rejet non-Gmail | `/register` avec `test@yahoo.com` | Message d'erreur FR |
| 2.3 | Connexion email | `/login` email + mot de passe | Session visiteur active |
| 2.4 | Google OAuth | Bouton Google sur `/login` | Connexion Gmail uniquement |
| 2.5 | Admin bloqué visiteur | Tenter login visiteur avec email admin | Refus / redirection admin |
| 2.6 | Déconnexion | Menu déconnexion | Session fermée |

- [x] 2.1 Inscription Gmail (tests auto OK)
- [x] 2.2 Rejet non-Gmail (tests auto OK)
- [ ] 2.3 Connexion email
- [ ] 2.4 Google OAuth
- [ ] 2.5 Séparation admin/visiteur
- [ ] 2.6 Déconnexion

---

## 3. Visiteur — Mot de passe oublié & profil

| # | Test | Étapes | Résultat attendu |
|---|------|--------|------------------|
| 3.1 | Demande reset | `/forgot-password` | Email reçu (lien FR) |
| 3.2 | Reset mot de passe | Cliquer le lien email | Nouveau MDP accepté |
| 3.3 | Profil | `/profile` | Modifier nom / email |
| 3.4 | Changer MDP | Onglet mot de passe profil | MDP mis à jour |
| 3.5 | Supprimer compte | Profil → supprimer | Compte supprimé |

- [ ] 3.1 Email reset reçu
- [ ] 3.2 Reset fonctionne
- [ ] 3.3 Profil
- [ ] 3.4 Changement MDP
- [ ] 3.5 Suppression compte

---

## 4. Visiteur — Interactions articles

| # | Test | Étapes | Résultat attendu |
|---|------|--------|------------------|
| 4.1 | Like | Cliquer ♥ sur un article | Compteur +1, état actif |
| 4.2 | Unlike | Re-cliquer ♥ | Compteur -1 |
| 4.3 | Favori | Ajouter aux favoris | Étoile active |
| 4.4 | Commentaire | Poster un commentaire | Visible en attente / publié |
| 4.5 | Réponse | Répondre à un commentaire | Fil de discussion OK |
| 4.6 | Sans connexion | Like sans être connecté | Redirection login |

- [ ] 4.1 Like
- [ ] 4.2 Unlike
- [ ] 4.3 Favori
- [ ] 4.4 Commentaire
- [ ] 4.5 Réponse
- [ ] 4.6 Protection login

---

## 5. Administration

**Connexion :** `/admin/login` — `kerphilesaint@gmail.com`

| # | Page | URL | À vérifier |
|---|------|-----|------------|
| 5.1 | Dashboard | `/admin/dashboard` | Stats, articles récents |
| 5.2 | Articles | `/admin/posts` | Liste 4 articles |
| 5.3 | Créer article | `/admin/posts/create` | Upload couverture nette, publication |
| 5.4 | Modifier article | `/admin/posts/{id}/edit` | Mise à jour OK |
| 5.5 | Commentaires | `/admin/comments` | Compteurs, approuver, rejeter, répondre 💬 |
| 5.6 | Utilisateurs | `/admin/users` | Liste visiteurs, bloquer, supprimer |
| 5.7 | Médias | `/admin/media` | Upload, suppression images |
| 5.8 | Paramètres | `/admin/settings` | Sauvegarde + exports CSV |
| 5.9 | Déconnexion admin | Bouton logout | Session admin fermée |

- [ ] 5.1 Dashboard
- [ ] 5.2 Liste articles
- [ ] 5.3 Création article + couverture
- [ ] 5.4 Édition article
- [ ] 5.5 Modération commentaires
- [ ] 5.6 Gestion utilisateurs
- [ ] 5.7 Bibliothèque médias
- [ ] 5.8 Paramètres
- [ ] 5.9 Déconnexion admin

---

## 6. Sécurité & séparation des sessions

| # | Test | Résultat attendu |
|---|------|------------------|
| 6.1 | Connecté visiteur → `/admin/dashboard` | Accès refusé |
| 6.2 | Connecté admin → `/admin/dashboard` | Accès OK |
| 6.3 | Admin + visiteur en parallèle (2 navigateurs) | Sessions indépendantes |
| 6.4 | Visiteur bloqué par admin | Connexion refusée |

- [ ] 6.1–6.4 Sécurité

---

## 7. Production Railway (même checklist rapide)

- [ ] Accueil production charge (200)
- [ ] Inscription Gmail production
- [ ] Reset mot de passe production (email reçu)
- [ ] Google OAuth production
- [ ] Couverture Wadagni = portrait complet
- [ ] Admin production accessible

---

## 8. Tests automatiques (développeur)

```bash
php artisan test
npm run build
```

- [ ] 26 tests PHPUnit passent
- [ ] Build Vite sans erreur

---

## Comptes de référence

| Rôle | Email | Connexion |
|------|-------|-----------|
| Admin | kerphilesaint@gmail.com | `/admin/login` |
| Admin | kerphileadome@gmail.com | `/admin/login` |
| Visiteur test | Gmail uniquement | `/register` ou Google |

---

## En cas d'échec

| Problème | Action |
|----------|--------|
| Email non reçu | Vérifier `MAIL_*` dans `.env`, lancer `php artisan blog:test-mail`. Sous Laragon/Windows, tester sur Railway si SSL local bloque |
| Google OAuth local | Renseigner `google.local.env`, URI `http://mon-blog.test:8080/auth/google/callback` dans Google Console |
| Google redirect_uri_mismatch | Vérifier `GOOGLE_REDIRECT_URI` = `{APP_URL}/auth/google/callback` dans Google Console |
| Images absentes | `php artisan storage:link` |
| Erreur base | `php artisan migrate` |

---

*Dernière mise à jour : juin 2026*
