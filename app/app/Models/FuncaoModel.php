<?php

namespace App\Models;

use CodeIgniter\Model;

class FuncaoModel extends Model
{
    protected $table = 'funcoes';
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
        'nome' => 'required|min_length[3]|max_length[100]|is_unique[funcoes.nome,id,{id}]',
    ];

    protected $validationMessages = [
        'nome' => [
            'is_unique' => 'Esta função já está cadastrada.',
            'required' => 'O nome da função é obrigatório.',
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
     * Get active funcoes
     */
    public function getAtivas(): array
    {
        return $this->where('ativo', true)
                    ->orderBy('nome', 'ASC')
                    ->findAll();
    }
}
