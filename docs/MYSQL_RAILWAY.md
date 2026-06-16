# 🐬 MySQL sur Railway — Guide optionnel

> **Configuration actuelle du projet :** **SQLite** en production (Railway), **MySQL** en local (Laragon).
> Ce guide sert uniquement si vous souhaitez passer Railway en MySQL plus tard.

Ce guide permet d'utiliser **MySQL** en production (Railway), comme en local (Laragon).
---

## 📋 Prérequis

- Projet Railway existant (`web-production-c5c2f.up.railway.app`)
- Code à jour sur GitHub (`main`)
- Accès au [dashboard Railway](https://railway.app)

---

## ÉTAPE 1 : Ajouter MySQL sur Railway

1. Ouvrez votre projet **elegant-serenity** (ou le nom de votre projet)
2. Cliquez sur **+ New** → **Database** → **MySQL**
3. Attendez que le service MySQL soit **Active** (icône verte)

Railway crée automatiquement une base avec utilisateur et mot de passe.

---

## ÉTAPE 2 : Lier MySQL au service Web

1. Cliquez sur le service **web** (votre application Laravel)
2. Onglet **Variables**
3. Cliquez sur **+ New Variable** → **Add Reference**
4. Sélectionnez le service **MySQL** et ajoutez ces références :

| Variable sur Web | Référence MySQL |
|---|---|
| `DB_CONNECTION` | `mysql` (valeur fixe, pas une référence) |
| `DB_HOST` | `${{MySQL.MYSQLHOST}}` |
| `DB_PORT` | `${{MySQL.MYSQLPORT}}` |
| `DB_DATABASE` | `${{MySQL.MYSQLDATABASE}}` |
| `DB_USERNAME` | `${{MySQL.MYSQLUSER}}` |
| `DB_PASSWORD` | `${{MySQL.MYSQLPASSWORD}}` |

> **Note :** les noms exacts peuvent varier selon la version Railway. Utilisez le menu **Add Reference** pour lier automatiquement les variables du service MySQL.

5. **Supprimez** ou commentez l'ancienne variable `DB_CONNECTION=sqlite` si elle existe encore.

---

## ÉTAPE 3 : Conserver les autres variables

Gardez ces variables sur le service **web** :

```
APP_NAME=KerpheX Blog
APP_ENV=production
APP_DEBUG=false
APP_KEY=<votre clé existante>
APP_URL=https://web-production-c5c2f.up.railway.app

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database

SYNC_EXPORT_TOKEN=<votre jeton>
GOOGLE_CLIENT_ID=<si configuré>
GOOGLE_CLIENT_SECRET=<si configuré>
GOOGLE_REDIRECT_URI=https://web-production-c5c2f.up.railway.app/auth/google/callback

MAIL_MAILER=brevo
BREVO_API_KEY=<clé API Brevo xkeysib-...>
MAIL_FROM_ADDRESS=kerphilesaint@gmail.com
MAIL_FROM_NAME=KerpheX Blog
```

---

## ÉTAPE 4 : Redéployer

Railway redéploie automatiquement après modification des variables.

1. Onglet **Deployments** → attendez **Success**
2. Vérifiez les logs : vous devez voir `Base de données : MySQL`

Au premier démarrage avec MySQL, `railway-init.sh` exécute :
- `php artisan migrate --force`
- `php artisan db:seed --class=AdminUserSeeder`
- `php artisan db:seed --class=ArticlesSeeder`

---

## ÉTAPE 5 : Vérifier la production

```powershell
powershell -ExecutionPolicy Bypass -File scripts/run-checklist-prod.ps1
```

Ou ouvrez : https://web-production-c5c2f.up.railway.app

---

## ⚠️ Migration des données SQLite → MySQL

Si vous aviez des **données réelles** en SQLite (commentaires, vues, inscriptions) :

1. **Avant** de passer à MySQL, exportez la base SQLite :
   - Via `php artisan blog:sync-from-production` en local (tant que prod est encore SQLite)
   - Ou téléchargez `database.sqlite` via Railway SSH

2. Après passage MySQL, les seeders recréent admin + 4 articles — les **anciennes données SQLite ne sont pas migrées automatiquement**.

3. Pour une migration complète, contactez-moi ou utilisez un outil d'import manuel (table par table via phpMyAdmin / artisan tinker).

---

## ÉTAPE 6 : Aligner le local sur la prod (MySQL → MySQL)

Une fois Railway en MySQL, vous pourrez synchroniser avec :

```powershell
powershell -ExecutionPolicy Bypass -File scripts/sync-from-production.ps1
```

(Export MySQL depuis la prod — à venir si `mysqldump` disponible sur Railway.)

---

## 🔧 Dépannage

### "SQLSTATE[HY000] [2002] Connection refused"
- Vérifiez que le service MySQL est **Active**
- Vérifiez que `DB_HOST` référence bien le service MySQL (pas `127.0.0.1`)

### "Access denied for user"
- Vérifiez `DB_USERNAME` et `DB_PASSWORD` via **Add Reference**

### Site vide après migration
- Normal si seeders seuls : admin + 4 articles de démo
- Connexion admin : `kerphilesaint@gmail.com`

---

## ✅ Résultat attendu

| Environnement | Base | Accès BDD |
|---|---|---|
| Local Laragon | MySQL `mon_blog` | phpMyAdmin |
| Railway prod | MySQL (service Railway) | Railway → MySQL → Connect |

Les deux environnements utilisent **MySQL** et le **même schéma** (migrations Laravel).
