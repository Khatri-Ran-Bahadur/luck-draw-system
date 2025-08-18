<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateEntries extends Migration
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
            'user_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'lucky_draw_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'entry_number' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'unique' => true,
            ],
            'payment_status' => [
                'type' => 'ENUM',
                'constraint' => ['pending', 'completed', 'failed', 'refunded'],
                'default' => 'pending',
            ],
            'payment_method' => [
                'type' => 'ENUM',
                'constraint' => ['easypaisa', 'paypal'],
                'null' => true,
            ],
            'payment_reference' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'amount_paid' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'entry_date' => [
                'type' => 'DATETIME',
                'null' => false,
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
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('lucky_draw_id', 'lucky_draws', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('entries');
    }

    public function down()
    {
        $this->forge->dropTable('entries');
    }
}
