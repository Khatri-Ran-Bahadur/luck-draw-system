<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class FixCashDrawsTable extends Migration
{
    public function up()
    {
        // Check if columns exist before adding them
        $db = \Config\Database::connect();
        $fields = $db->getFieldData('cash_draws');
        $existingColumns = array_column($fields, 'name');

        $fieldsToAdd = [];

        // Only add entry_fee if it doesn't exist
        if (!in_array('entry_fee', $existingColumns)) {
            $fieldsToAdd['entry_fee'] = [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'default' => 0.00,
                'after' => 'description'
            ];
        }

        // Only add total_winners if it doesn't exist
        if (!in_array('total_winners', $existingColumns)) {
            $fieldsToAdd['total_winners'] = [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 1,
                'after' => in_array('entry_fee', $existingColumns) ? 'entry_fee' : 'description'
            ];
        }

        // Only add prize_amount if it doesn't exist
        if (!in_array('prize_amount', $existingColumns)) {
            $fieldsToAdd['prize_amount'] = [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'default' => 0.00,
                'after' => in_array('total_winners', $existingColumns) ? 'total_winners' : (in_array('entry_fee', $existingColumns) ? 'entry_fee' : 'description')
            ];
        }

        // Only add columns if there are any to add
        if (!empty($fieldsToAdd)) {
            $this->forge->addColumn('cash_draws', $fieldsToAdd);
        }
    }

    public function down()
    {
        // Remove the added columns
        $this->forge->dropColumn('cash_draws', ['entry_fee', 'total_winners', 'prize_amount']);
    }
}
