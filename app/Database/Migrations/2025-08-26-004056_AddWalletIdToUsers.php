<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddWalletIdToUsers extends Migration
{
    public function up()
    {
        // Add wallet_id column to users table
        $this->forge->addColumn('users', [
            'wallet_id' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
                'after' => 'id'
            ]
        ]);

        // Add unique index on wallet_id
        $this->db->query('ALTER TABLE users ADD UNIQUE KEY unique_wallet_id (wallet_id)');
    }

    public function down()
    {
        // Remove unique index
        $this->db->query('ALTER TABLE users DROP INDEX unique_wallet_id');
        
        // Remove wallet_id column
        $this->forge->dropColumn('users', 'wallet_id');
    }
}
