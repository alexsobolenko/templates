# BOOK-STORAGE 🇨🇳

## 🎯 目标

创建一个参考网页应用模板，具有身份验证、角色管理、CRUD逻辑和 Docker 环境，可作为使用不同技术栈项目的起点。

## 📖 概述

一个显示书籍列表的网站。需要用户注册和登录，并通过邮件进行验证。模型（实体）：书籍、作者、用户。未登录用户可以查看作者和书籍列表及其详细信息。登录用户可以将书籍和作者添加到收藏夹。拥有管理员权限的用户可以添加新的作者、书籍和用户。

## ⚙️ 通用要求

### 🖥️ 后端

该项目有多种实现，每种实现遵循相同的数据模型和业务逻辑，但使用所选技术栈的惯用方法。实现选项：
* 自定义 MVC（纯 PHP 8.5）
* Symfony 
* Laravel
* Yii2
* Ruby on Rails 
* Golang

### 🐳 Docker Compose 平台

* `web` （必需：PHP / Ruby / Go）
* `db` （可选：MySQL / PostgreSQL；SQLite 不需要单独容器）
* `nginx` （必需）
* `mailcatcher` （仅用于开发环境）
* `redis` （可选，如需用于会话/缓存）

### 🗄️ 数据库

* MySQL / MariaDB
* PostgreSQL
* SQLite3

### 🌐 前端

服务端渲染（PHP / ERB / Twig / Blade / Go templates），CSS 无构建工具，JavaScript 仅用于提升用户体验（非 SPA）。

### 🔒 安全

* 注册
* 邮件验证
* 管理员角色
* 密码哈希
* CSRF 保护
* XSS 保护
* 密码重置
* 速率限制（Rate-limit）

### ⭐ 收藏

登录用户可以将书籍和作者添加到收藏夹。

### 🛠️ 管理面板

书籍、作者和用户的 CRUD 操作仅对拥有管理员权限的登录用户开放。

## 📊 数据库表结构

最小数据库结构如下，可在具体实现中根据框架或 DBMS 特性进行调整，但必须保持等效的业务逻辑。

### `users`

| 字段 | 类型 | 必填 | 索引 / 约束 |
|---|---|---|---|
| `id` | `BIGINT` / `UUID v7 BINARY` | 是 | PK |
| `email` | `VARCHAR(255)` | 是 | UNIQUE |
| `password_hash` | `VARCHAR(255)` | 是 | - |
| `username` | `VARCHAR(100)` | 是 | UNIQUE |
| `is_admin` | `BOOLEAN` | 是 | INDEX |
| `email_verified_at` | `TIMESTAMP` / `DATETIME_IMMUTABLE` | 否 | INDEX |
| `verification_token` | `VARCHAR(255)` | 否 | INDEX |
| `created_at` | `TIMESTAMP` / `DATETIME_IMMUTABLE` | 是 | INDEX |
| `updated_at` | `TIMESTAMP` / `DATETIME_IMMUTABLE` | 是 | - |

#### Notes

* `is_admin` 是一个简化的角色系统替代方案。
* 在 Symfony / Laravel 中，它可以被内置的 roles 机制替代。
* 密码必须只存储为哈希值；不允许存储明文密码。

### `authors`

| 字段 | 类型 | 必填 | 索引 / 约束 |
|---|---|---|---|
| `id` | `BIGINT` / `UUID v7 BINARY` | 是 | PK |
| `name` | `VARCHAR(255)` | 是 | INDEX |
| `created_at` | `TIMESTAMP` / `DATETIME_IMMUTABLE` | 是 | INDEX |
| `updated_at` | `TIMESTAMP` / `DATETIME_IMMUTABLE` | 是 | - |

### `books`

| 字段 | 类型 | 必填 | 索引 / 约束 |
|---|---|---|---|
| `id` | `BIGINT` / `UUID v7 BINARY` | 是 | PK |
| `title` | `VARCHAR(255)` | 是 | INDEX |
| `price` | `INTEGER` | 是 | - |
| `preview` | `TEXT` | 否 | - |
| `created_at` | `TIMESTAMP` / `DATETIME_IMMUTABLE` | 是 | INDEX |
| `updated_at` | `TIMESTAMP` / `DATETIME_IMMUTABLE` | 是 | - |

### `book_author_relations` (多对多)

| 字段 | 类型 | 必填 | 索引 / 约束 |
|---|---|---|---|
| `book_id` | `BIGINT` / `UUID v7 BINARY` | 是 | FK + INDEX |
| `author_id` | `BIGINT` / `UUID v7 BINARY` | 是 | FK + INDEX |

#### 约束

* PK (`book_id`, `author_id`)
* UNIQUE (`book_id`, `author_id`)
* ON DELETE CASCADE

### `user_book_favs` (多对多)

| 字段 | 类型 | 必填 | 索引 / 约束 |
|---|---|---|---|
| `book_id` | `BIGINT` / `UUID v7 BINARY` | 是 | FK + INDEX |
| `user_id` | `BIGINT` / `UUID v7 BINARY` | 是 | FK + INDEX |

#### 约束

* PK (`book_id`, `user_id`)
* UNIQUE (`book_id`, `user_id`)
* ON DELETE CASCADE

### `user_author_favs` (多对多)

| 字段 | 类型 | 必填 | 索引 / 约束 |
|---|---|---|---|
| `author_id` | `BIGINT` / `UUID v7 BINARY` | 是 | FK + INDEX |
| `user_id` | `BIGINT` / `UUID v7 BINARY` | 是 | FK + INDEX |

#### 约束

* PK (`author_id`, `user_id`)
* UNIQUE (`author_id`, `user_id`)
* ON DELETE CASCADE

## 📁 仓库分支

每个分支代表一个独立的可用模板项目。master 分支包含通用文档，描述概念、需求和不同实现之间的差异。

* `master` – Documentation
* `php-mysql` – PHP 纯实现 + MySQL
* `php-postgres` – PHP 纯实现 + PostgreSQL
* `php-sqlite` – PHP 纯实现 + SQLite
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
