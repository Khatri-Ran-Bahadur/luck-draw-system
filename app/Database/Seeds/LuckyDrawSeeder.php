<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class LuckDrawSeeder extends Seeder
{
    public function run()
    {
        // Seed Users (Admin and Regular Users)
        $this->seedUsers();

        // Seed Cash Draws
        $this->seedCashDraws();

        // Seed Product Draws
        $this->seedProductDraws();

        // Seed Wallets for users
        $this->seedWallets();
    }

    private function seedUsers()
    {
        $userModel = new \App\Models\UserModel();

        // Create Admin User
        $adminData = [
            'username' => 'admin',
            'email' => 'admin@luckydraw.com',
            'password' => password_hash('admin123', PASSWORD_DEFAULT),
            'full_name' => 'System Administrator',
            'phone' => '+92-300-1234567',
            'is_admin' => true,
            'email_verified' => true,
            'status' => 'active',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if (!$userModel->where('email', 'admin@luckydraw.com')->first()) {
            $userModel->insert($adminData);
            echo "Admin user created: admin@luckydraw.com / admin123\n";
        }

        // Create Sample Regular Users
        $sampleUsers = [
            [
                'username' => 'john_doe',
                'email' => 'john@example.com',
                'password' => password_hash('password123', PASSWORD_DEFAULT),
                'full_name' => 'John Doe',
                'phone' => '+92-300-1111111',
                'is_admin' => false,
                'email_verified' => true,
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'username' => 'sarah_khan',
                'email' => 'sarah@example.com',
                'password' => password_hash('password123', PASSWORD_DEFAULT),
                'full_name' => 'Sarah Khan',
                'phone' => '+92-300-2222222',
                'is_admin' => false,
                'email_verified' => true,
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'username' => 'ahmed_ali',
                'email' => 'ahmed@example.com',
                'password' => password_hash('password123', PASSWORD_DEFAULT),
                'full_name' => 'Ahmed Ali',
                'phone' => '+92-300-3333333',
                'is_admin' => false,
                'email_verified' => true,
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ];

        foreach ($sampleUsers as $userData) {
            if (!$userModel->where('email', $userData['email'])->first()) {
                $userModel->insert($userData);
            }
        }

        echo "Sample users created\n";
    }

    private function seedCashDraws()
    {
        $cashDrawModel = new \App\Models\CashDrawModel();

        $cashDraws = [
            [
                'title' => 'Weekly Cash Prize Draw',
                'description' => 'Win big cash prizes every week! Enter for just Rs. 10 and get a chance to win Rs. 50,000. Draw happens every Friday at 8 PM.',
                'entry_fee' => 10.00,
                'draw_date' => date('Y-m-d H:i:s', strtotime('+3 days 20:00:00')),
                'is_manual_selection' => false,
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'title' => 'Monthly Mega Cash Draw',
                'description' => 'Our biggest cash prize of the month! Rs. 200,000 up for grabs. Limited entries, maximum chances of winning.',
                'entry_fee' => 50.00,
                'draw_date' => date('Y-m-d H:i:s', strtotime('+15 days 21:00:00')),
                'is_manual_selection' => true,
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'title' => 'Daily Quick Win',
                'description' => 'Quick daily draw with instant results. Small entry fee, quick rewards. Perfect for daily players.',
                'entry_fee' => 5.00,
                'draw_date' => date('Y-m-d H:i:s', strtotime('+1 day 18:00:00')),
                'is_manual_selection' => false,
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ];

        foreach ($cashDraws as $draw) {
            if (!$cashDrawModel->where('title', $draw['title'])->first()) {
                $cashDrawModel->insert($draw);
            }
        }

        echo "Cash draws seeded\n";
    }

    private function seedProductDraws()
    {
        $productDrawModel = new \App\Models\ProductDrawModel();

        $productDraws = [
            [
                'title' => 'Honda CD 70 Lucky Draw',
                'description' => 'Win a brand new Honda CD 70 motorcycle! The most reliable bike in Pakistan. Entry fee is just Rs. 1 - anyone can participate!',
                'product_name' => 'Honda CD 70 2024 Model',
                'product_price' => 150000.00,
                'entry_fee' => 1.00,
                'draw_date' => date('Y-m-d H:i:s', strtotime('+7 days 19:00:00')),
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'title' => 'iPhone 15 Pro Max Giveaway',
                'description' => 'Latest iPhone 15 Pro Max with 1TB storage. The ultimate smartphone experience awaits the lucky winner!',
                'product_name' => 'iPhone 15 Pro Max 1TB',
                'product_price' => 450000.00,
                'entry_fee' => 1.00,
                'draw_date' => date('Y-m-d H:i:s', strtotime('+10 days 20:00:00')),
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'title' => 'Samsung 55" 4K Smart TV',
                'description' => 'Enjoy movies and shows like never before with this premium 55-inch 4K Smart TV from Samsung.',
                'product_name' => 'Samsung 55" QLED 4K Smart TV',
                'product_price' => 180000.00,
                'entry_fee' => 1.00,
                'draw_date' => date('Y-m-d H:i:s', strtotime('+5 days 21:00:00')),
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'title' => 'MacBook Pro M3 Lucky Draw',
                'description' => 'Professional grade laptop for creators and developers. MacBook Pro with M3 chip - the ultimate productivity machine.',
                'product_name' => 'MacBook Pro M3 16-inch',
                'product_price' => 550000.00,
                'entry_fee' => 1.00,
                'draw_date' => date('Y-m-d H:i:s', strtotime('+12 days 18:00:00')),
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'title' => 'Gold Jewelry Set Draw',
                'description' => 'Exquisite 22k gold jewelry set including necklace, earrings, and bangles. Perfect for special occasions.',
                'product_name' => '22K Gold Jewelry Set (50 grams)',
                'product_price' => 750000.00,
                'entry_fee' => 1.00,
                'draw_date' => date('Y-m-d H:i:s', strtotime('+20 days 19:30:00')),
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ];

        foreach ($productDraws as $draw) {
            if (!$productDrawModel->where('title', $draw['title'])->first()) {
                $productDrawModel->insert($draw);
            }
        }

        echo "Product draws seeded\n";
    }

    private function seedWallets()
    {
        $walletModel = new \App\Models\WalletModel();
        $userModel = new \App\Models\UserModel();

        // Get all non-admin users
        $users = $userModel->where('is_admin', false)->findAll();

        foreach ($users as $user) {
            $existingWallet = $walletModel->where('user_id', $user['id'])->first();

            if (!$existingWallet) {
                $walletData = [
                    'user_id' => $user['id'],
                    'balance' => rand(100, 1000), // Random balance between 100-1000
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ];

                $walletModel->insert($walletData);
            }
        }

        echo "User wallets seeded\n";
    }
}
