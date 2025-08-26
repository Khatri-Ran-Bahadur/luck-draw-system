<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCashDrawsTable extends Migration
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
            'title' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'description' => [
                'type' => 'TEXT',
            ],
            'entry_fee' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'default' => 0.00,
            ],
            'prize_amount' => [
                'type' => 'DECIMAL',
                'constraint' => '20,2',
                'default' => 0.00,
            ],
            'draw_date' => [
                'type' => 'DATETIME',
            ],
            'is_manual_selection' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['active', 'inactive', 'completed'],
                'default' => 'active',
            ],
            'total_winners' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'participant_count' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
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
        $this->forge->createTable('cash_draws');
    }

    public function down()
    {
        $this->forge->dropTable('cash_draws');
    }
}
