# Lucky Draw System

A complete web-based lucky draw system built with CodeIgniter 4, featuring user registration, payment processing, and admin management.

## Features

- **User Management**: Registration, login, profile management
- **Lucky Draw System**: Create, manage, and participate in lucky draws
- **Payment Integration**: Support for EasyPaisa and PayPal
- **Admin Panel**: Comprehensive admin dashboard and management tools
- **Responsive Design**: Built with Tailwind CSS for mobile-first design
- **Security**: Admin authentication, user validation, and secure payment processing

## System Requirements

- PHP 8.0 or higher
- MySQL 5.7 or higher / MariaDB 10.2 or higher
- Composer
- Web server (Apache/Nginx)

## Installation

### 1. Clone the Repository

```bash
git clone <repository-url>
cd lucky-draw-system
```

### 2. Install Dependencies

```bash
composer install
```

### 3. Environment Configuration

Copy the environment file and configure your database:

```bash
cp env .env
```

Edit `.env` file with your database credentials:

```env
database.default.hostname = localhost
database.default.database = lucky_draw_db
database.default.username = your_username
database.default.password = your_password
database.default.DBDriver = MySQLi
```

### 4. Database Setup

Run the database migrations:

```bash
php spark migrate
```

Seed the database with initial data:

```bash
php spark db:seed InitialSeeder
```

### 5. Web Server Configuration

#### Apache (.htaccess already included)

Ensure mod_rewrite is enabled and .htaccess files are allowed.

#### Nginx

```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /path/to/lucky-draw-system/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.0-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

### 6. Set Permissions

```bash
chmod -R 755 writable/
chmod -R 755 public/uploads/
```

## Default Admin Account

After running the seeder, you'll have access to the admin account:

- **Username**: admin
- **Password**: admin123
- **Email**: admin@luckydraw.com

**Important**: Change the default password after first login!

## Usage

### User Flow

1. **Registration**: Users create accounts
2. **Browse Draws**: View available lucky draws
3. **Join Draw**: Select a draw and choose payment method
4. **Payment**: Complete payment via EasyPaisa or PayPal
5. **Confirmation**: Receive confirmation and entry number
6. **Results**: Check results after draw completion

### Admin Functions

- **Dashboard**: View system statistics and recent activity
- **User Management**: Manage user accounts and permissions
- **Lucky Draw Management**: Create, edit, and manage draws
- **Entry Management**: View and manage user entries
- **System Settings**: Configure draw frequency, entry fees, etc.
- **Reports**: Generate reports on entries and revenue

## Configuration

### Lucky Draw Settings

- **Draw Frequency**: Set how often draws are held (daily, weekly, monthly)
- **Entry Fee**: Default entry fee for draws
- **Max Entries**: Maximum number of entries per draw

### Payment Configuration

The system supports two payment methods:

1. **EasyPaisa**: Mobile payment solution
2. **PayPal**: Online payment gateway

Configure payment gateway credentials in the respective payment controllers.

## File Structure

```
lucky-draw-system/
├── app/
│   ├── Config/          # Configuration files
│   ├── Controllers/     # Application controllers
│   ├── Database/        # Migrations and seeders
│   ├── Filters/         # Middleware filters
│   ├── Models/          # Database models
│   └── Views/           # View templates
├── public/              # Web root directory
├── writable/            # Logs, cache, uploads
└── vendor/              # Composer dependencies
```

## API Endpoints

### Public Endpoints

- `GET /` - Home page
- `GET /lucky-draw` - View lucky draws
- `GET /winners` - View past winners
- `GET /faq` - Frequently asked questions

### Authentication Endpoints

- `GET/POST /login` - User login
- `GET/POST /register` - User registration
- `GET /logout` - User logout
- `GET /profile` - User profile

### Lucky Draw Endpoints

- `GET /lucky-draw/join` - Join a lucky draw
- `POST /lucky-draw/process-payment` - Process payment
- `GET /lucky-draw/my-entries` - View user entries

### Admin Endpoints

- `GET /admin` - Admin dashboard
- `GET /admin/users` - User management
- `GET /admin/draws` - Lucky draw management
- `GET /admin/settings` - System settings

## Security Features

- **Password Hashing**: Secure password storage using PHP's password_hash()
- **Session Management**: Secure session handling
- **Input Validation**: Comprehensive input validation and sanitization
- **CSRF Protection**: Built-in CSRF protection
- **Admin Authentication**: Separate admin authentication system

## Customization

### Adding New Payment Methods

1. Create a new payment controller
2. Add payment method to the database
3. Update the join view to include the new option
4. Implement payment processing logic

### Modifying Draw Frequency

Update the settings in the admin panel or modify the `SettingModel` to change draw frequency logic.

### Styling

The system uses Tailwind CSS. Modify the CSS classes in the view files to customize the appearance.

## Troubleshooting

### Common Issues

1. **Database Connection Error**
   - Verify database credentials in `.env`
   - Ensure database server is running
   - Check database permissions

2. **Migration Errors**
   - Ensure database exists
   - Check PHP version compatibility
   - Verify database driver support

3. **Payment Processing Issues**
   - Check payment gateway credentials
   - Verify webhook configurations
   - Review server logs for errors

### Logs

Check the following log files for debugging:

- `writable/logs/log-*.php` - Application logs
- Web server error logs
- Payment gateway logs

## Support

For support and questions:

1. Check the FAQ section
2. Review the code comments
3. Check the CodeIgniter 4 documentation
4. Create an issue in the repository

## License

This project is licensed under the MIT License - see the LICENSE file for details.

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests if applicable
5. Submit a pull request

## Changelog

### Version 1.0.0
- Initial release
- User registration and authentication
- Lucky draw management
- Payment integration (EasyPaisa, PayPal)
- Admin panel
- Responsive design with Tailwind CSS
