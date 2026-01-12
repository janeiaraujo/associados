<?php

namespace App\Models;

use CodeIgniter\Model;

class RoleModel extends Model
{
    protected $table = 'roles';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ['name', 'description'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = false;

    // Validation
    protected $validationRules = [
        'name' => 'required|max_length[50]|is_unique[roles.name,id,{id}]',
    ];

    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;

    /**
     * Get role with permissions
     */
    public function getRoleWithPermissions(int $roleId): ?array
    {
        $role = $this->find($roleId);
        
        if (!$role) {
            return null;
        }

        $db = \Config\Database::connect();
        $builder = $db->table('role_permissions');
        $builder->select('permissions.id, permissions.key_name, permissions.description');
        $builder->join('permissions', 'permissions.id = role_permissions.permission_id');
        $builder->where('role_permissions.role_id', $roleId);
        $role['permissions'] = $builder->get()->getResultArray();

        return $role;
    }

    /**
     * Assign permissions to role
     */
    public function assignPermissions(int $roleId, array $permissionIds): bool
    {
        $db = \Config\Database::connect();
        
        // Remove existing permissions
        $db->table('role_permissions')->where('role_id', $roleId)->delete();
        
        // Insert new permissions
        if (!empty($permissionIds)) {
            $data = array_map(function($permissionId) use ($roleId) {
                return ['role_id' => $roleId, 'permission_id' => $permissionId];
            }, $permissionIds);
            
            return $db->table('role_permissions')->insertBatch($data);
        }
        
        return true;
    }
}
