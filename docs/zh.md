# TODO-LIST 🇨🇳

## 🎯 目标

创建一个带有身份认证、角色、CRUD 逻辑和 Docker 环境的参考 Web 应用模板，可作为使用不同技术的项目起点。

模板的业务领域应尽可能简单：用户管理自己的任务列表。这样可以专注于典型项目结构、安全性、运行环境、迁移、测试以及所选技术栈的惯用实现，而不让领域模型变复杂。

## 📖 大致说明

一个任务列表网站。需要身份认证和注册，并发送电子邮件。模型（实体）- 用户和任务。

未认证用户可以注册、登录、确认邮箱并重置密码。已认证用户可以查看、创建、编辑、完成和删除自己的任务。拥有管理员权限的已认证用户可以管理用户并查看所有用户的任务。

## ⚙️ 通用要求

### 🖥️ 后端

项目实现为多个变体，每个变体遵循相同的领域模型和业务逻辑，但使用所选技术栈的惯用方式。实现选项：
* 纯 PHP 8.5 自定义 MVC
* Symfony
* Laravel
* Yii2
* Ruby on Rails
* Golang

### 🐳 Docker Compose 平台

1. `web`（必需 php/ruby/go）
2. `db`（可选 mysql/postgres，因为 SQLite 不需要单独容器）
3. `nginx`（必需）
4. `mailcatcher`（仅用于 dev 环境）
5. `redis`（按需可选，会话/缓存）

### 🗄️ DBMS

* MySQL / MariaDB
* PostgreSQL
* SQLite3

### 🌐 前端

服务端渲染（PHP / ERB / Twig / Blade / Go templates），CSS 不使用构建工具，JavaScript 仅用于改善 UX（非 SPA）。

### 🔒 安全

* 注册
* 邮箱确认
* 管理员角色
* 密码哈希
* CSRF
* XSS 保护
* Reset password
* Rate-limit
* 检查任务是否属于当前用户

### ✅ 任务

已认证用户只能管理自己的任务：

* 查看自己的任务列表
* 创建任务
* 编辑任务
* 将任务标记为已完成或未完成
* 删除任务

### 🛠️ 管理员面板

用户 CRUD 操作仅对拥有管理员权限的已认证用户开放。

管理员还可以查看所有用户的任务。在具体实现中，如果界面清楚体现这一点且不违反基础业务逻辑，则允许通过管理员面板编辑其他用户的任务。

## 📊 数据库表结构

基础数据库结构描述最小必要的领域模型。在具体实现中，可以根据框架、语言特性（例如 Symfony 的角色系统、Laravel 内置机制、Rails 约定）或 DBMS 的特点调整 schema，但必须保持等价的业务逻辑。

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

#### 备注
* `is_admin` 是角色机制的极简替代方案。
* 在 Symfony/Laravel 中可以替换为 roles 机制。
* 密码只能是哈希，不能有任何 plaintext，即使是“示例”也不可以。
* 如果框架提供现成的用户模型，可以使用它，但必须保留注册、邮箱确认、密码重置和管理员角色的要求。

### `tasks`

| 字段 | 类型 | 必填 | 索引 / 约束 |
|---|---|---|---|
| `id` | `BIGINT` / `UUID v7 BINARY` | 是 | PK |
| `user_id` | `BIGINT` / `UUID v7 BINARY` | 是 | FK + INDEX |
| `title` | `VARCHAR(255)` | 是 | INDEX |
| `description` | `TEXT` | 否 | - |
| `is_completed` | `BOOLEAN` | 是 | INDEX |
| `due_at` | `TIMESTAMP` / `DATETIME_IMMUTABLE` | 否 | INDEX |
| `created_at` | `TIMESTAMP` / `DATETIME_IMMUTABLE` | 是 | INDEX |
| `updated_at` | `TIMESTAMP` / `DATETIME_IMMUTABLE` | 是 | - |

#### 约束

* FK (`user_id`) → `users.id`
* ON DELETE CASCADE

## 📁 仓库

仓库的每个分支都是一个独立的项目起始模板，可直接用于开发。master 分支包含通用文档，用于描述概念、要求以及不同实现之间的差异。

* `master` - 文档、说明
* `php-mysql` - 纯 PHP + MySQL
* `php-postgres` - 纯 PHP + PostgreSQL
* `php-sqlite` - 纯 PHP + SQLite
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
