<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class CheckConfigPermissions extends BaseCommand
{
    protected $group       = 'Database';
    protected $name        = 'check:config-permissions';
    protected $description = 'Verifica se as permissões de configurações estão corretas';

    public function run(array $params)
    {
        $db = \Config\Database::connect();

        CLI::write('=== Verificando Permissões de Configurações ===', 'yellow');
        CLI::newLine();

        // Ver se as permissões existem
        $perms = $db->query("SELECT id, key_name, description FROM permissions WHERE key_name LIKE 'configuracoes%'")->getResultArray();

        if (empty($perms)) {
            CLI::error('❌ ERRO: Permissões de configurações NÃO encontradas!');
            CLI::write('Execute: php spark migrate', 'yellow');
            CLI::newLine();
        } else {
            CLI::write('✅ Permissões encontradas:', 'green');
            foreach ($perms as $p) {
                CLI::write("  - ID: {$p['id']} | {$p['key_name']} | {$p['description']}");
            }
            CLI::newLine();
        }

        // Ver se o admin tem essas permissões
        $adminPerms = $db->query("
            SELECT p.key_name 
            FROM permissions p 
            INNER JOIN role_permissions rp ON rp.permission_id = p.id 
            WHERE rp.role_id = 1 AND p.key_name LIKE 'configuracoes%'
        ")->getResultArray();

        if (empty($adminPerms)) {
            CLI::error('❌ ERRO: Role Admin (id=1) NÃO tem permissões de configurações!');
            CLI::write('Execute: php spark migrate', 'yellow');
            CLI::newLine();
        } else {
            CLI::write('✅ Role Admin tem as permissões:', 'green');
            foreach ($adminPerms as $p) {
                CLI::write("  - {$p['key_name']}");
            }
        }
    }
}
