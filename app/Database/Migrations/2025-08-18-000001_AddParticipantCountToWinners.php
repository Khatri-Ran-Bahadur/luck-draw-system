<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddParticipantCountToWinners extends Migration
{
    public function up()
    {
        $fields = [
            'participant_count' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
                'after' => 'claim_approved'
            ]
        ];

        $this->forge->addColumn('winners', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('winners', ['participant_count']);
    }
}
