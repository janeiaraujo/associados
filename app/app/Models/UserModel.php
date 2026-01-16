<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'name',
        'email',
        'password',
        'password_hash',
        'is_active',
        'force_password_change',
        'last_login_at'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [
        'is_active' => 'boolean',
        'force_password_change' => 'boolean',
    ];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    // Validation
    protected $validationRules = [
        'id' => 'permit_empty|is_natural_no_zero',
        'name' => 'required|min_length[3]|max_length[150]',
        'email' => 'required|valid_email|max_length[150]|is_unique[users.email,id,{id}]',
        'password' => 'permit_empty|min_length[6]',
        'password_hash' => 'permit_empty',
    ];

    protected $validationMessages = [
        'email' => [
            'is_unique' => 'Este e-mail já está cadastrado.',
            'valid_email' => 'Por favor, insira um e-mail válido.',
        ],
        'password' => [
            'min_length' => 'A senha deve ter no mínimo 6 caracteres.',
        ],
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = ['hashPassword'];
    protected $afterInsert = [];
    protected $beforeUpdate = ['hashPassword'];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];

    /**
     * Get user with their roles
     */
    public function getUserWithRoles(int $userId): ?array
    {
        $user = $this->find($userId);
        
        if (!$user) {
            return null;
        }

        $db = \Config\Database::connect();
        $builder = $db->table('user_roles');
        $builder->select('roles.id, roles.name, roles.description');
        $builder->join('roles', 'roles.id = user_roles.role_id');
        $builder->where('user_roles.user_id', $userId);
        $user['roles'] = $builder->get()->getResultArray();

        return $user;
    }

    /**
     * Get user permissions through roles
     */
    public function getUserPermissions(int $userId): array
    {
        $db = \Config\Database::connect();
        $builder = $db->table('user_roles');
        $builder->select('permissions.key_name');
        $builder->join('role_permissions', 'role_permissions.role_id = user_roles.role_id');
        $builder->join('permissions', 'permissions.id = role_permissions.permission_id');
        $builder->where('user_roles.user_id', $userId);
        $builder->distinct();
        
        $results = $builder->get()->getResultArray();
        
        return array_column($results, 'key_name');
    }

    /**
     * Check if user has permission
     */
    public function hasPermission(int $userId, string $permission): bool
    {
        $permissions = $this->getUserPermissions($userId);
        return in_array($permission, $permissions);
    }

    /**
     * Assign roles to user
     */
    public function assignRoles(int $userId, array $roleIds): bool
    {
        $db = \Config\Database::connect();
        
        // Remove existing roles
        $db->table('user_roles')->where('user_id', $userId)->delete();
        
        // Insert new roles
        if (!empty($roleIds)) {
            $data = array_map(function($roleId) use ($userId) {
                return ['user_id' => $userId, 'role_id' => $roleId];
            }, $roleIds);
            
            return $db->table('user_roles')->insertBatch($data);
        }
        
        return true;
    }

    /**
     * Get user by email
     */
    public function getUserByEmail(string $email): ?array
    {
        return $this->where('email', $email)->first();
    }

    /**
     * Update last login
     */
    public function updateLastLogin(int $userId): bool
    {
        return $this->update($userId, ['last_login_at' => date('Y-m-d H:i:s')]);
    }

    /**
     * Hash password before insert or update
     */
    protected function hashPassword(array $data): array
    {
        // CodeIgniter callbacks receive data in $data['data'] key
        if (!isset($data['data'])) {
            return $data;
        }
        
        if (isset($data['data']['password']) && !empty($data['data']['password'])) {
            $data['data']['password_hash'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
            unset($data['data']['password']);
        }
        
        return $data;
    }
}
