# 🔐 CONFIGURATION GOOGLE OAUTH - GUIDE COMPLET

## ✅ CE QUI A ÉTÉ FAIT (Code)

1. ✅ Laravel Socialite installé
2. ✅ GoogleAuthController créé
3. ✅ Routes Google OAuth ajoutées
4. ✅ Bouton "Se connecter avec Google" ajouté à la page login
5. ✅ Configuration services.php préparée

---

## 🎯 CE QU'IL RESTE À FAIRE (Configuration Google)

Tu dois créer une application OAuth sur Google Cloud Console pour obtenir :
- **Client ID**
- **Client Secret**

---

## 📋 ÉTAPE PAR ÉTAPE : Créer l'application Google OAuth

### ÉTAPE 1 : Accéder à Google Cloud Console

1. Va sur https://console.cloud.google.com
2. Connecte-toi avec ton compte **kerphilesaint@gmail.com**

---

### ÉTAPE 2 : Créer un nouveau projet

1. En haut de la page, clique sur le **sélecteur de projet**
2. Clique sur **"Nouveau projet"**
3. Nom du projet : **Blog Laravel**
4. Clique sur **"Créer"**
5. Attends quelques secondes que le projet soit créé
6. Sélectionne le projet créé

---

### ÉTAPE 3 : Activer l'API Google+

1. Dans le menu à gauche, va dans **"API et services"** → **"Bibliothèque"**
2. Recherche **"Google+ API"**
3. Clique dessus
4. Clique sur **"Activer"**

---

### ÉTAPE 4 : Créer les identifiants OAuth

1. Dans le menu à gauche, va dans **"API et services"** → **"Identifiants"**
2. Clique sur **"Créer des identifiants"**
3. Sélectionne **"ID client OAuth"**

---

### ÉTAPE 5 : Configurer l'écran de consentement

Si c'est la première fois, tu dois configurer l'écran de consentement :

1. Clique sur **"Configurer l'écran de consentement"**
2. Sélectionne **"Externe"** (pour tester)
3. Clique sur **"Créer"**

**Informations à remplir :**
- Nom de l'application : **Blog KerpheX**
- Adresse e-mail de l'assistance : **kerphilesaint@gmail.com**
- Adresse e-mail du développeur : **kerphilesaint@gmail.com**

4. Clique sur **"Enregistrer et continuer"**
5. **Champs d'application** : Clique sur **"Enregistrer et continuer"** (pas besoin d'ajouter)
6. **Utilisateurs test** : Clique sur **"Ajouter des utilisateurs"**
   - Ajoute ton email : **kerphilesaint@gmail.com**
   - Clique sur **"Ajouter"**
7. Clique sur **"Enregistrer et continuer"**
8. Clique sur **"Retour au tableau de bord"**

---

### ÉTAPE 6 : Créer l'ID client OAuth

1. Retourne dans **"Identifiants"**
2. Clique sur **"Créer des identifiants"** → **"ID client OAuth"**
3. Type d'application : Sélectionne **"Application Web"**

**Informations à remplir :**

- Nom : **Blog Laravel OAuth**

- **Origines JavaScript autorisées** :
  ```
  https://web-production-c5c2f.up.railway.app
  http://mon-blog.test
  http://127.0.0.1
  ```

- **URI de redirection autorisés** :
  ```
  https://web-production-c5c2f.up.railway.app/auth/google/callback
  http://mon-blog.test/auth/google/callback
  http://127.0.0.1/mon_blog/public/auth/google/callback
  ```

4. Clique sur **"Créer"**

---

### ÉTAPE 7 : Récupérer les identifiants

Une fenêtre popup va s'afficher avec :
- ✅ **ID client** (commence par xxx.apps.googleusercontent.com)
- ✅ **Code secret du client**

**⚠️ COPIE CES DEUX VALEURS !** On va les utiliser juste après.

---

## 🔑 CONFIGURATION RAILWAY (Variables d'environnement)

