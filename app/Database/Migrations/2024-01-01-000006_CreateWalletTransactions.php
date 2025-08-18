<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateWalletTransactions extends Migration
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
            'wallet_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'type' => [
                'type' => 'ENUM',
                'constraint' => ['deposit', 'withdrawal', 'draw_entry', 'draw_win', 'refund'],
                'default' => 'deposit',
            ],
            'amount' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'payment_method' => [
                'type' => 'ENUM',
                'constraint' => ['paypal', 'easypaisa', 'wallet'],
                'null' => true,
            ],
            'payment_reference' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['pending', 'completed', 'failed', 'cancelled'],
                'default' => 'pending',
            ],
            'description' => [
                'type' => 'TEXT',
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
            'metadata' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'balance_before' => [
                'type' => 'DECIMAL',
                'constraint' => '20,2',
                'null' => true,
            ],
            'balance_after' => [
                'type' => 'DECIMAL',
                'constraint' => '20,2',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('wallet_id', 'wallets', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('wallet_transactions');
    }

    public function down()
    {
        $this->forge->dropTable('wallet_transactions');
    }
}
