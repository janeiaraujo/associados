<?php

namespace App\Models;

use CodeIgniter\Model;

class ConfiguracaoModel extends Model
{
    protected $table = 'configuracoes';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'chave',
        'valor',
        'descricao',
        'tipo',
        'grupo'
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    /**
     * Obtém o valor de uma configuração pela chave
     */
    public function get(string $chave, $default = null)
    {
        $config = $this->where('chave', $chave)->first();
        return $config ? $config['valor'] : $default;
    }

    /**
     * Define o valor de uma configuração
     */
    public function setConfig(string $chave, $valor): bool
    {
        $config = $this->where('chave', $chave)->first();
        
        if ($config) {
            return $this->update($config['id'], ['valor' => $valor]);
        }
        
        return false;
    }

    /**
     * Obtém todas as configurações de um grupo
     */
    public function getByGroup(string $grupo): array
    {
        return $this->where('grupo', $grupo)->findAll();
    }

    /**
     * Obtém todas as configurações indexadas pela chave
     */
    public function getAllIndexed(): array
    {
        $configs = $this->findAll();
        $indexed = [];
        
        foreach ($configs as $config) {
            $indexed[$config['chave']] = $config['valor'];
        }
        
        return $indexed;
    }

    /**
     * Obtém os dados formatados da empresa
     */
    public function getEmpresaData(): array
    {
        $configs = $this->getAllIndexed();
        
        return [
            'nome' => $configs['empresa_nome'] ?? '',
            'nome_completo' => $configs['empresa_nome_completo'] ?? '',
            'cnpj' => $configs['empresa_cnpj'] ?? '',
            'inscricao_estadual' => $configs['empresa_inscricao_estadual'] ?? '',
            'inscricao_municipal' => $configs['empresa_inscricao_municipal'] ?? '',
            'endereco' => [
                'logradouro' => $configs['endereco_logradouro'] ?? '',
                'numero' => $configs['endereco_numero'] ?? '',
                'complemento' => $configs['endereco_complemento'] ?? '',
                'bairro' => $configs['endereco_bairro'] ?? '',
                'cidade' => $configs['endereco_cidade'] ?? '',
                'estado' => $configs['endereco_estado'] ?? '',
                'cep' => $configs['endereco_cep'] ?? '',
                'completo' => $this->getEnderecoCompleto($configs),
            ],
            'contato' => [
                'telefone' => $configs['contato_telefone'] ?? '',
                'telefone2' => $configs['contato_telefone2'] ?? '',
                'celular' => $configs['contato_celular'] ?? '',
                'whatsapp' => $configs['contato_whatsapp'] ?? '',
                'email' => $configs['contato_email'] ?? '',
                'site' => $configs['contato_site'] ?? '',
                'telefone_formatado' => $this->formatTelefone($configs['contato_telefone'] ?? ''),
            ],
            'logos' => [
                'principal' => $configs['logo_principal'] ?? 'assets/images/logo.png',
                'relatorio' => $configs['logo_relatorio'] ?? 'assets/images/logo.png',
            ],
        ];
    }

    /**
     * Monta o endereço completo
     */
    private function getEnderecoCompleto(array $configs): string
    {
        $partes = [];
        
        if (!empty($configs['endereco_logradouro'])) {
            $partes[] = $configs['endereco_logradouro'];
        }
        if (!empty($configs['endereco_numero'])) {
            $partes[] = $configs['endereco_numero'];
        }
        if (!empty($configs['endereco_complemento'])) {
            $partes[] = $configs['endereco_complemento'];
        }
        if (!empty($configs['endereco_bairro'])) {
            $partes[] = $configs['endereco_bairro'];
        }
        if (!empty($configs['endereco_cidade'])) {
            $cidade = $configs['endereco_cidade'];
            if (!empty($configs['endereco_estado'])) {
                $cidade .= '/' . $configs['endereco_estado'];
            }
            $partes[] = $cidade;
        }
        if (!empty($configs['endereco_cep'])) {
            $partes[] = 'CEP ' . $configs['endereco_cep'];
        }
        
        return implode(', ', $partes);
    }

    /**
     * Formata número de telefone
     */
    private function formatTelefone(string $telefone): string
    {
        $telefone = preg_replace('/[^0-9]/', '', $telefone);
        
        if (strlen($telefone) === 11) {
            return '(' . substr($telefone, 0, 2) . ') ' . substr($telefone, 2, 5) . '-' . substr($telefone, 7);
        }
        if (strlen($telefone) === 10) {
            return '(' . substr($telefone, 0, 2) . ') ' . substr($telefone, 2, 4) . '-' . substr($telefone, 6);
        }
        
        return $telefone;
    }
}
