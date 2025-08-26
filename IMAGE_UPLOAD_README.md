# Product Image Upload System

This document explains how the product image upload system works in the Lucky Draw System.

## Features

- ✅ Product image upload in admin panel
- ✅ Image validation and processing
- ✅ Automatic fallback for missing images
- ✅ Responsive image display
- ✅ Image caching and optimization

## How It Works

### 1. Admin Panel Upload

When creating or editing a product draw in the admin panel:

1. **Create Product Draw**: Navigate to `Admin > Product Draws > Create`
2. **Upload Image**: Use the file input field to select an image
3. **Image Processing**: The system automatically:
   - Validates the uploaded file
   - Generates a unique filename
   - Moves the file to `public/uploads/products/`
   - Stores the relative path in the database

### 2. Image Storage

- **Directory**: `public/uploads/products/`
- **File Types**: JPG, JPEG, PNG, GIF, WebP
- **Max Size**: Configurable via PHP settings
- **Naming**: Random unique names to prevent conflicts

### 3. Image Display

The system uses helper functions to display images:

```php
// Display product image with fallback
<?= get_product_image($draw['product_image'], 'w-full h-full object-cover') ?>

// Get image source only
<?= get_product_image_src($draw['product_image']) ?>
```

### 4. Fallback System

If an image is missing or fails to load:
- Shows a placeholder icon
- Maintains consistent layout
- Graceful degradation

## File Structure

```
app/
├── Controllers/
│   └── Admin.php (handles uploads)
├── Helpers/
│   └── ImageHelper.php (image utilities)
├── Views/
│   ├── admin/
│   │   ├── create_product_draw.php
│   │   ├── edit_product_draw.php
│   │   └── product_draws.php
│   └── home/
│       └── product_draws.php
└── Config/
    └── Autoload.php (loads helpers)

public/
└── uploads/
    ├── .htaccess (access control)
    └── products/
        ├── .htaccess (product images)
        └── [uploaded images]
```

## Configuration

### PHP Settings

Ensure these PHP settings are configured:

```ini
upload_max_filesize = 10M
post_max_size = 10M
max_file_uploads = 20
```

### Directory Permissions

```bash
chmod 755 public/uploads
chmod 755 public/uploads/products
```

## Testing

### 1. Test Upload Directory

Run the test script to verify upload functionality:

```bash
php test_upload.php
```

### 2. Generate Placeholder Images

Create sample images for testing:

```bash
php generate_placeholder_images.php
```

### 3. Manual Testing

1. Create a product draw with an image
2. Edit the product draw and change the image
3. View the product draw on the website
4. Verify images display correctly

## Troubleshooting

### Common Issues

1. **Images not uploading**
   - Check directory permissions
   - Verify PHP upload settings
   - Check file size limits

2. **Images not displaying**
   - Verify file paths in database
   - Check .htaccess configuration
   - Ensure images exist on disk

3. **Permission errors**
   - Set correct directory permissions
   - Check web server user permissions

### Debug Steps

1. Check the browser console for errors
2. Verify file paths in the database
3. Test file access directly via URL
4. Check server error logs

## Security Considerations

- File type validation
- File size limits
- Secure filename generation
- Directory traversal protection
- CORS configuration for images

## Performance Optimization

- Image caching headers
- Responsive image sizing
- Lazy loading support
- CDN-ready structure

## Future Enhancements

- Image resizing and thumbnails
- Multiple image support
- Image compression
- Cloud storage integration
- Image optimization
