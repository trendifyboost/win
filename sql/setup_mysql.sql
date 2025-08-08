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

CREATE TABLE IF NOT EXISTS vip_levels (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL,
    bonus_percentage DECIMAL(5, 2) DEFAULT 0.00,
    min_balance DECIMAL(10, 2) DEFAULT 0.00
);

INSERT IGNORE INTO vip_levels (id, name, bonus_percentage, min_balance) VALUES
(1, 'Bronze', 0.00, 0.00),
(2, 'Silver', 5.00, 1000.00),
(3, 'Gold', 10.00, 5000.00),
(4, 'Platinum', 15.00, 10000.00);

CREATE TABLE IF NOT EXISTS login_rewards (
    id INT PRIMARY KEY AUTO_INCREMENT,
    day INT NOT NULL UNIQUE,
    reward_amount DECIMAL(10, 2) NOT NULL
);

INSERT IGNORE INTO login_rewards (day, reward_amount) VALUES
(1, 1.00),
(2, 2.00),
(3, 3.00),
(4, 4.00),
(5, 5.00),
(6, 6.00),
(7, 7.00);

CREATE TABLE IF NOT EXISTS user_login_history (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    login_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ip_address VARCHAR(45),
    user_agent TEXT,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS transactions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    transaction_type ENUM('deposit', 'withdrawal', 'bonus', 'rescue_fund', 'referral_bonus', 'login_reward') NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    description VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS user_favorites (
    user_id INT NOT NULL,
    game_id INT NOT NULL,
    PRIMARY KEY (user_id, game_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (game_id) REFERENCES games(id) ON DELETE CASCADE
);