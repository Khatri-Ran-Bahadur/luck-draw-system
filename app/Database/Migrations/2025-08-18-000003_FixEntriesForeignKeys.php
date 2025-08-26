<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class FixEntriesForeignKeys extends Migration
{
    public function up()
    {
        // Drop the old foreign key constraint that references lucky_draws
        try {
            $this->forge->dropForeignKey('entries', 'entries_lucky_draw_id_foreign');
        } catch (\Exception $e) {
            // Constraint might not exist, continue
        }

        // Make lucky_draw_id nullable since we're using cash_draw_id and product_draw_id now
        $this->forge->modifyColumn('entries', [
            'lucky_draw_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true
            ]
        ]);

        // Ensure the foreign key constraints are properly set
        $this->forge->addForeignKey('cash_draw_id', 'cash_draws', 'id', 'CASCADE', 'CASCADE', 'entries_cash_draw_id_foreign');
        $this->forge->addForeignKey('product_draw_id', 'product_draws', 'id', 'CASCADE', 'CASCADE', 'entries_product_draw_id_foreign');
    }

    public function down()
    {
        // Drop the foreign key constraints
        $this->forge->dropForeignKey('entries', 'entries_cash_draw_id_foreign');
        $this->forge->dropForeignKey('entries', 'entries_product_draw_id_foreign');
    }
}
