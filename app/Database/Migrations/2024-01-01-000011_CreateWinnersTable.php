<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateWinnersTable extends Migration
{
    public function up()
    {
        // Create winners table
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'lucky_draw_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'user_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'position' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 1,
            ],
            'prize_amount' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'default' => 0.00,
            ],
            'is_claimed' => [
                'type' => 'BOOLEAN',
                'default' => false,
            ],
            'claim_details' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'claim_approved' => [
                'type' => 'BOOLEAN',
                'default' => false,
            ],
            'approved_at' => [
                'type' => 'DATETIME',
                'null' => true,
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
        $this->forge->addForeignKey('lucky_draw_id', 'lucky_draws', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('winners');
    }

    public function down()
    {
        $this->forge->dropTable('winners');
    }
}
