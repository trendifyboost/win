<?php
// Games page
$category = $_GET['category'] ?? 'all';
$games = getGamesByCategory($category === 'all' ? null : $category);
$categories = getGameCategories();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Games - <?= getenv('APP_NAME') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="/assets/css/style.css" rel="stylesheet">
</head>
<body>
    <div class="app-container">
        <header class="app-header">
            <div class="container-fluid">
                <div class="d-flex align-items-center justify-content-between">
                    <a href="/" class="logo text-decoration-none">
                        <h1 class="logo-text">66<span class="logo-accent">ZZ</span><span class="logo-suffix">.com</span></h1>
                    </a>
                    <div class="header-actions">
                        <div class="search-box">
                            <input type="text" id="gameSearch" placeholder="Search games..." class="form-control">
                            <i class="fas fa-search"></i>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <main class="main-content">
            <div class="container py-4">
                <!-- Category Filter -->
                <div class="category-filter mb-4">
                    <div class="filter-tabs">
                        <button class="filter-tab <?= $category === 'all' ? 'active' : '' ?>" data-category="all">
                            All Games
                        </button>
                        <?php foreach ($categories as $cat): ?>
                            <button class="filter-tab <?= $category == $cat['id'] ? 'active' : '' ?>" data-category="<?= $cat['id'] ?>">
                                <?= $cat['icon'] ?> <?= htmlspecialchars($cat['name']) ?>
                            </button>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Games Grid -->
                <div class="games-section">
                    <div class="section-header">
                        <h2>
                            <?php 
                            if ($category === 'all') {
                                echo 'All Games';
                            } else {
                                $categoryName = '';
                                foreach ($categories as $cat) {
                                    if ($cat['id'] == $category) {
                                        $categoryName = $cat['name'];
                                        break;
                                    }
                                }
                                echo htmlspecialchars($categoryName) . ' Games';
                            }
                            ?>
                        </h2>
                        <span class="games-count"><?= count($games) ?> games found</span>
                    </div>

                    <div class="games-grid" id="gamesGrid">
                        <?php if (empty($games)): ?>
                            <div class="no-games">
                                <i class="fas fa-gamepad"></i>
                                <h3>No games found</h3>
                                <p>Try selecting a different category or check back later for new games!</p>
                            </div>
                        <?php else: ?>
                            <?php foreach ($games as $game): ?>
                                <div class="game-card" data-game-id="<?= $game['id'] ?>" data-title="<?= strtolower($game['title']) ?>">
                                    <div class="game-image">
                                        <img src="<?= htmlspecialchars($game['thumbnail_url']) ?>" alt="<?= htmlspecialchars($game['title']) ?>" loading="lazy">
                                        <div class="game-overlay">
                                            <button class="btn-play">
                                                <i class="fas fa-play"></i>
                                            </button>
                                            <button class="btn-favorite">
                                                <i class="far fa-heart"></i>
                                            </button>
                                        </div>
                                        <?php if ($game['is_hot']): ?>
                                            <div class="hot-badge">HOT</div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="game-info">
                                        <h4 class="game-title"><?= htmlspecialchars($game['title']) ?></h4>
                                        <?php if ($game['description']): ?>
                                            <p class="game-description"><?= htmlspecialchars(substr($game['description'], 0, 60)) ?>...</p>
                                        <?php endif; ?>
                                        <div class="game-stats">
                                            <span class="play-count">
                                                <i class="fas fa-play"></i> <?= $game['play_count'] ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </main>

        <!-- Bottom Navigation -->
        <nav class="bottom-nav">
            <div class="nav-items">
                <a href="/" class="nav-item">
                    <i class="fas fa-home"></i>
                    <span>Home</span>
                </a>
                <a href="/promotion" class="nav-item">
                    <i class="fas fa-gift"></i>
                    <span>Promotion</span>
                </a>
                <a href="/deposit" class="nav-item">
                    <i class="fas fa-plus-circle"></i>
                    <span>Deposit</span>
                </a>
                <a href="/services" class="nav-item">
                    <i class="fas fa-headset"></i>
                    <span>Services</span>
                </a>
                <a href="/member" class="nav-item">
                    <i class="fas fa-user"></i>
                    <span>Member</span>
                </a>
            </div>
        </nav>
    </div>

    <style>
    .search-box {
        position: relative;
        max-width: 300px;
    }

    .search-box input {
        background: var(--dark-bg);
        border: 1px solid var(--border-color);
        color: var(--text-primary);
        padding-right: 40px;
    }

    .search-box i {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-secondary);
    }

    .category-filter {
        overflow-x: auto;
        padding-bottom: 0.5rem;
    }

    .filter-tabs {
        display: flex;
        gap: 0.5rem;
        min-width: max-content;
        padding: 0 1rem;
    }

    .filter-tab {
        background: var(--dark-card);
        border: 1px solid var(--border-color);
        color: var(--text-secondary);
        padding: 0.75rem 1.5rem;
        border-radius: 25px;
        cursor: pointer;
        transition: all 0.3s ease;
        white-space: nowrap;
    }

    .filter-tab.active,
    .filter-tab:hover {
        background: var(--gradient-secondary);
        color: var(--dark-bg);
        border-color: var(--accent-orange);
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }

    .section-header h2 {
        color: var(--text-primary);
        margin: 0;
    }

    .games-count {
        color: var(--text-secondary);
        font-size: 0.9rem;
    }

    .no-games {
        grid-column: 1 / -1;
        text-align: center;
        padding: 3rem;
        color: var(--text-secondary);
    }

    .no-games i {
        font-size: 4rem;
        margin-bottom: 1rem;
        color: var(--accent-orange);
    }

    .game-description {
        font-size: 0.8rem;
        color: var(--text-secondary);
        margin: 0.5rem 0;
    }

    .game-stats {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.8rem;
        color: var(--text-secondary);
        margin-top: 0.5rem;
    }

    .play-count {
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    @media (max-width: 768px) {
        .section-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
        }

        .search-box {
            max-width: 100%;
        }
    }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/app.js"></script>

    <script>
    // Games page specific JavaScript
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('gameSearch');
        const filterTabs = document.querySelectorAll('.filter-tab');
        const gameCards = document.querySelectorAll('.game-card');

        // Search functionality
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            
            gameCards.forEach(card => {
                const title = card.dataset.title;
                if (title.includes(searchTerm)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });

            updateGamesCount();
        });

        // Category filter functionality
        filterTabs.forEach(tab => {
            tab.addEventListener('click', function() {
                const category = this.dataset.category;
                
                // Update active tab
                filterTabs.forEach(t => t.classList.remove('active'));
                this.classList.add('active');
                
                // Redirect to filtered page
                window.location.href = `/games?category=${category}`;
            });
        });

        function updateGamesCount() {
            const visibleGames = Array.from(gameCards).filter(card => card.style.display !== 'none');
            const countElement = document.querySelector('.games-count');
            if (countElement) {
                countElement.textContent = `${visibleGames.length} games found`;
            }
        }
    });
    </script>
</body>
</html>
