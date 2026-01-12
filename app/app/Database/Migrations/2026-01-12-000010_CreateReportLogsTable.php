<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateReportLogsTable extends Migration
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
            'report_name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'filters' => [
                'type' => 'JSON',
                'null' => true,
            ],
            'result_count' => [
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
        $this->forge->createTable('report_logs');
    }

    public function down()
    {
        $this->forge->dropTable('report_logs');
    }
}
