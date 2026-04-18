# TODO-LIST PHP + MySQL

## 🚀 Create Project

Create a new project from this branch:

```bash
git clone --branch php-mysql --single-branch https://github.com/alexsobolenko/templates.git my-project
cd my-project
```

To make the new project independent from the template repository:

```bash
rm -rf .git
git init
git add .
git commit -m "Initial commit"
```

## 🎯 Goal

Create a simple reference web application template on pure PHP and MySQL with authentication, roles, CRUD logic, server-side rendering, and a Docker Compose development environment.

The template should have the simplest possible domain model: a user manages a list of their tasks. This makes it possible to focus on the typical project structure, security, routing, database access, SQL schema initialization, tests, and plain PHP implementation without complicating the domain model.

## 📖 Overview

A website with a list of tasks. Authentication and registration with email delivery are required. Models (entities) - user and task.

An unauthenticated user can register, log in, verify their email, and reset their password. An authenticated user can view, create, edit, complete, and delete their own tasks. An authenticated user with administrator rights can manage users and view all users' tasks.

## ⚙️ Technical Requirements

### 🖥️ Backend

The backend is implemented on pure PHP 8.5 without a full-stack framework.

The project should stay small, but it must include the basic infrastructure expected from a real starter template:

* a normal HTTP router
* controllers or controller-like request handlers
* a small PDO wrapper for database access
* plain PHP views/templates without third-party template engines
* simple middleware or equivalent request checks where useful
* SQL schema files for initial database setup
* seed data for development if useful

## 🔎 Static Analysis

The project uses:

* `PHPStan`
* `PHP_CodeSniffer`
* `Psalm`
* `PHPUnit`

Typical commands:

```bash
composer install
make lint
make test
```

### 🐳 Docker Compose Platform

1. `web` (PHP)
2. `db` (MySQL)
3. `nginx`
4. `mailcatcher` (dev environment only)

Redis, queues, search engines, asset builders, and other additional infrastructure are intentionally out of scope for this branch.

### 🗄️ Database

* MySQL

### 🌐 Frontend

Server-side rendering with plain PHP templates. CSS without build tools. JavaScript only for small UX enhancements (no SPA).

No Twig, Blade, Smarty, or other third-party template engines.

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

The administrator can also view all users' tasks. Editing other users' tasks through the admin panel is allowed if this is clearly reflected in the interface and does not violate the base business logic.

## 📊 Database Table Structure

The base database structure defines the minimally required data model for this PHP + MySQL implementation.

### `users`

| Field | Type | Required | Indexes / Constraints |
|---|---|---|---|
| `id` | `INTEGER` | yes | PK, AUTO_INCREMENT |
| `email` | `VARCHAR(255)` | yes | UNIQUE |
| `password_hash` | `VARCHAR(255)` | yes | - |
| `username` | `VARCHAR(100)` | yes | UNIQUE |
| `is_admin` | `BOOLEAN` | yes | INDEX |
| `email_verified_at` | `TIMESTAMP` | no | INDEX |
| `verification_token` | `VARCHAR(255)` | no | INDEX |
| `created_at` | `TIMESTAMP` | yes | INDEX |
| `updated_at` | `TIMESTAMP` | yes | - |

#### Notes
* `is_admin` is a minimal alternative to a full roles system.
* Passwords must be stored only as hashes, never as plaintext even "for an example".

### `tasks`

| Field | Type | Required | Indexes / Constraints |
|---|---|---|---|
| `id` | `INTEGER` | yes | PK, AUTO_INCREMENT |
| `user_id` | `INTEGER` | yes | FK + INDEX |
| `title` | `VARCHAR(255)` | yes | INDEX |
| `description` | `TEXT` | no | - |
| `is_completed` | `BOOLEAN` | yes | INDEX |
| `due_at` | `TIMESTAMP` | no | INDEX |
| `created_at` | `TIMESTAMP` | yes | INDEX |
| `updated_at` | `TIMESTAMP` | yes | - |

#### Constraints

* FK (`user_id`) → `users.id`
* ON DELETE CASCADE
