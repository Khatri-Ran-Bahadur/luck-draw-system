<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DemoDataSeeder extends Seeder
{
    public function run()
    {
        // Create sample users
        $userModel = new \App\Models\UserModel();
        $users = [
            [
                'username' => 'john_doe',
                'email' => 'john@example.com',
                'password' => $userModel->hashPassword('password123'),
                'full_name' => 'John Doe',
                'phone' => '+1234567890',
                'is_admin' => false,
                'status' => 'active'
            ],
            [
                'username' => 'jane_smith',
                'email' => 'jane@example.com',
                'password' => $userModel->hashPassword('password123'),
                'full_name' => 'Jane Smith',
                'phone' => '+1234567891',
                'is_admin' => false,
                'status' => 'active'
            ],
            [
                'username' => 'bob_wilson',
                'email' => 'bob@example.com',
                'password' => $userModel->hashPassword('password123'),
                'full_name' => 'Bob Wilson',
                'phone' => '+1234567892',
                'is_admin' => false,
                'status' => 'active'
            ]
        ];

        foreach ($users as $userData) {
            $userData['created_at'] = date('Y-m-d H:i:s');
            $userData['updated_at'] = date('Y-m-d H:i:s');
            $userModel->insert($userData);
        }

        // Create sample lucky draws
        $luckyDrawModel = new \App\Models\LuckDrawModel();
        $draws = [
            [
                'title' => 'Weekly Cash Prize - $500',
                'description' => 'Join our weekly lucky draw for a chance to win big cash prize!',
                'draw_type' => 'cash',
                'prize_description' => 'Cash prize of $500 transferred instantly to your account',
                'prize_value' => 500.00,
                'draw_date' => date('Y-m-d H:i:s', strtotime('+7 days')),
                'entry_fee' => 5.00,
                'max_entries' => 100,
                'status' => 'active'
            ],
            [
                'title' => 'iPhone 15 Pro Max Giveaway',
                'description' => 'Win the latest iPhone 15 Pro Max in our exclusive product draw!',
                'draw_type' => 'product',
                'prize_description' => 'Brand new iPhone 15 Pro Max 256GB in your choice of color',
                'prize_value' => 1199.00,
                'draw_date' => date('Y-m-d H:i:s', strtotime('+15 days')),
                'entry_fee' => 15.00,
                'max_entries' => 200,
                'status' => 'active'
            ],
            [
                'title' => 'Daily Cash Boost - $100',
                'description' => 'Quick daily draw with instant cash prizes!',
                'draw_type' => 'cash',
                'prize_description' => 'Instant cash transfer of $100 to your account',
                'prize_value' => 100.00,
                'draw_date' => date('Y-m-d H:i:s', strtotime('+1 day')),
                'entry_fee' => 2.00,
                'max_entries' => 50,
                'status' => 'active'
            ],
            [
                'title' => 'MacBook Air M3 Prize',
                'description' => 'Win a brand new MacBook Air with M3 chip!',
                'draw_type' => 'product',
                'prize_description' => 'MacBook Air 15-inch with M3 chip, 256GB SSD, Space Gray',
                'prize_value' => 1299.00,
                'draw_date' => date('Y-m-d H:i:s', strtotime('+20 days')),
                'entry_fee' => 20.00,
                'max_entries' => 150,
                'status' => 'active'
            ],
            [
                'title' => 'Mega Cash Prize - $1000',
                'description' => 'Our biggest cash prize draw of the month!',
                'draw_type' => 'cash',
                'prize_description' => 'Instant cash transfer of $1000 to your account',
                'prize_value' => 1000.00,
                'draw_date' => date('Y-m-d H:i:s', strtotime('+30 days')),
                'entry_fee' => 10.00,
                'max_entries' => 300,
                'status' => 'active'
            ],
            [
                'title' => 'PlayStation 5 Bundle',
                'description' => 'Win a PlayStation 5 with extra controller and games!',
                'draw_type' => 'product',
                'prize_description' => 'PlayStation 5 Console Bundle with 2 controllers and 3 games',
                'prize_value' => 699.00,
                'draw_date' => date('Y-m-d H:i:s', strtotime('+25 days')),
                'entry_fee' => 12.00,
                'max_entries' => 180,
                'status' => 'active'
            ]
        ];

        foreach ($draws as $drawData) {
            $drawData['created_at'] = date('Y-m-d H:i:s');
            $drawData['updated_at'] = date('Y-m-d H:i:s');
            $luckyDrawModel->insert($drawData);
        }

        // Create some sample entries
        $entryModel = new \App\Models\EntryModel();
        $entries = [
            [
                'user_id' => 1, // john_doe
                'lucky_draw_id' => 1,
                'entry_number' => $entryModel->generateEntryNumber(),
                'payment_status' => 'completed',
                'payment_method' => 'paypal',
                'payment_reference' => 'PAY-' . uniqid(),
                'amount_paid' => 5.00,
                'entry_date' => date('Y-m-d H:i:s', strtotime('-2 days'))
            ],
            [
                'user_id' => 2, // jane_smith
                'lucky_draw_id' => 1,
                'entry_number' => $entryModel->generateEntryNumber(),
                'payment_status' => 'completed',
                'payment_method' => 'easypaisa',
                'payment_reference' => 'EASY-' . uniqid(),
                'amount_paid' => 5.00,
                'entry_date' => date('Y-m-d H:i:s', strtotime('-1 day'))
            ],
            [
                'user_id' => 3, // bob_wilson
                'lucky_draw_id' => 2,
                'entry_number' => $entryModel->generateEntryNumber(),
                'payment_status' => 'completed',
                'payment_method' => 'paypal',
                'payment_reference' => 'PAY-' . uniqid(),
                'amount_paid' => 15.00,
                'entry_date' => date('Y-m-d H:i:s', strtotime('-5 days'))
            ]
        ];

        foreach ($entries as $entryData) {
            $entryData['created_at'] = date('Y-m-d H:i:s');
            $entryData['updated_at'] = date('Y-m-d H:i:s');
            $entryModel->insert($entryData);
        }

        echo "Demo data seeded successfully!\n";
        echo "Sample users created:\n";
        echo "- john_doe (john@example.com) - password: password123\n";
        echo "- jane_smith (jane@example.com) - password: password123\n";
        echo "- bob_wilson (bob@example.com) - password: password123\n";
        echo "\nSample lucky draws created with sample entries.\n";
    }
}
