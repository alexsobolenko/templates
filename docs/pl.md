# BOOK-STORAGE 🇵🇱

## 🎯 Cel

Stworzenie szablonu aplikacji internetowej z uwierzytelnianiem, rolami, logiką CRUD i środowiskiem Docker, który może być używany jako punkt startowy dla projektów wykorzystujących różne technologie.

## 📖 Opis

Strona internetowa z listą książek. Wymagane jest uwierzytelnianie i rejestracja użytkowników z potwierdzeniem e-mail. Modele (encje) — książka, autor, użytkownik. Nieuwierzytelnieni użytkownicy mogą przeglądać listy autorów i książek oraz ich szczegóły. Uwierzytelnieni użytkownicy mogą dodawać książki i autorów do ulubionych. Uwierzytelnieni użytkownicy z uprawnieniami administratora mogą dodawać nowych autorów, książki i użytkowników.

## ⚙️ Wymagania ogólne

### 🖥️ Backend

Projekt jest zaimplementowany w wielu wariantach, z których każdy korzysta z tego samego modelu danych i logiki biznesowej, ale wykorzystuje idiomatyczne podejścia wybranego stosu technologicznego. Opcje implementacji:
* Niestandardowy MVC w czystym PHP 8.5
* Symfony
* Laravel
* Yii2
* Ruby on Rails
* Golang

### 🐳 Platforma Docker Compose

* `web` (wymagane: PHP / Ruby / Go)
* `db` (opcjonalne: MySQL / PostgreSQL; SQLite nie wymaga osobnego kontenera)
* `nginx` (wymagane)
* `mailcatcher` (tylko dla środowiska deweloperskiego)
* `redis` (opcjonalne, w razie potrzeby dla sesji / pamięci podręcznej)

### 🗄️ Baza danych (DBMS)

* MySQL / MariaDB
* PostgreSQL
* SQLite3

### 🌐 Frontend

Renderowanie po stronie serwera (PHP / ERB / Twig / Blade / szablony Go), CSS bez narzędzi budowania, JavaScript tylko dla ulepszeń UX (bez SPA).

### 🔒 Bezpieczeństwo

* Rejestracja
* Weryfikacja e-mail
* Rola administratora
* Hashowanie haseł
* Ochrona CSRF
* Ochrona XSS
* Resetowanie hasła
* Ograniczanie liczby żądań (rate-limiting)

### ⭐ Ulubione

Uwierzytelnieni użytkownicy mogą zapisywać książki i autorów jako ulubione.

### 🛠️ Panel administratora

Operacje CRUD dla książek, autorów i użytkowników są dostępne tylko dla uwierzytelnionych użytkowników z uprawnieniami administratora.

## 📊 Struktura tabel bazy danych

Podstawowa struktura bazy danych definiuje minimalnie wymagany model danych. W konkretnych implementacjach dozwolone są zmiany schematu wynikające z funkcji frameworka lub języka (na przykład system ról Symfony, wbudowane mechanizmy Laravel, konwencje Rails) lub specyfiki DBMS, pod warunkiem zachowania równoważnej logiki biznesowej.

### `users`

| Pole | Typ | Wymagane | Indeks / Ograniczenia |
|---|---|---|---|
| `id` | `BIGINT` / `UUID v7 BINARY` | tak | PK |
| `email` | `VARCHAR(255)` | tak | UNIQUE |
| `password_hash` | `VARCHAR(255)` | tak | - |
| `username` | `VARCHAR(100)` | tak | UNIQUE |
| `is_admin` | `BOOLEAN` | tak | INDEX |
| `email_verified_at` | `TIMESTAMP` / `DATETIME_IMMUTABLE` | nie | INDEX |
| `verification_token` | `VARCHAR(255)` | nie | INDEX |
| `created_at` | `TIMESTAMP` / `DATETIME_IMMUTABLE` | tak | INDEX |
| `updated_at` | `TIMESTAMP` / `DATETIME_IMMUTABLE` | tak | - |

#### Uwagi

* `is_admin` to minimalna alternatywa dla systemu ról.
* W Symfony / Laravel może zostać zastąpione mechanizmem ról.
* Hasła muszą być przechowywane tylko jako hashe; hasła w postaci jawnej są niedozwolone.

### `authors`

| Pole | Typ | Wymagane | Indeks / Ograniczenia |
|---|---|---|---|
| `id` | `BIGINT` / `UUID v7 BINARY` | tak | PK |
| `name` | `VARCHAR(255)` | tak | INDEX |
| `created_at` | `TIMESTAMP` / `DATETIME_IMMUTABLE` | tak | INDEX |
| `updated_at` | `TIMESTAMP` / `DATETIME_IMMUTABLE` | tak | - |

### `books`

| Pole | Typ | Wymagane | Indeks / Ograniczenia |
|---|---|---|---|
| `id` | `BIGINT` / `UUID v7 BINARY` | tak | PK |
| `title` | `VARCHAR(255)` | tak | INDEX |
| `price` | `INTEGER` | tak | - |
| `preview` | `TEXT` | nie | - |
| `created_at` | `TIMESTAMP` / `DATETIME_IMMUTABLE` | tak | INDEX |
| `updated_at` | `TIMESTAMP` / `DATETIME_IMMUTABLE` | tak | - |

### `book_author_relations` (wiele do wielu)

| Pole | Typ | Wymagane | Indeks / Ograniczenia |
|---|---|---|---|
| `book_id` | `BIGINT` / `UUID v7 BINARY` | tak | FK + INDEX |
| `author_id` | `BIGINT` / `UUID v7 BINARY` | tak | FK + INDEX |

#### Ograniczenia

* PK (`book_id`, `author_id`)
* UNIQUE (`book_id`, `author_id`)
* ON DELETE CASCADE

### `user_book_favs` (wiele do wielu)

| Pole | Typ | Wymagane | Indeks / Ograniczenia |
|---|---|---|---|
| `book_id` | `BIGINT` / `UUID v7 BINARY` | tak | FK + INDEX |
| `user_id` | `BIGINT` / `UUID v7 BINARY` | tak | FK + INDEX |

#### Ograniczenia
* PK (`book_id`, `user_id`)
* UNIQUE (`book_id`, `user_id`)
* ON DELETE CASCADE

### `user_author_favs` (wiele do wielu)

| Pole | Typ | Wymagane | Indeks / Ograniczenia |
|---|---|---|---|
| `author_id` | `BIGINT` / `UUID v7 BINARY` | tak | FK + INDEX |
| `user_id` | `BIGINT` / `UUID v7 BINARY` | tak | FK + INDEX |

#### Ograniczenia

* PK (`user_id`, `author_id`)
* UNIQUE (`user_id`, `author_id`)
* ON DELETE CASCADE

## 📁 Repozytorium

Każda gałąź repozytorium reprezentuje niezależny szablon projektu startowego, przeznaczony do bezpośredniego użycia w programowaniu. Gałąź master zawiera uogólnioną dokumentację opisującą koncepcje, wymagania i różnice między implementacjami.

* `master` – dokumentacja
* `php-mysql` – czysty PHP + MySQL
* `php-postgres` – czysty PHP + PostgreSQL
* `php-sqlite` – czysty PHP + SQLite
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