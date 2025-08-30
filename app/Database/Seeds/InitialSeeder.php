<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class InitialSeeder extends Seeder
{
    public function run()
    {
        // Create default admin user
        $userModel = new \App\Models\UserModel();
        $adminData = [
            'username' => 'admin',
            'email' => 'admin@luckydraw.com',
            'password' => $userModel->hashPassword('admin123'),
            'full_name' => 'System Administrator',
            'phone' => '+1234567890',
            'is_admin' => true,
            'status' => 'active',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $userModel->insert($adminData);

        // Create default settings
        $settingModel = new \App\Models\SettingModel();
        $settings = [
            'draw_frequency' => '7',
            'entry_fee' => '10.00',
            'max_entries' => '100',
            'site_name' => 'Lucky Draw System',
            'site_description' => 'Join our exciting lucky draws and win amazing prizes!',
            'maintenance_mode' => 'false'
        ];

        foreach ($settings as $key => $value) {
            $settingModel->setSetting($key, $value, 'Default system setting');
        }

        // Create sample lucky draw
        $luckyDrawModel = new \App\Models\LuckDrawModel();
        $sampleDraw = [
            'title' => 'Welcome Lucky Draw',
            'description' => 'Join our first lucky draw and win exciting prizes!',
            'draw_date' => date('Y-m-d H:i:s', strtotime('+7 days')),
            'entry_fee' => 10.00,
            'max_entries' => 50,
            'status' => 'upcoming',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $luckyDrawModel->insert($sampleDraw);

        echo "Initial data seeded successfully!\n";
        echo "Admin credentials:\n";
        echo "Username: admin\n";
        echo "Password: admin123\n";
        echo "Email: admin@luckydraw.com\n";
    }
}
