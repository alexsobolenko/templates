# BOOK-STORAGE

## Goal

Create a reference web application template with authentication, roles, CRUD logic, and a Docker environment, which can be used as a starting point for projects using different technologies.

## Overview

A website with a list of books. User authentication and registration with email confirmation are required. Models (entities) — book, author, user. Unauthenticated users can view lists of authors and books and their details. Authenticated users can add books and authors to favorites. Authenticated users with administrator rights can add new authors, books, and users.

## General Requirements

### Backend

The project is implemented in multiple variants, each following the same data model and business logic, but using idiomatic approaches of the chosen technology stack. Implementation options:
* Custom MVC on pure PHP 8.5
* Symfony
* Laravel
* Yii2
* Ruby on Rails
* Golang

### Docker Compose Platform

* `web` (required: PHP / Ruby / Go)
* `db` (optional: MySQL / PostgreSQL; SQLite does not require a separate container)
* `nginx` (required)
* `mailcatcher` (only for dev environment)
* `redis` (optional, if needed for sessions / cache)

### Database (DBMS)

* MySQL / MariaDB
* PostgreSQL
* SQLite3

### Frontend

Server-side rendering (PHP / ERB / Twig / Blade / Go templates), CSS without build tools, JavaScript only for UX enhancements (no SPA).

### Security

* Registration
* Email verification
* Administrator role
* Password hashing
* CSRF protection
* XSS protection
* Password reset
* Rate-limiting

### Favorites

Authenticated users can save books and authors as favorites.

### Admin Panel

CRUD operations for books, authors, and users are available only to authenticated users with administrator rights.

## Database Table Structure

The base database structure defines the minimally required data model. In specific implementations, schema changes are allowed due to framework or language features (for example, Symfony roles system, Laravel built-in mechanisms, Rails conventions) or DBMS peculiarities, provided that equivalent business logic is maintained.

### `users`

| Field | Type | Required | Index / Constraints |
|---|---|---|---|
| `id` | `BIGINT` / `UUID v7 BINARY` | yes | PK |
| `email` | `VARCHAR(255)` | yes | UNIQUE |
| `password_hash` | `VARCHAR(255)` | yes | - |
| `username` | `VARCHAR(100)` | yes | UNIQUE |
| `is_admin` | `BOOLEAN` | yes | INDEX |
| `email_verified_at` | `TIMESTAMP` / `DATETIME_IMMUTABLE` | no | INDEX |
| `verification_token` | `VARCHAR(255)` | no | INDEX |
| `created_at` | `TIMESTAMP` / `DATETIME_IMMUTABLE` | yes | INDEX |
| `updated_at` | `TIMESTAMP` / `DATETIME_IMMUTABLE` | yes | - |

#### Notes

* `is_admin` is a minimal alternative to a roles system.
* In Symfony / Laravel, it may be replaced with a roles mechanism.
* Passwords must be stored only as hashes; plaintext passwords are not allowed.

### `authors`

| Field | Type | Required | Index / Constraints |
|---|---|---|---|
| `id` | `BIGINT` / `UUID v7 BINARY` | yes | PK |
| `name` | `VARCHAR(255)` | yes | INDEX |
| `created_at` | `TIMESTAMP` / `DATETIME_IMMUTABLE` | yes | INDEX |
| `updated_at` | `TIMESTAMP` / `DATETIME_IMMUTABLE` | yes | - |

### `books`

| Field | Type | Required | Index / Constraints |
|---|---|---|---|
| `id` | `BIGINT` / `UUID v7 BINARY` | yes | PK |
| `title` | `VARCHAR(255)` | yes | INDEX |
| `price` | `INTEGER` | yes | - |
| `preview` | `TEXT` | no | - |
| `created_at` | `TIMESTAMP` / `DATETIME_IMMUTABLE` | yes | INDEX |
| `updated_at` | `TIMESTAMP` / `DATETIME_IMMUTABLE` | yes | - |

### `book_author_relations` (many-to-many)

| Field | Type | Required | Index / Constraints |
|---|---|---|---|
| `book_id` | `BIGINT` / `UUID v7 BINARY` | да | FK + INDEX |
| `author_id` | `BIGINT` / `UUID v7 BINARY` | да | FK + INDEX |

#### Constraints

* PK (`book_id`, `author_id`)
* UNIQUE (`book_id`, `author_id`)
* ON DELETE CASCADE

### `user_book_favs` (many-to-many)

| Field | Type | Required | Index / Constraints |
|---|---|---|---|
| `book_id` | `BIGINT` / `UUID v7 BINARY` | да | FK + INDEX |
| `user_id` | `BIGINT` / `UUID v7 BINARY` | да | FK + INDEX |

#### Constraints
* PK (`book_id`, `user_id`)
* UNIQUE (`book_id`, `user_id`)
* ON DELETE CASCADE

### `user_author_favs` (many-to-many)

| Field | Type | Required | Index / Constraints |
|---|---|---|---|
| `author_id` | `BIGINT` / `UUID v7 BINARY` | да | FK + INDEX |
| `user_id` | `BIGINT` / `UUID v7 BINARY` | да | FK + INDEX |

#### Constraints

* PK (`user_id`, `author_id`)
* UNIQUE (`user_id`, `author_id`)
* ON DELETE CASCADE

## Repository

Each branch of the repository represents an independent starter project template, intended for direct use in development. The master branch contains generalized documentation describing concepts, requirements, and differences between implementations.

* `master` – documentation
* `php-mysql` – pure PHP + MySQL
* `php-postgres` – pure PHP + PostgreSQL
* `php-sqlite` – pure PHP + SQLite
* `symfony-mysql` – Symfony + MySQL
* `symfony-postgres` – Symfony + PostgreSQL
* `symfony-sqlite` – Symfony + SQLite
* `yii2-mysql` – Yii2 + MySQL
* `yii2-postgres` – Yii2 + PostgreSQL
* `yii2-sqlite` – Yii2 + SQLite
* `laravel-mysql` – Laravel + MySQL
* `laravel-postgres` – Laravel + PostgreSQL
* `laravel-sqlite` – Laravel + SQLite
* `ruby-mysql` – Ruby on Rails + MySQL
* `ruby-postgres` – Ruby on Rails + PostgreSQL
* `ruby-sqlite` – Ruby on Rails + SQLite
* `go-mysql` – Golang + MySQL
* `go-postgres` – Golang + PostgreSQL
* `go-sqlite` – Golang + SQLite
