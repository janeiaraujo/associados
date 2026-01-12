<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UpdatePermissionsSeeder extends Seeder
{
    public function run()
    {
        $db = $this->db;
        
        // Get admin role
        $adminRole = $db->table('roles')->where('name', 'admin')->get()->getRow();
        if (!$adminRole) {
            echo "Admin role not found!\n";
            return;
        }
        
        // Get all permissions
        $allPermissions = $db->table('permissions')->select('id')->get()->getResultArray();
        $permissionIds = array_column($allPermissions, 'id');
        
        // Clear existing admin permissions
        $db->table('role_permissions')->where('role_id', $adminRole->id)->delete();
        
        // Assign all permissions to admin
        foreach ($permissionIds as $permId) {
            $db->table('role_permissions')->insert([
                'role_id' => $adminRole->id,
                'permission_id' => $permId
            ]);
        }
        
        echo "Admin permissions updated! Total: " . count($permissionIds) . "\n";
    }
}
