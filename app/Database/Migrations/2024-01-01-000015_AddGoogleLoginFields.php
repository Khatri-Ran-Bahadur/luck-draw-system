<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddGoogleLoginFields extends Migration
{
    public function up()
    {
        // Check if columns exist before adding them
        $db = \Config\Database::connect();
        $fields = $db->getFieldData('users');
        $existingColumns = array_column($fields, 'name');

        $fieldsToAdd = [];

        // Only add google_id if it doesn't exist
        if (!in_array('google_id', $existingColumns)) {
            $fieldsToAdd['google_id'] = [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'password'
            ];
        }

        // Only add login_type if it doesn't exist
        if (!in_array('login_type', $existingColumns)) {
            $fieldsToAdd['login_type'] = [
                'type' => 'ENUM',
                'constraint' => ['email', 'google'],
                'default' => 'email',
                'after' => 'status'
            ];
        }

        // Only add columns if there are any to add
        if (!empty($fieldsToAdd)) {
            $this->forge->addColumn('users', $fieldsToAdd);
        }

        // Add index for google_id for better performance
        $this->forge->addKey('google_id', false, false, 'idx_users_google_id');
    }

    public function down()
    {
        // Drop the index first
        $this->forge->dropKey('users', 'idx_users_google_id');

        // Drop the columns
        $this->forge->dropColumn('users', ['google_id', 'login_type']);
    }
}
