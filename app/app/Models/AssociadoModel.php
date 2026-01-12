<?php

namespace App\Models;

use CodeIgniter\Model;

class AssociadoModel extends Model
{
    protected $table = 'associados';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'nome',
        'unidade',
        'matricula_docas',
        'funcao',
        'data_nascimento',
        'cpf',
        'telefone',
        'email',
        'endereco',
        'matricula_sindical',
        'status'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    // Validation
    protected $validationRules = [
        'nome' => 'required|min_length[3]|max_length[255]',
        'cpf' => 'required|exact_length[14]|is_unique[associados.cpf,id,{id}]',
        'email' => 'permit_empty|valid_email|max_length[150]',
    ];

    protected $validationMessages = [
        'cpf' => [
            'is_unique' => 'Este CPF já está cadastrado.',
            'exact_length' => 'CPF deve ter 14 caracteres (formato: 000.000.000-00).',
        ],
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = [];
    protected $afterInsert = [];
    protected $beforeUpdate = [];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];

    /**
     * Search associados with filters
     */
    public function searchAssociados(array $filters = [], int $perPage = 20)
    {
        $builder = $this->builder();

        // Search text
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $builder->groupStart()
                ->like('nome', $search)
                ->orLike('cpf', $search)
                ->orLike('email', $search)
                ->orLike('matricula_docas', $search)
                ->orLike('matricula_sindical', $search)
                ->groupEnd();
        }

        // Filter by unidade
        if (!empty($filters['unidade'])) {
            $builder->where('unidade', $filters['unidade']);
        }

        // Filter by funcao
        if (!empty($filters['funcao'])) {
            $builder->where('funcao', $filters['funcao']);
        }

        // Filter by status
        if (!empty($filters['status'])) {
            $builder->where('status', $filters['status']);
        }

        // Filter by age range
        if (!empty($filters['idade_min']) || !empty($filters['idade_max'])) {
            $today = date('Y-m-d');
            
            if (!empty($filters['idade_max'])) {
                $minDate = date('Y-m-d', strtotime("-{$filters['idade_max']} years"));
                $builder->where('data_nascimento >=', $minDate);
            }
            
            if (!empty($filters['idade_min'])) {
                $maxDate = date('Y-m-d', strtotime("-{$filters['idade_min']} years"));
                $builder->where('data_nascimento <=', $maxDate);
            }
        }

        $builder->orderBy('nome', 'ASC');

        return $builder->paginate($perPage);
    }

    /**
     * Get all unidades (distinct)
     */
    public function getUnidades(): array
    {
        return $this->select('unidade')
            ->distinct()
            ->where('unidade IS NOT NULL')
            ->orderBy('unidade', 'ASC')
            ->findAll();
    }

    /**
     * Get all funcoes (distinct)
     */
    public function getFuncoes(): array
    {
        return $this->select('funcao')
            ->distinct()
            ->where('funcao IS NOT NULL')
            ->orderBy('funcao', 'ASC')
            ->findAll();
    }

    /**
     * Get associado by CPF
     */
    public function getByCpf(string $cpf): ?array
    {
        return $this->where('cpf', $cpf)->first();
    }

    /**
     * Upsert associado by CPF
     */
    public function upsertByCpf(array $data): array
    {
        $existing = $this->getByCpf($data['cpf']);
        
        if ($existing) {
            $this->update($existing['id'], $data);
            return ['action' => 'updated', 'id' => $existing['id']];
        } else {
            $id = $this->insert($data);
            return ['action' => 'inserted', 'id' => $id];
        }
    }

    /**
     * Get statistics
     */
    public function getStatistics(): array
    {
        $db = \Config\Database::connect();
        
        // Total
        $total = $this->countAll();
        
        // By status
        $ativos = $this->where('status', 'ATIVO')->countAllResults(false);
        $inativos = $this->where('status', 'INATIVO')->countAllResults();
        
        // By unidade
        $byUnidade = $db->table($this->table)
            ->select('unidade, COUNT(*) as total')
            ->groupBy('unidade')
            ->orderBy('total', 'DESC')
            ->limit(5)
            ->get()
            ->getResultArray();
        
        // By funcao
        $byFuncao = $db->table($this->table)
            ->select('funcao, COUNT(*) as total')
            ->groupBy('funcao')
            ->orderBy('total', 'DESC')
            ->limit(5)
            ->get()
            ->getResultArray();
        
        // Age distribution
        $ageDistribution = $this->getAgeDistribution();
        
        return [
            'total' => $total,
            'ativos' => $ativos,
            'inativos' => $inativos,
            'by_unidade' => $byUnidade,
            'by_funcao' => $byFuncao,
            'age_distribution' => $ageDistribution,
        ];
    }

    /**
     * Get age distribution
     */
    public function getAgeDistribution(): array
    {
        $db = \Config\Database::connect();
        
        $ranges = [
            '18-25' => ['min' => 18, 'max' => 25],
            '26-35' => ['min' => 26, 'max' => 35],
            '36-45' => ['min' => 36, 'max' => 45],
            '46-60' => ['min' => 46, 'max' => 60],
            '60+' => ['min' => 60, 'max' => 120],
        ];
        
        $distribution = [];
        
        foreach ($ranges as $label => $range) {
            $minDate = date('Y-m-d', strtotime("-{$range['max']} years"));
            $maxDate = date('Y-m-d', strtotime("-{$range['min']} years"));
            
            $count = $db->table($this->table)
                ->where('data_nascimento >=', $minDate)
                ->where('data_nascimento <=', $maxDate)
                ->countAllResults();
            
            $distribution[$label] = $count;
        }
        
        return $distribution;
    }

    /**
     * Get status distribution
     */
    public function getStatusDistribution(): array
    {
        $db = \Config\Database::connect();
        
        $ativos = $db->table($this->table)
            ->where('status', 'ativo')
            ->countAllResults();
            
        $inativos = $db->table($this->table)
            ->where('status', 'inativo')
            ->countAllResults();
        
        return [
            'ativo' => $ativos,
            'inativo' => $inativos,
        ];
    }
}
