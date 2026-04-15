# TODO-LIST 🇫🇷

## 🎯 Objectif

Créer un modèle de référence d'application web avec authentification, rôles, logique CRUD et environnement Docker, utilisable comme point de départ pour des projets basés sur différentes technologies.

Le modèle doit rester aussi simple que possible sur le plan métier : un utilisateur gère sa liste de tâches. Cela permet de se concentrer sur la structure habituelle du projet, la sécurité, l'environnement, les migrations, les tests et l'implémentation idiomatique de la pile choisie, sans complexifier le modèle de domaine.

## 📖 Description approximative

Un site avec une liste de tâches. L'authentification et l'inscription avec envoi d'e-mails sont nécessaires. Modèles (entités) - utilisateur et tâche.

Un utilisateur non authentifié peut s'inscrire, se connecter, confirmer son adresse e-mail et réinitialiser son mot de passe. Un utilisateur authentifié peut consulter, créer, modifier, terminer et supprimer ses propres tâches. Un utilisateur authentifié avec des droits d'administrateur peut gérer les utilisateurs et consulter les tâches de tous les utilisateurs.

## ⚙️ Exigences générales

### 🖥️ Backend

Le projet est réalisé en plusieurs variantes, chacune respectant le même modèle de domaine et la même logique métier, mais utilisant l'approche idiomatique de la pile choisie. Variantes d'implémentation :
* MVC maison en PHP 8.5 pur
* Symfony
* Laravel
* Yii2
* Ruby on Rails
* Golang

### 🐳 Plateforme Docker Compose

1. `web` (obligatoire php/ruby/go)
2. `db` (optionnel mysql/postgres, car SQLite n'a pas besoin d'un conteneur séparé)
3. `nginx` (obligatoire)
4. `mailcatcher` (uniquement pour l'environnement dev)
5. `redis` (optionnel si nécessaire, sessions/cache)

### 🗄️ SGBD

* MySQL / MariaDB
* PostgreSQL
* SQLite3

### 🌐 Frontend

Rendu côté serveur (PHP / ERB / Twig / Blade / Go templates), CSS sans outils de build, JavaScript uniquement pour améliorer l'UX (pas de SPA).

### 🔒 Sécurité

* Inscription
* Confirmation de l'e-mail
* Rôle d'administrateur
* Hachage des mots de passe
* CSRF
* Protection XSS
* Reset password
* Rate-limit
* Vérification de l'appartenance des tâches à l'utilisateur

### ✅ Tâches

Un utilisateur authentifié ne peut gérer que ses propres tâches :

* consulter la liste de ses tâches
* créer une tâche
* modifier une tâche
* marquer une tâche comme terminée ou non terminée
* supprimer une tâche

### 🛠️ Panneau d'administration

Les opérations CRUD sur les utilisateurs sont accessibles uniquement à un utilisateur authentifié disposant de droits d'administrateur.

L'administrateur peut également consulter les tâches de tous les utilisateurs. La modification des tâches d'autres utilisateurs via le panneau d'administration est autorisée dans les implémentations concrètes si cela est clairement indiqué dans l'interface et ne viole pas la logique métier de base.

## 📊 Structure des tables de la base de données

La structure de base de la base de données décrit le modèle de domaine minimal nécessaire. Dans les implémentations concrètes, des modifications de schéma sont autorisées si elles sont liées aux particularités du framework, du langage (par exemple, le système de rôles de Symfony, les mécanismes intégrés de Laravel, les conventions Rails) ou du SGBD, à condition de conserver une logique métier équivalente.

### `users`

| Champ | Type | Obligatoire | Index / contraintes |
|---|---|---|---|
| `id` | `BIGINT` / `UUID v7 BINARY` | oui | PK |
| `email` | `VARCHAR(255)` | oui | UNIQUE |
| `password_hash` | `VARCHAR(255)` | oui | - |
| `username` | `VARCHAR(100)` | oui | UNIQUE |
| `is_admin` | `BOOLEAN` | oui | INDEX |
| `email_verified_at` | `TIMESTAMP` / `DATETIME_IMMUTABLE` | non | INDEX |
| `verification_token` | `VARCHAR(255)` | non | INDEX |
| `created_at` | `TIMESTAMP` / `DATETIME_IMMUTABLE` | oui | INDEX |
| `updated_at` | `TIMESTAMP` / `DATETIME_IMMUTABLE` | oui | - |

#### Notes
* `is_admin` est une alternative minimaliste aux rôles.
* Dans Symfony/Laravel, il peut être remplacé par un mécanisme de rôles.
* Le mot de passe doit être uniquement un hash, aucun plaintext même "pour l'exemple".
* Si le framework fournit un modèle utilisateur prêt à l'emploi, il peut être utilisé à condition de conserver les exigences d'inscription, de confirmation de l'e-mail, de récupération du mot de passe et de rôle d'administrateur.

### `tasks`

| Champ | Type | Obligatoire | Index / contraintes |
|---|---|---|---|
| `id` | `BIGINT` / `UUID v7 BINARY` | oui | PK |
| `user_id` | `BIGINT` / `UUID v7 BINARY` | oui | FK + INDEX |
| `title` | `VARCHAR(255)` | oui | INDEX |
| `description` | `TEXT` | non | - |
| `is_completed` | `BOOLEAN` | oui | INDEX |
| `due_at` | `TIMESTAMP` / `DATETIME_IMMUTABLE` | non | INDEX |
| `created_at` | `TIMESTAMP` / `DATETIME_IMMUTABLE` | oui | INDEX |
| `updated_at` | `TIMESTAMP` / `DATETIME_IMMUTABLE` | oui | - |

#### Contraintes

* FK (`user_id`) → `users.id`
* ON DELETE CASCADE

## 📁 Dépôt

Chaque branche du dépôt représente un modèle de projet de départ indépendant, destiné à être utilisé directement en développement. La branche master contient une documentation générale décrivant les concepts, les exigences et les différences entre les implémentations.

* `master` - documentation, description
* `php-mysql` - PHP pur + MySQL
* `php-postgres` - PHP pur + PostgreSQL
* `php-sqlite` - PHP pur + SQLite
* `symfony-mysql` - Symfony + MySQL
* `symfony-postgres` - Symfony + PostgreSQL
* `symfony-sqlite` - Symfony + SQLite
* `yii2-mysql` - Yii2 + MySQL
* `yii2-postgres` - Yii2 + PostgreSQL
* `yii2-sqlite` - Yii2 + SQLite
* `laravel-mysql` - Laravel + MySQL
* `laravel-postgres` - Laravel + PostgreSQL
* `laravel-sqlite` - Laravel + SQLite
* `ruby-mysql` - Ruby on Rails + MySQL
* `ruby-postgres` - Ruby on Rails + PostgreSQL
* `ruby-sqlite` - Ruby on Rails + SQLite
* `go-mysql` - Golang + MySQL
* `go-postgres` - Golang + PostgreSQL
* `go-sqlite` - Golang + SQLite
