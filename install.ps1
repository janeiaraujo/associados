# ==========================================
# SCRIPT DE INSTALAÇÃO - Sistema de Associados
# Para Windows PowerShell
# ==========================================

Write-Host "============================================" -ForegroundColor Cyan
Write-Host " Sistema de Gestão de Associados - Setup" -ForegroundColor Cyan
Write-Host "============================================" -ForegroundColor Cyan
Write-Host ""

# Verificar se está na pasta correta
$currentPath = Get-Location
if ($currentPath.Path -notlike "*github_associados*") {
    Write-Host "ERRO: Execute este script na pasta github_associados" -ForegroundColor Red
    Write-Host "Exemplo: cd C:\xampp-8.0.30\htdocs\github_associados" -ForegroundColor Yellow
    exit 1
}

# Passo 1: Verificar Composer
Write-Host "[1/7] Verificando Composer..." -ForegroundColor Yellow
$composerCheck = Get-Command composer -ErrorAction SilentlyContinue
if (-not $composerCheck) {
    Write-Host "ERRO: Composer não encontrado!" -ForegroundColor Red
    Write-Host "Instale o Composer: https://getcomposer.org/download/" -ForegroundColor Yellow
    exit 1
}
Write-Host "✓ Composer encontrado" -ForegroundColor Green

# Passo 2: Instalar dependências
Write-Host ""
Write-Host "[2/7] Instalando dependências PHP..." -ForegroundColor Yellow
cd app
composer install --no-interaction
if ($LASTEXITCODE -ne 0) {
    Write-Host "ERRO ao instalar dependências!" -ForegroundColor Red
    exit 1
}
Write-Host "✓ Dependências instaladas" -ForegroundColor Green

# Passo 3: Criar .env
Write-Host ""
Write-Host "[3/7] Configurando arquivo .env..." -ForegroundColor Yellow
if (Test-Path ".env") {
    Write-Host "⚠ Arquivo .env já existe. Pulando..." -ForegroundColor Yellow
} else {
    Copy-Item ".env.example" ".env"
    Write-Host "✓ Arquivo .env criado" -ForegroundColor Green
    Write-Host "ATENÇÃO: Configure o .env antes de continuar!" -ForegroundColor Yellow
    Write-Host "Edite: app\.env" -ForegroundColor Yellow
    Write-Host ""
    
    $continue = Read-Host "Deseja continuar? (s/n)"
    if ($continue -ne "s") {
        Write-Host "Instalação cancelada. Configure o .env e execute novamente." -ForegroundColor Yellow
        exit 0
    }
}

# Passo 4: Gerar chave de encriptação
Write-Host ""
Write-Host "[4/7] Gerando chave de encriptação..." -ForegroundColor Yellow
php spark key:generate
if ($LASTEXITCODE -ne 0) {
    Write-Host "ERRO ao gerar chave!" -ForegroundColor Red
    exit 1
}
Write-Host "✓ Chave gerada" -ForegroundColor Green

# Passo 5: Criar banco de dados
Write-Host ""
Write-Host "[5/7] Criando banco de dados..." -ForegroundColor Yellow
Write-Host "NOTA: Execute manualmente no phpMyAdmin:" -ForegroundColor Yellow
Write-Host "CREATE DATABASE associados_db CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;" -ForegroundColor Cyan
Write-Host ""

$dbCreated = Read-Host "Banco de dados criado? (s/n)"
if ($dbCreated -ne "s") {
    Write-Host "Crie o banco de dados e execute novamente." -ForegroundColor Yellow
    exit 0
}

# Passo 6: Executar migrations
Write-Host ""
Write-Host "[6/7] Executando migrations..." -ForegroundColor Yellow
php spark migrate
if ($LASTEXITCODE -ne 0) {
    Write-Host "ERRO ao executar migrations!" -ForegroundColor Red
    Write-Host "Verifique as configurações do banco no .env" -ForegroundColor Yellow
    exit 1
}
Write-Host "✓ Migrations executadas" -ForegroundColor Green

# Passo 7: Executar seeds
Write-Host ""
Write-Host "[7/7] Populando banco com dados iniciais..." -ForegroundColor Yellow
php spark db:seed InitialSeeder
if ($LASTEXITCODE -ne 0) {
    Write-Host "ERRO ao executar seeds!" -ForegroundColor Red
    exit 1
}
Write-Host "✓ Seeds executados" -ForegroundColor Green

# Finalização
Write-Host ""
Write-Host "============================================" -ForegroundColor Green
Write-Host " Instalação Concluída com Sucesso!" -ForegroundColor Green
Write-Host "============================================" -ForegroundColor Green
Write-Host ""
Write-Host "Credenciais de acesso:" -ForegroundColor Cyan
Write-Host "URL: http://localhost/github_associados/app/public" -ForegroundColor White
Write-Host "Email: admin@associados.local" -ForegroundColor White
Write-Host "Senha: Admin@123456" -ForegroundColor White
Write-Host ""
Write-Host "IMPORTANTE:" -ForegroundColor Yellow
Write-Host "1. Altere a senha do admin após o primeiro login" -ForegroundColor Yellow
Write-Host "2. Configure o SMTP no .env para reset de senha" -ForegroundColor Yellow
Write-Host "3. Leia o README.md para mais informações" -ForegroundColor Yellow
Write-Host ""

# Retornar à pasta raiz
cd ..
