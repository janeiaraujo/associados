-- ==========================================
-- SCRIPT DE MIGRAÇÃO - MÓDULO DE CONFIGURAÇÕES
-- Data: 03/02/2026
-- Descrição: Cria tabela de configurações e adiciona permissões
-- ==========================================

USE associados_db;

-- ==========================================
-- 1. CRIAR TABELA CONFIGURACOES
-- ==========================================

CREATE TABLE IF NOT EXISTS `configuracoes` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `chave` VARCHAR(100) NOT NULL,
    `valor` TEXT NULL,
    `descricao` VARCHAR(255) NULL,
    `tipo` ENUM('text', 'textarea', 'image', 'email', 'tel', 'number') NOT NULL DEFAULT 'text',
    `grupo` VARCHAR(50) NOT NULL DEFAULT 'geral',
    `created_at` DATETIME NULL,
    `updated_at` DATETIME NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `chave` (`chave`),
    KEY `idx_grupo` (`grupo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ==========================================
-- 2. INSERIR CONFIGURAÇÕES PADRÃO
-- ==========================================

INSERT INTO `configuracoes` (`chave`, `valor`, `descricao`, `tipo`, `grupo`) VALUES
-- Dados da Empresa
('empresa_nome', 'STSPPERJ', 'Nome da Empresa/Sindicato', 'text', 'empresa'),
('empresa_nome_completo', 'Sindicato dos Trabalhadores em Saúde Pública e Previdência do Estado do Rio de Janeiro', 'Nome Completo/Razão Social', 'text', 'empresa'),
('empresa_cnpj', '', 'CNPJ', 'text', 'empresa'),
('empresa_inscricao_estadual', '', 'Inscrição Estadual', 'text', 'empresa'),
('empresa_inscricao_municipal', '', 'Inscrição Municipal', 'text', 'empresa'),

-- Endereço
('endereco_logradouro', '', 'Logradouro', 'text', 'endereco'),
('endereco_numero', '', 'Número', 'text', 'endereco'),
('endereco_complemento', '', 'Complemento', 'text', 'endereco'),
('endereco_bairro', '', 'Bairro', 'text', 'endereco'),
('endereco_cidade', 'Rio de Janeiro', 'Cidade', 'text', 'endereco'),
('endereco_estado', 'RJ', 'Estado (UF)', 'text', 'endereco'),
('endereco_cep', '', 'CEP', 'text', 'endereco'),

-- Contato
('contato_telefone', '', 'Telefone', 'tel', 'contato'),
('contato_telefone2', '', 'Telefone 2', 'tel', 'contato'),
('contato_celular', '', 'Celular', 'tel', 'contato'),
('contato_whatsapp', '', 'WhatsApp', 'tel', 'contato'),
('contato_email', '', 'E-mail', 'email', 'contato'),
('contato_site', '', 'Website', 'text', 'contato'),

-- Imagens
('logo_principal', 'assets/images/logo.png', 'Logo Principal', 'image', 'imagens'),
('logo_relatorio', 'assets/images/logo.png', 'Logo para Relatórios', 'image', 'imagens'),

-- Relatórios
('relatorio_cabecalho', '', 'Cabeçalho dos Relatórios', 'textarea', 'relatorios'),
('relatorio_rodape', '', 'Rodapé dos Relatórios', 'textarea', 'relatorios')
ON DUPLICATE KEY UPDATE 
    `descricao` = VALUES(`descricao`),
    `tipo` = VALUES(`tipo`),
    `grupo` = VALUES(`grupo`);

-- ==========================================
-- 3. ADICIONAR PERMISSÕES
-- ==========================================

-- Inserir permissões de configurações
INSERT INTO `permissions` (`key_name`, `description`) 
SELECT 'configuracoes.view', 'Visualizar configurações do sistema'
WHERE NOT EXISTS (
    SELECT 1 FROM `permissions` WHERE `key_name` = 'configuracoes.view'
);

INSERT INTO `permissions` (`key_name`, `description`) 
SELECT 'configuracoes.update', 'Alterar configurações do sistema'
WHERE NOT EXISTS (
    SELECT 1 FROM `permissions` WHERE `key_name` = 'configuracoes.update'
);

-- ==========================================
-- 4. ATRIBUIR PERMISSÕES AO ROLE ADMIN
-- ==========================================

-- Atribuir permissão de visualizar ao admin (role_id = 1)
INSERT INTO `role_permissions` (`role_id`, `permission_id`)
SELECT 1, p.id
FROM `permissions` p
WHERE p.key_name = 'configuracoes.view'
AND NOT EXISTS (
    SELECT 1 FROM `role_permissions` rp 
    WHERE rp.role_id = 1 AND rp.permission_id = p.id
);

-- Atribuir permissão de alterar ao admin (role_id = 1)
INSERT INTO `role_permissions` (`role_id`, `permission_id`)
SELECT 1, p.id
FROM `permissions` p
WHERE p.key_name = 'configuracoes.update'
AND NOT EXISTS (
    SELECT 1 FROM `role_permissions` rp 
    WHERE rp.role_id = 1 AND rp.permission_id = p.id
);

-- ==========================================
-- 5. VERIFICAÇÃO
-- ==========================================

-- Verificar se a tabela foi criada
SELECT 
    'Tabela configuracoes criada' AS status,
    COUNT(*) AS total_registros
FROM `configuracoes`;

-- Verificar permissões
SELECT 
    'Permissões adicionadas' AS status,
    COUNT(*) AS total_permissoes
FROM `permissions`
WHERE `key_name` LIKE 'configuracoes.%';

-- Verificar role_permissions
SELECT 
    'Permissões atribuídas ao Admin' AS status,
    COUNT(*) AS total_atribuicoes
FROM `role_permissions` rp
INNER JOIN `permissions` p ON p.id = rp.permission_id
WHERE rp.role_id = 1 
AND p.key_name LIKE 'configuracoes.%';

-- ==========================================
-- FIM DO SCRIPT
-- ==========================================
-- Execute este script completo no seu banco de produção
-- Ele é seguro para executar múltiplas vezes (idempotente)
-- ==========================================
