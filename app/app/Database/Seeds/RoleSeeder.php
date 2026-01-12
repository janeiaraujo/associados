<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run()
    {
        $roleModel = model('RoleModel');
        $permissionModel = model('PermissionModel');

        // Create roles
        $roles = [
            [
                'name' => 'admin',
                'description' => 'Administrador com acesso total ao sistema',
            ],
            [
                'name' => 'manager',
                'description' => 'Gerente com permissões de gestão e importação',
            ],
            [
                'name' => 'viewer',
                'description' => 'Visualizador com acesso somente leitura',
            ],
        ];

        $roleIds = [];
        foreach ($roles as $role) {
            $roleIds[$role['name']] = $roleModel->insert($role);
        }

        // Get all permissions
        $allPermissions = $permissionModel->findAll();
        $permissionsByKey = [];
        foreach ($allPermissions as $permission) {
            $permissionsByKey[$permission['key_name']] = $permission['id'];
        }

        // Assign permissions to admin (all permissions)
        $adminPermissions = array_column($allPermissions, 'id');
        $roleModel->assignPermissions($roleIds['admin'], $adminPermissions);

        // Assign permissions to manager
        $managerPermissions = [
            'dashboard.view',
            'associados.view',
            'associados.create',
            'associados.update',
            'associados.delete',
            'associados.import',
            'relatorios.view',
            'relatorios.export',
        ];
        $managerPermissionIds = array_map(function($key) use ($permissionsByKey) {
            return $permissionsByKey[$key];
        }, $managerPermissions);
        $roleModel->assignPermissions($roleIds['manager'], $managerPermissionIds);

        // Assign permissions to viewer
        $viewerPermissions = [
            'dashboard.view',
            'associados.view',
            'relatorios.view',
        ];
        $viewerPermissionIds = array_map(function($key) use ($permissionsByKey) {
            return $permissionsByKey[$key];
        }, $viewerPermissions);
        $roleModel->assignPermissions($roleIds['viewer'], $viewerPermissionIds);

        echo "Roles created and permissions assigned successfully!\n";
    }
}
