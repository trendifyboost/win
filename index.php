<?php
session_start();
require_once 'config/database.php';
require_once 'includes/functions.php';
require_once 'includes/auth.php';

// Get page parameter
$page = $_GET['page'] ?? 'home';
$user = getCurrentUser();

// Handle different pages
switch ($page) {
    case 'profile':
        if (!$user) {
            header('Location: /');
            exit;
        }
        include 'pages/profile.php';
        exit;
    case 'myaccount':
        if (!$user) {
            header('Location: /');
            exit;
        }
        include 'pages/users/myaccount.php';
        exit;
    case 'deposit':
        if (!$user) {
            header('Location: /');
            exit;
        }
        include 'pages/users/Deposit.php';
        exit;
    case 'withdraw':
        if (!$user) {
            header('Location: /');
            exit;
        }
        include 'pages/users/Withdraw.php';
        exit;
    default:
        // Home page - render inline
        $games = getGames();
        $categories = getCategories();
        break;
}

function getGames() {
    global $pdo;
    try {
        $stmt = $pdo->query("SELECT * FROM games ORDER BY is_hot DESC, created_at DESC LIMIT 12");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return [];
    }
}

function getCategories() {
    return [
        ['id' => 1, 'name' => 'Hot', 'icon' => '🔥', 'color' => '#ff4757'],
        ['id' => 2, 'name' => 'Slots', 'icon' => '🎰', 'color' => '#5352ed'],
        ['id' => 3, 'name' => 'Fish', 'icon' => '🐠', 'color' => '#20bf6b'],
        ['id' => 4, 'name' => 'Live', 'icon' => '📺', 'color' => '#fd79a8'],
        ['id' => 5, 'name' => 'Poker', 'icon' => '♠️', 'color' => '#ff6b6b'],
        ['id' => 6, 'name' => 'Sports', 'icon' => '⚽', 'color' => '#4834d4']
    ];
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>66ZZ Gaming Platform</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="/assets/css/style.css" rel="stylesheet">
</head>
<body>
        <div class="app-container">
            <!-- Header -->
            <header class="app-header">
                <div class="container-fluid">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="logo">
                            <h1 class="logo-text">66<span class="logo-accent">ZZ</span><span class="logo-suffix">.com</span></h1>
                        </div>
                        <div class="header-actions">
                            <?php if (!$user): ?>
                                <button class="btn btn-login" data-bs-toggle="modal" data-bs-target="#loginModal">Login</button>
                                <button class="btn btn-register" data-bs-toggle="modal" data-bs-target="#registerModal">Register</button>
                            <?php else: ?>
                                <div class="user-balance-section">
                                    <div class="balance-display">
                                        
                                        <div class="balance-amount">৳ <span id="userBalance"><?= number_format($user['balance'], 2) ?></span></div>
                                        <button class="btn-reload-balance" onclick="reloadBalance()">
                                            <i class="fas fa-sync-alt"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="user-menu">
                                    <button class="btn btn-user-menu" onclick="toggleSidebar()">
                                        <i class="fas fa-bars"></i>
                                    </button>
                                </div>
                            <?php endif; ?>
                            <button class="btn btn-download">
                                <i class="fas fa-download"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content -->
            <main class="main-content">
                <?php if ($page === 'home'): ?>
                    <!-- Hero Banner -->
                    <section class="hero-banner">
                        <div class="container">
                            <div class="hero-content">
                                <div class="hero-text">
                                    <h2 class="hero-title">দারুণ সব রিয়েল গেম খেলুন</h2>
                                    <p class="hero-subtitle">গেমিং ও স্পোর্টস গেম খেলে অনলাইন এখন সহজেই জিতে এবং আরো মজা পান অবারিত।</p>
                                </div>
                                <div class="hero-character">
                                    <img src="https://pixabay.com/get/gb042dcf615632f0f3fd231ce60e50d195433c12ff83d509defca28cc3d9f4412e5b235ea7addd200711d081639a5798cdd46bc1e1f8441b73dc13fbb9c9beca4_1280.jpg" alt="Gaming Character" class="character-img">
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- Notification Bar -->
                    <div class="notification-bar">
                        <div class="container">
                            <div class="notification-content">
                                <i class="fas fa-bell notification-icon"></i>
                                <span class="notification-text">নির্জেন করুন ১০০% -> নির্জেন ১২ মাস গেম খেলার মাধ্যমে -></span>
                                <button class="btn btn-app-download">
                                    <i class="fas fa-mobile-alt"></i>
                                    APP
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Game Categories -->
                    <section class="game-categories">
                        <div class="container">
                            <div class="categories-grid">
                                <?php foreach ($categories as $category): ?>
                                    <div class="category-card" data-category="<?= $category['id'] ?>">
                                        <div class="category-icon" style="background: <?= $category['color'] ?>;">
                                            <?= $category['icon'] ?>
                                        </div>
                                        <span class="category-name"><?= $category['name'] ?></span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </section>

                    <!-- Featured Games -->
                    <section class="featured-games">
                        <div class="container">
                            <div class="section-header">
                                <h3 class="section-title">RECOMMENDED HOT GAMES</h3>
                            </div>
                            <div class="games-grid">
                                <?php 
                                $gameImages = [
                                    'https://pixabay.com/get/g1b7163608dfcc1c0b55ca44bcfe425789b0267934d316fb6ca51c97f33f16b953402e118d0f6373c1b2a9f8f7634c0c2e16d701d38c73e394cc809a1ef9721ad_1280.jpg',
                                    'https://pixabay.com/get/g8d701ceff5a5b24ed3969f9a2e60bd49feb10c53b1cc98243ab79ed86361617ec4af8c65a8a0229718f4f75c73cd5cfa083a2a5091af5bd0265ae60705370203_1280.jpg',
                                    'https://pixabay.com/get/g48d9fbba361a63345c5af4ccfd8b2c310938880410d71510d48d4b4252abed23b961cb27493512a9f7e02d3b7dc0074534f4eae2a5931f912838203b26bdcd82_1280.jpg',
                                    'https://pixabay.com/get/g60679fd854a926dddb18329e6e11568d86a71adb420d00a5781ef42b3a27e0a902a0f1a1179a6d90e344c2987d7c1e3bd9f6defc4ba38bc97e01beb79b118b88_1280.jpg'
                                ];
                                
                                $gameNames = ['Super Ace', 'Boxing King', 'Super Elements', 'Aviator', 'Fortune Gems', 'Wild Bounty Showdown', 'Fruity Slots', 'Dragon Fortune'];
                                
                                for ($i = 0; $i < 8; $i++): 
                                    $imageIndex = $i % count($gameImages);
                                ?>
                                    <div class="game-card" data-game-id="<?= $i + 1 ?>">
                                        <div class="game-image">
                                            <img src="<?= $gameImages[$imageIndex] ?>" alt="<?= $gameNames[$i] ?>" loading="lazy">
                                            <div class="game-overlay">
                                                <button class="btn-play">
                                                    <i class="fas fa-play"></i>
                                                </button>
                                                <button class="btn-favorite">
                                                    <i class="far fa-heart"></i>
                                                </button>
                                            </div>
                                            <?php if ($i < 3): ?>
                                                <div class="hot-badge">HOT</div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="game-info">
                                            <h4 class="game-title"><?= $gameNames[$i] ?></h4>
                                        </div>
                                    </div>
                                <?php endfor; ?>
                            </div>
                        </div>
                    </section>
                <?php endif; ?>
            </main>

            <!-- Bottom Navigation -->
            <nav class="bottom-nav">
                <div class="nav-items">
                    <a href="/" class="nav-item <?= $page === 'home' ? 'active' : '' ?>">
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
                    <a href="/myaccount" class="nav-item">
                        <i class="fas fa-user"></i>
                        <span>Member</span>
                    </a>
                </div>
            </nav>

            <!-- Sidebar Menu -->
            <div class="sidebar" id="sidebar">
                <div class="sidebar-header">
                    <button class="btn-close-sidebar" onclick="toggleSidebar()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="sidebar-content">
                    <div class="user-info">
                        <div class="user-avatar">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="user-details">
                            <h4><?= $user['username'] ?? 'Guest' ?></h4>
                        </div>
                    </div>
                    
                    <?php if ($user): ?>
                    <div class="financial-section">
                        <div class="financial-buttons-sidebar">
                            <button class="btn-deposit-sidebar" onclick="showDepositModal()">
                                <i class="fas fa-plus"></i>
                                <span>Deposit Money</span>
                            </button>
                            <button class="btn-withdraw-sidebar" onclick="showWithdrawModal()">
                                <i class="fas fa-minus"></i>
                                <span>Withdraw Money</span>
                            </button>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <div class="menu-items">
                        <a href="/profile" class="menu-item">
                            <i class="fas fa-calendar"></i>
                            <span>Sign in rewards</span>
                        </a>
                        <a href="/rewards" class="menu-item">
                            <i class="fas fa-gift"></i>
                            <span>Reward Center</span>
                        </a>
                        <a href="/betting" class="menu-item">
                            <i class="fas fa-list"></i>
                            <span>Betting Record</span>
                        </a>
                        <a href="/account" class="menu-item">
                            <i class="fas fa-credit-card"></i>
                            <span>Account record</span>
                        </a>
                        <a href="/security" class="menu-item">
                            <i class="fas fa-shield-alt"></i>
                            <span>Security center</span>
                        </a>
                        <a href="/messages" class="menu-item">
                            <i class="fas fa-envelope"></i>
                            <span>Message</span>
                        </a>
                        <a href="/myaccount" class="menu-item">
                            <i class="fas fa-user-circle"></i>
                            <span>My account</span>
                        </a>
                        <a href="#" class="menu-item" onclick="logout()">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>Logout</span>
                        </a>
                    </div>
                    
                    <div class="language-selector">
                        <h5>Language</h5>
                        <div class="language-options">
                            <button class="lang-btn active" data-lang="bn">
                                <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 60'%3E%3Crect width='100' height='60' fill='%23006a4e'/%3E%3Ccircle cx='37.5' cy='30' r='20' fill='%23f42a41'/%3E%3C/svg%3E" alt="Bengali">
                            </button>
                            <button class="lang-btn" data-lang="en">
                                <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 60'%3E%3Crect width='100' height='60' fill='%23012169'/%3E%3Cpath d='M0 0h100v60H0z' fill='%23012169'/%3E%3Cpath d='M0 0l100 60M100 0L0 60' stroke='%23fff' stroke-width='6'/%3E%3Cpath d='M0 0l100 60M100 0L0 60' stroke='%23C8102E' stroke-width='4'/%3E%3Cpath d='M50 0v60M0 30h100' stroke='%23fff' stroke-width='10'/%3E%3Cpath d='M50 0v60M0 30h100' stroke='%23C8102E' stroke-width='6'/%3E%3C/svg%3E" alt="English">
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Login Modal -->
        <div class="modal fade" id="loginModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Login</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form id="loginForm">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Username</label>
                                <input type="text" class="form-control" name="username" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" class="form-control" name="password" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary w-100">Login</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Register Modal -->
        <div class="modal fade" id="registerModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Register</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form id="registerForm">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Username</label>
                                <input type="text" class="form-control" name="username" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" class="form-control" name="password" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Confirm Password</label>
                                <input type="password" class="form-control" name="confirm_password" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary w-100">Register</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Deposit Modal -->
        <div class="modal fade" id="depositModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="fas fa-plus text-success"></i>
                            Deposit Money
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form id="depositForm">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Amount (৳)</label>
                                <input type="number" class="form-control" name="amount" min="100" max="50000" required placeholder="Enter amount">
                                <div class="form-text">Minimum: ৳100, Maximum: ৳50,000</div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Payment Method</label>
                                <select class="form-control" name="payment_method" required>
                                    <option value="">Select Payment Method</option>
                                    <option value="bkash">bKash</option>
                                    <option value="nagad">Nagad</option>
                                    <option value="rocket">Rocket</option>
                                    <option value="bank">Bank Transfer</option>
                                </select>
                            </div>
                            <div class="quick-amounts">
                                <label class="form-label">Quick Select:</label>
                                <div class="quick-amount-buttons">
                                    <button type="button" class="btn btn-outline-primary" onclick="setDepositAmount(500)">৳500</button>
                                    <button type="button" class="btn btn-outline-primary" onclick="setDepositAmount(1000)">৳1,000</button>
                                    <button type="button" class="btn btn-outline-primary" onclick="setDepositAmount(2000)">৳2,000</button>
                                    <button type="button" class="btn btn-outline-primary" onclick="setDepositAmount(5000)">৳5,000</button>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success w-100">
                                <i class="fas fa-credit-card"></i>
                                Proceed to Payment
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Withdraw Modal -->
        <div class="modal fade" id="withdrawModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="fas fa-minus text-warning"></i>
                            Withdraw Money
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form id="withdrawForm">
                        <div class="modal-body">
                            <div class="balance-info mb-3">
                                <div class="current-balance">
                                    <strong>Available Balance: ৳<span id="availableBalance"><?= number_format($user['balance'], 2) ?></span></strong>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Amount (৳)</label>
                                <input type="number" class="form-control" name="amount" min="100" max="1250" required placeholder="Enter amount">
                                <div class="form-text">Minimum: ৳100, Maximum: ৳1,250</div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Withdrawal Method</label>
                                <select class="form-control" name="withdrawal_method" required>
                                    <option value="">Select Withdrawal Method</option>
                                    <option value="bkash">bKash</option>
                                    <option value="nagad">Nagad</option>
                                    <option value="rocket">Rocket</option>
                                    <option value="bank">Bank Transfer</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Account Number</label>
                                <input type="text" class="form-control" name="account_number" required placeholder="Enter account number">
                            </div>
                            <div class="withdrawal-note">
                                <small class="text-muted">
                                    <i class="fas fa-info-circle"></i>
                                    Withdrawal processing time: 1-24 hours
                                </small>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-warning w-100">
                                <i class="fas fa-money-bill-wave"></i>
                                Request Withdrawal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/app.js"></script>

    <script>
    // Financial transaction functions
    function reloadBalance() {
        const balanceElement = document.getElementById('userBalance');
        const availableBalanceElement = document.getElementById('availableBalance');
        const reloadBtn = document.querySelector('.btn-reload-balance');
        
        // Add spinning animation
        reloadBtn.style.transform = 'rotate(360deg)';
        
        fetch('/api/get_balance.php')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    balanceElement.textContent = data.balance;
                    if (availableBalanceElement) {
                        availableBalanceElement.textContent = data.balance;
                    }
                    if (window.gamingPlatform) {
                        window.gamingPlatform.showNotification('Balance refreshed', 'success');
                    }
                } else {
                    if (window.gamingPlatform) {
                        window.gamingPlatform.showNotification(data.message, 'error');
                    }
                }
            })
            .catch(() => {
                if (window.gamingPlatform) {
                    window.gamingPlatform.showNotification('Error refreshing balance', 'error');
                }
            })
            .finally(() => {
                reloadBtn.style.transform = 'rotate(0deg)';
            });
    }

    function showDepositModal() {
        const depositModal = new bootstrap.Modal(document.getElementById('depositModal'));
        depositModal.show();
    }

    function showWithdrawModal() {
        const withdrawModal = new bootstrap.Modal(document.getElementById('withdrawModal'));
        withdrawModal.show();
    }

    function setDepositAmount(amount) {
        document.querySelector('#depositForm input[name="amount"]').value = amount;
    }

    // Handle deposit form submission
    document.getElementById('depositForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const amount = formData.get('amount');
        const paymentMethod = formData.get('payment_method');
        
        // Show processing notification
        if (window.gamingPlatform) {
            window.gamingPlatform.showNotification(`Processing deposit of ৳${amount} via ${paymentMethod}...`, 'info');
        }
        
        // Close modal
        bootstrap.Modal.getInstance(document.getElementById('depositModal')).hide();
        
        // In a real app, this would process the payment
        setTimeout(() => {
            if (window.gamingPlatform) {
                window.gamingPlatform.showNotification('Deposit request sent successfully!', 'success');
            }
        }, 2000);
    });

    // Handle withdrawal form submission
    document.getElementById('withdrawForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const amount = formData.get('amount');
        const withdrawalMethod = formData.get('withdrawal_method');
        
        // Show processing notification
        if (window.gamingPlatform) {
            window.gamingPlatform.showNotification(`Processing withdrawal of ৳${amount} via ${withdrawalMethod}...`, 'info');
        }
        
        // Close modal
        bootstrap.Modal.getInstance(document.getElementById('withdrawModal')).hide();
        
        // In a real app, this would process the withdrawal
        setTimeout(() => {
            if (window.gamingPlatform) {
                window.gamingPlatform.showNotification('Withdrawal request submitted successfully!', 'success');
            }
        }, 2000);
    });
    </script>
</body>
</html>
