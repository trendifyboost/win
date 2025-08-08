-- Gaming Platform Database Setup

-- Create users table
CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    balance DECIMAL(10, 2) DEFAULT 0.00,
    vip_level_id INT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    is_active BOOLEAN DEFAULT TRUE,
    last_login TIMESTAMP NULL,
    INDEX idx_username (username),
    INDEX idx_email (email)
);

-- Create vip_levels table
CREATE TABLE IF NOT EXISTS vip_levels (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL,
    bonus_percentage DECIMAL(5, 2) DEFAULT 0.00,
    min_balance DECIMAL(10, 2) DEFAULT 0.00
);

-- Insert default VIP levels
INSERT IGNORE INTO vip_levels (id, name, bonus_percentage, min_balance) VALUES
(1, 'Bronze', 0.00, 0.00),
(2, 'Silver', 5.00, 1000.00),
(3, 'Gold', 10.00, 5000.00),
(4, 'Platinum', 15.00, 10000.00);

-- Create login_rewards table
CREATE TABLE IF NOT EXISTS login_rewards (
    id INT PRIMARY KEY AUTO_INCREMENT,
    day INT NOT NULL UNIQUE,
    reward_amount DECIMAL(10, 2) NOT NULL
);

-- Insert default login rewards
INSERT IGNORE INTO login_rewards (day, reward_amount) VALUES
(1, 1.00),
(2, 2.00),
(3, 3.00),
(4, 4.00),
(5, 5.00),
(6, 6.00),
(7, 7.00);

-- Create user_login_history table
CREATE TABLE IF NOT EXISTS user_login_history (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    login_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ip_address VARCHAR(45),
    user_agent TEXT,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Create transactions table
CREATE TABLE IF NOT EXISTS transactions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    transaction_type ENUM('deposit', 'withdrawal', 'bonus', 'rescue_fund', 'referral_bonus', 'login_reward') NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    description VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);


-- Create game categories table
CREATE TABLE IF NOT EXISTS categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL,
    slug VARCHAR(50) NOT NULL UNIQUE,
    icon VARCHAR(10),
    color VARCHAR(7),
    sort_order INT DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create games table
CREATE TABLE IF NOT EXISTS games (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    thumbnail_url VARCHAR(500),
    category_id INT,
    is_hot BOOLEAN DEFAULT FALSE,
    is_active BOOLEAN DEFAULT TRUE,
    play_count INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL,
    INDEX idx_category (category_id),
    INDEX idx_hot (is_hot),
    INDEX idx_active (is_active)
);

-- Create user favorites table
CREATE TABLE IF NOT EXISTS user_favorites (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    game_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (game_id) REFERENCES games(id) ON DELETE CASCADE,
    UNIQUE KEY unique_favorite (user_id, game_id),
    INDEX idx_user (user_id),
    INDEX idx_game (game_id)
);

-- Create user activity log table
CREATE TABLE IF NOT EXISTS user_activity (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    action VARCHAR(50) NOT NULL,
    details TEXT,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user (user_id),
    INDEX idx_action (action),
    INDEX idx_created (created_at)
);

-- Create sessions table for better session management
CREATE TABLE IF NOT EXISTS user_sessions (
    id VARCHAR(128) PRIMARY KEY,
    user_id INT,
    ip_address VARCHAR(45),
    user_agent TEXT,
    payload TEXT,
    last_activity TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user (user_id),
    INDEX idx_last_activity (last_activity)
);

-- Insert default categories
INSERT IGNORE INTO categories (id, name, slug, icon, color, sort_order) VALUES
(1, 'Hot', 'hot', '🔥', '#ff4757', 1),
(2, 'Slots', 'slots', '🎰', '#5352ed', 2),
(3, 'Fish', 'fish', '🐠', '#20bf6b', 3),
(4, 'Live', 'live', '📺', '#fd79a8', 4),
(5, 'Poker', 'poker', '♠️', '#ff6b6b', 5),
(6, 'Sports', 'sports', '⚽', '#4834d4', 6);

