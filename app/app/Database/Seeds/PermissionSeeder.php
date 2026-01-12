<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            // Dashboard
            ['key_name' => 'dashboard.view', 'description' => 'Visualizar dashboard'],
            
            // Associados
            ['key_name' => 'associados.view', 'description' => 'Visualizar associados'],
            ['key_name' => 'associados.create', 'description' => 'Criar associados'],
            ['key_name' => 'associados.update', 'description' => 'Editar associados'],
            ['key_name' => 'associados.delete', 'description' => 'Excluir associados'],
            ['key_name' => 'associados.import', 'description' => 'Importar associados'],
            
            // Unidades
            ['key_name' => 'unidades.view', 'description' => 'Visualizar unidades'],
            ['key_name' => 'unidades.create', 'description' => 'Criar unidades'],
            ['key_name' => 'unidades.update', 'description' => 'Editar unidades'],
            ['key_name' => 'unidades.delete', 'description' => 'Excluir unidades'],
            
            // Funções
            ['key_name' => 'funcoes.view', 'description' => 'Visualizar funções'],
            ['key_name' => 'funcoes.create', 'description' => 'Criar funções'],
            ['key_name' => 'funcoes.update', 'description' => 'Editar funções'],
            ['key_name' => 'funcoes.delete', 'description' => 'Excluir funções'],
            
            // Relatórios
            ['key_name' => 'relatorios.view', 'description' => 'Visualizar relatórios'],
            ['key_name' => 'relatorios.export', 'description' => 'Exportar relatórios'],
            
            // Usuários
            ['key_name' => 'users.manage', 'description' => 'Gerenciar usuários'],
            
            // Auditoria
            ['key_name' => 'audit.view', 'description' => 'Visualizar logs de auditoria'],
            
            // Configurações
            ['key_name' => 'config.view', 'description' => 'Visualizar configurações'],
        ];

        // Insert permissions
        $permissionModel = model('PermissionModel');
        foreach ($permissions as $permission) {
            $permissionModel->insert($permission);
        }

        echo "Permissions created successfully!\n";
    }
}
