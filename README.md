# Sistema de Gestão de Associados

Sistema web completo para gestão de associados com autenticação, importação de Excel, relatórios, auditoria e dashboard com KPIs.

## 📋 Requisitos

- **XAMPP 8.0.30** ou superior
- **PHP 8.0+**
- **MySQL 8.0+**
- **Composer**
- **Git**

## 🚀 Instalação no XAMPP (Windows)

### 1. Clonar o Repositório

```powershell
cd C:\xampp-8.0.30\htdocs
git clone https://github.com/janeiaraujo/associados.git github_associados
cd github_associados\app
```

### 2. Instalar Dependências

```powershell
composer install
```

### 3. Configurar Ambiente

Copie `.env.example` para `.env`:

```powershell
copy .env.example .env
```

**Configurações importantes no `.env`:**

**Banco de Dados:**
```env
database.default.hostname = localhost
database.default.database = associados_db
database.default.username = root
database.default.password = 
```

**SMTP (Gmail):**
```env
email.SMTPHost = smtp.gmail.com
email.SMTPUser = seu-email@gmail.com
email.SMTPPass = sua-senha-de-app
email.SMTPPort = 587
```

**Admin Inicial:**
```env
app.adminEmail = admin@associados.local
app.adminPassword = Admin@123456
```

### 4. Criar Banco de Dados

No phpMyAdmin (http://localhost/phpmyadmin):

```sql
CREATE DATABASE associados_db CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
```

### 5. Executar Migrations e Seeds

```powershell
php spark migrate
php spark db:seed InitialSeeder
```

### 6. Gerar Chave de Encriptação

```powershell
php spark key:generate
```

### 7. Acessar o Sistema

http://localhost/github_associados/app/public

**Credenciais padrão:**
- Email: `admin@associados.local`
- Senha: `Admin@123456`

## 📁 Estrutura

```
github_associados/
├── app/
│   ├── app/
│   │   ├── Controllers/     # Auth, Dashboard, Associados, Importacao, Relatorios, Users
│   │   ├── Models/          # Models de dados
│   │   ├── Views/           # Templates
│   │   ├── Filters/         # AuthFilter, PermissionFilter
│   │   ├── Helpers/         # auth_helper.php
│   │   └── Database/
│   │       ├── Migrations/  # 10 migrations (users, roles, permissions, etc)
│   │       └── Seeds/       # Seeds iniciais
│   ├── public/              # DocumentRoot
│   ├── writable/            # Cache, logs, uploads
│   ├── .env                 # Configurações (não commitar)
│   └── composer.json
├── .gitignore
└── README.md
```

## 🔐 Permissões (RBAC)

**Roles:**
- `admin` - Acesso total
- `manager` - Gestão e importação
- `viewer` - Somente leitura

**Permissions:**
- dashboard.view, associados.*, relatorios.*, users.manage, audit.view

## 📊 Importação Excel

**Colunas aceitas:** NOME, CPF, UNIDADE, MAT. DOCAS, FUNÇÃO, NASCIMENTO, TELEFONE, E-MAIL, ENDEREÇO, MAT. SINDICAL

**Estratégia:** UPSERT por CPF (atualiza se existir, insere se novo)

## 🔄 Git / GitHub

**Conventional Commits:**
```powershell
git commit -m "feat: nova funcionalidade"
git commit -m "fix: correção"
git commit -m "docs: documentação"
```

**Tags SemVer:**
```powershell
git tag -a v1.0.0 -m "Release 1.0.0"
git push origin v1.0.0
```

## 🐛 Troubleshooting

**Erro "Base table not found":** Execute `php spark migrate`

**Erro SMTP:** Verifique senha de app do Gmail

**Permissão writable:** Execute como admin:
```powershell
icacls "C:\xampp-8.0.30\htdocs\github_associados\app\writable" /grant Users:(OI)(CI)F /T
```

## 📝 Comandos Úteis

```powershell
php spark migrate              # Executar migrations
php spark db:seed InitialSeeder # Executar seeds
php spark cache:clear         # Limpar cache
php spark routes              # Listar rotas
```

## 📄 Licença

Projeto proprietário e confidencial.

---

**Desenvolvido com CodeIgniter 4**