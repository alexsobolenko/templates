# TODO-LIST 🇩🇪

## 🎯 Ziel

Eine Referenzvorlage für eine Webanwendung mit Authentifizierung, Rollen, CRUD-Logik und Docker-Umgebung erstellen, die als Ausgangspunkt für Projekte mit unterschiedlichen Technologien verwendet werden kann.

Die Vorlage soll fachlich möglichst einfach sein: Ein Benutzer verwaltet seine Aufgabenliste. Dadurch kann man sich auf den typischen Projektaufbau, Sicherheit, Umgebung, Migrationen, Tests und die idiomatische Umsetzung des gewählten Stacks konzentrieren, ohne das Domänenmodell zu verkomplizieren.

## 📖 Beispielbeschreibung

Eine Website mit einer Aufgabenliste. Authentifizierung und Registrierung mit E-Mail-Versand sind erforderlich. Modelle (Entitäten) - Benutzer und Aufgabe.

Ein nicht authentifizierter Benutzer kann sich registrieren, anmelden, seine E-Mail-Adresse bestätigen und das Passwort zurücksetzen. Ein authentifizierter Benutzer kann seine eigenen Aufgaben anzeigen, erstellen, bearbeiten, abschließen und löschen. Ein authentifizierter Benutzer mit Administratorrechten kann Benutzer verwalten und die Aufgaben aller Benutzer anzeigen.

## ⚙️ Allgemeine Anforderungen

### 🖥️ Backend

Das Projekt wird in mehreren Varianten umgesetzt. Jede Variante hält sich an dasselbe Domänenmodell und dieselbe Geschäftslogik, verwendet aber den idiomatischen Ansatz des gewählten Technologie-Stacks. Implementierungsvarianten:
* Eigenes MVC auf reinem PHP 8.5
* Symfony
* Laravel
* Yii2
* Ruby on Rails
* Golang

### 🐳 Docker-Compose-Plattform

1. `web` (erforderlich php/ruby/go)
2. `db` (optional mysql/postgres, da SQLite keinen separaten Container benötigt)
3. `nginx` (erforderlich)
4. `mailcatcher` (nur für die Entwicklungsumgebung)
5. `redis` (optional bei Bedarf, Sitzungen/Cache)

### 🗄️ DBMS

* MySQL / MariaDB
* PostgreSQL
* SQLite3

### 🌐 Frontend

Serverseitiges Rendering (PHP / ERB / Twig / Blade / Go templates), CSS ohne Build-Tools, JavaScript nur zur Verbesserung der UX (kein SPA).

### 🔒 Sicherheit

* Registrierung
* E-Mail-Bestätigung
* Administratorrolle
* Passwort-Hashing
* CSRF
* XSS-Schutz
* Reset password
* Rate-limit
* Prüfung, ob Aufgaben dem Benutzer gehören

### ✅ Aufgaben

Ein authentifizierter Benutzer kann nur seine eigenen Aufgaben verwalten:

* die Liste seiner Aufgaben anzeigen
* eine Aufgabe erstellen
* eine Aufgabe bearbeiten
* eine Aufgabe als erledigt oder nicht erledigt markieren
* eine Aufgabe löschen

### 🛠️ Administrationsbereich

CRUD-Operationen für Benutzer sind nur für einen authentifizierten Benutzer mit Administratorrechten verfügbar.

Der Administrator kann außerdem die Aufgaben aller Benutzer anzeigen. Das Bearbeiten fremder Aufgaben über den Administrationsbereich ist in konkreten Implementierungen zulässig, wenn dies in der Oberfläche klar ersichtlich ist und die grundlegende Geschäftslogik nicht verletzt.

## 📊 Datenbanktabellenstruktur

Die grundlegende Datenbankstruktur beschreibt das minimal erforderliche Domänenmodell. In konkreten Implementierungen sind Schemaänderungen zulässig, die durch Besonderheiten des Frameworks, der Sprache (zum Beispiel Symfony-Rollensystem, eingebaute Laravel-Mechanismen, Rails-Konventionen) oder des DBMS bedingt sind, sofern die äquivalente Geschäftslogik erhalten bleibt.

### `users`

| Feld | Typ | Erforderlich | Indizes / Einschränkungen |
|---|---|---|---|
| `id` | `BIGINT` / `UUID v7 BINARY` | ja | PK |
| `email` | `VARCHAR(255)` | ja | UNIQUE |
| `password_hash` | `VARCHAR(255)` | ja | - |
| `username` | `VARCHAR(100)` | ja | UNIQUE |
| `is_admin` | `BOOLEAN` | ja | INDEX |
| `email_verified_at` | `TIMESTAMP` / `DATETIME_IMMUTABLE` | nein | INDEX |
| `verification_token` | `VARCHAR(255)` | nein | INDEX |
| `created_at` | `TIMESTAMP` / `DATETIME_IMMUTABLE` | ja | INDEX |
| `updated_at` | `TIMESTAMP` / `DATETIME_IMMUTABLE` | ja | - |

#### Hinweise
* `is_admin` ist eine minimalistische Alternative zu Rollen.
* In Symfony/Laravel kann es durch einen Rollenmechanismus ersetzt werden.
* Das Passwort darf nur als Hash gespeichert werden, kein plaintext, auch nicht "als Beispiel".
* Wenn das Framework ein fertiges Benutzermodell bereitstellt, darf es verwendet werden, sofern die Anforderungen an Registrierung, E-Mail-Bestätigung, Passwortwiederherstellung und Administratorrolle erhalten bleiben.

### `tasks`

| Feld | Typ | Erforderlich | Indizes / Einschränkungen |
|---|---|---|---|
| `id` | `BIGINT` / `UUID v7 BINARY` | ja | PK |
| `user_id` | `BIGINT` / `UUID v7 BINARY` | ja | FK + INDEX |
| `title` | `VARCHAR(255)` | ja | INDEX |
| `description` | `TEXT` | nein | - |
| `is_completed` | `BOOLEAN` | ja | INDEX |
| `due_at` | `TIMESTAMP` / `DATETIME_IMMUTABLE` | nein | INDEX |
| `created_at` | `TIMESTAMP` / `DATETIME_IMMUTABLE` | ja | INDEX |
| `updated_at` | `TIMESTAMP` / `DATETIME_IMMUTABLE` | ja | - |

#### Einschränkungen

* FK (`user_id`) → `users.id`
* ON DELETE CASCADE

## 📁 Repository

Jeder Branch des Repositorys ist eine eigenständige Startvorlage für ein Projekt, die direkt in der Entwicklung verwendet werden kann. Der master-Branch enthält allgemeine Dokumentation, die Konzepte, Anforderungen und Unterschiede zwischen den Implementierungen beschreibt.

* `master` - Dokumentation, Beschreibung
* `php-mysql` - reines PHP + MySQL
* `php-postgres` - reines PHP + PostgreSQL
* `php-sqlite` - reines PHP + SQLite
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
