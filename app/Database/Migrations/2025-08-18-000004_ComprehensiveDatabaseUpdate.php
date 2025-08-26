<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ComprehensiveDatabaseUpdate extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();
        
        // Check if cash_draws table exists, if not create it
        if (!$db->tableExists('cash_draws')) {
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

        // Check if product_draws table exists, if not create it
        if (!$db->tableExists('product_draws')) {
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
                'product_name' => [
                    'type' => 'VARCHAR',
                    'constraint' => 255,
                    'null' => true,
                ],
                'product_image' => [
                    'type' => 'VARCHAR',
                    'constraint' => 255,
                    'null' => true,
                ],
                'product_price' => [
                    'type' => 'DECIMAL',
                    'constraint' => '10,2',
                    'null' => true,
                ],
                'description' => [
                    'type' => 'TEXT',
                ],
                'entry_fee' => [
                    'type' => 'DECIMAL',
                    'constraint' => '10,2',
                    'default' => 1.00,
                ],
                'draw_date' => [
                    'type' => 'DATETIME',
                ],
                'status' => [
                    'type' => 'ENUM',
                    'constraint' => ['active', 'inactive', 'completed'],
                    'default' => 'active',
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
            $this->forge->createTable('product_draws');
        }

        // Check if wallets table exists, if not create it
        if (!$db->tableExists('wallets')) {
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
                'balance' => [
                    'type' => 'DECIMAL',
                    'constraint' => '10,2',
                    'default' => 0.00,
                ],
                'currency' => [
                    'type' => 'VARCHAR',
                    'constraint' => 3,
                    'default' => 'PKR',
                ],
                'status' => [
                    'type' => 'ENUM',
                    'constraint' => ['active', 'blocked', 'suspended'],
                    'default' => 'active',
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
            $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
            $this->forge->createTable('wallets');
        }

        // Check if settings table exists, if not create it
        if (!$db->tableExists('settings')) {
            $this->forge->addField([
                'id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                    'auto_increment' => true,
                ],
                'key' => [
                    'type' => 'VARCHAR',
                    'constraint' => 100,
                    'unique' => true,
                ],
                'value' => [
                    'type' => 'TEXT',
                    'null' => true,
                ],
                'description' => [
                    'type' => 'VARCHAR',
                    'constraint' => 255,
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
            $this->forge->addKey('key', true);
            $this->forge->createTable('settings');
        }

        // Add missing columns to entries table if they don't exist
        $entriesFields = $db->getFieldData('entries');
        $entriesColumns = array_column($entriesFields, 'name');
        
        if (!in_array('cash_draw_id', $entriesColumns)) {
            $this->forge->addColumn('entries', [
                'cash_draw_id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                    'null' => true,
                    'after' => 'lucky_draw_id'
                ]
            ]);
        }
        
        if (!in_array('product_draw_id', $entriesColumns)) {
            $this->forge->addColumn('entries', [
                'product_draw_id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                    'null' => true,
                    'after' => 'cash_draw_id'
                ]
            ]);
        }
        
        if (!in_array('draw_type', $entriesColumns)) {
            $this->forge->addColumn('entries', [
                'draw_type' => [
                    'type' => 'ENUM',
                    'constraint' => ['cash', 'product'],
                    'null' => true,
                    'after' => 'product_draw_id'
                ]
            ]);
        }

        // Add missing columns to winners table if they don't exist
        $winnersFields = $db->getFieldData('winners');
        $winnersColumns = array_column($winnersFields, 'name');
        
        if (!in_array('cash_draw_id', $winnersColumns)) {
            $this->forge->addColumn('winners', [
                'cash_draw_id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                    'null' => true,
                    'after' => 'lucky_draw_id'
                ]
            ]);
        }
        
        if (!in_array('product_draw_id', $winnersColumns)) {
            $this->forge->addColumn('winners', [
                'product_draw_id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                    'null' => true,
                    'after' => 'cash_draw_id'
                ]
            ]);
        }
        
        if (!in_array('draw_type', $winnersColumns)) {
            $this->forge->addColumn('winners', [
                'draw_type' => [
                    'type' => 'ENUM',
                    'constraint' => ['cash', 'product'],
                    'null' => true,
                    'after' => 'product_draw_id'
                ]
            ]);
        }
        
        if (!in_array('participant_count', $winnersColumns)) {
            $this->forge->addColumn('winners', [
                'participant_count' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'default' => 0,
                    'after' => 'claim_approved'
                ]
            ]);
        }

        // Add missing columns to wallet_transactions if they don't exist
        $walletFields = $db->getFieldData('wallet_transactions');
        $walletColumns = array_column($walletFields, 'name');
        
        if (!in_array('balance_before', $walletColumns)) {
            $this->forge->addColumn('wallet_transactions', [
                'balance_before' => [
                    'type' => 'DECIMAL',
                    'constraint' => '20,2',
                    'null' => true,
                    'after' => 'amount'
                ]
            ]);
        }
        
        if (!in_array('balance_after', $walletColumns)) {
            $this->forge->addColumn('wallet_transactions', [
                'balance_after' => [
                    'type' => 'DECIMAL',
                    'constraint' => '20,2',
                    'null' => true,
                    'after' => 'balance_before'
                ]
            ]);
        }
        
        if (!in_array('metadata', $walletColumns)) {
            $this->forge->addColumn('wallet_transactions', [
                'metadata' => [
                    'type' => 'TEXT',
                    'null' => true,
                    'after' => 'payment_reference'
                ]
            ]);
        }

        // Add missing columns to users if they don't exist
        $usersFields = $db->getFieldData('users');
        $usersColumns = array_column($usersFields, 'name');
        
        if (!in_array('google_id', $usersColumns)) {
            $this->forge->addColumn('users', [
                'google_id' => [
                    'type' => 'VARCHAR',
                    'constraint' => 255,
                    'null' => true,
                    'after' => 'password'
                ]
            ]);
        }
        
        if (!in_array('google_picture', $usersColumns)) {
            $this->forge->addColumn('users', [
                'google_picture' => [
                    'type' => 'VARCHAR',
                    'constraint' => 255,
                    'null' => true,
                    'after' => 'google_id'
                ]
            ]);
        }
        
        if (!in_array('reset_token', $usersColumns)) {
            $this->forge->addColumn('users', [
                'reset_token' => [
                    'type' => 'VARCHAR',
                    'constraint' => 64,
                    'null' => true,
                    'after' => 'password'
                ]
            ]);
        }
        
        if (!in_array('reset_token_expires', $usersColumns)) {
            $this->forge->addColumn('users', [
                'reset_token_expires' => [
                    'type' => 'DATETIME',
                    'null' => true,
                    'after' => 'reset_token'
                ]
            ]);
        }
        
        if (!in_array('last_login', $usersColumns)) {
            $this->forge->addColumn('users', [
                'last_login' => [
                    'type' => 'DATETIME',
                    'null' => true,
                    'after' => 'status'
                ]
            ]);
        }
        
        if (!in_array('login_type', $usersColumns)) {
            $this->forge->addColumn('users', [
                'login_type' => [
                    'type' => 'ENUM',
                    'constraint' => ['email', 'google'],
                    'default' => 'email',
                    'after' => 'last_login'
                ]
            ]);
        }

        // Create notifications table if it doesn't exist
        if (!$db->tableExists('notifications')) {
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
                    'null' => true,
                ],
                'admin_id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                    'null' => true,
                ],
                'type' => [
                    'type' => 'ENUM',
                    'constraint' => ['user_topup', 'user_withdraw', 'draw_participation', 'withdrawal_approved', 'withdrawal_rejected', 'draw_win', 'system_message', 'admin_message'],
                    'default' => 'system_message',
                ],
                'title' => [
                    'type' => 'VARCHAR',
                    'constraint' => 255,
                ],
                'message' => [
                    'type' => 'TEXT',
                ],
                'data' => [
                    'type' => 'TEXT',
                    'null' => true,
                ],
                'is_read' => [
                    'type' => 'BOOLEAN',
                    'default' => false,
                ],
                'priority' => [
                    'type' => 'ENUM',
                    'constraint' => ['low', 'medium', 'high', 'urgent'],
                    'default' => 'medium',
                ],
                'expires_at' => [
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
            $this->forge->addKey('user_id');
            $this->forge->addKey('admin_id');
            $this->forge->addKey('type');
            $this->forge->addKey('is_read');
            $this->forge->addKey('created_at');
            $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
            $this->forge->addForeignKey('admin_id', 'users', 'id', 'CASCADE', 'CASCADE');
            $this->forge->createTable('notifications');
        }
    }

    public function down()
    {
        // This is a comprehensive update, so we can't easily rollback
        // Individual migrations should handle their own rollbacks
    }
}