Maintenant, on va ajouter ces identifiants sur Railway.

### ÉTAPE 8 : Ajouter les variables sur Railway

1. Va sur https://railway.app
2. Sélectionne ton projet **Blog Laravel**
3. Va dans l'onglet **"Variables"**
4. Ajoute ces 3 nouvelles variables :

```
GOOGLE_CLIENT_ID=COLLE_TON_CLIENT_ID_ICI
GOOGLE_CLIENT_SECRET=COLLE_TON_CLIENT_SECRET_ICI
GOOGLE_REDIRECT_URI=https://web-production-c5c2f.up.railway.app/auth/google/callback
```

5. Railway va **redéployer automatiquement**

---

## 🧪 TESTER EN LOCAL

1. **Copiez `google.local.env.example` → `google.local.env`** et collez Client ID / Secret.

2. **Lancez :**
   ```powershell
   powershell -ExecutionPolicy Bypass -File scripts/setup-google-oauth.ps1
   ```

3. **Vérifiez :**
   ```powershell
   powershell -ExecutionPolicy Bypass -File scripts/verify-google-oauth.ps1
   ```

4. **Testez :**
   - http://mon-blog.test/login
   - Cliquez sur « Se connecter avec Google »

---

## ✅ APRÈS CONFIGURATION

Une fois que tu as ajouté les variables sur Railway et attendu le redéploiement :

### Comment te connecter :

1. Va sur https://web-production-c5c2f.up.railway.app/login
2. Clique sur le bouton **"Se connecter avec Google"**
3. Sélectionne ton compte Google : **kerphilesaint@gmail.com**
4. Autorise l'application
5. ✅ **Tu es connecté automatiquement !**

---

## 🎯 AVANTAGES

✅ **Plus besoin de mot de passe**  
✅ **Connexion en 1 clic**  
✅ **Plus sécurisé** (OAuth Google)  
✅ **Plus pratique**  
✅ **Seul ton email Google peut se connecter**

---

## 🔒 SÉCURITÉ

Le code vérifie que **seul kerphilesaint@gmail.com** peut se connecter :

```php
if ($googleUser->getEmail() === 'kerphilesaint@gmail.com') {
    // Créer le compte admin
} else {
    // Refuser l'accès
}
```

Donc même si quelqu'un trouve l'URL de login, il ne pourra pas se connecter avec son propre compte Google.

---

## 🆘 EN CAS DE PROBLÈME

### Problème 1 : "Error 400: redirect_uri_mismatch"

**Solution :**
- Vérifie que l'URI de redirection dans Google Console est exactement :
  ```
  https://web-production-c5c2f.up.railway.app/auth/google/callback
  ```
- Pas d'espace, pas de slash à la fin

### Problème 2 : "Erreur lors de la connexion"

**Solution :**
- Vérifie que les variables sont bien dans Railway
- Vérifie qu'il n'y a pas de guillemets autour des valeurs
- Redémarre le déploiement sur Railway

### Problème 3 : "Accès non autorisé"

**Solution :**
- Vérifie que tu utilises bien **kerphilesaint@gmail.com**
- Vérifie que cet email est dans les utilisateurs test sur Google Console

---

## 📞 PROCHAINES ÉTAPES

1. ✅ Suis les étapes ci-dessus pour créer l'app Google OAuth
2. ✅ Copie le Client ID et Client Secret
3. ✅ Ajoute les variables sur Railway
4. ⏰ Attends le redéploiement (2-3 minutes)
5. 🧪 Teste la connexion avec Google
6. 🎉 **Profite de la connexion en 1 clic !**

---

## 💡 NOTE IMPORTANTE

Tu pourras toujours te connecter avec **email/mot de passe** aussi :
- Email : kerphilesaint@gmail.com
- Mot de passe : Blogperso20?

Les deux méthodes fonctionnent en parallèle ! 👍

---

**Commence par l'ÉTAPE 1 et suis le guide pas à pas ! Je suis là si tu as des questions ! 🚀**
