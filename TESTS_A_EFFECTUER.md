# 🧪 TESTS À EFFECTUER AVANT LA PRÉSENTATION

## 📋 CHECKLIST COMPLÈTE

### 1️⃣ AUTHENTIFICATION
- [ ] Inscription visiteur avec email/password
- [ ] Connexion visiteur avec email/password
- [ ] Connexion avec Google OAuth (visiteur)
- [ ] Connexion admin via `/admin/login`
- [ ] Connexion Google OAuth admin (kerphileadome@gmail.com)
- [ ] Déconnexion admin
- [ ] Déconnexion visiteur

---

### 2️⃣ ARTICLES (VISITEUR)
- [ ] Liste des articles sur la page d'accueil
- [ ] Lecture d'un article complet
- [ ] Vues incrémentées après lecture
- [ ] Like d'un article
- [ ] Unlike d'un article
- [ ] Ajout aux favoris
- [ ] Retrait des favoris
- [ ] Accès à la page "Mes favoris"

---

### 3️⃣ COMMENTAIRES (VISITEUR)
- [ ] Poster un commentaire sur un article
- [ ] Voir les commentaires approuvés
- [ ] Les commentaires non approuvés ne sont pas visibles

---

### 4️⃣ ADMIN - DASHBOARD
- [ ] Accès au dashboard admin
- [ ] Affichage des 9 statistiques:
  - [ ] Articles totaux
  - [ ] Publiés
  - [ ] Brouillons
  - [ ] Utilisateurs
  - [ ] Commentaires
  - [ ] En attente
  - [ ] Vues totales
  - [ ] Likes
  - [ ] Favoris
- [ ] Liste des articles récents
- [ ] Liste des commentaires en attente
- [ ] Bouton "Nouvel article"

---

### 5️⃣ ADMIN - GESTION DES ARTICLES
- [ ] **CRÉER** un nouvel article:
  - [ ] Avec titre
  - [ ] Avec catégorie
  - [ ] Avec image de couverture
  - [ ] Avec contenu
  - [ ] Publier immédiatement
  - [ ] Sauvegarder comme brouillon
- [ ] **MODIFIER** un article existant:
  - [ ] Accès via le bouton crayon ✏️
  - [ ] Modification du titre
  - [ ] Modification du contenu
  - [ ] Changement de l'image
  - [ ] Publication d'un brouillon
  - [ ] Passage en brouillon d'un article publié
- [ ] **SUPPRIMER** un article
- [ ] **VOIR** la liste de tous les articles

---

### 6️⃣ ADMIN - GESTION DES COMMENTAIRES
- [ ] Voir tous les commentaires
- [ ] Approuver un commentaire en attente
- [ ] Rejeter un commentaire approuvé
- [ ] Supprimer un commentaire
- [ ] Voir le statut (approuvé/en attente)

---

### 7️⃣ ADMIN - GESTION DES UTILISATEURS
- [ ] Liste de tous les utilisateurs
- [ ] Voir les détails d'un utilisateur
- [ ] Bloquer un utilisateur
- [ ] Débloquer un utilisateur
- [ ] Supprimer un utilisateur
- [ ] Vérifier qu'un utilisateur bloqué ne peut pas se connecter

---

### 8️⃣ ADMIN - BIBLIOTHÈQUE DE MÉDIAS
- [ ] Upload d'une image
- [ ] Voir toutes les images uploadées
- [ ] Copier l'URL d'une image
- [ ] Supprimer une image
- [ ] Suppression en masse

---

### 9️⃣ ADMIN - PARAMÈTRES
- [ ] Accès à la page paramètres
- [ ] Export des utilisateurs (CSV)
- [ ] Export des statistiques (CSV)

---

### 🔟 SÉCURITÉ ET ACCÈS
- [ ] Visiteur ne peut pas accéder à `/admin/dashboard`
- [ ] Visiteur ne peut pas créer d'articles
- [ ] Visiteur ne peut pas modifier d'articles
- [ ] Visiteur ne peut pas supprimer d'articles
- [ ] Visiteur ne peut pas supprimer de commentaires
- [ ] Visiteur ne peut pas gérer les utilisateurs
- [ ] Utilisateur non connecté est redirigé vers login
- [ ] Utilisateur bloqué ne peut pas se connecter

---

### 1️⃣1️⃣ INTERFACE UTILISATEUR
- [ ] Navigation fluide entre les pages
- [ ] Logo professionnel affiché correctement
- [ ] Menu admin visible et fonctionnel
- [ ] Boutons d'action visibles
- [ ] Messages de succès affichés
- [ ] Messages d'erreur affichés
- [ ] Design responsive (mobile friendly)
- [ ] Formulaires bien alignés et centrés

---

### 1️⃣2️⃣ PERFORMANCE
- [ ] Temps de chargement acceptable (<2s)
- [ ] Images optimisées
- [ ] Pagination fonctionnelle
- [ ] Pas d'erreur 500
- [ ] Pas d'erreur 404 sur les liens internes

---

## 🎯 TESTS PRIORITAIRES POUR LA PRÉSENTATION

### SCÉNARIO 1: Visiteur découvre le blog
1. Accéder à https://web-production-c5c2f.up.railway.app
2. S'inscrire avec un nouveau compte
3. Lire un article
4. Liker l'article
5. Ajouter aux favoris
6. Poster un commentaire
7. Accéder à "Mes favoris"

### SCÉNARIO 2: Admin gère le contenu
1. Se connecter en tant qu'admin
2. Accéder au dashboard
3. Créer un nouvel article avec image
4. Le publier immédiatement
5. Modérer les commentaires
6. Voir les statistiques

### SCÉNARIO 3: Modification d'article (LE PLUS IMPORTANT)
1. Se connecter en tant qu'admin
2. Aller sur le dashboard
3. Cliquer sur le crayon ✏️ du 3ème article (brouillon)
4. **VÉRIFIER QU'IL N'Y A PLUS D'ERREUR 500**
5. Modifier le contenu
6. Cocher "Publié"
7. Mettre à jour l'article
8. Vérifier que l'article est maintenant publié

---

## ✅ RÉSULTAT ATTENDU

Tous les tests doivent passer **SANS ERREUR**.

Le projet doit être **100% fonctionnel** pour la présentation de jeudi.

---

**Date:** 4 juin 2026
**URL:** https://web-production-c5c2f.up.railway.app
