CREATE DATABASE IF NOT EXISTS zbos_milktea;
USE zbos_milktea;


CREATE TABLE menu_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    item_id VARCHAR(50) UNIQUE NOT NULL,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    category VARCHAR(50) DEFAULT 'milktea',
    is_available BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);


INSERT INTO menu_items (item_id, name, description, price, category) VALUES
-- Milk Teas
('classic', 'Classic Milk Tea', 'Traditional milk tea with chewy tapioca pearls', 50.00, 'classic'),
('taro', 'Taro Milk Tea', 'Creamy taro flavor with milk and pearls', 50.00, 'classic'),
('matcha', 'Matcha Milk Tea', 'Premium matcha green tea with milk', 50.00, 'premium'),
('chocolate', 'Chocolate Milk Tea', 'Rich chocolate flavor with milk tea', 50.00, 'classic'),
('strawberry', 'Strawberry Milk Tea', 'Sweet strawberry with milk tea', 50.00, 'classic'),
('brown_sugar', 'Brown Sugar Milk Tea', 'Caramelized brown sugar with fresh milk', 50.00, 'premium'),
('oolong', 'Oolong Milk Tea', 'Delicate oolong tea blended with creamy milk', 50.00, 'classic'),
('honeydew', 'Honeydew Milk Tea', 'Fragrant honeydew melon paired with smooth milk tea', 50.00, 'premium'),
('thai_tea', 'Thai Milk Tea', 'Strong brewed Thai tea with sweetened condensed milk', 50.00, 'premium'),
('lavender', 'Lavender Milk Tea', 'Light floral notes of lavender with creamy milk tea', 50.00, 'specialty'),
('oat_milk', 'Oat Milk Tea', 'Creamy oat milk with a smooth tea base, dairy-free option', 50.00, 'specialty'),

-- Hot Coffee
('coffee_hot_americano', 'Hot Americano', 'Rich espresso shots with hot water, bold and smooth', 70.00, 'coffee_hot'),
('coffee_hot_cappuccino', 'Hot Cappuccino', 'Espresso with steamed milk and velvety foam', 70.00, 'coffee_hot'),
('coffee_hot_latte', 'Hot Latte', 'Smooth espresso with steamed milk, creamy and comforting', 70.00, 'coffee_hot'),
('coffee_hot_mocha', 'Hot Mocha', 'Espresso with chocolate and steamed milk, indulgent and rich', 70.00, 'coffee_hot'),
('coffee_hot_espresso', 'Hot Espresso', 'Pure, intense espresso shot with rich crema', 70.00, 'coffee_hot'),
('coffee_latte', 'Coffee Milk Latte', 'Espresso blended with milk for a coffee-forward latte', 70.00, 'coffee_hot'),

-- Cold Coffee
('coffee_cold_iced_latte', 'Iced Latte', 'Smooth espresso with cold milk over ice', 60.00, 'coffee_cold'),
('coffee_cold_iced_mocha', 'Iced Mocha', 'Chilled espresso with chocolate and cold milk', 60.00, 'coffee_cold'),
('coffee_cold_cold_brew', 'Cold Brew', 'Smooth, naturally sweet cold-brewed coffee', 60.00, 'coffee_cold'),
('coffee_cold_iced_cappuccino', 'Iced Cappuccino', 'Espresso with cold milk and foam, refreshing', 60.00, 'coffee_cold'),
('coffee_cold_iced_americano', 'Iced Americano', 'Chilled espresso with cold water over ice', 60.00, 'coffee_cold'),

-- Frappes
('frappe_mocha', 'Mocha Frappe', 'Blended mocha coffee with ice, topped with whipped cream', 95.00, 'frappes'),
('frappe_caramel', 'Caramel Frappe', 'Rich caramel flavor blended with coffee and ice', 95.00, 'frappes'),
('frappe_chocolate', 'Chocolate Frappe', 'Decadent chocolate coffee frappe, rich and indulgent', 95.00, 'frappes'),
('frappe_matcha', 'Matcha Frappe', 'Premium matcha green tea blended with ice, refreshing', 95.00, 'frappes'),
('frappe_strawberry', 'Strawberry Frappe', 'Sweet strawberry flavor blended to perfection', 95.00, 'frappes'),
('frappe_vanilla', 'Vanilla Frappe', 'Smooth vanilla blended with coffee and ice', 95.00, 'frappes'),

