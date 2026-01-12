<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAssociadoContatosTable extends Migration
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
            'tipo' => [
                'type' => 'ENUM',
                'constraint' => ['celular', 'fixo', 'email', 'responsavel', 'outro'],
                'default' => 'celular',
            ],
            'valor' => [
                'type' => 'VARCHAR',
                'constraint' => 150,
            ],
            'observacao' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
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
        $this->forge->createTable('associado_contatos');
    }

    public function down()
    {
        $this->forge->dropTable('associado_contatos');
    }
}
