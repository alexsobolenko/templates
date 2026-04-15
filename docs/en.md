# TODO-LIST 🇬🇧

## 🎯 Goal

Create a reference web application template with authentication, roles, CRUD logic, and a Docker environment, which can be used as a starting point for projects using different technologies.

The template should have the simplest possible domain model: a user manages a list of their tasks. This makes it possible to focus on the typical project structure, security, environment, migrations, tests, and idiomatic implementation of the chosen stack without complicating the domain model.

## 📖 Overview

A website with a list of tasks. Authentication and registration with email delivery are required. Models (entities) - user and task.

An unauthenticated user can register, log in, verify their email, and reset their password. An authenticated user can view, create, edit, complete, and delete their own tasks. An authenticated user with administrator rights can manage users and view all users' tasks.

## ⚙️ General Requirements

### 🖥️ Backend

The project is implemented in multiple variants, each following the same data model and business logic, but using idiomatic approaches of the chosen technology stack. Implementation options:
* Custom MVC on pure PHP 8.5
* Symfony
* Laravel
* Yii2
* Ruby on Rails
* Golang

### 🐳 Docker Compose Platform

1. `web` (required: php/ruby/go)
2. `db` (optional mysql/postgres, because SQLite does not require a separate container)
3. `nginx` (required)
4. `mailcatcher` (dev environment only)
5. `redis` (optional if needed, sessions/cache)

### 🗄️ Database

* MySQL / MariaDB
* PostgreSQL
* SQLite3

### 🌐 Frontend

Server-side rendering (PHP / ERB / Twig / Blade / Go templates), CSS without build tools, JavaScript only for UX enhancements (no SPA).

### 🔒 Security

* Registration
* Email verification
* Administrator role
* Password hashing
* CSRF
* XSS protection
* Reset password
* Rate-limit
* Checking task ownership by user

### ✅ Tasks

An authenticated user can manage only their own tasks:

* view their task list
* create a task
* edit a task
* mark a task as completed or not completed
* delete a task

### 🛠️ Admin Panel

CRUD operations for users are available only to an authenticated user with administrator rights.

The administrator can also view all users' tasks. Editing other users' tasks through the admin panel is allowed in specific implementations if this is clearly reflected in the interface and does not violate the base business logic.

## 📊 Database Table Structure

The base database structure defines the minimally required data model. In specific implementations, schema changes are allowed due to framework or language features (for example, Symfony roles system, Laravel built-in mechanisms, Rails conventions) or DBMS specifics, provided that equivalent business logic is maintained.

### `users`

| Field | Type | Required | Indexes / Constraints |
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
* `is_admin` is a minimal alternative to roles.
* In Symfony/Laravel, it may be replaced with a roles mechanism.
* Passwords must be stored only as hashes, never as plaintext even "for an example".
* If a framework provides a ready-made user model, it may be used while preserving the requirements for registration, email verification, password reset, and administrator role.

### `tasks`

| Field | Type | Required | Indexes / Constraints |
|---|---|---|---|
| `id` | `BIGINT` / `UUID v7 BINARY` | yes | PK |
| `user_id` | `BIGINT` / `UUID v7 BINARY` | yes | FK + INDEX |
| `title` | `VARCHAR(255)` | yes | INDEX |
| `description` | `TEXT` | no | - |
| `is_completed` | `BOOLEAN` | yes | INDEX |
| `due_at` | `TIMESTAMP` / `DATETIME_IMMUTABLE` | no | INDEX |
| `created_at` | `TIMESTAMP` / `DATETIME_IMMUTABLE` | yes | INDEX |
| `updated_at` | `TIMESTAMP` / `DATETIME_IMMUTABLE` | yes | - |

#### Constraints

* FK (`user_id`) → `users.id`
* ON DELETE CASCADE

## 📁 Repository

Each branch of the repository represents an independent starter project template, intended for direct use in development. The master branch contains generalized documentation describing concepts, requirements, and differences between implementations.

* `master` - documentation, description
* `php-mysql` - pure PHP + MySQL
* `php-postgres` - pure PHP + PostgreSQL
* `php-sqlite` - pure PHP + SQLite
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