-- Insert sample games
INSERT IGNORE INTO games (id, title, slug, description, thumbnail_url, category_id, is_hot) VALUES
(1, 'Super Ace', 'super-ace', 'Experience the ultimate card game adventure', 'https://pixabay.com/get/g1b7163608dfcc1c0b55ca44bcfe425789b0267934d316fb6ca51c97f33f16b953402e118d0f6373c1b2a9f8f7634c0c2e16d701d38c73e394cc809a1ef9721ad_1280.jpg', 1, TRUE),
(2, 'Boxing King', 'boxing-king', 'Enter the ring and become the champion', 'https://pixabay.com/get/g8d701ceff5a5b24ed3969f9a2e60bd49feb10c53b1cc98243ab79ed86361617ec4af8c65a8a0229718f4f75c73cd5cfa083a2a5091af5bd0265ae60705370203_1280.jpg', 6, TRUE),
(3, 'Super Elements', 'super-elements', 'Master the power of elements in this puzzle adventure', 'https://pixabay.com/get/g48d9fbba361a63345c5af4ccfd8b2c310938880410d71510d48d4b4252abed23b961cb27493512a9f7e02d3b7dc0074534f4eae2a5931f912838203b26bdcd82_1280.jpg', 1, TRUE),
(4, 'Aviator', 'aviator', 'Soar through the skies in this thrilling flight game', 'https://pixabay.com/get/g60679fd854a926dddb18329e6e11568d86a71adb420d00a5781ef42b3a27e0a902a0f1a1179a6d90e344c2987d7c1e3bd9f6defc4ba38bc97e01beb79b118b88_1280.jpg', 1, FALSE),
(5, 'Fortune Gems', 'fortune-gems', 'Discover precious gems and win big rewards', 'https://pixabay.com/get/g7fb78a300c689935326d7bac4124c9ff2a1c94b006054513f74c0948899960ff9b4ad2bf1a878fea62027138c509d585c02f4a0864f2d9a837f7ceb4debb6ff7_1280.jpg', 2, FALSE),
(6, 'Wild Bounty Showdown', 'wild-bounty-showdown', 'Western-themed action game with bounty hunting', 'https://pixabay.com/get/gfb749e8adbcb2110381b9c6f6ff192b60c0d2705d6247ca64a63e9541cb4eb38282f05d1cdea10652d7a12318e062698f2d0660c3ba1f619977739d36f6c65bd_1280.jpg', 1, FALSE),
(7, 'Fruity Slots', 'fruity-slots', 'Classic fruit machine with modern twists', 'https://pixabay.com/get/g6e1295d48e3e08e9d6f21847617dcfe7274318a0003c1e32edd8cfe9244ad3a706892a9f3ea8d2d070ec98cbda1812f3aaadd1bfa391ff15ea5b9738f33509c1_1280.jpg', 2, FALSE),
(8, 'Dragon Fortune', 'dragon-fortune', 'Ancient dragon-themed slot adventure', 'https://pixabay.com/get/g3bfbba5509b0fc2dbb2ca38725249e37db9210193eae51b63f01fae591f9990b8575ed493bcc342db074e9ecee66a5edb57022fb919f770dcb4a36ba6e36ca19_1280.jpg', 2, FALSE);

-- Create indexes for better performance
CREATE INDEX IF NOT EXISTS idx_games_category_hot ON games(category_id, is_hot);
CREATE INDEX IF NOT EXISTS idx_games_active_hot ON games(is_active, is_hot);
CREATE INDEX IF NOT EXISTS idx_user_activity_user_created ON user_activity(user_id, created_at);

-- Create a view for popular games
CREATE OR REPLACE VIEW popular_games AS
SELECT 
    g.*,
    c.name as category_name,
    COUNT(uf.id) as favorite_count
FROM games g
LEFT JOIN categories c ON g.category_id = c.id
LEFT JOIN user_favorites uf ON g.id = uf.game_id
WHERE g.is_active = TRUE
GROUP BY g.id
ORDER BY favorite_count DESC, g.play_count DESC;

-- Create a view for user statistics
CREATE OR REPLACE VIEW user_stats AS
SELECT 
    u.id,
    u.username,
    u.created_at,
    COUNT(DISTINCT uf.game_id) as favorite_games_count,
    COUNT(DISTINCT ua.id) as total_activities,
    MAX(ua.created_at) as last_activity
FROM users u
LEFT JOIN user_favorites uf ON u.id = uf.user_id
LEFT JOIN user_activity ua ON u.id = ua.user_id
WHERE u.is_active = TRUE
GROUP BY u.id;

-- Add some constraints for data integrity
ALTER TABLE users 
ADD CONSTRAINT chk_username_length CHECK (CHAR_LENGTH(username) >= 3),
ADD CONSTRAINT chk_email_format CHECK (email REGEXP '^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}
);

ALTER TABLE games
ADD CONSTRAINT chk_title_length CHECK (CHAR_LENGTH(title) >= 1);

-- Create trigger to update game play count
DELIMITER //
CREATE TRIGGER IF NOT EXISTS update_game_play_count
AFTER INSERT ON user_activity
FOR EACH ROW
BEGIN
    IF NEW.action = 'play_game' THEN
        UPDATE games 
        SET play_count = play_count + 1 
        WHERE id = CAST(NEW.details AS UNSIGNED);
    END IF;
END //
DELIMITER ;

-- Create trigger to log user login
DELIMITER //
CREATE TRIGGER IF NOT EXISTS log_user_login
AFTER UPDATE ON users
FOR EACH ROW
BEGIN
    IF NEW.last_login IS NOT NULL AND (OLD.last_login IS NULL OR NEW.last_login != OLD.last_login) THEN
        INSERT INTO user_login_history (user_id, ip_address, user_agent)
        SELECT id, ip_address, user_agent FROM user_sessions WHERE user_id = NEW.id ORDER BY last_activity DESC LIMIT 1;
    END IF;
END //
DELIMITER ;
