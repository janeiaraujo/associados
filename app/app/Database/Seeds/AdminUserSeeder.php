<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        $userModel = model('UserModel');
        $roleModel = model('RoleModel');
        $auditLogModel = model('AuditLogModel');

        // Get admin role
        $adminRole = $roleModel->where('name', 'admin')->first();

        if (!$adminRole) {
            echo "Error: Admin role not found. Run RoleSeeder first.\n";
            return;
        }

        // Get admin credentials from .env
        $adminEmail = env('app.adminEmail', 'admin@associados.local');
        $adminName = env('app.adminName', 'Administrador');
        $adminPassword = env('app.adminPassword', 'Admin@123456');

        // Check if admin already exists
        $existingAdmin = $userModel->where('email', $adminEmail)->first();

        if ($existingAdmin) {
            echo "Admin user already exists: {$adminEmail}\n";
            return;
        }

        // Create admin user
        $userId = $userModel->insert([
            'name' => $adminName,
            'email' => $adminEmail,
            'password_hash' => password_hash($adminPassword, PASSWORD_BCRYPT),
            'is_active' => 1,
            'force_password_change' => 0,
        ]);

        if (!$userId) {
            echo "Error creating admin user.\n";
            return;
        }

        // Assign admin role
        $userModel->assignRoles($userId, [$adminRole['id']]);

        // Log the creation
        $auditLogModel->logAction(
            'users',
            $userId,
            'SEED_CREATE',
            null,
            [
                'name' => $adminName,
                'email' => $adminEmail,
                'role' => 'admin',
            ],
            null
        );

        echo "Admin user created successfully!\n";
        echo "Email: {$adminEmail}\n";
        echo "Password: {$adminPassword}\n";
        echo "IMPORTANT: Change the password after first login!\n";
    }
}
