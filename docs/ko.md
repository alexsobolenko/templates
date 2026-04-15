# TODO-LIST 🇰🇷

## 🎯 목표

인증, 역할, CRUD 로직, Docker 환경을 갖춘 기준 웹 애플리케이션 템플릿을 만들고, 다양한 기술로 만든 프로젝트의 시작점으로 사용할 수 있게 한다.

템플릿의 도메인은 최대한 단순해야 한다. 사용자는 자신의 작업 목록을 관리한다. 이렇게 하면 도메인 모델을 복잡하게 만들지 않고도 일반적인 프로젝트 구조, 보안, 환경, 마이그레이션, 테스트, 선택한 스택의 관용적인 구현에 집중할 수 있다.

## 📖 예시 설명

작업 목록이 있는 사이트. 이메일 발송을 포함한 인증과 회원가입이 필요하다. 모델(엔티티) - 사용자와 작업.

인증되지 않은 사용자는 회원가입, 로그인, 이메일 확인, 비밀번호 복구를 할 수 있다. 인증된 사용자는 자신의 작업을 조회, 생성, 수정, 완료 처리, 삭제할 수 있다. 관리자 권한이 있는 인증된 사용자는 사용자를 관리하고 모든 사용자의 작업을 조회할 수 있다.

## ⚙️ 일반 요구사항

### 🖥️ 백엔드

프로젝트는 여러 변형으로 구현된다. 각 변형은 동일한 도메인 모델과 비즈니스 로직을 따르지만, 선택한 기술 스택의 관용적인 접근 방식을 사용한다. 구현 변형:
* 순수 PHP 8.5 기반 자체 MVC
* Symfony
* Laravel
* Yii2
* Ruby on Rails
* Golang

### 🐳 Docker Compose 플랫폼

1. `web` (필수 php/ruby/go)
2. `db` (선택 mysql/postgres, SQLite는 별도 컨테이너가 필요 없기 때문)
3. `nginx` (필수)
4. `mailcatcher` (dev 환경에서만 사용)
5. `redis` (필요한 경우 선택, 세션/캐시)

### 🗄️ DBMS

* MySQL / MariaDB
* PostgreSQL
* SQLite3

### 🌐 프론트엔드

서버 사이드 렌더링(PHP / ERB / Twig / Blade / Go templates), 빌드 도구 없는 CSS, UX 개선만을 위한 JavaScript(SPA 아님).

### 🔒 보안

* 회원가입
* 이메일 확인
* 관리자 역할
* 비밀번호 해싱
* CSRF
* XSS 보호
* Reset password
* Rate-limit
* 작업이 사용자에게 속하는지 확인

### ✅ 작업

인증된 사용자는 자신의 작업만 관리할 수 있다:

* 자신의 작업 목록 조회
* 작업 생성
* 작업 수정
* 작업을 완료 또는 미완료로 표시
* 작업 삭제

### 🛠️ 관리자 패널

사용자 CRUD 작업은 관리자 권한이 있는 인증된 사용자에게만 제공된다.

관리자는 모든 사용자의 작업도 조회할 수 있다. 관리자 패널을 통한 다른 사용자의 작업 수정은, 인터페이스에 명확히 표시되고 기본 비즈니스 로직을 위반하지 않는다면 구체적인 구현에서 허용된다.

## 📊 데이터베이스 테이블 구조

기본 데이터베이스 구조는 최소한으로 필요한 도메인 모델을 설명한다. 구체적인 구현에서는 프레임워크, 언어 특성(예: Symfony 역할 시스템, Laravel 내장 메커니즘, Rails 관례) 또는 DBMS 특성으로 인한 스키마 변경이 허용되지만, 동등한 비즈니스 로직은 유지되어야 한다.

### `users`

| 필드 | 타입 | 필수 | 인덱스 / 제약 |
|---|---|---|---|
| `id` | `BIGINT` / `UUID v7 BINARY` | 예 | PK |
| `email` | `VARCHAR(255)` | 예 | UNIQUE |
| `password_hash` | `VARCHAR(255)` | 예 | - |
| `username` | `VARCHAR(100)` | 예 | UNIQUE |
| `is_admin` | `BOOLEAN` | 예 | INDEX |
| `email_verified_at` | `TIMESTAMP` / `DATETIME_IMMUTABLE` | 아니오 | INDEX |
| `verification_token` | `VARCHAR(255)` | 아니오 | INDEX |
| `created_at` | `TIMESTAMP` / `DATETIME_IMMUTABLE` | 예 | INDEX |
| `updated_at` | `TIMESTAMP` / `DATETIME_IMMUTABLE` | 예 | - |

#### 참고
* `is_admin`은 역할에 대한 최소한의 대안이다.
* Symfony/Laravel에서는 roles 메커니즘으로 대체할 수 있다.
* 비밀번호는 반드시 해시만 저장해야 하며, "예시용"이라도 plaintext는 없어야 한다.
* 프레임워크가 준비된 사용자 모델을 제공하는 경우, 회원가입, 이메일 확인, 비밀번호 복구, 관리자 역할 요구사항을 유지한다면 사용할 수 있다.

### `tasks`

| 필드 | 타입 | 필수 | 인덱스 / 제약 |
|---|---|---|---|
| `id` | `BIGINT` / `UUID v7 BINARY` | 예 | PK |
| `user_id` | `BIGINT` / `UUID v7 BINARY` | 예 | FK + INDEX |
| `title` | `VARCHAR(255)` | 예 | INDEX |
| `description` | `TEXT` | 아니오 | - |
| `is_completed` | `BOOLEAN` | 예 | INDEX |
| `due_at` | `TIMESTAMP` / `DATETIME_IMMUTABLE` | 아니오 | INDEX |
| `created_at` | `TIMESTAMP` / `DATETIME_IMMUTABLE` | 예 | INDEX |
| `updated_at` | `TIMESTAMP` / `DATETIME_IMMUTABLE` | 예 | - |

#### 제약

* FK (`user_id`) → `users.id`
* ON DELETE CASCADE

## 📁 저장소

저장소의 각 브랜치는 개발에서 바로 사용할 수 있는 독립적인 시작 프로젝트 템플릿이다. master 브랜치는 개념, 요구사항, 구현 간 차이를 설명하는 일반 문서를 포함한다.

* `master` - 문서, 설명
* `php-mysql` - 순수 PHP + MySQL
* `php-postgres` - 순수 PHP + PostgreSQL
* `php-sqlite` - 순수 PHP + SQLite
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
