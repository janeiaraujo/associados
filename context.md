[AGENT ROLE]
Você é um desenvolvedor senior full-stack e arquiteto de software.
Crie toda a aplicação de forma AUTÔNOMA.
Só faça perguntas se houver BLOQUEIO REAL (ex.: credenciais SMTP finais, ou se o usuário não tiver o template disponível).
Caso algo não esteja definido, escolha a opção mais segura, documente no README e continue.

━━━━━━━━━━━━━━━━━━━━━━━━━━
[AMBIENTE]
- Sistema operacional: Windows
- Servidor local: XAMPP 8.0.30
- PHP: 8.x
- Framework: CodeIgniter (versão mais atual)
- Banco relacional: MySQL 8.0
- Banco NoSQL: MongoDB
- Shell: PowerShell
- Separador de comandos: ponto e vírgula (;)
- PROIBIDO usar &&
━━━━━━━━━━━━━━━━━━━━━━━━━━

[REPOSITÓRIO / GIT / GITHUB - OBRIGATÓRIO]
- O projeto DEVE ser versionado em Git desde o primeiro commit.
- Cada mudança relevante deve gerar commit.
- Padrão de commits (Conventional Commits):
  - feat: nova funcionalidade
  - fix: correção
  - refactor: refatoração sem mudança de comportamento
  - docs: documentação
  - chore: tarefas de build/config
  - test: testes
- Nunca commitar segredos (SMTP, chaves, senhas). Usar .env e documentar.
- Fornecer no README um fluxo de trabalho claro:
  - branch main (estável)
  - branch develop (opcional) ou commits diretos em main se for single-dev, mas com tags de release
  - tags semver: v1.0.0 etc.
- Ao final, o projeto precisa estar pronto para “git init; git add .; git commit -m ...; git remote add origin ...; git push -u origin main”

IMPORTANTE:
Todos os comandos sugeridos no README para Windows PowerShell devem usar ; como separador (nunca &&).

━━━━━━━━━━━━━━━━━━━━━━━━━━
[OBJETIVO DA APLICAÇÃO]
Plataforma de gestão de associados para um cliente final, com:
- Cadastro completo de associados
- Importação de Excel (.xlsx)
- Filtros avançados e listagens
- Relatórios e exportações (Excel/CSV/PDF)
- Auditoria e histórico (MongoDB)
- Dashboard com KPIs úteis
- Sistema de login com permissões (RBAC)
- Reset de senha via e-mail (SMTP)
- Interface moderna com Bootstrap + template

━━━━━━━━━━━━━━━━━━━━━━━━━━
[FRONTEND/UI - TEMPLATE BOOTSTRAP]
- Usar Bootstrap 5.
- Integração com template moderno fornecido pelo usuário.
- O template estará em uma pasta específica dentro do projeto: /public/template/
- Você deve:
  1) Criar um layout master em app/Views/layout/
  2) Adaptar header/sidebar/footer do template para o layout master
  3) Garantir que todas as páginas usem o mesmo layout
  4) Não quebrar responsividade
- Se algum asset estiver faltando, usar placeholders e documentar o que faltou.

━━━━━━━━━━━━━━━━━━━━━━━━━━
[MODELO DE DADOS - MYSQL]
Criar banco: associados_db

TABELAS (melhores práticas, não usar “tudo em uma tabela”):

1) associados
- id (INT PK AI)
- nome (VARCHAR 255)
- unidade (VARCHAR 100)
- matricula_docas (VARCHAR 50)
- funcao (VARCHAR 100)
- data_nascimento (DATE)
- cpf (VARCHAR 20 UNIQUE)
- telefone (VARCHAR 30 NULL)
- email (VARCHAR 150 NULL)
- endereco (TEXT)
- matricula_sindical (VARCHAR 50)
- status (ENUM('ATIVO','INATIVO') default 'ATIVO')
- created_at (DATETIME)
- updated_at (DATETIME)

2) users
- id (INT PK AI)
- name (VARCHAR 150)
- email (VARCHAR 150 UNIQUE)
- password_hash (VARCHAR 255)
- is_active (TINYINT default 1)
- last_login_at (DATETIME NULL)
- created_at (DATETIME)
- updated_at (DATETIME)

3) roles
- id (INT PK AI)
- name (VARCHAR 50 UNIQUE)  // ex: admin, manager, viewer
- description (VARCHAR 255 NULL)

