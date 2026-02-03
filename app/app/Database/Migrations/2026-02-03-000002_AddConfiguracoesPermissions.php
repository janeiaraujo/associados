<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddConfiguracoesPermissions extends Migration
{
    public function up()
    {
        // Adicionar permissões de configurações
        $permissions = [
            ['key_name' => 'configuracoes.view', 'description' => 'Visualizar configurações do sistema'],
            ['key_name' => 'configuracoes.update', 'description' => 'Alterar configurações do sistema'],
        ];

        foreach ($permissions as $permission) {
            // Verificar se já existe
            $exists = $this->db->table('permissions')
                ->where('key_name', $permission['key_name'])
                ->countAllResults();
            
            if ($exists === 0) {
                $this->db->table('permissions')->insert($permission);
            }
        }

        // Atribuir permissões ao role admin (role_id = 1)
        $adminRoleId = 1;
        
        // Obter IDs das permissões
        $permissionIds = $this->db->table('permissions')
            ->whereIn('key_name', ['configuracoes.view', 'configuracoes.update'])
            ->get()
            ->getResultArray();
        
        foreach ($permissionIds as $perm) {
            // Verificar se já existe a associação
            $exists = $this->db->table('role_permissions')
                ->where('role_id', $adminRoleId)
                ->where('permission_id', $perm['id'])
                ->countAllResults();
            
            if ($exists === 0) {
                $this->db->table('role_permissions')->insert([
                    'role_id' => $adminRoleId,
                    'permission_id' => $perm['id'],
                ]);
            }
        }
    }

    public function down()
    {
        // Remover permissões
        $this->db->table('permissions')
            ->whereIn('key_name', ['configuracoes.view', 'configuracoes.update'])
            ->delete();
    }
}
