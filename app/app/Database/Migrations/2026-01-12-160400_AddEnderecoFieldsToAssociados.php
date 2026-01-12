<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddEnderecoFieldsToAssociados extends Migration
{
    public function up()
    {
        $fields = [
            'endereco_cep' => [
                'type' => 'VARCHAR',
                'constraint' => 10,
                'null' => true,
                'after' => 'email',
            ],
            'endereco_logradouro' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'endereco_numero' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true,
            ],
            'endereco_complemento' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'endereco_bairro' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'endereco_cidade' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'endereco_estado' => [
                'type' => 'CHAR',
                'constraint' => 2,
                'null' => true,
            ],
        ];
        
        $this->forge->addColumn('associados', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('associados', [
            'endereco_cep',
            'endereco_logradouro',
            'endereco_numero',
            'endereco_complemento',
            'endereco_bairro',
            'endereco_cidade',
            'endereco_estado',
        ]);
    }
}
