<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDrawTypeToLuckDraws extends Migration
{
    public function up()
    {
        $this->forge->addColumn('lucky_draws', [
            'draw_type' => [
                'type' => 'ENUM',
                'constraint' => ['cash', 'product'],
                'default' => 'cash',
                'after' => 'description'
            ],
            'prize_description' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'draw_type'
            ],
            'prize_value' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'default' => 0.00,
                'after' => 'prize_description'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('lucky_draws', ['draw_type', 'prize_description', 'prize_value']);
    }
}
