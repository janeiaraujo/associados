<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateAssociadosAddNewFields extends Migration
{
    public function up()
    {
        // Remove coluna matricula antiga
        $this->forge->dropColumn('associados', 'matricula');
        
        // Adiciona novos campos
        $fields = [
            'registro' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
                'after' => 'unidade_id'
            ],
            'matricula_sindical' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
                'after' => 'registro'
            ],
            'tipo_aposentado' => [
                'type' => 'ENUM',
                'constraint' => ['CLT', 'PENSIONISTA', 'NAO_APOSENTADO'],
                'default' => 'NAO_APOSENTADO',
                'null' => false,
                'after' => 'funcao_id'
            ]
        ];
        
        $this->forge->addColumn('associados', $fields);
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