4) permissions
- id (INT PK AI)
- key_name (VARCHAR 80 UNIQUE) // ex: associados.view, associados.create
- description (VARCHAR 255 NULL)

5) role_permissions (pivot)
- role_id (INT FK)
- permission_id (INT FK)
- PRIMARY KEY (role_id, permission_id)

6) user_roles (pivot)
- user_id (INT FK)
- role_id (INT FK)
- PRIMARY KEY (user_id, role_id)

7) password_resets
- id (INT PK AI)
- user_id (INT FK)
- token_hash (VARCHAR 255) // armazenar HASH do token, nunca token puro
- expires_at (DATETIME)
- used_at (DATETIME NULL)
- created_at (DATETIME)

8) login_audit (opcional mas recomendado)
- id (INT PK AI)
- user_id (INT NULL)
- email_attempt (VARCHAR 150)
- ip (VARCHAR 45)
- user_agent (VARCHAR 255)
- success (TINYINT)
- created_at (DATETIME)

9) app_config (para SMTP e config sem commitar segredo)
- id (INT PK AI)
- config_key (VARCHAR 100 UNIQUE) // SMTP_HOST, SMTP_PORT, SMTP_USER etc
- config_value (TEXT)
- is_secret (TINYINT default 0)
- updated_at (DATETIME)

Observação: o sistema deve carregar configs do .env e/ou app_config.
Preferir .env para segredos. app_config pode existir para “interface admin” futura.

━━━━━━━━━━━━━━━━━━━━━━━━━━
[MODELO DE DADOS - MONGODB]
Database: associados_logs

Coleções:
1) audit_logs
- entity (ex: "associados", "users")
- entity_id
- action (CREATE, UPDATE, DELETE, IMPORT, LOGIN, PASSWORD_RESET)
- before (obj)
- after (obj)
- actor_user_id (nullable)
- ip
- created_at

2) relatorios_gerados
- name
- filters_used
- result_count
- generated_by_user_id
- created_at

━━━━━━━━━━━━━━━━━━━━━━━━━━
[AUTH / LOGIN / RESET DE SENHA]
Implementar:
- Login (email + senha)
- Sessão segura (regenerar session id ao logar)
- Logout
- Middleware/Filter de autenticação
- Middleware/Filter de autorização por permissão

RESET DE SENHA via SMTP:
- Tela “Esqueci minha senha” (informar email)
- Se email existir: gerar token aleatório forte, armazenar hash em password_resets
- Token expira em 30 minutos
- Enviar link com token (ex: /reset-senha?token=... )
- Ao usar token: validar hash, validade, used_at
- Permitir definir nova senha + confirmação
- Invalidar token (used_at preenchido)
- Logar em MongoDB (PASSWORD_RESET)
- Segurança: resposta genérica (“Se existir conta, enviamos email”), para não vazar existência de usuário.

QUALIDADE DE SENHA (barra):
- No front-end: mostrar barra de força (fraca/média/forte) e checklist:
  - mínimo 8 caracteres
  - 1 letra maiúscula
  - 1 minúscula
  - 1 número
  - 1 caractere especial
- No back-end: validar as mesmas regras.
- Hash: password_hash (bcrypt/argon2 conforme suporte do PHP/CI)

SMTP:
- Implementar via config do CodeIgniter (Email library) lendo do .env:
  SMTP_HOST, SMTP_PORT, SMTP_USER, SMTP_PASS, SMTP_CRYPTO, SMTP_FROM_EMAIL, SMTP_FROM_NAME
- Documentar no README um exemplo de .env (sem segredos reais).

━━━━━━━━━━━━━━━━━━━━━━━━━━
[IMPORTAÇÃO DE EXCEL]
- Usar PhpSpreadsheet
- Importar .xlsx
- Mapear colunas:
  NOME -> nome
  UNIDADE -> unidade
  MAT. DOCAS -> matricula_docas
  FUNÇÃO -> funcao
  NASCIMENTO -> data_nascimento
  CPF -> cpf
  TELEFONE -> telefone
  E-MAIL -> email
  ENDEREÇO -> endereco
  MAT. SINDICAL -> matricula_sindical
- Evitar duplicidade por CPF:
  - Se CPF já existe: atualizar (se preferível) OU pular, mas escolher uma estratégia segura e documentar.
  - Padrão recomendado: “upsert por CPF” com log detalhado.
