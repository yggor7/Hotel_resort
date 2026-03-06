# Island Hotel (PHP) - Guide d'installation cPanel

## Prérequis
- PHP 7.4 ou supérieur (PDO, GD Library)
- MySQL 5.7 ou supérieur
- Apache avec mod_rewrite

---

## Étape 1 : Uploader les fichiers

Via **cPanel File Manager** ou **FTP** :
- Uploadez tout le contenu de `island_hotel(php)/` dans votre dossier public (ex: `public_html/` ou `public_html/hotel/`)

---

## Étape 2 : Créer la base de données

1. Dans cPanel → **MySQL Databases**
2. Créez une nouvelle base de données (ex: `votreuser_hotel`)
3. Créez un utilisateur MySQL avec un mot de passe fort
4. Accordez tous les privilèges à l'utilisateur sur la base de données

---

## Étape 3 : Importer le schéma SQL

1. Dans cPanel → **phpMyAdmin**
2. Sélectionnez votre base de données
3. Cliquez sur **Importer**
4. Sélectionnez le fichier `database.sql`
5. Cliquez sur **Exécuter**

---

## Étape 4 : Configurer la connexion

Éditez le fichier `includes/config.php` :

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'votreuser_hotel');    // Votre nom de BDD
define('DB_USER', 'votreuser_dbuser');   // Votre utilisateur BDD
define('DB_PASS', 'votremotdepasse');    // Votre mot de passe BDD

// URL du site (sans slash final)
define('SITE_URL', 'https://votredomaine.com');
// ou si installé dans un sous-dossier:
define('SITE_URL', 'https://votredomaine.com/hotel');
```

---

## Étape 5 : Permissions des dossiers

Via **cPanel File Manager** → Clic droit → Permissions :

| Dossier | Permission |
|---------|-----------|
| `uploads/` | `755` |
| `uploads/rooms/` | `755` |
| `uploads/services/` | `755` |
| `uploads/gallery/` | `755` |
| `uploads/hero/` | `755` |
| `uploads/testimonials/` | `755` |

---

## Étape 6 : Accéder au site

- **Site public** : `https://votredomaine.com/`
- **Dashboard admin** : `https://votredomaine.com/dashboard/login.php`

### Identifiants par défaut
- **Utilisateur** : `admin`
- **Mot de passe** : `admin123`

> ⚠️ **IMPORTANT** : Changez le mot de passe immédiatement après la première connexion!

---

## Changer le mot de passe admin

Via **phpMyAdmin** → table `admin_users` :
```sql
UPDATE admin_users SET password = '$2y$10$VOTRE_HASH' WHERE username = 'admin';
```

Ou générez un hash PHP :
```php
echo password_hash('VotreNouveauMotDePasse', PASSWORD_DEFAULT);
```

---

## Structure des fichiers

```
island_hotel(php)/
├── index.php              ← Page d'accueil
├── rooms.php              ← Liste des chambres
├── room.php               ← Détail d'une chambre (?slug=xxx)
├── booking.php            ← Formulaire de réservation
├── services.php           ← Liste des services
├── service.php            ← Détail d'un service
├── about.php              ← À propos
├── contact.php            ← Contact
├── database.sql           ← Schéma + données initiales
├── .htaccess              ← Règles Apache
│
├── includes/
│   ├── config.php         ← ⚙️ CONFIGURER ICI
│   ├── functions.php      ← Fonctions utilitaires
│   ├── header.php         ← En-tête commun
│   └── footer.php         ← Pied de page commun
│
├── dashboard/
│   ├── login.php          ← Connexion admin
│   ├── logout.php         ← Déconnexion
│   ├── index.php          ← Tableau de bord
│   ├── reservations/      ← Gestion réservations
│   ├── rooms/             ← CRUD chambres
│   ├── services/          ← CRUD services
│   ├── testimonials/      ← CRUD témoignages
│   ├── gallery/           ← CRUD galerie
│   ├── hero/              ← CRUD section hero
│   └── settings/          ← Paramètres du site
│
├── assets/
│   ├── css/
│   │   ├── style.css      ← CSS principal
│   │   └── dashboard.css  ← CSS tableau de bord
│   ├── js/
│   │   └── main.js        ← JavaScript principal
│   └── images/            ← 10 photos de resort
│
└── uploads/               ← Images uploadées
    ├── rooms/
    ├── services/
    ├── gallery/
    ├── hero/
    └── testimonials/
```

---

## Dépannage

**Erreur de connexion BDD** : Vérifiez config.php (host, name, user, pass)

**Images ne s'affichent pas** : Vérifiez les permissions du dossier uploads/ (755)

**Page 404** : Vérifiez que mod_rewrite est activé et que .htaccess est en place

**Erreur session** : Vérifiez que PHP sessions sont activées sur votre hébergement

---

## Sécurité en production

1. Supprimez le fichier `INSTALLATION.md` après installation
2. Changez le mot de passe admin immédiatement
3. Activez HTTPS (décommentez les lignes dans .htaccess)
4. Vérifiez les permissions des fichiers (644 pour les .php)
