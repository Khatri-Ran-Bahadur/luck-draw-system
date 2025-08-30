<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddMissingColumnsToLuckDraws extends Migration
{
    public function up()
    {
        // Add missing columns that don't exist yet
        $fields = [
            'total_winners' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 1,
                'after' => 'entry_fee'
            ],
            'is_manual_selection' => [
                'type' => 'BOOLEAN',
                'default' => false,
                'after' => 'total_winners'
            ],
            'product_image' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'is_manual_selection'
            ],
            'product_details' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'product_image'
            ]
        ];

        $this->forge->addColumn('lucky_draws', $fields);
    }

    public function down()
    {
        // Remove the added columns
        $this->forge->dropColumn('lucky_draws', [
            'total_winners',
            'is_manual_selection',
            'product_image',
            'product_details'
        ]);
    }
}