- Logar importação em MongoDB (IMPORT), com contagens: inseridos, atualizados, ignorados, erros.

━━━━━━━━━━━━━━━━━━━━━━━━━━
[FILTROS / LISTAGEM]
- Listagem paginada
- Busca por texto (nome, cpf, matrícula, email)
- Filtro por unidade, função, status
- Filtro por faixa etária (idade calculada pela data_nascimento)

━━━━━━━━━━━━━━━━━━━━━━━━━━
[RELATÓRIOS / EXPORTAÇÃO]
Relatórios mínimos:
1) Total de associados (ativos/inativos)
2) Associados por unidade (ranking)
3) Associados por função (ranking)
4) Distribuição por faixa etária (ex: 18-25, 26-35, 36-45, 46-60, 60+)
5) Últimas importações (data, usuário, contagens)
Exportações:
- CSV
- Excel
- PDF

Gerar logs de relatórios em MongoDB (relatorios_gerados).

━━━━━━━━━━━━━━━━━━━━━━━━━━
[DASHBOARD - KPIs QUE FAZEM SENTIDO]
Criar um dashboard ao logar, com cards e gráficos (Bootstrap + Chart.js):
- Total de associados
- Ativos vs Inativos
- Top 5 Unidades por volume
- Top 5 Funções por volume
- Faixa etária (gráfico)
- Últimas importações (tabela)
- Últimas alterações (audit_logs: associados) (tabela)

━━━━━━━━━━━━━━━━━━━━━━━━━━
[ARQUITETURA - CODEIGNITER]
Organização:
- app/Controllers
  - Auth.php (login/logout/forgot/reset)
  - Users.php (admin: criar/editar usuários e roles)  [somente admin]
  - Associados.php
  - Relatorios.php
  - Importacao.php
  - Dashboard.php

- app/Models
  - AssociadoModel.php
  - UserModel.php
  - RoleModel.php
  - PermissionModel.php
  - PasswordResetModel.php
  - ConfigModel.php

- app/Filters
  - AuthFilter.php
  - PermissionFilter.php

- app/Libraries
  - MongoLogger.php (registrar logs/auditoria no MongoDB)
  - ExcelImporter.php (importação)
  - ExportService.php (csv/xlsx/pdf)

- app/Views
  - layout/
  - auth/
  - dashboard/
  - associados/
  - relatorios/
  - importacao/
  - users/ (admin)

━━━━━━━━━━━━━━━━━━━━━━━━━━
[PERMISSÕES (RBAC)]
Criar permissões iniciais:
- dashboard.view
- associados.view
- associados.create
- associados.update
- associados.delete
- associados.import
- relatorios.view
- relatorios.export
- users.manage (somente admin)
- config.view (opcional)
- audit.view (opcional)

Criar roles iniciais:
- admin (todas)
- manager (dashboard + associados + import + relatorios)
- viewer (dashboard + associados.view + relatorios.view)

Ao instalar: criar usuário admin padrão (email e senha inicial).
A senha inicial deve ser forçada a troca no primeiro login? Se sim, implemente com segurança.
Se não, documente recomendação no README.

━━━━━━━━━━━━━━━━━━━━━━━━━━
[AUDITORIA]
Toda ação relevante (CRUD, importação, login, reset senha, exportação) deve:
- Registrar no MongoDB audit_logs
- Registrar actor_user_id quando houver
- Registrar ip e created_at

━━━━━━━━━━━━━━━━━━━━━━━━━━
[ENTREGÁVEIS]
1) Código completo e funcional
2) Script SQL de criação (migrations ou arquivo .sql)
3) Script/guia para MongoDB (coleções e conexão)
4) README.md muito completo contendo:
   - Requisitos
   - Instalação no XAMPP (passo a passo)
   - Configuração do .env (SMTP, DB MySQL, MongoDB)
   - Como importar a planilha
   - Como rodar migrations/seeds
   - Credenciais do admin inicial
   - Estrutura de pastas
   - Regras de segurança
   - Padrão de commits + fluxo GitHub
   - Checklist de deploy
5) Seeds:
   - roles e permissions
   - admin inicial

━━━━━━━━━━━━━━━━━━━━━━━━━━
[REGRAS FINAIS]
- NÃO pedir confirmação
- NÃO usar && em comandos
- Se algo for ambíguo, escolha o padrão mais seguro e documente
- Concluir com a aplicação rodando localmente no XAMPP e pronta para push no GitHub
