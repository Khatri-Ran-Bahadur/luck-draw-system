<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateEntriesForCashDraws extends Migration
{
    public function up()
    {
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

        $this->forge->addColumn('entries', $fields);

        // Add foreign key constraints
        $this->forge->addForeignKey('cash_draw_id', 'cash_draws', 'id', 'CASCADE', 'CASCADE', 'entries_cash_draw_fk');
        $this->forge->addForeignKey('product_draw_id', 'product_draws', 'id', 'CASCADE', 'CASCADE', 'entries_product_draw_fk');
    }

    public function down()
    {
        // Drop foreign key constraints
        $this->forge->dropForeignKey('entries', 'entries_cash_draw_fk');
        $this->forge->dropForeignKey('entries', 'entries_product_draw_fk');

        // Drop the columns
        $this->forge->dropColumn('entries', ['cash_draw_id', 'product_draw_id', 'draw_type']);
    }
}
