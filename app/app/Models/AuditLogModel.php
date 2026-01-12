<?php

namespace App\Models;

use CodeIgniter\Model;

class AuditLogModel extends Model
{
    protected $table = 'audit_logs';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'entity',
        'entity_id',
        'action',
        'before_data',
        'after_data',
        'user_id',
        'ip',
        'user_agent'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [
        'before_data' => 'json',
        'after_data' => 'json',
    ];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = null;
    protected $deletedField = null;

    // Validation
    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;

    /**
     * Log an action
     */
    public function logAction(
        string $entity,
        ?int $entityId,
        string $action,
        $beforeData = null,
        $afterData = null,
        ?int $userId = null
    ): bool {
        $request = \Config\Services::request();
        
        $data = [
            'entity' => $entity,
            'entity_id' => $entityId,
            'action' => $action,
            'before_data' => $beforeData ? json_encode($beforeData) : null,
            'after_data' => $afterData ? json_encode($afterData) : null,
            'user_id' => $userId,
            'ip' => $request->getIPAddress(),
            'user_agent' => $request->getUserAgent()->getAgentString(),
        ];
        
        return $this->insert($data) !== false;
    }

    /**
     * Get recent logs
     */
    public function getRecentLogs(int $limit = 10, ?string $entity = null): array
    {
        $builder = $this->builder();
        $builder->select('audit_logs.*, users.name as user_name');
        $builder->join('users', 'users.id = audit_logs.user_id', 'left');
        
        if ($entity) {
            $builder->where('audit_logs.entity', $entity);
        }
        
        $builder->orderBy('audit_logs.created_at', 'DESC');
        $builder->limit($limit);
        
        return $builder->get()->getResultArray();
    }

    /**
     * Get logs by entity
     */
    public function getLogsByEntity(string $entity, int $entityId, int $limit = 50): array
    {
        return $this->where('entity', $entity)
            ->where('entity_id', $entityId)
            ->orderBy('created_at', 'DESC')
            ->limit($limit)
            ->findAll();
    }
}
