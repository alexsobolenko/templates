# BOOK-STORAGE 🇪🇸

## 🎯 Objetivo

Crear una plantilla de aplicación web de referencia con autenticación, roles, lógica CRUD y entorno Docker, que pueda usarse como punto de partida para proyectos utilizando diferentes tecnologías.

## 📖 Resumen

Un sitio web con una lista de libros. Se requiere autenticación y registro de usuarios con confirmación por correo electrónico. Modelos (entidades): libro, autor, usuario. Los usuarios no autenticados pueden ver listas de autores y libros y sus detalles. Los usuarios autenticados pueden agregar libros y autores a favoritos. Los usuarios autenticados con derechos de administrador pueden agregar nuevos autores, libros y usuarios.

## ⚙️ Requisitos Generales

### 🖥️ Backend

El proyecto se implementa en varias variantes, cada una siguiendo el mismo modelo de datos y lógica de negocio, pero utilizando los enfoques idiomáticos de la pila tecnológica elegida. Opciones de implementación:
* MVC personalizado en PHP puro 8.5
* Symfony
* Laravel
* Yii2
* Ruby on Rails
* Golang

### 🐳 Plataforma Docker Compose

* `web` (obligatorio: PHP / Ruby / Go)
* `db` (opcional: MySQL / PostgreSQL; SQLite no requiere un contenedor separado)
* `nginx` (obligatorio)
* `mailcatcher` (solo para entorno de desarrollo)
* `redis` (opcional, si se necesita para sesiones / caché)

### 🗄️ Base de Datos (DBMS)

* MySQL / MariaDB
* PostgreSQL
* SQLite3

## 🌐 Frontend

Renderizado en el servidor (PHP / ERB / Twig / Blade / plantillas Go), CSS sin herramientas de compilación, JavaScript solo para mejoras de UX (sin SPA).

## 🔒 Seguridad

* Registro de usuario
* Confirmación de correo electrónico
* Rol de administrador
* Hash de contraseñas
* Protección CSRF
* Protección XSS
* Restablecimiento de contraseña
* Limitación de tasa (rate-limiting)

## ⭐ Favoritos

Los usuarios autenticados pueden guardar libros y autores como favoritos.

## 🛠️ Panel de Administrador

Operaciones CRUD para libros, autores y usuarios disponibles solo para usuarios autenticados con derechos de administrador.

## 📊 Estructura de Tablas de Base de Datos

La estructura básica de la base de datos define el modelo de datos mínimo requerido. En implementaciones específicas, se permiten cambios en el esquema debido a características del framework o lenguaje (por ejemplo, sistema de roles en Symfony, mecanismos integrados en Laravel, convenciones de Rails) o particularidades del DBMS, siempre que se mantenga una lógica de negocio equivalente.

### `users`

| Campo | Tipo | Obligatorio | Índices / Restricciones |
|---|---|---|---|
| `id` | `BIGINT` / `UUID v7 BINARY` | sí | PK |
| `email` | `VARCHAR(255)` | sí | UNIQUE |
| `password_hash` | `VARCHAR(255)` | sí | - |
| `username` | `VARCHAR(100)` | sí | UNIQUE |
| `is_admin` | `BOOLEAN` | sí | INDEX |
| `email_verified_at` | `TIMESTAMP` / `DATETIME_IMMUTABLE` | no | INDEX |
| `verification_token` | `VARCHAR(255)` | no | INDEX |
| `created_at` | `TIMESTAMP` / `DATETIME_IMMUTABLE` | sí | INDEX |
| `updated_at` | `TIMESTAMP` / `DATETIME_IMMUTABLE` | sí | - |

#### Notas
* `is_admin` es una alternativa mínima al sistema de roles.
* En Symfony / Laravel puede reemplazarse con un mecanismo de roles.
* Las contraseñas deben almacenarse solo como hashes; no se permite texto plano.

### `authors`

| Campo | Tipo | Obligatorio | Índices / Restricciones |
|---|---|---|---|
| `id` | `BIGINT` / `UUID v7 BINARY` | sí | PK |
| `name` | `VARCHAR(255)` | sí | INDEX |
| `created_at` | `TIMESTAMP` / `DATETIME_IMMUTABLE` | sí | INDEX |
| `updated_at` | `TIMESTAMP` / `DATETIME_IMMUTABLE` | sí | - |

### `books`

| Campo | Tipo | Obligatorio | Índices / Restricciones |
|---|---|---|---|
| `id` | `BIGINT` / `UUID v7 BINARY` | sí | PK |
| `title` | `VARCHAR(255)` | sí | INDEX |
| `price` | `INTEGER` | sí | - |
| `preview` | `TEXT` | no | - |
| `created_at` | `TIMESTAMP` / `DATETIME_IMMUTABLE` | sí | INDEX |
| `updated_at` | `TIMESTAMP` / `DATETIME_IMMUTABLE` | sí | - |

### `book_author_relations` (many-to-many)

| Campo | Tipo | Obligatorio | Índices / Restricciones |
|---|---|---|---|
| `book_id` | `BIGINT` / `UUID v7 BINARY` | sí | FK + INDEX |
| `author_id` | `BIGINT` / `UUID v7 BINARY` | sí | FK + INDEX |

#### Restricciones

* PK (`book_id`, `author_id`)
* UNIQUE (`book_id`, `author_id`)
* ON DELETE CASCADE

### `user_book_favs` (many-to-many)

| Campo | Tipo | Obligatorio | Índices / Restricciones |
|---|---|---|---|
| `book_id` | `BIGINT` / `UUID v7 BINARY` | yes | FK + INDEX |
| `user_id` | `BIGINT` / `UUID v7 BINARY` | yes | FK + INDEX |

#### Restricciones
* PK (`book_id`, `user_id`)
* UNIQUE (`book_id`, `user_id`)
* ON DELETE CASCADE

### `user_author_favs` (many-to-many)

| Campo | Tipo | Obligatorio | Índices / Restricciones |
|---|---|---|---|
| `author_id` | `BIGINT` / `UUID v7 BINARY` | yes | FK + INDEX |
| `user_id` | `BIGINT` / `UUID v7 BINARY` | yes | FK + INDEX |

#### Restricciones

* PK (`user_id`, `author_id`)
* UNIQUE (`user_id`, `author_id`)
* ON DELETE CASCADE

## 📁 Repositorio

Cada rama del repositorio representa una plantilla de proyecto independiente, lista para usar en desarrollo. La rama master contiene documentación general que describe conceptos, requisitos y diferencias entre implementaciones.

* `master` – documentación
* `php-mysql` – PHP puro + MySQL
* `php-postgres` – PHP puro + PostgreSQL
* `php-sqlite` – PHP puro + SQLite
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
