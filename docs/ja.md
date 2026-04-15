# TODO-LIST 🇯🇵

## 🎯 目的

認証、ロール、CRUD ロジック、Docker 環境を備えたリファレンス Web アプリケーションテンプレートを作成し、さまざまな技術のプロジェクトの出発点として使えるようにする。

テンプレートのドメインはできるだけ単純にする。ユーザーは自分のタスクリストを管理する。これにより、ドメインモデルを複雑にせず、一般的なプロジェクト構成、セキュリティ、環境、マイグレーション、テスト、選択したスタックらしい実装に集中できる。

## 📖 概要

タスク一覧のサイト。メール送信を含む認証と登録が必要。モデル（エンティティ）- ユーザーとタスク。

未認証ユーザーは登録、ログイン、メール確認、パスワード再設定ができる。認証済みユーザーは自分のタスクを表示、作成、編集、完了、削除できる。管理者権限を持つ認証済みユーザーは、ユーザーを管理し、すべてのユーザーのタスクを表示できる。

## ⚙️ 一般要件

### 🖥️ バックエンド

プロジェクトは複数のバリエーションで実装される。各バリエーションは同じドメインモデルとビジネスロジックに従いながら、選択した技術スタックの慣用的な方法を使う。実装バリエーション:
* 純粋な PHP 8.5 による独自 MVC
* Symfony
* Laravel
* Yii2
* Ruby on Rails
* Golang

### 🐳 Docker Compose プラットフォーム

1. `web` (必須 php/ruby/go)
2. `db` (任意 mysql/postgres。SQLite は別コンテナを必要としないため)
3. `nginx` (必須)
4. `mailcatcher` (dev 環境のみ)
5. `redis` (必要に応じて任意、セッション/キャッシュ)

### 🗄️ DBMS

* MySQL / MariaDB
* PostgreSQL
* SQLite3

### 🌐 フロントエンド

サーバーサイドレンダリング (PHP / ERB / Twig / Blade / Go templates)、ビルドツールなしの CSS、UX 改善のためだけの JavaScript (SPA ではない)。

### 🔒 セキュリティ

* 登録
* メール確認
* 管理者ロール
* パスワードハッシュ化
* CSRF
* XSS 保護
* Reset password
* Rate-limit
* タスクがユーザーに属していることの確認

### ✅ タスク

認証済みユーザーは自分のタスクだけを管理できる:

* 自分のタスク一覧を表示する
* タスクを作成する
* タスクを編集する
* タスクを完了または未完了としてマークする
* タスクを削除する

### 🛠️ 管理者パネル

ユーザーの CRUD 操作は、管理者権限を持つ認証済みユーザーだけが利用できる。

管理者はすべてのユーザーのタスクも表示できる。管理者パネルから他のユーザーのタスクを編集することは、インターフェース上で明確に示され、基本的なビジネスロジックを破らない場合に、具体的な実装で許可される。

## 📊 データベーステーブル構造

基本的なデータベース構造は、最小限必要なドメインモデルを表す。具体的な実装では、フレームワーク、言語の特徴（例: Symfony のロールシステム、Laravel の組み込み機構、Rails の規約）または DBMS の特性に応じたスキーマ変更を認める。ただし、同等のビジネスロジックは維持しなければならない。

### `users`

| フィールド | 型 | 必須 | インデックス / 制約 |
|---|---|---|---|
| `id` | `BIGINT` / `UUID v7 BINARY` | はい | PK |
| `email` | `VARCHAR(255)` | はい | UNIQUE |
| `password_hash` | `VARCHAR(255)` | はい | - |
| `username` | `VARCHAR(100)` | はい | UNIQUE |
| `is_admin` | `BOOLEAN` | はい | INDEX |
| `email_verified_at` | `TIMESTAMP` / `DATETIME_IMMUTABLE` | いいえ | INDEX |
| `verification_token` | `VARCHAR(255)` | いいえ | INDEX |
| `created_at` | `TIMESTAMP` / `DATETIME_IMMUTABLE` | はい | INDEX |
| `updated_at` | `TIMESTAMP` / `DATETIME_IMMUTABLE` | はい | - |

#### 注記
* `is_admin` はロールの最小限の代替である。
* Symfony/Laravel では roles メカニズムに置き換えてもよい。
* パスワードはハッシュのみで保存し、"例として" であっても plaintext は使わない。
* フレームワークが既製のユーザーモデルを提供している場合、登録、メール確認、パスワード再設定、管理者ロールの要件を保つなら使用してよい。

### `tasks`

| フィールド | 型 | 必須 | インデックス / 制約 |
|---|---|---|---|
| `id` | `BIGINT` / `UUID v7 BINARY` | はい | PK |
| `user_id` | `BIGINT` / `UUID v7 BINARY` | はい | FK + INDEX |
| `title` | `VARCHAR(255)` | はい | INDEX |
| `description` | `TEXT` | いいえ | - |
| `is_completed` | `BOOLEAN` | はい | INDEX |
| `due_at` | `TIMESTAMP` / `DATETIME_IMMUTABLE` | いいえ | INDEX |
| `created_at` | `TIMESTAMP` / `DATETIME_IMMUTABLE` | はい | INDEX |
| `updated_at` | `TIMESTAMP` / `DATETIME_IMMUTABLE` | はい | - |

#### 制約

* FK (`user_id`) → `users.id`
* ON DELETE CASCADE

## 📁 リポジトリ

リポジトリの各ブランチは、開発で直接使用するための独立したスタータープロジェクトテンプレートである。master ブランチには、概念、要件、実装間の違いを説明する一般ドキュメントが含まれる。

* `master` - ドキュメント、説明
* `php-mysql` - 純粋な PHP + MySQL
* `php-postgres` - 純粋な PHP + PostgreSQL
* `php-sqlite` - 純粋な PHP + SQLite
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
