<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'username' => 'admin',
            'email' => 'admin@admin.com',
            'password' => password_hash('admin123', PASSWORD_DEFAULT),
            'full_name' => 'System Administrator',
            'phone' => '1234567890',
            'is_admin' => true,
            'status' => 'active',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        // Insert the admin user
        $this->db->table('users')->insert($data);

        echo "Admin user created:\n";
        echo "- Username: admin\n";
        echo "- Email: admin@admin.com\n";
        echo "- Password: admin123\n";
    }
}
