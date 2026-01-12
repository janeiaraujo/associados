<?php

namespace App\Models;

use CodeIgniter\Model;

class UnidadeModel extends Model
{
    protected $table = 'unidades';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ['nome', 'descricao', 'ativo'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation
    protected $validationRules = [
        'nome' => 'required|min_length[3]|max_length[100]|is_unique[unidades.nome,id,{id}]',
    ];

    protected $validationMessages = [
        'nome' => [
            'is_unique' => 'Esta unidade já está cadastrada.',
            'required' => 'O nome da unidade é obrigatório.',
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
     * Get active unidades
     */
    public function getAtivas(): array
    {
        return $this->where('ativo', true)
                    ->orderBy('nome', 'ASC')
                    ->findAll();
    }
}
