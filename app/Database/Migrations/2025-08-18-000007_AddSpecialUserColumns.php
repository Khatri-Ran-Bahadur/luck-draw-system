<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSpecialUserColumns extends Migration
{
    public function up()
    {
        // Add special user columns to users table
        $this->forge->addColumn('users', [
            'is_special_user' => [
                'type' => 'BOOLEAN',
                'default' => false,
                'after' => 'wallet_type'
            ],
            'wallet_active' => [
                'type' => 'BOOLEAN',
                'default' => false,
                'after' => 'is_special_user'
            ],
            'bank_name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
                'after' => 'wallet_active'
            ]
        ]);
    }

    public function down()
    {
        // Remove special user columns from users table
        $this->forge->dropColumn('users', ['is_special_user', 'wallet_active', 'bank_name']);
    }
}
