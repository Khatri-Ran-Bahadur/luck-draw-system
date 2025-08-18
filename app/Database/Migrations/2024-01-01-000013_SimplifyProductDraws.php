<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class SimplifyProductDraws extends Migration
{
    public function up()
    {
        // Drop the complex products table
        $this->forge->dropTable('products', true);

        // Add product fields directly to product_draws table
        $fields = [
            'product_name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'after' => 'title'
            ],
            'product_image' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'product_name'
            ],
            'product_price' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'after' => 'product_image'
            ]
        ];

        $this->forge->addColumn('product_draws', $fields);

        // Update entry_fee default to 1 (Rs. 1)
        $this->forge->modifyColumn('product_draws', [
            'entry_fee' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'default' => 1.00,
            ]
        ]);
    }

    public function down()
    {
        // Remove the added columns
        $this->forge->dropColumn('product_draws', ['product_name', 'product_image', 'product_price']);

        // Recreate products table if needed
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
}
