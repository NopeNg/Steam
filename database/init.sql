-- Khởi tạo Cơ sở dữ liệu
CREATE DATABASE IF NOT EXISTS game_key_marketplace;
USE game_key_marketplace;

-- ============================================
-- 1. NHÓM BẢNG DANH MỤC & SẢN PHẨM (GAMES)
-- ============================================

CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_name VARCHAR(100) NOT NULL UNIQUE
);

CREATE TABLE games (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    release_date DATE,
    description TEXT,
    cover_image VARCHAR(255),
    publisher VARCHAR(150),
    developer VARCHAR(150),
    -- Mặc định là 'Inactive' để làm bản nháp an toàn, tránh lộ game chưa hoàn thiện ra ngoài shop
    status VARCHAR(50) DEFAULT 'Inactive', 
    requirements TEXT
);

CREATE TABLE game_images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    game_id INT NOT NULL,
    image_path VARCHAR(255) NOT NULL,
    image_type VARCHAR(50),
    game_part VARCHAR(100),
    FOREIGN KEY (game_id) REFERENCES games(id) ON DELETE CASCADE
);

CREATE TABLE game_categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    game_id INT NOT NULL,
    category_id INT NOT NULL,
    FOREIGN KEY (game_id) REFERENCES games(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
);

-- ============================================
-- 2. NHÓM BẢNG KHUYẾN MÃI & PHIÊN BẢN GAME
-- ============================================

CREATE TABLE promotions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    campaign_name VARCHAR(255) NOT NULL,
    discount_percent INT NOT NULL,
    start_time DATETIME NOT NULL,
    end_time DATETIME NOT NULL
);

CREATE TABLE game_versions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    game_id INT NOT NULL,
    promotion_id INT DEFAULT NULL,
    version_name VARCHAR(100) NOT NULL,
    price DECIMAL(15, 2) NOT NULL,
    discount_price DECIMAL(15, 2) DEFAULT NULL,
    -- ❌ ĐÃ XÓA CỘT STATUS TẠI ĐÂY (Mọi logic kiểm tra mua bán sẽ đi theo status của bảng games)
    FOREIGN KEY (game_id) REFERENCES games(id) ON DELETE CASCADE,
    FOREIGN KEY (promotion_id) REFERENCES promotions(id) ON DELETE SET NULL
);

-- ============================================
-- 3. NHÓM BẢNG NGƯỜI DÙNG & TƯƠNG TÁC
-- ============================================

CREATE TABLE players (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    fullname VARCHAR(150),
    status VARCHAR(50) DEFAULT 'Active', -- Player mặc định đăng ký xong là Active để chơi ngay
    balance DECIMAL(15,2) DEFAULT 0.00,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE friendships (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sender_id INT NOT NULL,
    receiver_id INT NOT NULL,
    status VARCHAR(50) DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sender_id) REFERENCES players(id) ON DELETE CASCADE,
    FOREIGN KEY (receiver_id) REFERENCES players(id) ON DELETE CASCADE
);

CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ============================================
-- 4. NHÓM BẢNG ĐƠN HÀNG & KEY SỐ
-- ============================================

CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    player_id INT NOT NULL,
    handled_by_admin INT DEFAULT NULL,
    total_amount DECIMAL(15, 2) NOT NULL,
    order_type VARCHAR(50),
    status VARCHAR(50) DEFAULT 'Pending',
    payment_method VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (player_id) REFERENCES players(id),
    FOREIGN KEY (handled_by_admin) REFERENCES admins(id)
);

CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    game_version_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    price_at_purchase DECIMAL(15, 2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (game_version_id) REFERENCES game_versions(id)
);

CREATE TABLE game_keys (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_item_id INT DEFAULT NULL,
    key_code VARCHAR(100) NOT NULL,
    status VARCHAR(50) DEFAULT 'Delivered',
    fetched_at DATETIME NOT NULL,
    supplier_transaction_id VARCHAR(150) NOT NULL,
    supplier_code VARCHAR(100) DEFAULT NULL,
    FOREIGN KEY (order_item_id) REFERENCES order_items(id) ON DELETE SET NULL
);

-- ============================================
-- 5. NHÓM BẢNG KHO LƯU TRỮ & QUÀ TẶNG
-- ============================================

CREATE TABLE library (
    id INT AUTO_INCREMENT PRIMARY KEY,
    player_id INT NOT NULL,
    game_key_id INT NOT NULL,
    game_id INT DEFAULT NULL,
    key_code VARCHAR(255) DEFAULT NULL,
    version_id INT DEFAULT NULL,
    order_item_id INT DEFAULT NULL,
    purchased_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (player_id) REFERENCES players(id) ON DELETE CASCADE,
    FOREIGN KEY (game_key_id) REFERENCES game_keys(id) ON DELETE CASCADE,
    CONSTRAINT unique_player_key UNIQUE (player_id, game_key_id)
);

CREATE TABLE gifts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sender_id INT NOT NULL,
    receiver_id INT NOT NULL,
    game_key_id INT NOT NULL,
    message TEXT,
    status VARCHAR(50) DEFAULT 'Sent',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sender_id) REFERENCES players(id),
    FOREIGN KEY (receiver_id) REFERENCES players(id),
    FOREIGN KEY (game_key_id) REFERENCES game_keys(id),
    CONSTRAINT unique_gift_key UNIQUE (game_key_id)
);

-- ============================================
-- 6. NHÓM BẢNG GIỎ HÀNG
-- ============================================

CREATE TABLE carts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    player_id INT NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (player_id) REFERENCES players(id) ON DELETE CASCADE
);

CREATE TABLE cart_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cart_id INT NOT NULL,
    game_version_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (cart_id) REFERENCES carts(id) ON DELETE CASCADE,
    FOREIGN KEY (game_version_id) REFERENCES game_versions(id) ON DELETE CASCADE,
    CONSTRAINT unique_cart_game UNIQUE (cart_id, game_version_id)
);



-- ============================================
-- 7. NHÓM BẢNG VÍ TIỀN & WALLETS
-- ============================================

CREATE TABLE wallet_transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    player_id INT NOT NULL,
    amount DECIMAL(15, 2) NOT NULL,
    transaction_code VARCHAR(150) NOT NULL,
    status VARCHAR(50) DEFAULT 'success',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (player_id) REFERENCES players(id) ON DELETE CASCADE
);

-- ============================================
-- 8. NHÓM BẢNG NHÀ CUNG CẤP (SUPPLIERS)
-- ============================================

CREATE TABLE supplier_providers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    code VARCHAR(100) UNIQUE NOT NULL,
    base_url VARCHAR(500) NOT NULL,
    api_key VARCHAR(500),
    api_key_header VARCHAR(100) DEFAULT 'X-API-Key',
    timeout INT DEFAULT 15,
    priority INT DEFAULT 0,
    headers JSON,
    purchase_endpoint VARCHAR(500) DEFAULT '/api/purchase',
    verify_endpoint VARCHAR(500) DEFAULT '/api/verify-key',
    status VARCHAR(50) DEFAULT 'Active',
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE game_supplier_mappings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    game_id INT NOT NULL,
    supplier_provider_id INT NOT NULL,
    supplier_game_id VARCHAR(255),
    status VARCHAR(50) DEFAULT 'Active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (supplier_provider_id) REFERENCES supplier_providers(id) ON DELETE CASCADE,
    UNIQUE KEY unique_game_supplier (game_id, supplier_provider_id)
);

-- ============================================
-- 9. NHÓM BẢNG LOG HOẠT ĐỘNG
-- ============================================

CREATE TABLE activity_logs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    action VARCHAR(255) NOT NULL,
    details TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);
