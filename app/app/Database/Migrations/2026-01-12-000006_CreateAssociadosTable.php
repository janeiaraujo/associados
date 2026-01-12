<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAssociadosTable extends Migration
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
            'nome' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'unidade' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'matricula_docas' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
            ],
            'funcao' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'data_nascimento' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'cpf' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'unique' => true,
            ],
            'telefone' => [
                'type' => 'VARCHAR',
                'constraint' => 30,
                'null' => true,
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => 150,
                'null' => true,
            ],
            'endereco' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'matricula_sindical' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['ATIVO', 'INATIVO'],
                'default' => 'ATIVO',
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
        $this->forge->createTable('associados');
    }

    public function down()
    {
        $this->forge->dropTable('associados');
    }
}
