<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddBalanceColumnsToWalletTransactions extends Migration
{
    public function up()
    {
        // Check if columns exist before adding them
        $db = \Config\Database::connect();
        $fields = $db->getFieldData('wallet_transactions');
        $existingColumns = array_column($fields, 'name');

        $fieldsToAdd = [];

        // Only add balance_before if it doesn't exist
        if (!in_array('balance_before', $existingColumns)) {
            $fieldsToAdd['balance_before'] = [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'default' => '0.00',
                'after' => 'amount'
            ];
        }

        // Only add balance_after if it doesn't exist
        if (!in_array('balance_after', $existingColumns)) {
            $fieldsToAdd['balance_after'] = [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'default' => '0.00',
                'after' => in_array('balance_before', $existingColumns) ? 'balance_before' : 'amount'
            ];
        }

        // Only add metadata if it doesn't exist
        if (!in_array('metadata', $existingColumns)) {
            $fieldsToAdd['metadata'] = [
                'type' => 'JSON',
                'null' => true,
                'after' => 'payment_reference'
            ];
        }

        // Only add columns if there are any to add
        if (!empty($fieldsToAdd)) {
            $this->forge->addColumn('wallet_transactions', $fieldsToAdd);
        }
    }

    public function down()
    {
        $this->forge->dropColumn('wallet_transactions', ['balance_before', 'balance_after', 'metadata']);
    }
}
