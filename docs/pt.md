# TODO-LIST 🇵🇹

## 🎯 Objetivo

Criar um template de referência de aplicação web com autenticação, papéis, lógica CRUD e ambiente Docker, que possa ser usado como ponto de partida para projetos em diferentes tecnologias.

O template deve ser o mais simples possível no domínio: o usuário gerencia a lista de suas tarefas. Isso permite focar na estrutura típica do projeto, segurança, ambiente, migrações, testes e implementação idiomática da stack escolhida, sem complicar o modelo de domínio.

## 📖 Descrição aproximada

Um site com uma lista de tarefas. São necessários autenticação e registro com envio de e-mails. Modelos (entidades) - usuário e tarefa.

Um usuário não autenticado pode se registrar, entrar no sistema, confirmar o e-mail e recuperar a senha. Um usuário autenticado pode visualizar, criar, editar, concluir e excluir suas próprias tarefas. Um usuário autenticado com direitos de administrador pode gerenciar usuários e visualizar as tarefas de todos os usuários.

## ⚙️ Requisitos gerais

### 🖥️ Backend

O projeto é implementado em várias variantes, cada uma seguindo o mesmo modelo de domínio e a mesma lógica de negócio, mas usando a abordagem idiomática da stack escolhida. Variantes de implementação:
* MVC próprio em PHP 8.5 puro
* Symfony
* Laravel
* Yii2
* Ruby on Rails
* Golang

### 🐳 Plataforma Docker Compose

1. `web` (obrigatório php/ruby/go)
2. `db` (opcional mysql/postgres, porque SQLite não precisa de um contêiner separado)
3. `nginx` (obrigatório)
4. `mailcatcher` (somente para o ambiente dev)
5. `redis` (opcional se necessário, sessões/cache)

### 🗄️ SGBD

* MySQL / MariaDB
* PostgreSQL
* SQLite3

### 🌐 Frontend

Renderização no servidor (PHP / ERB / Twig / Blade / Go templates), CSS sem ferramentas de build, JavaScript apenas para melhorar a UX (sem SPA).

### 🔒 Segurança

* Registro
* Confirmação de e-mail
* Papel de administrador
* Hash de senhas
* CSRF
* Proteção XSS
* Reset password
* Rate-limit
* Verificação de pertencimento das tarefas ao usuário

### ✅ Tarefas

Um usuário autenticado pode gerenciar apenas suas próprias tarefas:

* visualizar a lista de suas tarefas
* criar uma tarefa
* editar uma tarefa
* marcar uma tarefa como concluída ou não concluída
* excluir uma tarefa

### 🛠️ Painel de administração

Operações CRUD para usuários estão disponíveis apenas para um usuário autenticado com direitos de administrador.

O administrador também pode visualizar as tarefas de todos os usuários. A edição de tarefas de outros usuários pelo painel de administração é permitida em implementações concretas, se isso estiver claramente refletido na interface e não violar a lógica de negócio básica.

## 📊 Estrutura das tabelas do banco de dados

A estrutura básica do banco de dados descreve o modelo de domínio mínimo necessário. Em implementações concretas, são permitidas alterações no esquema devido a características do framework, da linguagem (por exemplo, sistema de papéis do Symfony, mecanismos integrados do Laravel, convenções do Rails) ou do SGBD, desde que a lógica de negócio equivalente seja preservada.

### `users`

| Campo | Tipo | Obrigatório | Índices / restrições |
|---|---|---|---|
| `id` | `BIGINT` / `UUID v7 BINARY` | sim | PK |
| `email` | `VARCHAR(255)` | sim | UNIQUE |
| `password_hash` | `VARCHAR(255)` | sim | - |
| `username` | `VARCHAR(100)` | sim | UNIQUE |
| `is_admin` | `BOOLEAN` | sim | INDEX |
| `email_verified_at` | `TIMESTAMP` / `DATETIME_IMMUTABLE` | não | INDEX |
| `verification_token` | `VARCHAR(255)` | não | INDEX |
| `created_at` | `TIMESTAMP` / `DATETIME_IMMUTABLE` | sim | INDEX |
| `updated_at` | `TIMESTAMP` / `DATETIME_IMMUTABLE` | sim | - |

#### Observações
* `is_admin` é uma alternativa minimalista aos papéis.
* Em Symfony/Laravel, pode ser substituído por um mecanismo de papéis.
* A senha deve ser apenas um hash, sem plaintext nem mesmo "como exemplo".
* Se o framework fornecer um modelo de usuário pronto, ele pode ser usado mantendo os requisitos de registro, confirmação de e-mail, recuperação de senha e papel de administrador.

### `tasks`

| Campo | Tipo | Obrigatório | Índices / restrições |
|---|---|---|---|
| `id` | `BIGINT` / `UUID v7 BINARY` | sim | PK |
| `user_id` | `BIGINT` / `UUID v7 BINARY` | sim | FK + INDEX |
| `title` | `VARCHAR(255)` | sim | INDEX |
| `description` | `TEXT` | não | - |
| `is_completed` | `BOOLEAN` | sim | INDEX |
| `due_at` | `TIMESTAMP` / `DATETIME_IMMUTABLE` | não | INDEX |
| `created_at` | `TIMESTAMP` / `DATETIME_IMMUTABLE` | sim | INDEX |
| `updated_at` | `TIMESTAMP` / `DATETIME_IMMUTABLE` | sim | - |

#### Restrições

* FK (`user_id`) → `users.id`
* ON DELETE CASCADE

## 📁 Repositório

Cada branch do repositório representa um template inicial de projeto independente, destinado ao uso direto em desenvolvimento. A branch master contém documentação geral que descreve conceitos, requisitos e diferenças entre as implementações.

* `master` - documentação, descrição
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
