<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UpdateUsersPermissionsSeeder extends Seeder
{
    public function run()
    {
        $permissionModel = model('PermissionModel');
        $db = \Config\Database::connect();
        
        // Novas permissões de usuários
        $newPermissions = [
            ['key_name' => 'users.view', 'description' => 'Visualizar usuários'],
            ['key_name' => 'users.create', 'description' => 'Criar usuários'],
            ['key_name' => 'users.update', 'description' => 'Editar usuários'],
            ['key_name' => 'users.delete', 'description' => 'Excluir usuários'],
        ];

        $permissionIds = [];
        
        // Inserir novas permissões
        foreach ($newPermissions as $permission) {
            // Verificar se já existe
            $existing = $permissionModel->where('key_name', $permission['key_name'])->first();
            
            if (!$existing) {
                $permissionModel->insert($permission);
                $permissionIds[] = $permissionModel->getInsertID();
                echo "✓ Permissão criada: {$permission['key_name']}\n";
            } else {
                $permissionIds[] = $existing['id'];
                echo "- Permissão já existe: {$permission['key_name']}\n";
            }
        }

        // Atribuir todas as novas permissões ao perfil Admin (role_id = 1)
        $adminRole = model('RoleModel')->find(1);
        
        if ($adminRole) {
            foreach ($permissionIds as $permissionId) {
                // Verificar se já existe
                $existing = $db->table('role_permissions')
                    ->where('role_id', 1)
                    ->where('permission_id', $permissionId)
                    ->get()
                    ->getRow();
                
                if (!$existing) {
                    $db->table('role_permissions')->insert([
                        'role_id' => 1,
                        'permission_id' => $permissionId,
                    ]);
                }
            }
            echo "✓ Permissões atribuídas ao perfil Admin\n";
        }

        echo "\nPermissões de usuários atualizadas com sucesso!\n";
    }
}
