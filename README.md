# ist-demande

Application de gestion de toutes sortes de demandes auprès de l'établissement IST-D (relevés de notes, quitus, attestations, diplômes, légalisations, etc.).

Construite avec **Laravel 13**, **Vite** et **Tailwind CSS 4**.

---

## Prérequis

Installez ces outils avant de commencer :

| Outil | Version minimale | Vérifier avec |
| --- | --- | --- |
| PHP | 8.3 | `php -v` |
| Composer | 2.x | `composer -V` |
| Node.js + npm | Node 20 ou plus | `node -v` et `npm -v` |
| Git | — | `git --version` |
| MySQL / MariaDB *(optionnel)* | MySQL 8 ou MariaDB 10.6 | — |

Extensions PHP nécessaires (activées par défaut dans la plupart des installations, sinon à décommenter dans `php.ini`) :
`pdo_sqlite` ou `pdo_mysql`, `mbstring`, `openssl`, `fileinfo`, `curl`, `xml`, `zip`.

> Sous Windows, le plus simple est d'utiliser [Laragon](https://laragon.org/) ou [XAMPP](https://www.apachefriends.org/), qui fournissent PHP et MySQL.

---

## Installation

### 1. Récupérer le projet

```bash
git clone https://github.com/dahy123/ist-demande.git
cd ist-demande
```

*(Si vous avez téléchargé le ZIP, décompressez-le puis ouvrez un terminal dans le dossier.)*

### 2. Installer les dépendances

```bash
composer install
npm install
```

### 3. Créer le fichier d'environnement

```bash
# Linux / macOS / Git Bash
cp .env.example .env

# Windows (PowerShell / CMD)
copy .env.example .env
```

Puis générez la clé de l'application :

```bash
php artisan key:generate
```

### 4. Configurer la base de données

Choisissez **une** des deux options.

**Option A — SQLite (le plus simple, rien à installer)**

Laissez `DB_CONNECTION=sqlite` dans `.env`, puis créez le fichier de base :

```bash
# Linux / macOS / Git Bash
touch database/database.sqlite

# Windows (PowerShell)
New-Item database/database.sqlite
```

**Option B — MySQL / MariaDB**

1. Créez une base vide, par exemple `ist_demande`.
2. Modifiez ces lignes dans `.env` (et retirez les `#` devant) :

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ist_demande
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Créer les tables et les données de départ

```bash
php artisan migrate --seed
```

Cette commande crée toutes les tables et ajoute les données de référence (écoles, parcours, rôles, types de demande, tarifs, pièces requises) ainsi qu'un compte administrateur.

### 6. Compiler les fichiers front-end

```bash
npm run build
```

---

## Lancer l'application

```bash
php artisan serve
```

Ouvrez ensuite **http://localhost:8000** dans votre navigateur.

Pendant le développement, pour que les modifications CSS/JS se rechargent automatiquement, lancez en plus (dans un second terminal) :

```bash
npm run dev
```

Ou lancez tout d'un coup avec :

```bash
composer run dev
```

---

## Connexion

Un compte administrateur est créé par le seeder :

| Identifiant | Mot de passe |
| --- | --- |
| `admin` | `password` |

⚠️ Changez ce mot de passe après la première connexion (menu **Paramètres → Profil**).

---

## Commandes utiles

| Commande | Rôle |
| --- | --- |
| `php artisan migrate:fresh --seed` | Supprime toutes les tables et repart d'une base propre (**efface les données**) |
| `php artisan db:seed --class=ReferentielSeeder` | Recharge uniquement les données de référence (sans doublons) |
| `php artisan test` | Lance les tests |
| `php artisan optimize:clear` | Vide les caches (config, routes, vues) |

---

## Problèmes fréquents

- **`Unable to locate file in Vite manifest`** → lancez `npm run build` (ou `npm run dev`).
- **`No application encryption key has been specified`** → lancez `php artisan key:generate`.
- **`could not find driver`** → activez l'extension `pdo_sqlite` ou `pdo_mysql` dans votre `php.ini`.
- **`Database file at path ... does not exist`** → créez le fichier `database/database.sqlite` (voir étape 4, option A).
- **Erreur de connexion MySQL** → vérifiez que MySQL est démarré et que les identifiants dans `.env` sont corrects, puis lancez `php artisan config:clear`.
