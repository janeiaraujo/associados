<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAssociadoEnderecosTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'associado_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'cep' => [
                'type' => 'VARCHAR',
                'constraint' => 10,
                'null' => true,
            ],
            'logradouro' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'numero' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true,
            ],
            'complemento' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'bairro' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'cidade' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'estado' => [
                'type' => 'CHAR',
                'constraint' => 2,
            ],
            'principal' => [
                'type' => 'BOOLEAN',
                'default' => false,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('associado_id');
        $this->forge->addForeignKey('associado_id', 'associados', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('associado_enderecos');
    }

    public function down()
    {
        $this->forge->dropTable('associado_enderecos');
    }
}
