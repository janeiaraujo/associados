<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateAssociadosAddNewFields extends Migration
{
    public function up()
    {
        // Verifica se coluna matricula existe antes de remover
        $db = \Config\Database::connect();
        if ($db->fieldExists('matricula', 'associados')) {
            $this->forge->dropColumn('associados', 'matricula');
        }
        
        // Adiciona novos campos apenas se não existirem
        $fieldsToAdd = [];
        
        if (!$db->fieldExists('registro', 'associados')) {
            $fieldsToAdd['registro'] = [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
                'after' => 'unidade_id'
            ];
        }
        
        if (!$db->fieldExists('matricula_sindical', 'associados')) {
            $fieldsToAdd['matricula_sindical'] = [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
                'after' => 'registro'
            ];
        }
        
        if (!$db->fieldExists('tipo_aposentado', 'associados')) {
            $fieldsToAdd['tipo_aposentado'] = [
                'type' => 'ENUM',
                'constraint' => ['CLT', 'PENSIONISTA', 'NAO_APOSENTADO'],
                'default' => 'NAO_APOSENTADO',
                'null' => false,
                'after' => 'funcao_id'
            ];
        }
        
        if (!empty($fieldsToAdd)) {
            $this->forge->addColumn('associados', $fieldsToAdd);
        }
    }

    public function down()
    {
        // Remove novos campos
        $this->forge->dropColumn('associados', ['registro', 'matricula_sindical', 'tipo_aposentado']);
        
        // Restaura coluna matricula
        $fields = [
            'matricula' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
                'after' => 'unidade_id'
            ]
        ];
        
        $this->forge->addColumn('associados', $fields);
    }
}
