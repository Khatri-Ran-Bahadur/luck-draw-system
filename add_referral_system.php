<?php

// Simple script to add referral system to database
require_once 'preload.php';

try {
    $db = \Config\Database::connect();

    echo "Adding referral fields to users table...\n";

    // Add referral fields to users table
    $db->query("ALTER TABLE users ADD COLUMN referral_code VARCHAR(20) UNIQUE NULL AFTER profile_image");
    $db->query("ALTER TABLE users ADD COLUMN referred_by INT(11) UNSIGNED NULL AFTER referral_code");
    $db->query("ALTER TABLE users ADD COLUMN referral_bonus_earned DECIMAL(10,2) DEFAULT 0.00 AFTER referred_by");

    echo "Creating referrals table...\n";

    // Create referrals table
    $db->query("CREATE TABLE IF NOT EXISTS referrals (
        id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
        referrer_id INT(11) UNSIGNED NOT NULL,
        referred_id INT(11) UNSIGNED NOT NULL,
        referral_code VARCHAR(20) NOT NULL,
        bonus_amount DECIMAL(10,2) DEFAULT 0.00,
        bonus_paid BOOLEAN DEFAULT FALSE,
        status ENUM('pending', 'active', 'completed', 'cancelled') DEFAULT 'pending',
        created_at DATETIME NOT NULL,
        updated_at DATETIME NOT NULL,
        PRIMARY KEY (id),
        KEY referrer_id (referrer_id),
        KEY referred_id (referred_id),
        KEY referral_code (referral_code)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");

    echo "Adding referral settings...\n";

    // Add referral settings to settings table
    $db->query("INSERT IGNORE INTO settings (`key`, value, description, created_at, updated_at) VALUES 
        ('referral_bonus_amount', '100.00', 'Referral bonus amount in PKR for new user registration', NOW(), NOW()),
        ('referral_bonus_conditions', 'registration', 'When to give referral bonus: registration, first_purchase, etc.', NOW(), NOW()),
        ('referral_code_length', '8', 'Length of referral codes generated for users', NOW(), NOW()),
        ('max_referrals_per_user', '0', 'Maximum referrals allowed per user (0 = unlimited)', NOW(), NOW())");

    echo "Generating referral codes for existing users...\n";

    // Generate referral codes for existing users
    $users = $db->query("SELECT id FROM users WHERE referral_code IS NULL")->getResultArray();

    foreach ($users as $user) {
        do {
            $code = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 8));
            $exists = $db->query("SELECT id FROM users WHERE referral_code = ?", [$code])->getRow();
        } while ($exists);

        $db->query("UPDATE users SET referral_code = ? WHERE id = ?", [$code, $user['id']]);
    }

    echo "Referral system added successfully!\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
