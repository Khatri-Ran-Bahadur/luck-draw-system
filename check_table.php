<?php

require_once 'vendor/autoload.php';

// Load CodeIgniter
$app = require_once 'app/Config/Boot.php';

$db = \Config\Database::connect();

echo "Cash Draws Table Structure:\n";
echo "================================\n";
$query = $db->query('DESCRIBE cash_draws');
foreach ($query->getResult() as $row) {
    echo $row->Field . ' - ' . $row->Type . "\n";
}

echo "\nProduct Draws Table Structure:\n";
echo "===============================\n";
$query = $db->query('DESCRIBE product_draws');
foreach ($query->getResult() as $row) {
    echo $row->Field . ' - ' . $row->Type . "\n";
}
