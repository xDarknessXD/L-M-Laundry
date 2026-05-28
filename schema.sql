-- Laundry Management System Database Schema

-- Users Table
CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    phone VARCHAR(255) NULL,
    email_verified_at TIMESTAMP NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'staff') DEFAULT 'staff',
    status ENUM('pending', 'active', 'suspended') DEFAULT 'pending',
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);

-- Password Reset Tokens Table
CREATE TABLE password_reset_tokens (
    email VARCHAR(255) PRIMARY KEY,
    token VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NULL
);

-- Sessions Table
CREATE TABLE sessions (
    id VARCHAR(255) PRIMARY KEY,
    user_id BIGINT UNSIGNED NULL,
    ip_address VARCHAR(45) NULL,
    user_agent TEXT NULL,
    payload LONGTEXT NOT NULL,
    last_activity INT NOT NULL,
    INDEX idx_user_id (user_id),
    INDEX idx_last_activity (last_activity)
);

-- Services Table
CREATE TABLE services (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    type ENUM('regular', 'self_service', 'rush', 'comforter') NOT NULL,
    price_per_kilo DECIMAL(10,2) DEFAULT 0,
    wash_price DECIMAL(10,2) DEFAULT 0,
    wash_minutes INT DEFAULT 0,
    dry_price DECIMAL(10,2) DEFAULT 0,
    dry_minutes INT DEFAULT 0,
    fold_price DECIMAL(10,2) DEFAULT 0,
    minimum_kilos DECIMAL(10,2) DEFAULT 5,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);

-- Transactions Table
CREATE TABLE transactions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_number VARCHAR(255) NOT NULL UNIQUE,
    customer_name VARCHAR(255) NOT NULL,
    customer_phone VARCHAR(255) NULL,
    service_id BIGINT UNSIGNED NOT NULL,
    material_type ENUM('light', 'jeans', 'heavy') DEFAULT 'light',
    kilos DECIMAL(10,2) DEFAULT 0,
    minutes_per_kilo INT DEFAULT 0,
    subtotal DECIMAL(10,2) DEFAULT 0,
    addons_total DECIMAL(10,2) DEFAULT 0,
    total_amount DECIMAL(10,2) DEFAULT 0,
    amount_paid DECIMAL(10,2) DEFAULT 0,
    balance DECIMAL(10,2) DEFAULT 0,
    payment_status ENUM('unpaid', 'partial', 'paid') DEFAULT 'unpaid',
    order_status ENUM('pending', 'in_progress', 'completed', 'cancelled') DEFAULT 'pending',
    created_by BIGINT UNSIGNED NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    CONSTRAINT fk_transactions_service FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE RESTRICT,
    CONSTRAINT fk_transactions_created_by FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE RESTRICT
);

-- Inventory Categories Table
CREATE TABLE inventory_categories (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    icon VARCHAR(255) DEFAULT '📦',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);

-- Inventory Items Table
CREATE TABLE inventory_items (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    inventory_category_id BIGINT UNSIGNED NOT NULL,
    name VARCHAR(255) NOT NULL,
    brand VARCHAR(255) NULL,
    unit VARCHAR(255) NOT NULL,
    price DECIMAL(10,2) DEFAULT 0,
    stock_quantity INT DEFAULT 0,
    status ENUM('available', 'out_of_stock', 'low_stock') DEFAULT 'available',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    CONSTRAINT fk_inventory_items_category FOREIGN KEY (inventory_category_id) REFERENCES inventory_categories(id) ON DELETE CASCADE
);

-- Transaction Addons Table
CREATE TABLE transaction_addons (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    transaction_id BIGINT UNSIGNED NOT NULL,
    inventory_item_id BIGINT UNSIGNED NULL,
    quantity INT DEFAULT 1,
    price DECIMAL(10,2) DEFAULT 0,
    is_customer_own BOOLEAN DEFAULT FALSE,
    custom_item_name VARCHAR(255) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    CONSTRAINT fk_transaction_addons_transaction FOREIGN KEY (transaction_id) REFERENCES transactions(id) ON DELETE CASCADE,
    CONSTRAINT fk_transaction_addons_inventory_item FOREIGN KEY (inventory_item_id) REFERENCES inventory_items(id) ON DELETE SET NULL
);

-- Machines Table
CREATE TABLE machines (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    machine_code VARCHAR(255) NOT NULL UNIQUE,
    name VARCHAR(255) NOT NULL,
    type VARCHAR(255) NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);

-- Machine Logs Table
CREATE TABLE machine_logs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    machine_id BIGINT UNSIGNED NOT NULL,
    cycle_type ENUM('wash', 'dry') NOT NULL,
    load_kilos DECIMAL(10,2) DEFAULT 0,
    duration_minutes INT DEFAULT 0,
    start_time TIMESTAMP NULL,
    end_time TIMESTAMP NULL,
    staff_id BIGINT UNSIGNED NOT NULL,
    status ENUM('in_progress', 'completed') DEFAULT 'in_progress',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    CONSTRAINT fk_machine_logs_machine FOREIGN KEY (machine_id) REFERENCES machines(id) ON DELETE CASCADE,
    CONSTRAINT fk_machine_logs_staff FOREIGN KEY (staff_id) REFERENCES users(id) ON DELETE RESTRICT
);

-- Cache Table
CREATE TABLE cache (
    key VARCHAR(255) PRIMARY KEY,
    value LONGTEXT NOT NULL,
    expiration INT NOT NULL
);

-- Jobs Table
CREATE TABLE jobs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    queue VARCHAR(255) NOT NULL,
    payload LONGTEXT NOT NULL,
    attempts TINYINT UNSIGNED NOT NULL,
    reserved TINYINT UNSIGNED NULL,
    reserved_at TIMESTAMP NULL,
    available_at TIMESTAMP NOT NULL,
    created_at TIMESTAMP NOT NULL
);

-- Job Batches Table
CREATE TABLE job_batches (
    id VARCHAR(255) PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    total_jobs INT UNSIGNED NOT NULL,
    pending_jobs INT UNSIGNED NOT NULL,
    failed_jobs INT UNSIGNED NOT NULL,
    failed_job_ids LONGTEXT NULL,
    options LONGTEXT NULL,
    cancelled_at TIMESTAMP NULL,
    created_at TIMESTAMP NULL,
    finished_at TIMESTAMP NULL
);

-- Failed Jobs Table
CREATE TABLE failed_jobs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    uuid VARCHAR(255) UNIQUE,
    connection TEXT NOT NULL,
    queue TEXT NOT NULL,
    payload LONGTEXT NOT NULL,
    exception LONGTEXT NOT NULL,
    failed_at TIMESTAMP NOT NULL
);