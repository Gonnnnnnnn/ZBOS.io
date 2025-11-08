# ZapBrew Ordering System

A complete milk tea ordering system built with PHP and Bootstrap. This system allows customers to browse menu items, customize their orders, and place orders online. It also includes an admin panel for order management.

## Features

### Customer Features
- 🧋 Browse milk tea menu with descriptions and prices
- 🎯 Customize orders (size, sugar level, ice level, quantity)
- 🛒 Shopping cart functionality
- 📱 Responsive design for mobile and desktop
- 💳 Multiple payment options (Cash, GCash, PayMaya)
- 📧 Order confirmation with receipt
- 🖨️ Print receipt functionality

### Admin Features
- 🔐 Secure admin login
- 📊 Order management dashboard
- 📈 Order statistics and analytics
- ✏️ Update order status (Pending, Preparing, Completed)
- 👥 Customer information management
- 📋 Detailed order tracking

## Installation

### Requirements
- PHP 7.4 or higher
- Web server (Apache/Nginx)
- MySQL (optional, for database version)

### Quick Setup
1. Clone or download the project files
2. Place files in your web server directory (e.g., `htdocs` for XAMPP)
3. Ensure PHP sessions are enabled
4. Access the system through your web browser

### File Structure
```
ZBOS/
├── index.php              # Main ordering interface
├── process_order.php       # Order processing
├── order_confirmation.php  # Order confirmation page
├── admin.php              # Admin panel
├── database_schema.sql    # Database schema (optional)
├── orders.json           # Order storage (auto-created)
└── README.md             # This file
```

## Usage

### For Customers
1. Open `index.php` in your browser
2. Browse the milk tea menu
3. Click "Order" on any item to customize
4. Add items to cart
5. Proceed to checkout
6. Fill in delivery information
7. Place order and receive confirmation

### For Administrators
1. Access `admin.php`
2. Login with password: `admin123` (change in production)
3. View and manage orders
4. Update order status as needed
5. Monitor order statistics

## Customization

### Adding New Menu Items
Edit the `$menu_items` array in `index.php`:

```php
$menu_items = [
    'new_item' => [
        'name' => 'New Milk Tea',
        'price' => 125,
        'description' => 'Description of the new item'
    ],
    // ... existing items
];
```

### Changing Admin Password
Update the `$admin_password` variable in `admin.php`:

```php
$admin_password = 'your_secure_password';
```

### Styling
The system uses Bootstrap 5 with custom CSS. Modify the `<style>` sections in each PHP file to customize the appearance.

## Database Integration (Optional)

For production use, consider implementing the database schema:

1. Create a MySQL database
2. Import `database_schema.sql`
3. Update the PHP files to use database connections instead of JSON files
4. Implement proper user authentication

## Security Considerations

- Change default admin password
- Implement proper user authentication
- Use HTTPS in production
- Validate and sanitize all user inputs
- Implement CSRF protection
- Use prepared statements for database queries

## Browser Support

- Chrome 60+
- Firefox 60+
- Safari 12+
- Edge 79+

## Technologies Used

- **Backend**: PHP 7.4+
- **Frontend**: Bootstrap 5, HTML5, CSS3, JavaScript
- **Icons**: Font Awesome 6
- **Storage**: JSON files (file-based storage)

## License

This project is open source and available under the MIT License.

## Support

For support or questions, please contact the development team.

---

**ZapBrew Ordering System** - Made with ❤️ for milk tea lovers!
