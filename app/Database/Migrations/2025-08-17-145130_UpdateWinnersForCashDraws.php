<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateWinnersForCashDraws extends Migration
{
    public function up()
    {
        // First, drop the existing foreign key constraint that references the non-existent lucky_draws table
        try {
            $this->forge->dropForeignKey('winners', 'winners_lucky_draw_id_foreign');
        } catch (\Exception $e) {
            // Constraint might not exist, continue
        }

        // Make lucky_draw_id nullable since we're adding new relationships
        $this->forge->modifyColumn('winners', [
            'lucky_draw_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true
            ]
        ]);

        // Add new columns for cash draw and product draw relationships
        $fields = [
            'cash_draw_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'after' => 'lucky_draw_id'
            ],
            'product_draw_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'after' => 'cash_draw_id'
            ],
            'draw_type' => [
                'type' => 'ENUM',
                'constraint' => ['cash', 'product'],
                'null' => true,
                'after' => 'product_draw_id'
            ]
        ];

        $this->forge->addColumn('winners', $fields);

        // Add foreign key constraints for the new relationships
        $this->forge->addForeignKey('cash_draw_id', 'cash_draws', 'id', 'CASCADE', 'CASCADE', 'winners_cash_draw_fk');
        $this->forge->addForeignKey('product_draw_id', 'product_draws', 'id', 'CASCADE', 'CASCADE', 'winners_product_draw_fk');
    }

    public function down()
    {
        // Drop foreign key constraints
        $this->forge->dropForeignKey('winners', 'winners_cash_draw_fk');
        $this->forge->dropForeignKey('winners', 'winners_product_draw_fk');
        
        // Drop the columns
        $this->forge->dropColumn('winners', ['cash_draw_id', 'product_draw_id', 'draw_type']);
    }
}