-- Fruit Teas
('fruit_tea_strawberry', 'Strawberry Fruit Tea', 'Fresh strawberries with fruity tea blend, naturally sweet', 75.00, 'fruit_tea'),
('fruit_tea_peach', 'Peach Fruit Tea', 'Juicy peach chunks with refreshing fruit tea', 80.00, 'fruit_tea'),
('fruit_tea_mango', 'Mango Fruit Tea', 'Fresh mango with light tea, tropical and refreshing', 75.00, 'fruit_tea'),
('fruit_tea_lychee', 'Lychee Fruit Tea', 'Sweet lychee with delicate tea blend', 80.00, 'fruit_tea'),
('fruit_tea_passion', 'Passion Fruit Tea', 'Tangy passion fruit with aromatic tea', 75.00, 'fruit_tea'),
('fruit_tea_grape', 'Grape Fruit Tea', 'Sweet grape flavor with refreshing fruit tea', 80.00, 'fruit_tea'),
('fruit_tea_watermelon', 'Watermelon Fruit Tea', 'Refreshing watermelon with light fruit tea', 75.00, 'fruit_tea'),
('fruit_tea_blueberry', 'Blueberry Fruit Tea', 'Antioxidant-rich blueberries with fruity tea', 85.00, 'fruit_tea'),

-- Cookies
('cookie_choc_chip', 'Chocolate Chip Cookie', 'Classic chewy chocolate chip cookie, baked fresh', 55.00, 'cookies'),
('cookie_double_choco', 'Double Chocolate Cookie', 'Intensely chocolatey cookie with gooey chunks', 65.00, 'cookies'),
('cookie_matcha', 'Matcha Cookie', 'Delicate matcha-flavored cookie with a subtle tea aroma', 60.00, 'cookies'),
('cookie_oatmeal_raisin', 'Oatmeal Raisin Cookie', 'Hearty oatmeal cookie with plump raisins', 55.00, 'cookies'),
('cookie_shortbread', 'Classic Shortbread', 'Traditional buttery shortbread cookie', 50.00, 'cookies');


CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_number VARCHAR(20) UNIQUE NOT NULL,
    customer_name VARCHAR(100) NOT NULL,
    contact_number VARCHAR(20) NOT NULL,
    delivery_address TEXT NOT NULL,
    payment_method ENUM('cash', 'gcash', 'paymaya') NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL,
    status ENUM('pending', 'preparing', 'completed', 'cancelled') DEFAULT 'pending',
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);


CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    item_id VARCHAR(50) NOT NULL,
    item_name VARCHAR(100) NOT NULL,
    size ENUM('regular', 'large') NOT NULL,
    sugar_level ENUM('no_sugar', 'less_sugar', 'normal', 'more_sugar') NOT NULL,
    ice_level ENUM('no_ice', 'less_ice', 'normal', 'extra_ice') NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    unit_price DECIMAL(10,2) NOT NULL,
    total_price DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (item_id) REFERENCES menu_items(item_id)
);


CREATE TABLE admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL
);

-- Users table for customer accounts
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
        password_hash VARCHAR(255) NOT NULL,
        full_name VARCHAR(100) NOT NULL,
        contact_number VARCHAR(11),
        address TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        last_login TIMESTAMP NULL,
        status ENUM('active', 'inactive', 'suspended') DEFAULT 'active',
        verification_token VARCHAR(100),
        is_verified BOOLEAN DEFAULT FALSE
);

-- Add user_id to orders table to link orders to users
ALTER TABLE orders ADD COLUMN user_id INT NULL AFTER id;
ALTER TABLE orders ADD FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL;


INSERT INTO admin_users (username, password_hash, full_name, email) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator', 'admin@zbos.com');

-- Create indexes for better performance
CREATE INDEX idx_orders_status ON orders(status);
CREATE INDEX idx_orders_date ON orders(order_date);
CREATE INDEX idx_order_items_order_id ON order_items(order_id);
CREATE INDEX idx_order_items_item_id ON order_items(item_id);

-- Create views for common queries
CREATE VIEW order_summary AS
SELECT 
    o.id,
    o.order_number,
    o.customer_name,
    o.contact_number,
    o.total_amount,
    o.status,
    o.order_date,
    COUNT(oi.id) as item_count
FROM orders o
LEFT JOIN order_items oi ON o.id = oi.order_id
GROUP BY o.id, o.order_number, o.customer_name, o.contact_number, o.total_amount, o.status, o.order_date;

CREATE VIEW daily_sales AS
SELECT 
    DATE(order_date) as sale_date,
    COUNT(*) as total_orders,
    SUM(total_amount) as total_revenue,
    AVG(total_amount) as average_order_value
FROM orders 
WHERE status != 'cancelled'
GROUP BY DATE(order_date)
ORDER BY sale_date DESC;

