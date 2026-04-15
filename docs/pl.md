# TODO-LIST 🇵🇱

## 🎯 Cel

Stworzyć wzorcowy szablon aplikacji webowej z uwierzytelnianiem, rolami, logiką CRUD i środowiskiem Docker, który może być używany jako punkt startowy dla projektów w różnych technologiach.

Szablon powinien mieć możliwie najprostszy model dziedzinowy: użytkownik zarządza listą swoich zadań. Pozwala to skupić się na typowej strukturze projektu, bezpieczeństwie, środowisku, migracjach, testach i idiomatycznej implementacji wybranego stosu, bez komplikowania modelu dziedzinowego.

## 📖 Przykładowy opis

Strona z listą zadań. Potrzebne są uwierzytelnianie i rejestracja z wysyłaniem wiadomości e-mail. Modele (encje) - użytkownik i zadanie.

Nieuwierzytelniony użytkownik może się zarejestrować, zalogować, potwierdzić e-mail i odzyskać hasło. Uwierzytelniony użytkownik może przeglądać, tworzyć, edytować, kończyć i usuwać swoje zadania. Uwierzytelniony użytkownik z uprawnieniami administratora może zarządzać użytkownikami i przeglądać zadania wszystkich użytkowników.

## ⚙️ Wymagania ogólne

### 🖥️ Backend

Projekt jest realizowany w kilku wariantach, z których każdy trzyma się tego samego modelu dziedzinowego i logiki biznesowej, ale używa idiomatycznego podejścia wybranego stosu technologicznego. Warianty implementacji:
* Własny MVC w czystym PHP 8.5
* Symfony
* Laravel
* Yii2
* Ruby on Rails
* Golang

### 🐳 Platforma Docker Compose

1. `web` (wymagane php/ruby/go)
2. `db` (opcjonalnie mysql/postgres, ponieważ SQLite nie potrzebuje osobnego kontenera)
3. `nginx` (wymagane)
4. `mailcatcher` (tylko dla środowiska dev)
5. `redis` (opcjonalnie w razie potrzeby, sesje/cache)

### 🗄️ DBMS

* MySQL / MariaDB
* PostgreSQL
* SQLite3

### 🌐 Frontend

Renderowanie po stronie serwera (PHP / ERB / Twig / Blade / Go templates), CSS bez narzędzi budowania, JavaScript tylko do ulepszeń UX (bez SPA).

### 🔒 Bezpieczeństwo

* Rejestracja
* Potwierdzenie e-mail
* Rola administratora
* Hashowanie haseł
* CSRF
* Ochrona XSS
* Reset password
* Rate-limit
* Sprawdzanie przynależności zadań do użytkownika

### ✅ Zadania

Uwierzytelniony użytkownik może zarządzać tylko swoimi zadaniami:

* przeglądać listę swoich zadań
* tworzyć zadanie
* edytować zadanie
* oznaczać zadanie jako wykonane lub niewykonane
* usuwać zadanie

### 🛠️ Panel administratora

Operacje CRUD dla użytkowników są dostępne tylko dla uwierzytelnionego użytkownika z uprawnieniami administratora.

Administrator może także przeglądać zadania wszystkich użytkowników. Edycja cudzych zadań przez panel administratora jest dopuszczalna w konkretnych implementacjach, jeśli jest to jasno odzwierciedlone w interfejsie i nie narusza bazowej logiki biznesowej.

## 📊 Struktura tabel bazy danych

Podstawowa struktura bazy danych opisuje minimalny wymagany model dziedzinowy. W konkretnych implementacjach dopuszcza się zmiany schematu wynikające z cech frameworka, języka (na przykład system ról Symfony, wbudowane mechanizmy Laravel, konwencje Rails) lub DBMS, pod warunkiem zachowania równoważnej logiki biznesowej.

### `users`

| Pole | Typ | Wymagane | Indeksy / ograniczenia |
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
* `is_admin` to minimalistyczna alternatywa dla ról.
* W Symfony/Laravel może zostać zastąpione mechanizmem ról.
* Hasło - tylko hash, bez plaintext nawet "dla przykładu".
* Jeśli framework zapewnia gotowy model użytkownika, można go użyć przy zachowaniu wymagań dotyczących rejestracji, potwierdzenia e-mail, odzyskiwania hasła i roli administratora.

### `tasks`

| Pole | Typ | Wymagane | Indeksy / ograniczenia |
|---|---|---|---|
| `id` | `BIGINT` / `UUID v7 BINARY` | tak | PK |
| `user_id` | `BIGINT` / `UUID v7 BINARY` | tak | FK + INDEX |
| `title` | `VARCHAR(255)` | tak | INDEX |
| `description` | `TEXT` | nie | - |
| `is_completed` | `BOOLEAN` | tak | INDEX |
| `due_at` | `TIMESTAMP` / `DATETIME_IMMUTABLE` | nie | INDEX |
| `created_at` | `TIMESTAMP` / `DATETIME_IMMUTABLE` | tak | INDEX |
| `updated_at` | `TIMESTAMP` / `DATETIME_IMMUTABLE` | tak | - |

#### Ograniczenia

* FK (`user_id`) → `users.id`
* ON DELETE CASCADE

## 📁 Repozytorium

Każda gałąź repozytorium reprezentuje samodzielny startowy szablon projektu przeznaczony do bezpośredniego użycia w programowaniu. Gałąź master zawiera uogólnioną dokumentację opisującą koncepcje, wymagania i różnice między implementacjami.

* `master` - dokumentacja, opis
* `php-mysql` - czysty PHP + MySQL
* `php-postgres` - czysty PHP + PostgreSQL
* `php-sqlite` - czysty PHP + SQLite
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
