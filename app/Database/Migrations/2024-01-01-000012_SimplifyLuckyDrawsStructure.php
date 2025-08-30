<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class SimplifyLuckDrawsStructure extends Migration
{
    public function up()
    {
        // Drop the existing lucky_draws table
        $this->forge->dropTable('lucky_draws');

        // Create cash_draws table for cash-based lucky draws
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
            'draw_date' => [
                'type' => 'DATETIME',
            ],
            'is_manual_selection' => [
                'type' => 'BOOLEAN',
                'default' => false,
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['active', 'inactive', 'completed'],
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
        $this->forge->createTable('cash_draws');

        // Create cash_draw_entries table for different entry fee options
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'cash_draw_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'entry_fee' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'total_winners' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 1,
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['active', 'inactive'],
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
        $this->forge->addForeignKey('cash_draw_id', 'cash_draws', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('cash_draw_entries');

        // Create product_draws table for product-based lucky draws
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
                'default' => 0.01,
            ],
            'draw_date' => [
                'type' => 'DATETIME',
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['active', 'inactive', 'completed'],
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
        $this->forge->createTable('product_draws');

        // Create products table for individual products in product draws
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'product_draw_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'description' => [
                'type' => 'TEXT',
            ],
            'image' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'value' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['active', 'inactive'],
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
        $this->forge->addForeignKey('product_draw_id', 'product_draws', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('products');
    }

    public function down()
    {
        // Drop the new tables
        $this->forge->dropTable('products');
        $this->forge->dropTable('product_draws');
        $this->forge->dropTable('cash_draw_entries');
        $this->forge->dropTable('cash_draws');

        // Recreate the old lucky_draws table (you would need to restore the original structure)
    }
}
