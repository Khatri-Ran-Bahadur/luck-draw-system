<?php
// Test script to verify upload directory functionality

echo "<h1>Upload Directory Test</h1>";

// Check if uploads directory exists
$uploadsDir = __DIR__ . '/public/uploads';
$productsDir = $uploadsDir . '/products';

echo "<h2>Directory Check</h2>";
echo "Uploads directory exists: " . (is_dir($uploadsDir) ? 'YES' : 'NO') . "<br>";
echo "Products directory exists: " . (is_dir($productsDir) ? 'YES' : 'NO') . "<br>";

// Check permissions
echo "<h2>Permissions Check</h2>";
echo "Uploads directory writable: " . (is_writable($uploadsDir) ? 'YES' : 'NO') . "<br>";
echo "Products directory writable: " . (is_writable($productsDir) ? 'YES' : 'NO') . "<br>";

// Check if directories can be created
echo "<h2>Directory Creation Test</h2>";
if (!is_dir($uploadsDir)) {
    if (mkdir($uploadsDir, 0755, true)) {
        echo "Created uploads directory<br>";
    } else {
        echo "Failed to create uploads directory<br>";
    }
}

if (!is_dir($productsDir)) {
    if (mkdir($productsDir, 0755, true)) {
        echo "Created products directory<br>";
    } else {
        echo "Failed to create products directory<br>";
    }
}

// List contents
echo "<h2>Directory Contents</h2>";
if (is_dir($uploadsDir)) {
    echo "Uploads directory contents:<br>";
    $files = scandir($uploadsDir);
    foreach ($files as $file) {
        if ($file != '.' && $file != '..') {
            echo "- $file<br>";
        }
    }
}

if (is_dir($productsDir)) {
    echo "Products directory contents:<br>";
    $files = scandir($productsDir);
    foreach ($files as $file) {
        if ($file != '.' && $file != '..') {
            echo "- $file<br>";
        }
    }
}

// Test file creation
echo "<h2>File Creation Test</h2>";
$testFile = $productsDir . '/test.txt';
if (file_put_contents($testFile, 'Test content')) {
    echo "Successfully created test file<br>";
    if (unlink($testFile)) {
        echo "Successfully deleted test file<br>";
    }
} else {
    echo "Failed to create test file<br>";
}

echo "<h2>PHP Info</h2>";
echo "PHP version: " . phpversion() . "<br>";
echo "Upload max filesize: " . ini_get('upload_max_filesize') . "<br>";
echo "Post max size: " . ini_get('post_max_size') . "<br>";
echo "Max file uploads: " . ini_get('max_file_uploads') . "<br>";
