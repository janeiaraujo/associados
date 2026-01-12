<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateAssociadosUnidadeFuncaoToFK extends Migration
{
    public function up()
    {
        // Primeiro, migrar dados existentes para as novas tabelas
        // Remover a coluna unidade e funcao (VARCHAR) e adicionar unidade_id e funcao_id (INT)
        
        $this->forge->dropColumn('associados', ['unidade', 'funcao']);
        
        $fields = [
            'unidade_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'after' => 'nome',
            ],
            'funcao_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'after' => 'unidade_id',
            ],
        ];
        
        $this->forge->addColumn('associados', $fields);
        
        // Adicionar foreign keys
        $this->forge->addForeignKey('unidade_id', 'unidades', 'id', 'SET NULL', 'CASCADE', 'fk_associados_unidade');
        $this->forge->addForeignKey('funcao_id', 'funcoes', 'id', 'SET NULL', 'CASCADE', 'fk_associados_funcao');
        $this->forge->processIndexes('associados');
    }

    public function down()
    {
        $this->db->disableForeignKeyChecks();
        
        $this->forge->dropForeignKey('associados', 'fk_associados_unidade');
        $this->forge->dropForeignKey('associados', 'fk_associados_funcao');
        
        $this->forge->dropColumn('associados', ['unidade_id', 'funcao_id']);
        
        $fields = [
            'unidade' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'funcao' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
        ];
        
        $this->forge->addColumn('associados', $fields);
        
        $this->db->enableForeignKeyChecks();
    }
}
