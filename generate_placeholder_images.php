<?php
// Generate placeholder product images for testing

require_once 'vendor/autoload.php';

// Check if GD extension is available
if (!extension_loaded('gd')) {
    die("GD extension is required to generate images\n");
}

$productsDir = __DIR__ . '/public/uploads/products';

// Ensure directory exists
if (!is_dir($productsDir)) {
    mkdir($productsDir, 0755, true);
}

// Sample products for placeholder images
$sampleProducts = [
    'honda_bike' => [
        'name' => 'Honda CD 70',
        'color' => [52, 152, 219], // Blue
        'icon' => '🏍️'
    ],
    'iphone' => [
        'name' => 'iPhone 15 Pro',
        'color' => [155, 89, 182], // Purple
        'icon' => '📱'
    ],
    'samsung_tv' => [
        'name' => 'Samsung TV',
        'color' => [231, 76, 60], // Red
        'icon' => '📺'
    ],
    'macbook' => [
        'name' => 'MacBook Pro',
        'color' => [46, 204, 113], // Green
        'icon' => '💻'
    ],
    'gold_jewelry' => [
        'name' => 'Gold Jewelry',
        'color' => [241, 196, 15], // Yellow
        'icon' => '💎'
    ]
];

foreach ($sampleProducts as $key => $product) {
    $filename = $productsDir . '/' . $key . '_placeholder.jpg';

    // Create image
    $width = 400;
    $height = 300;
    $image = imagecreate($width, $height);

    // Set colors
    $bgColor = imagecolorallocate($image, 245, 245, 245);
    $textColor = imagecolorallocate($image, $product['color'][0], $product['color'][1], $product['color'][2]);
    $accentColor = imagecolorallocate($image, 255, 255, 255);

    // Fill background
    imagefill($image, 0, 0, $bgColor);

    // Add gradient effect
    for ($i = 0; $i < $height; $i++) {
        $alpha = 127 - (127 * $i / $height);
        $color = imagecolorallocatealpha($image, $product['color'][0], $product['color'][1], $product['color'][2], $alpha);
        imageline($image, 0, $i, $width, $i, $color);
    }

    // Add product icon (emoji as text)
    $fontSize = 48;
    $text = $product['icon'];
    $bbox = imagettfbbox($fontSize, 0, '/System/Library/Fonts/Apple Color Emoji.ttc', $text);
    $textWidth = $bbox[4] - $bbox[0];
    $textHeight = $bbox[1] - $bbox[5];
    $x = ($width - $textWidth) / 2;
    $y = ($height - $textHeight) / 2 + $textHeight;

    // Try to use TTF font for emoji, fallback to basic text
    if (function_exists('imagettftext') && file_exists('/System/Library/Fonts/Apple Color Emoji.ttc')) {
        imagettftext($image, $fontSize, 0, $x, $y, $accentColor, '/System/Library/Fonts/Apple Color Emoji.ttc', $text);
    } else {
        // Fallback to basic text
        $text = substr($product['name'], 0, 1);
        $fontSize = 72;
        $bbox = imagestring($image, 5, $x, $y - $fontSize, $text, $accentColor);
    }

    // Add product name
    $fontSize = 24;
    $text = $product['name'];
    $bbox = imagestring($image, 5, 20, $height - 60, $text, $textColor);

    // Add "Product Image" text
    $text = "Product Image";
    $bbox = imagestring($image, 3, 20, $height - 40, $text, $textColor);

    // Save image
    if (imagejpeg($image, $filename, 90)) {
        echo "Generated: $filename\n";
    } else {
        echo "Failed to generate: $filename\n";
    }

    // Free memory
    imagedestroy($image);
}

echo "\nPlaceholder images generated successfully!\n";
echo "You can now test the product image upload functionality.\n";
