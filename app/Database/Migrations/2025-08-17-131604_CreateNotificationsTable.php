<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateNotificationsTable extends Migration
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
                'null' => true, // null for admin notifications
            ],
            'admin_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true, // null for user notifications
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
                'type' => 'JSON',
                'null' => true, // Additional data like transaction_id, draw_id, etc.
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
                'null' => true, // For temporary notifications
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

        // Foreign key constraints
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('admin_id', 'users', 'id', 'CASCADE', 'CASCADE');

        // Only create table if it doesn't exist
        if (!$this->db->tableExists('notifications')) {
            $this->forge->createTable('notifications');
        }
    }

    public function down()
    {
        $this->forge->dropTable('notifications');
    }
}
