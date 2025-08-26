<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddReferralSystem extends Migration
{
    public function up()
    {
        // Add referral fields to users table
        $this->forge->addColumn('users', [
            'referral_code' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'unique' => true,
                'null' => true,
                'after' => 'profile_image'
            ],
            'referred_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'after' => 'referral_code'
            ],
            'referral_bonus_earned' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'default' => 0.00,
                'after' => 'referred_by'
            ]
        ]);

        // Create referrals table to track referral relationships and bonuses
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'referrer_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => false,
            ],
            'referred_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => false,
            ],
            'referral_code' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => false,
            ],
            'bonus_amount' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'default' => 0.00,
            ],
            'bonus_paid' => [
                'type' => 'BOOLEAN',
                'default' => false,
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['pending', 'active', 'completed', 'cancelled'],
                'default' => 'pending',
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
        $this->forge->addKey('referrer_id');
        $this->forge->addKey('referred_id');
        $this->forge->addKey('referral_code');
        
        // Add foreign key constraints
        $this->forge->addForeignKey('referrer_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('referred_id', 'users', 'id', 'CASCADE', 'CASCADE');
        
        $this->forge->createTable('referrals');

        // Add referral bonus settings to settings table
        $this->db->table('settings')->insertBatch([
            [
                'key' => 'referral_bonus_amount',
                'value' => '100.00',
                'description' => 'Referral bonus amount in PKR for new user registration',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'key' => 'referral_bonus_conditions',
                'value' => 'registration',
                'description' => 'When to give referral bonus: registration, first_purchase, etc.',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'key' => 'referral_code_length',
                'value' => '8',
                'description' => 'Length of referral codes generated for users',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'key' => 'max_referrals_per_user',
                'value' => '0',
                'description' => 'Maximum referrals allowed per user (0 = unlimited)',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ]);
    }

    public function down()
    {
        // Remove referral fields from users table
        $this->forge->dropColumn('users', ['referral_code', 'referred_by', 'referral_bonus_earned']);
        
        // Drop referrals table
        $this->forge->dropTable('referrals');
        
        // Remove referral settings
        $this->db->table('settings')->whereIn('key', [
            'referral_bonus_amount',
            'referral_bonus_conditions',
            'referral_code_length',
            'max_referrals_per_user'
        ])->delete();
    }
}
