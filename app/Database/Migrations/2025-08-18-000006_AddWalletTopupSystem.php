<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddWalletTopupSystem extends Migration
{
    public function up()
    {
        // Add wallet details fields to users table
        $this->forge->addColumn('users', [
            'wallet_name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
                'after' => 'referral_bonus_earned'
            ],
            'wallet_number' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
                'after' => 'wallet_name'
            ],
            'wallet_type' => [
                'type' => 'ENUM',
                'constraint' => ['easypaisa', 'jazz_cash', 'bank', 'hbl', 'ubank', 'mcb', 'abank', 'nbp', 'sbank', 'citi', 'hsbc', 'payoneer', 'skrill', 'neteller', 'other'],
                'default' => 'easypaisa',
                'after' => 'wallet_number'
            ]
        ]);

        // Create wallet_topup_requests table
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
            'amount' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => false,
            ],
            'payment_method' => [
                'type' => 'ENUM',
                'constraint' => ['easypaisa', 'jazz_cash', 'bank', 'manual'],
                'default' => 'manual',
            ],
            'payment_proof' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['pending', 'approved', 'rejected'],
                'default' => 'pending',
            ],
            'admin_notes' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'processed_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'processed_at' => [
                'type' => 'DATETIME',
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
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('processed_by', 'users', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('wallet_topup_requests');

        // Create user_transfers table for user-to-user transfers
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'from_user_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'to_user_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'amount' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => false,
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['pending', 'completed', 'failed', 'cancelled'],
                'default' => 'pending',
            ],
            'admin_notes' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'processed_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'processed_at' => [
                'type' => 'DATETIME',
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
        $this->forge->addForeignKey('from_user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('to_user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('processed_by', 'users', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('user_transfers');

        // Add payment method settings to settings table
        $this->db->query("INSERT IGNORE INTO settings (`key`, value, description, created_at, updated_at) VALUES
            ('paypal_enabled', 'true', 'Enable/disable PayPal payments', NOW(), NOW()),
            ('easypaisa_enabled', 'true', 'Enable/disable Easypaisa payments', NOW(), NOW()),
            ('jazz_cash_enabled', 'true', 'Enable/disable Jazz Cash payments', NOW(), NOW()),
            ('bank_transfer_enabled', 'true', 'Enable/disable bank transfer payments', NOW(), NOW()),
            ('manual_topup_enabled', 'true', 'Enable/disable manual top-up requests', NOW(), NOW()),
            ('min_topup_amount', '500.00', 'Minimum top-up amount in PKR', NOW(), NOW()),
            ('max_topup_amount', '50000.00', 'Maximum top-up amount in PKR', NOW(), NOW()),
            ('topup_approval_required', 'true', 'Require admin approval for top-ups', NOW(), NOW()),
            ('user_transfer_enabled', 'true', 'Enable user-to-user wallet transfers', NOW(), NOW()),
            ('transfer_fee_percentage', '0.00', 'Transfer fee as percentage of amount', NOW(), NOW()),
            ('random_wallet_display', 'true', 'Show random wallet details for top-ups', NOW(), NOW()),
            ('wallet_display_count', '3', 'Number of random wallets to display', NOW(), NOW())");
    }

    public function down()
    {
        // Remove wallet details fields from users table
        $this->forge->dropColumn('users', ['wallet_name', 'wallet_number', 'wallet_type']);
        
        // Drop tables
        $this->forge->dropTable('wallet_topup_requests');
        $this->forge->dropTable('user_transfers');
        
        // Remove payment method settings
        $this->db->query("DELETE FROM settings WHERE `key` IN (
            'paypal_enabled', 'easypaisa_enabled', 'jazz_cash_enabled', 'bank_transfer_enabled',
            'manual_topup_enabled', 'min_topup_amount', 'max_topup_amount', 'topup_approval_required',
            'user_transfer_enabled', 'transfer_fee_percentage', 'random_wallet_display', 'wallet_display_count'
        )");
    }
}
