<?php

namespace App\Models;

use CodeIgniter\Model;

class ImportLogModel extends Model
{
    protected $table = 'import_logs';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'file_name',
        'total_rows',
        'inserted',
        'updated',
        'skipped',
        'user_id'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
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
     * Get recent imports
     */
    public function getRecentImports(int $limit = 10): array
    {
        $builder = $this->builder();
        $builder->select('import_logs.*, users.name as user_name');
        $builder->join('users', 'users.id = import_logs.user_id', 'left');
        $builder->orderBy('import_logs.created_at', 'DESC');
        $builder->limit($limit);
        
        return $builder->get()->getResultArray();
    }
}
