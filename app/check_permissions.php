<?php
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR);
require 'app/Config/Paths.php';
$paths = new Config\Paths();
require rtrim($paths->systemDirectory, '\\/ ') . DIRECTORY_SEPARATOR . 'bootstrap.php';

$db = \Config\Database::connect();

echo "=== Verificando Permissões de Configurações ===\n\n";

// Ver se as permissões existem
$perms = $db->query("SELECT id, key_name, description FROM permissions WHERE key_name LIKE 'configuracoes%'")->getResultArray();

if (empty($perms)) {
    echo "❌ ERRO: Permissões de configurações NÃO encontradas!\n";
    echo "Execute: php spark migrate\n\n";
} else {
    echo "✅ Permissões encontradas:\n";
    foreach ($perms as $p) {
        echo "  - ID: {$p['id']} | {$p['key_name']} | {$p['description']}\n";
    }
    echo "\n";
}

// Ver se o admin tem essas permissões
$adminPerms = $db->query("
    SELECT p.key_name 
    FROM permissions p 
    INNER JOIN role_permissions rp ON rp.permission_id = p.id 
    WHERE rp.role_id = 1 AND p.key_name LIKE 'configuracoes%'
")->getResultArray();

if (empty($adminPerms)) {
    echo "❌ ERRO: Role Admin (id=1) NÃO tem permissões de configurações!\n";
    echo "Execute o script migration_configuracoes_producao.sql\n\n";
} else {
    echo "✅ Role Admin tem as permissões:\n";
    foreach ($adminPerms as $p) {
        echo "  - {$p['key_name']}\n";
    }
}
