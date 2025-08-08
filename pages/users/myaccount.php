<?php
if (!$user) {
    header('Location: /');
    exit;
}

// Fetch user details again to get all fields
global $pdo;
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user['id']]);
$account_details = $stmt->fetch();

$stmt = $pdo->prepare("SELECT * FROM vip_levels WHERE id = ?");
$stmt->execute([$account_details['vip_level_id']]);
$vip_level = $stmt->fetch();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Account - 66ZZ Gaming</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #1a1a1a;
            color: #fff;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            margin: 0;
        }
        .account-page {
            display: flex;
            flex-direction: column;
            height: 100vh;
        }
        .top-bar {
            display: flex;
            align-items: center;
            padding: 1rem;
            background-color: #2c2c2c;
        }
        .top-bar .back-arrow {
            font-size: 1.5rem;
            margin-right: 1rem;
            color: #fff;
            text-decoration: none;
        }
        .top-bar h1 {
            font-size: 1.2rem;
            margin: 0;
        }
        .user-profile-header {
            background: linear-gradient(to bottom, #4a4a4a, #2c2c2c);
            padding: 1.5rem;
            text-align: center;
            position: relative;
        }
        .user-profile-header .profile-pic {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            border: 3px solid #ffc107;
            margin: 0 auto 1rem;
        }
        .user-profile-header .username {
            font-size: 1.2rem;
            font-weight: bold;
        }
        .user-profile-header .vip-badge {
            background-color: #ffc107;
            color: #000;
            padding: 0.2rem 0.5rem;
            border-radius: 5px;
            font-size: 0.8rem;
            margin-left: 0.5rem;
        }
        .user-profile-header .balance {
            font-size: 2rem;
            margin: 1rem 0;
        }
        .user-profile-header .header-buttons {
            display: flex;
            justify-content: center;
            gap: 1rem;
        }
        .user-profile-header .btn-header {
            background-color: #444;
            color: #fff;
            border: 1px solid #555;
            padding: 0.5rem 1.5rem;
            border-radius: 20px;
            text-decoration: none;
        }
        .sign-in-banner {
            background-color: #d9534f;
            color: #fff;
            padding: 0.5rem 1rem;
            text-align: center;
            font-weight: bold;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .member-center {
            padding: 1.5rem;
        }
        .member-center h2 {
            font-size: 1rem;
            color: #ffc107;
            margin-bottom: 1rem;
        }
        .menu-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
            text-align: center;
        }
        .menu-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            color: #fff;
            text-decoration: none;
        }
        .menu-item .icon {
            font-size: 2rem;
            color: #ffc107;
            margin-bottom: 0.5rem;
        }
        .menu-item .text {
            font-size: 0.8rem;
        }
        .mission-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background-color: red;
            color: white;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
        }
        .icon-container {
            position: relative;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>
<body>
    <div class="account-page">
        <div class="top-bar">
            <a href="/" class="back-arrow"><i class="fas fa-arrow-left"></i></a>
            <h1>My Account</h1>
        </div>

        <div class="user-profile-header">
            <img src="https://i.pravatar.cc/150?u=<?= htmlspecialchars($account_details['username']) ?>" alt="Profile Picture" class="profile-pic">
            <div class="username">
                <?= htmlspecialchars($account_details['username']) ?>
                <span class="vip-badge"><?= htmlspecialchars($vip_level['name']) ?></span>
            </div>
            <div class="balance">৳ <?= number_format($account_details['balance'], 2) ?></div>
            <div class="header-buttons">
                <a href="/deposit" class="btn-header">Deposit</a>
                <a href="/withdraw" class="btn-header">Withdrawal</a>
                <a href="#" class="btn-header">My Cards</a>
            </div>
        </div>

        <div class="sign-in-banner">
            <span><i class="fas fa-check-square"></i> Sign In</span>
            <i class="fas fa-chevron-right"></i>
        </div>

        <div class="member-center">
            <h2>Member Center</h2>
            <div class="menu-grid">
                <a href="#" class="menu-item"><div class="icon"><i class="fas fa-trophy"></i></div><span class="text">Reward Center</span></a>
                <a href="#" class="menu-item"><div class="icon"><i class="fas fa-chart-line"></i></div><span class="text">Betting Record</span></a>
                <a href="#" class="menu-item"><div class="icon"><i class="fas fa-file-invoice-dollar"></i></div><span class="text">Profit and Loss</span></a>
                <a href="#" class="menu-item"><div class="icon"><i class="fas fa-arrow-up"></i></div><span class="text">Deposit Record</span></a>
                <a href="#" class="menu-item"><div class="icon"><i class="fas fa-arrow-down"></i></div><span class="text">Withdrawal Record</span></a>
                <a href="#" class="menu-item"><div class="icon"><i class="fas fa-history"></i></div><span class="text">Account Record</span></a>
                <a href="/myaccount" class="menu-item"><div class="icon"><i class="fas fa-user-circle"></i></div><span class="text">My Account</span></a>
                <a href="#" class="menu-item"><div class="icon"><i class="fas fa-shield-alt"></i></div><span class="text">Security Center</span></a>
                <a href="#" class="menu-item"><div class="icon"><i class="fas fa-user-plus"></i></div><span class="text">Invite Friends</span></a>
                <a href="#" class="menu-item">
                    <div class="icon-container">
                        <div class="icon"><i class="fas fa-gift"></i></div>
                        <div class="mission-badge">1</div>
                    </div>
                    <span class="text">Mission</span>
                </a>
                <a href="#" class="menu-item"><div class="icon"><i class="fas fa-undo"></i></div><span class="text">Manual Rebate</span></a>
                <a href="#" class="menu-item"><div class="icon"><i class="fas fa-envelope"></i></div><span class="text">Internal Message</span></a>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>