-- Fix sugar level and ice level enums
ALTER TABLE order_items MODIFY COLUMN sugar_level ENUM('no_sugar', 'less_sugar', 'regular', 'more_sugar') NOT NULL;
ALTER TABLE order_items MODIFY COLUMN ice_level ENUM('no_ice', 'less_ice', 'regular', 'more_ice') NOT NULL;

-- Add performance indexes
CREATE INDEX idx_users_username ON users(username);
CREATE INDEX idx_users_email ON users(email);
CREATE INDEX idx_menu_items_category ON menu_items(category);
CREATE INDEX idx_orders_user_id ON orders(user_id);

-- Fix menu_items to match our PHP array
ALTER TABLE menu_items ADD COLUMN popular BOOLEAN DEFAULT FALSE;
ALTER TABLE menu_items ADD COLUMN rating DECIMAL(3,1) DEFAULT 4.5;
ALTER TABLE menu_items ADD COLUMN prep_time VARCHAR(50);

-- Create error log table for debugging
CREATE TABLE error_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    error_type VARCHAR(50) NOT NULL,
    error_message TEXT NOT NULL,
    error_details TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);