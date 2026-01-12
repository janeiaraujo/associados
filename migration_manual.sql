-- SQL para atualizar tabela associados no servidor de produção
-- Execute este script no phpMyAdmin ou cliente MySQL

-- Verifica e remove coluna matricula (se existir)
SET @dbname = DATABASE();
SET @tablename = 'associados';
SET @columnname = 'matricula';
SET @preparedStatement = (SELECT IF(
  (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
   WHERE (table_name = @tablename)
   AND (table_schema = @dbname)
   AND (column_name = @columnname)) > 0,
  'ALTER TABLE associados DROP COLUMN matricula;',
  'SELECT 1;'
));
PREPARE alterIfExists FROM @preparedStatement;
EXECUTE alterIfExists;
DEALLOCATE PREPARE alterIfExists;

-- Adiciona coluna registro (se não existir)
SET @columnname = 'registro';
SET @preparedStatement = (SELECT IF(
  (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
   WHERE (table_name = @tablename)
   AND (table_schema = @dbname)
   AND (column_name = @columnname)) = 0,
  'ALTER TABLE associados ADD `registro` VARCHAR(50) NULL AFTER `unidade_id`;',
  'SELECT 1;'
));
PREPARE alterIfExists FROM @preparedStatement;
EXECUTE alterIfExists;
DEALLOCATE PREPARE alterIfExists;

-- Adiciona coluna matricula_sindical (se não existir)
SET @columnname = 'matricula_sindical';
SET @preparedStatement = (SELECT IF(
  (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
   WHERE (table_name = @tablename)
   AND (table_schema = @dbname)
   AND (column_name = @columnname)) = 0,
  'ALTER TABLE associados ADD `matricula_sindical` VARCHAR(50) NULL AFTER `registro`;',
  'SELECT 1;'
));
PREPARE alterIfExists FROM @preparedStatement;
EXECUTE alterIfExists;
DEALLOCATE PREPARE alterIfExists;

-- Adiciona coluna tipo_aposentado (se não existir)
SET @columnname = 'tipo_aposentado';
SET @preparedStatement = (SELECT IF(
  (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
   WHERE (table_name = @tablename)
   AND (table_schema = @dbname)
   AND (column_name = @columnname)) = 0,
  'ALTER TABLE associados ADD `tipo_aposentado` ENUM(\'CLT\',\'PENSIONISTA\',\'NAO_APOSENTADO\') NOT NULL DEFAULT \'NAO_APOSENTADO\' AFTER `funcao_id`;',
  'SELECT 1;'
));
PREPARE alterIfExists FROM @preparedStatement;
EXECUTE alterIfExists;
DEALLOCATE PREPARE alterIfExists;

-- Verifica as colunas criadas
SELECT COLUMN_NAME, DATA_TYPE, IS_NULLABLE, COLUMN_DEFAULT 
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_SCHEMA = DATABASE() 
AND TABLE_NAME = 'associados' 
AND COLUMN_NAME IN ('registro', 'matricula_sindical', 'tipo_aposentado')
ORDER BY ORDINAL_POSITION;
