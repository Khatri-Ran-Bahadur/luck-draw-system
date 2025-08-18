#!/bin/bash

echo "🚀 Lucky Draw System Setup Script"
echo "=================================="

# Check if PHP is installed
if ! command -v php &> /dev/null; then
    echo "❌ PHP is not installed. Please install PHP 8.0 or higher."
    exit 1
fi

# Check PHP version
PHP_VERSION=$(php -r "echo PHP_VERSION;")
PHP_MAJOR=$(echo $PHP_VERSION | cut -d. -f1)
PHP_MINOR=$(echo $PHP_VERSION | cut -d. -f2)

if [ "$PHP_MAJOR" -lt 8 ]; then
    echo "❌ PHP version $PHP_VERSION is not supported. Please install PHP 8.0 or higher."
    exit 1
fi

echo "✅ PHP $PHP_VERSION detected"

# Check if Composer is installed
if ! command -v composer &> /dev/null; then
    echo "❌ Composer is not installed. Please install Composer first."
    exit 1
fi

echo "✅ Composer detected"

# Check if MySQL/MariaDB is running
if ! command -v mysql &> /dev/null; then
    echo "⚠️  MySQL/MariaDB client not found. Please ensure your database server is running."
else
    echo "✅ MySQL/MariaDB client detected"
fi

echo ""
echo "📦 Installing dependencies..."
composer install --no-dev --optimize-autoloader

echo ""
echo "🔧 Setting up environment..."

# Copy environment file if it doesn't exist
if [ ! -f .env ]; then
    cp env .env
    echo "✅ Environment file created (.env)"
    echo "⚠️  Please edit .env file with your database credentials before continuing"
    echo ""
    read -p "Press Enter after you've configured the .env file..."
else
    echo "✅ Environment file already exists"
fi

echo ""
echo "🗄️  Setting up database..."

# Check if .env has database configuration
if grep -q "database.default.database" .env; then
    echo "✅ Database configuration found in .env"
else
    echo "❌ Database configuration not found in .env. Please configure it first."
    exit 1
fi

echo ""
echo "🚀 Running database migrations..."
php spark migrate

if [ $? -eq 0 ]; then
    echo "✅ Database migrations completed successfully"
else
    echo "❌ Database migrations failed. Please check your database configuration."
    exit 1
fi

echo ""
echo "🌱 Seeding database with initial data..."
php spark db:seed InitialSeeder

if [ $? -eq 0 ]; then
    echo "✅ Database seeded successfully"
else
    echo "❌ Database seeding failed."
    exit 1
fi

echo ""
echo "📁 Setting permissions..."
chmod -R 755 writable/
chmod -R 755 public/uploads/

echo ""
echo "🎉 Setup completed successfully!"
echo ""
echo "📋 Next steps:"
echo "1. Configure your web server to point to the 'public' directory"
echo "2. Access your application in the browser"
echo "3. Login to admin panel with:"
echo "   - Username: admin"
echo "   - Password: admin123"
echo "   - Email: admin@luckydraw.com"
echo ""
echo "⚠️  IMPORTANT: Change the default admin password after first login!"
echo ""
echo "🔗 Useful URLs:"
echo "- Home: /"
echo "- Admin: /admin"
echo "- Lucky Draws: /lucky-draw"
echo ""
echo "📚 For more information, see README.md"
