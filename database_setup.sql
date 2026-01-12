-- ==========================================
-- SCRIPT DE CRIAÇÃO DO BANCO DE DADOS
-- Sistema de Gestão de Associados
-- ==========================================

-- Criar banco de dados
CREATE DATABASE IF NOT EXISTS associados_db 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_general_ci;

USE associados_db;

-- ==========================================
-- NOTA IMPORTANTE:
-- Este script cria apenas o banco de dados.
-- As tabelas serão criadas automaticamente 
-- pelas migrations do CodeIgniter.
-- 
-- Para criar as tabelas, execute:
-- php spark migrate
-- 
-- Para popular com dados iniciais:
-- php spark db:seed InitialSeeder
-- ==========================================

-- Verificar se o banco foi criado
SELECT 
    SCHEMA_NAME as 'Banco de Dados',
    DEFAULT_CHARACTER_SET_NAME as 'Charset',
    DEFAULT_COLLATION_NAME as 'Collation'
FROM information_schema.SCHEMATA
WHERE SCHEMA_NAME = 'associados_db';

-- Mostrar tabelas (após migrations)
-- SHOW TABLES;
