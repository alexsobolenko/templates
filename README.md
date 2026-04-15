# TEMPLATES

## One project, multiple implementations

This repository contains reference starter templates for the same small web application implemented with different technology stacks and databases.

The sample application is a TODO list with authentication, roles, CRUD logic, server-side rendering, and a Docker Compose development environment. The `master` branch contains the shared documentation, while each implementation lives in its own branch.

## Usage

Create a new project from the branch that matches the stack and database you need:

```bash
git clone --branch php-sqlite --single-branch https://github.com/alexsobolenko/templates.git my-project
cd my-project
```

Replace `php-sqlite` with another branch name, for example `symfony-postgres`, `laravel-mysql`, `ruby-sqlite`, or `go-postgres`.

To keep the new project independent from this template repository:

```bash
rm -rf .git
git init
git add .
git commit -m "Initial project from template"
```

## Branches

* `master` - documentation, description
* `php-mysql` - pure PHP + MySQL
* `php-postgres` - pure PHP + PostgreSQL
* `php-sqlite` - pure PHP + SQLite
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

## Documentation

* 🇬🇧 [English](https://github.com/alexsobolenko/templates/blob/master/docs/en.md)
* 🇷🇺 [Русский](https://github.com/alexsobolenko/templates/blob/master/docs/ru.md)
* 🇪🇸 [Español](https://github.com/alexsobolenko/templates/blob/master/docs/es.md)
* 🇨🇳 [中文](https://github.com/alexsobolenko/templates/blob/master/docs/zh.md)
* 🇵🇱 [Polska](https://github.com/alexsobolenko/templates/blob/master/docs/pl.md)
* 🇩🇪 [Deutsch](https://github.com/alexsobolenko/templates/blob/master/docs/de.md)
* 🇫🇷 [Français](https://github.com/alexsobolenko/templates/blob/master/docs/fr.md)
* 🇵🇹 [Português](https://github.com/alexsobolenko/templates/blob/master/docs/pt.md)
* 🇰🇷 [한국어](https://github.com/alexsobolenko/templates/blob/master/docs/ko.md)
* 🇯🇵 [日本語](https://github.com/alexsobolenko/templates/blob/master/docs/ja.md)
