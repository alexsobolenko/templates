# BOOK-STORAGE

## Цель

Создать эталонный шаблон веб-приложения с аутентификацией, ролями, CRUD-логикой и Docker-окружением, который можно использовать как стартовую точку для проектов на разных технологиях.

## Примерное описание

Сайт со списком книг. Нужна авторизация и регистрация с отправкой писем на почту. Модели (сущности) - книга, автор, пользователь. Неавторизованный пользователь может просматривать список авторов и книг и их детали. Авторизованный пользователь может добавлять в избранное авторов и книги. Авторизованный пользователь с правами администратора может добавлять новых авторов, книги и пользователей.

## Общие требования

### Бэк‑энд

Проект реализуется в нескольких вариантах, каждый из которых придерживается одинаковой предметной модели и бизнес-логики, но использует идиоматичный подход выбранного стека. Варианты реализации:
* Самописный MVC на чистом PHP 8.5
* Symfony 8
* Laravel 12
* Yii 2
* Ruby on Rails 8
* Golang

### Платформа Docker Compose 

1. web (обязательно php/ruby/go)
2. db (опционально mysql/postgres, потому что для sqlite не нужен отдельный контейнер)
3. nginx (обязательно)
4. mailcatcher (только для dev окружения)
5. redis (опционально при необходимости, сессии/кеш)

### СУБД

* MySQL / MariaDB
* PostgreSQL
* SQLite3

### Фронт‑энд

Серверный рендеринг (PHP / ERB / Twig / Blade / Go templates), CSS без сборщиков, JavaScript — только для улучшения UX (без SPA).

### Безопасность

* Регистрация
* Подтверждение почты
* Роль администратора
* Хеширование паролей
* CSRF
* XSS защита
* Reset password
* Rate-limit

### Избранное

Авторизованный пользователь может сохранять книги и авторов в избранное

### Панель администратора

CRUD‑операции для книг, авторов, пользователей доступны только авторизованному пользователю с правами администратора

## Структура таблиц БД

Базовая структура БД описывает минимально необходимую предметную модель. В конкретных реализациях допускаются изменения схемы, обусловленные особенностями фреймворка, языка (например, система ролей Symfony, встроенные механизмы Laravel, conventions Rails) или СУБД, при условии сохранения эквивалентной бизнес-логики.

### `users`

| Поле |Тип | Обязательно | Индексы / ограничения |
|---|---|---|---|
| `id` | `BIGINT` / `UUID v7 BINARY` | да | PK |
| `email` | `VARCHAR(255)` | да | UNIQUE |
| `password_hash` | `VARCHAR(255)` | да | - |
| `username` | `VARCHAR(100)` | да | UNIQUE |
| `is_admin` | `BOOLEAN` | да | INDEX |
| `email_verified_at` | `TIMESTAMP` / `DATETIME_IMMUTABLE` | нет | INDEX |
| `verification_token` | `VARCHAR(255)` | нет | INDEX | 
| `created_at` | `TIMESTAMP` / `DATETIME_IMMUTABLE` | да | INDEX |
| `updated_at` | `TIMESTAMP` / `DATETIME_IMMUTABLE` | да | - |

#### Примечания
* `is_admin` — минималистичная альтернатива ролям.
* В Symfony/Laravel может быть заменено на roles-механизм.
* Пароль — только хеш, никаких plaintext даже «для примера».

### `authors`

| Поле |Тип | Обязательно | Индексы / ограничения |
|---|---|---|---|
| `id` | `BIGINT` / `UUID v7 BINARY` | да | PK |
| `name` | `VARCHAR(255)` | да | INDEX | 
| `created_at` | `TIMESTAMP` / `DATETIME_IMMUTABLE` | да | INDEX |
| `updated_at` | `TIMESTAMP` / `DATETIME_IMMUTABLE` | да | - |

### `books`

| Поле |Тип | Обязательно | Индексы / ограничения |
|---|---|---|---|
| `id` | `BIGINT` / `UUID v7 BINARY` | да | PK |
| `title` | `VARCHAR(255)` | да | INDEX | 
| `price` | `INTEGER` | да | - | 
| `preview` | `TEXT` | нет | - |
| `created_at` | `TIMESTAMP` / `DATETIME_IMMUTABLE` | да | INDEX |
| `updated_at` | `TIMESTAMP` / `DATETIME_IMMUTABLE` | да | - |

### `book_author_relations` (many-to-many)

| Поле |Тип | Обязательно | Индексы / ограничения |
|---|---|---|---|
| `book_id` | `BIGINT` / `UUID v7 BINARY` | да | FK + INDEX |
| `author_id` | `BIGINT` / `UUID v7 BINARY` | да | FK + INDEX |

#### Ограничения

* PK (`book_id`, `author_id`)
* UNIQUE (`book_id`, `author_id`)
* ON DELETE CASCADE

### `user_book_favs` (many-to-many)

| Поле |Тип | Обязательно | Индексы / ограничения |
|---|---|---|---|
| `book_id` | `BIGINT` / `UUID v7 BINARY` | да | FK + INDEX |
| `user_id` | `BIGINT` / `UUID v7 BINARY` | да | FK + INDEX |

#### Ограничения

* PK (`book_id`, `user_id`)
* UNIQUE (`book_id`, `user_id`)
* ON DELETE CASCADE

### `user_author_favs` (many-to-many)

| Поле |Тип | Обязательно | Индексы / ограничения |
|---|---|---|---|
| `author_id` | `BIGINT` / `UUID v7 BINARY` | да | FK + INDEX |
| `user_id` | `BIGINT` / `UUID v7 BINARY` | да | FK + INDEX |

#### Ограничения

* PK (`user_id`, `author_id`)
* UNIQUE (`user_id`, `author_id`)
* ON DELETE CASCADE

## Репозиторий

Каждая ветка репозитория представляет собой самостоятельный стартовый шаблон проекта, предназначенный для непосредственного использования в разработке. Ветка master содержит обобщённую документацию, описывающую концепции, требования и различия между реализациями.

* `master` - документация, описание
* `php-mysql` - чистый PHP + MySQL
* `php-postgres` - чистый PHP + PostgreSQL
* `php-sqlite` - чистый PHP + SQLite
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
