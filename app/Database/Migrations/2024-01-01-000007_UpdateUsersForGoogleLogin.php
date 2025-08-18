<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateUsersForGoogleLogin extends Migration
{
    public function up()
    {
        $fields = [
            'google_id' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'password'
            ],
            'google_picture' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'google_id'
            ],
            'last_login' => [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'status'
            ],
            'login_type' => [
                'type' => 'ENUM',
                'constraint' => ['email', 'google'],
                'default' => 'email',
                'after' => 'last_login'
            ]
        ];

        $this->forge->addColumn('users', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('users', ['google_id', 'google_picture', 'last_login', 'login_type']);
    }
}
