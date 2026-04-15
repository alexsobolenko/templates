# TODO-LIST 🇪🇸

## 🎯 Objetivo

Crear una plantilla de aplicación web de referencia con autenticación, roles, lógica CRUD y entorno Docker, que pueda usarse como punto de partida para proyectos con diferentes tecnologías.

La plantilla debe tener un modelo de dominio lo más simple posible: el usuario gestiona una lista de sus tareas. Esto permite centrarse en la estructura típica del proyecto, la seguridad, el entorno, las migraciones, las pruebas y la implementación idiomática del stack elegido, sin complicar el modelo de dominio.

## 📖 Descripción aproximada

Un sitio web con una lista de tareas. Se necesita autenticación y registro con envío de correos electrónicos. Modelos (entidades) - usuario y tarea.

Un usuario no autenticado puede registrarse, iniciar sesión, confirmar su correo electrónico y recuperar la contraseña. Un usuario autenticado puede ver, crear, editar, completar y eliminar sus propias tareas. Un usuario autenticado con derechos de administrador puede gestionar usuarios y ver las tareas de todos los usuarios.

## ⚙️ Requisitos generales

### 🖥️ Backend

El proyecto se implementa en varias variantes, cada una con el mismo modelo de dominio y lógica de negocio, pero usando un enfoque idiomático del stack tecnológico elegido. Opciones de implementación:
* MVC personalizado en PHP puro 8.5
* Symfony
* Laravel
* Yii2
* Ruby on Rails
* Golang

### 🐳 Plataforma Docker Compose

1. `web` (obligatorio php/ruby/go)
2. `db` (opcional mysql/postgres, porque SQLite no necesita un contenedor separado)
3. `nginx` (obligatorio)
4. `mailcatcher` (solo para el entorno de desarrollo)
5. `redis` (opcional si es necesario, sesiones/caché)

### 🗄️ DBMS

* MySQL / MariaDB
* PostgreSQL
* SQLite3

### 🌐 Frontend

Renderizado en el servidor (PHP / ERB / Twig / Blade / Go templates), CSS sin herramientas de compilación, JavaScript solo para mejoras de UX (sin SPA).

### 🔒 Seguridad

* Registro
* Confirmación de correo electrónico
* Rol de administrador
* Hashing de contraseñas
* CSRF
* Protección XSS
* Reset password
* Rate-limit
* Comprobación de que la tarea pertenece al usuario

### ✅ Tareas

Un usuario autenticado solo puede gestionar sus propias tareas:

* ver la lista de sus tareas
* crear una tarea
* editar una tarea
* marcar una tarea como completada o no completada
* eliminar una tarea

### 🛠️ Panel de administración

Las operaciones CRUD para usuarios están disponibles solo para un usuario autenticado con derechos de administrador.

El administrador también puede ver las tareas de todos los usuarios. La edición de tareas de otros usuarios desde el panel de administración se permite en implementaciones concretas si se refleja claramente en la interfaz y no rompe la lógica de negocio base.

## 📊 Estructura de tablas de la base de datos

La estructura base de la base de datos describe el modelo de dominio mínimo necesario. En implementaciones concretas se permiten cambios de esquema derivados de las características del framework, del lenguaje (por ejemplo, el sistema de roles de Symfony, mecanismos integrados de Laravel, convenciones de Rails) o del DBMS, siempre que se conserve una lógica de negocio equivalente.

### `users`

| Campo | Tipo | Obligatorio | Índices / restricciones |
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
* `is_admin` es una alternativa minimalista a los roles.
* En Symfony/Laravel puede sustituirse por un mecanismo de roles.
* La contraseña debe almacenarse solo como hash, nunca en texto plano ni siquiera "para el ejemplo".
* Si el framework proporciona un modelo de usuario listo para usar, se permite utilizarlo siempre que se conserven los requisitos de registro, confirmación de correo electrónico, recuperación de contraseña y rol de administrador.

### `tasks`

| Campo | Tipo | Obligatorio | Índices / restricciones |
|---|---|---|---|
| `id` | `BIGINT` / `UUID v7 BINARY` | sí | PK |
| `user_id` | `BIGINT` / `UUID v7 BINARY` | sí | FK + INDEX |
| `title` | `VARCHAR(255)` | sí | INDEX |
| `description` | `TEXT` | no | - |
| `is_completed` | `BOOLEAN` | sí | INDEX |
| `due_at` | `TIMESTAMP` / `DATETIME_IMMUTABLE` | no | INDEX |
| `created_at` | `TIMESTAMP` / `DATETIME_IMMUTABLE` | sí | INDEX |
| `updated_at` | `TIMESTAMP` / `DATETIME_IMMUTABLE` | sí | - |

#### Restricciones

* FK (`user_id`) → `users.id`
* ON DELETE CASCADE

## 📁 Repositorio

Cada rama del repositorio representa una plantilla de proyecto inicial independiente, destinada al uso directo en desarrollo. La rama master contiene documentación general que describe los conceptos, requisitos y diferencias entre implementaciones.

* `master` - documentación, descripción
* `php-mysql` - PHP puro + MySQL
* `php-postgres` - PHP puro + PostgreSQL
* `php-sqlite` - PHP puro + SQLite
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
