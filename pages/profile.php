<?php
// Profile page
if (!$user) {
    header('Location: /');
    exit;
}

$favorites = getUserFavorites($user['id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - <?= getenv('APP_NAME') ?></title>
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
                        <button class="btn btn-user-menu" onclick="history.back()">
                            <i class="fas fa-arrow-left"></i>
                        </button>
                    </div>
                </div>
            </div>
        </header>

        <main class="main-content">
            <div class="container py-4">
                <div class="row">
                    <div class="col-12">
                        <div class="profile-header">
                            <div class="user-avatar-large">
                                <i class="fas fa-user"></i>
                            </div>
                            <div class="user-info-large">
                                <h2><?= htmlspecialchars($user['username']) ?></h2>
                                <p class="text-secondary">Member since <?= formatDate($user['created_at']) ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="profile-section">
                            <h3>Account Information</h3>
                            <div class="info-item">
                                <strong>Username:</strong> <?= htmlspecialchars($user['username']) ?>
                            </div>
                            <div class="info-item">
                                <strong>Email:</strong> <?= htmlspecialchars($user['email']) ?>
                            </div>
                            <div class="info-item">
                                <strong>Join Date:</strong> <?= formatDate($user['created_at']) ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="profile-section">
                            <h3>Statistics</h3>
                            <div class="info-item">
                                <strong>Favorite Games:</strong> <?= count($favorites) ?>
                            </div>
                            <div class="info-item">
                                <strong>Games Played:</strong> 0
                            </div>
                            <div class="info-item">
                                <strong>Total Playtime:</strong> 0 hours
                            </div>
                        </div>
                    </div>
                </div>

                <?php if (!empty($favorites)): ?>
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="profile-section">
                            <h3>Favorite Games</h3>
                            <div class="games-grid">
                                <?php foreach ($favorites as $game): ?>
                                    <div class="game-card" data-game-id="<?= $game['id'] ?>">
                                        <div class="game-image">
                                            <img src="<?= htmlspecialchars($game['thumbnail_url']) ?>" alt="<?= htmlspecialchars($game['title']) ?>">
                                            <div class="game-overlay">
                                                <button class="btn-play">
                                                    <i class="fas fa-play"></i>
                                                </button>
                                                <button class="btn-favorite">
                                                    <i class="fas fa-heart"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="game-info">
                                            <h4 class="game-title"><?= htmlspecialchars($game['title']) ?></h4>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
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
                <a href="/member" class="nav-item active">
                    <i class="fas fa-user"></i>
                    <span>Member</span>
                </a>
            </div>
        </nav>
    </div>

    <style>
    .profile-header {
        background: var(--dark-card);
        padding: 2rem;
        border-radius: 15px;
        display: flex;
        align-items: center;
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .user-avatar-large {
        width: 80px;
        height: 80px;
        background: var(--gradient-secondary);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--dark-bg);
        font-size: 2rem;
    }

    .user-info-large h2 {
        margin: 0;
        color: var(--text-primary);
    }

    .profile-section {
        background: var(--dark-card);
        padding: 1.5rem;
        border-radius: 15px;
        margin-bottom: 1.5rem;
        border: 1px solid var(--border-color);
    }

    .profile-section h3 {
        color: var(--text-primary);
        margin-bottom: 1rem;
        font-size: 1.2rem;
    }

    .info-item {
        padding: 0.5rem 0;
        border-bottom: 1px solid var(--border-color);
        color: var(--text-secondary);
    }

    .info-item:last-child {
        border-bottom: none;
    }

    .info-item strong {
        color: var(--text-primary);
    }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/app.js"></script>
</body>
</html>
