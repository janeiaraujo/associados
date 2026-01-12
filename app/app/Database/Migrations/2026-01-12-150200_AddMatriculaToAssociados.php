<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddMatriculaToAssociados extends Migration
{
    public function up()
    {
        $this->forge->addColumn('associados', [
            'matricula' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
                'after' => 'cpf',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('associados', 'matricula');
    }
}
