<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateImportLogsTable extends Migration
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
            'file_name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'total_rows' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
            ],
            'inserted' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
            ],
            'updated' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
            ],
            'skipped' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
            ],
            'user_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('user_id');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('import_logs');
    }

    public function down()
    {
        $this->forge->dropTable('import_logs');
    }
}
