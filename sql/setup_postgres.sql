-- Gaming Platform Database Setup for PostgreSQL

-- Create users table
CREATE TABLE IF NOT EXISTS users (
    id SERIAL PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_active BOOLEAN DEFAULT TRUE,
    last_login TIMESTAMP NULL
);

-- Create game categories table
CREATE TABLE IF NOT EXISTS categories (
    id SERIAL PRIMARY KEY,
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
    id SERIAL PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    thumbnail_url VARCHAR(500),
    category_id INT,
    is_hot BOOLEAN DEFAULT FALSE,
    is_active BOOLEAN DEFAULT TRUE,
    play_count INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_games_category FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
);

-- Create user favorites table
CREATE TABLE IF NOT EXISTS user_favorites (
    id SERIAL PRIMARY KEY,
    user_id INT NOT NULL,
    game_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_favorites_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT fk_favorites_game FOREIGN KEY (game_id) REFERENCES games(id) ON DELETE CASCADE,
    CONSTRAINT unique_favorite UNIQUE (user_id, game_id)
);

-- Create user activity log table
CREATE TABLE IF NOT EXISTS user_activity (
    id SERIAL PRIMARY KEY,
    user_id INT NOT NULL,
    action VARCHAR(50) NOT NULL,
    details TEXT,
    ip_address INET,
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_activity_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Create sessions table for better session management
CREATE TABLE IF NOT EXISTS user_sessions (
    id VARCHAR(128) PRIMARY KEY,
    user_id INT,
    ip_address INET,
    user_agent TEXT,
    payload TEXT,
    last_activity TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_sessions_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Create indexes for better performance
CREATE INDEX IF NOT EXISTS idx_users_username ON users(username);
CREATE INDEX IF NOT EXISTS idx_users_email ON users(email);
CREATE INDEX IF NOT EXISTS idx_games_category ON games(category_id);
CREATE INDEX IF NOT EXISTS idx_games_hot ON games(is_hot);
CREATE INDEX IF NOT EXISTS idx_games_active ON games(is_active);
CREATE INDEX IF NOT EXISTS idx_games_category_hot ON games(category_id, is_hot);
CREATE INDEX IF NOT EXISTS idx_favorites_user ON user_favorites(user_id);
CREATE INDEX IF NOT EXISTS idx_favorites_game ON user_favorites(game_id);
CREATE INDEX IF NOT EXISTS idx_activity_user ON user_activity(user_id);
CREATE INDEX IF NOT EXISTS idx_activity_action ON user_activity(action);
CREATE INDEX IF NOT EXISTS idx_activity_created ON user_activity(created_at);
CREATE INDEX IF NOT EXISTS idx_sessions_user ON user_sessions(user_id);
CREATE INDEX IF NOT EXISTS idx_sessions_activity ON user_sessions(last_activity);

-- Insert default categories (using INSERT ... ON CONFLICT for PostgreSQL)
INSERT INTO categories (id, name, slug, icon, color, sort_order) VALUES
(1, 'Hot', 'hot', '🔥', '#ff4757', 1),
(2, 'Slots', 'slots', '🎰', '#5352ed', 2),
(3, 'Fish', 'fish', '🐠', '#20bf6b', 3),
(4, 'Live', 'live', '📺', '#fd79a8', 4),
(5, 'Poker', 'poker', '♠️', '#ff6b6b', 5),
(6, 'Sports', 'sports', '⚽', '#4834d4', 6)
ON CONFLICT (id) DO NOTHING;

-- Reset sequence for categories
SELECT setval('categories_id_seq', (SELECT MAX(id) FROM categories));

-- Insert sample games
INSERT INTO games (id, title, slug, description, thumbnail_url, category_id, is_hot) VALUES
(1, 'Super Ace', 'super-ace', 'Experience the ultimate card game adventure', 'https://pixabay.com/get/g1b7163608dfcc1c0b55ca44bcfe425789b0267934d316fb6ca51c97f33f16b953402e118d0f6373c1b2a9f8f7634c0c2e16d701d38c73e394cc809a1ef9721ad_1280.jpg', 1, TRUE),
(2, 'Boxing King', 'boxing-king', 'Enter the ring and become the champion', 'https://pixabay.com/get/g8d701ceff5a5b24ed3969f9a2e60bd49feb10c53b1cc98243ab79ed86361617ec4af8c65a8a0229718f4f75c73cd5cfa083a2a5091af5bd0265ae60705370203_1280.jpg', 6, TRUE),
(3, 'Super Elements', 'super-elements', 'Master the power of elements in this puzzle adventure', 'https://pixabay.com/get/g48d9fbba361a63345c5af4ccfd8b2c310938880410d71510d48d4b4252abed23b961cb27493512a9f7e02d3b7dc0074534f4eae2a5931f912838203b26bdcd82_1280.jpg', 1, TRUE),
(4, 'Aviator', 'aviator', 'Soar through the skies in this thrilling flight game', 'https://pixabay.com/get/g60679fd854a926dddb18329e6e11568d86a71adb420d00a5781ef42b3a27e0a902a0f1a1179a6d90e344c2987d7c1e3bd9f6defc4ba38bc97e01beb79b118b88_1280.jpg', 1, FALSE),
(5, 'Fortune Gems', 'fortune-gems', 'Discover precious gems and win big rewards', 'https://pixabay.com/get/g7fb78a300c689935326d7bac4124c9ff2a1c94b006054513f74c0948899960ff9b4ad2bf1a878fea62027138c509d585c02f4a0864f2d9a837f7ceb4debb6ff7_1280.jpg', 2, FALSE),
(6, 'Wild Bounty Showdown', 'wild-bounty-showdown', 'Western-themed action game with bounty hunting', 'https://pixabay.com/get/gfb749e8adbcb2110381b9c6f6ff192b60c0d2705d6247ca64a63e9541cb4eb38282f05d1cdea10652d7a12318e062698f2d0660c3ba1f619977739d36f6c65bd_1280.jpg', 1, FALSE),
(7, 'Fruity Slots', 'fruity-slots', 'Classic fruit machine with modern twists', 'https://pixabay.com/get/g6e1295d48e3e08e9d6f21847617dcfe7274318a0003c1e32edd8cfe9244ad3a706892a9f3ea8d2d070ec98cbda1812f3aaadd1bfa391ff15ea5b9738f33509c1_1280.jpg', 2, FALSE),
(8, 'Dragon Fortune', 'dragon-fortune', 'Ancient dragon-themed slot adventure', 'https://pixabay.com/get/g3bfbba5509b0fc2dbb2ca38725249e37db9210193eae51b63f01fae591f9990b8575ed493bcc342db074e9ecee66a5edb57022fb919f770dcb4a36ba6e36ca19_1280.jpg', 2, FALSE)
ON CONFLICT (id) DO NOTHING;

-- Reset sequence for games
SELECT setval('games_id_seq', (SELECT MAX(id) FROM games));

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
GROUP BY g.id, c.name
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
GROUP BY u.id, u.username, u.created_at;

-- Create function to update game play count (PostgreSQL version)
CREATE OR REPLACE FUNCTION update_game_play_count()
RETURNS TRIGGER AS $$
BEGIN
    IF NEW.action = 'play_game' THEN
        UPDATE games 
        SET play_count = play_count + 1 
        WHERE id = CAST(NEW.details AS INTEGER);
    END IF;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

-- Create trigger to update game play count
DROP TRIGGER IF EXISTS trigger_update_game_play_count ON user_activity;
CREATE TRIGGER trigger_update_game_play_count
    AFTER INSERT ON user_activity
    FOR EACH ROW
    EXECUTE FUNCTION update_game_play_count();

-- Create function to update timestamps
CREATE OR REPLACE FUNCTION update_updated_at_column()
RETURNS TRIGGER AS $$
BEGIN
    NEW.updated_at = CURRENT_TIMESTAMP;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

-- Create triggers to auto-update timestamps
DROP TRIGGER IF EXISTS trigger_users_updated_at ON users;
CREATE TRIGGER trigger_users_updated_at
    BEFORE UPDATE ON users
    FOR EACH ROW
    EXECUTE FUNCTION update_updated_at_column();

DROP TRIGGER IF EXISTS trigger_games_updated_at ON games;
CREATE TRIGGER trigger_games_updated_at
    BEFORE UPDATE ON games
    FOR EACH ROW
    EXECUTE FUNCTION update_updated_at_column();

DROP TRIGGER IF EXISTS trigger_sessions_updated_at ON user_sessions;
CREATE TRIGGER trigger_sessions_updated_at
    BEFORE UPDATE ON user_sessions
    FOR EACH ROW
    EXECUTE FUNCTION update_updated_at_column();