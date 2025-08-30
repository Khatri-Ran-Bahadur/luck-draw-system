<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateLuckDraws extends Migration
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
                'null' => true,
            ],
            'draw_date' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'entry_fee' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'default' => 0.00,
            ],
            'max_entries' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['upcoming', 'active', 'completed', 'cancelled'],
                'default' => 'upcoming',
            ],
            'winner_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],

            'created_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('winner_id', 'users', 'id', 'CASCADE', 'SET NULL');
        $this->forge->createTable('lucky_draws');
    }

    public function down()
    {
        $this->forge->dropTable('lucky_draws');
    }
}
