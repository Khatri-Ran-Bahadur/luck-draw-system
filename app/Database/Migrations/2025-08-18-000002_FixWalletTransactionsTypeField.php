<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class FixWalletTransactionsTypeField extends Migration
{
    public function up()
    {
        // Fix the type field to ensure it has a proper default value
        $this->forge->modifyColumn('wallet_transactions', [
            'type' => [
                'type' => 'ENUM',
                'constraint' => ['deposit', 'withdrawal', 'draw_entry', 'draw_win', 'refund'],
                'default' => 'deposit',
            ]
        ]);

        // Update any existing records with empty type to 'deposit'
        $this->db->query("UPDATE wallet_transactions SET type = 'deposit' WHERE type = '' OR type IS NULL");
    }

    public function down()
    {
        // No rollback needed for this fix
    }
}
