<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ContactSettingsSeeder extends Seeder
{
    public function run()
    {
        $settings = [
            [
                'key' => 'contact_email',
                'value' => 'support@luckydraw.com',
                'description' => 'Primary contact email address',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'key' => 'contact_phone',
                'value' => '+92 300 1234567',
                'description' => 'Primary contact phone number',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'key' => 'contact_address',
                'value' => '123 Main Street, Lahore, Pakistan',
                'description' => 'Business address',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'key' => 'contact_working_hours',
                'value' => 'Monday to Friday, 9am to 6pm',
                'description' => 'Business working hours',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'key' => 'facebook_url',
                'value' => '#',
                'description' => 'Facebook page URL',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'key' => 'twitter_url',
                'value' => '#',
                'description' => 'Twitter profile URL',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'key' => 'instagram_url',
                'value' => '#',
                'description' => 'Instagram profile URL',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'key' => 'linkedin_url',
                'value' => '#',
                'description' => 'LinkedIn profile URL',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'key' => 'youtube_url',
                'value' => '#',
                'description' => 'YouTube channel URL',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'key' => 'footer_description',
                'value' => 'Join our exciting lucky draws and stand a chance to win incredible cash prizes and amazing products!',
                'description' => 'Footer description text',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'key' => 'footer_copyright',
                'value' => 'Lucky Draw System. All rights reserved.',
                'description' => 'Footer copyright text',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'key' => 'newsletter_enabled',
                'value' => 'true',
                'description' => 'Enable newsletter signup in footer',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'key' => 'newsletter_placeholder',
                'value' => 'Enter your email',
                'description' => 'Newsletter input placeholder text',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ];

        foreach ($settings as $setting) {
            // Check if setting already exists
            $existing = $this->db->table('settings')->where('key', $setting['key'])->get()->getRow();

            if (!$existing) {
                $this->db->table('settings')->insert($setting);
                echo "Added setting: {$setting['key']}\n";
            } else {
                echo "Setting already exists: {$setting['key']}\n";
            }
        }

        echo "Contact settings seeder completed!\n";
    }
}
