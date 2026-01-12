<?php

namespace App\Models;

use CodeIgniter\Model;

class ReportLogModel extends Model
{
    protected $table = 'report_logs';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'report_name',
        'filters',
        'result_count',
        'user_id'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [
        'filters' => 'json',
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
     * Log report generation
     */
    public function logReport(string $reportName, array $filters, int $resultCount, ?int $userId = null): bool
    {
        $data = [
            'report_name' => $reportName,
            'filters' => json_encode($filters),
            'result_count' => $resultCount,
            'user_id' => $userId,
        ];
        
        return $this->insert($data) !== false;
    }

    /**
     * Get recent reports
     */
    public function getRecentReports(int $limit = 10): array
    {
        $builder = $this->builder();
        $builder->select('report_logs.*, users.name as user_name');
        $builder->join('users', 'users.id = report_logs.user_id', 'left');
        $builder->orderBy('report_logs.created_at', 'DESC');
        $builder->limit($limit);
        
        return $builder->get()->getResultArray();
    }
}
